import { Client, Types } from '../../../../t4apiwrapper/t4.ts/esm/index.js'
import { url, token } from './config.js'
import XLSX from 'xlsx-js-style'
import { promises } from 'node:fs'
import { resolve } from 'node:path'
const { stat } = promises

const { contentType, content, list, serverSideLink, upload, hierarchy } = new Client(url, token)

const setionIdInput = process.argv.splice(2)[0]
const regex = /\(max size: \d+\)/, listObjs = {}
const workbook = XLSX.readFile('./book.xlsx')
for (let sheet of workbook.SheetNames) {
  const sheetObj = XLSX.utils.sheet_to_json(workbook.Sheets[sheet])[0],
    cleanSheet = {},
    ct = await contentType.get(sheetObj.contentTypeID),
    formattedElements = content.util.getElementNames(ct.contentTypeElements)
  Object.keys(sheetObj).map(sheetName => {
    console.log(sheetName)
    const trimmedName = sheetName.replace(regex, '').trim()
    cleanSheet[formattedElements[trimmedName] || trimmedName] = sheetObj[sheetName]
  })
  delete cleanSheet.contentTypeID
  const parsedElements = await parseElements(cleanSheet, ct)
  if (!parsedElements) {
    console.log(`Failed to parse worksheet: ${sheet}`)
    continue
  }
  try {
    const { name, id, elements } = await content.create(setionIdInput, {
      elements: parsedElements.sheet,
      contentTypeID: ct.id,
      language: 'en',
      status: 0
    }, true)
    console.log(`Created ${name} with ID of ${id}`)
    console.log(elements)
  } catch (e) {
    console.log(`Failed to parse worksheet: ${sheet}\n${e}`)
  }
}

async function parseElements(sheet, ct) {
  let failed = false, newId = -Math.floor(Math.random() * (Types.max - Types.min) + Types.max),
  sslArr = []
  await Promise.all(Object.keys(sheet).map(async key => {
    if (failed) return
    const [id, type] = (key.split('#')[1].split(':')).map(Number)
    try {
      switch (type) {
        case 2:
          sheet[key] = await parseImageUpload(sheet[key], type)
          break
        case 9:
        case 6:
          sheet[key] = await parseListValue(sheet[key], {ct, type, id})
          break
        case 14:
          sheet[key] = await parseServerSideLink(sheet[key], newId)
          break
        default:
          break
      }
    } catch(error) {
      console.log(error)
      failed = true
    }
  }))
  return failed ? null : { sheet, id: newId, sslArr }
}

async function parseListValue(str, {ct, type, id}) {
  const contentElement = ct.contentTypeElements.filter(element => element.id == id && element.type == type)[0]
  if (!contentElement) throw Error(`No contentElement exists with ${id}:${type}`)
  if (!listObjs[contentElement.listId]) {
    listObjs[contentElement.listId] = await list.get(contentElement.listId)
  }
  str = str.toLowerCase()
  const option = listObjs[contentElement.listId].items.filter(item => (item.name.toLowerCase()).includes(str) || (item.value.toLowerCase()).includes(str))
  if(!option.length) throw Error(`No list value exists with value ${str}`)
  return `${contentElement.listId}:${option[0].id}`
}

async function parseServerSideLink(str, newId) {
  const [sectionId, contentId] = str.split(',').map(str => str.trim()).map(Number)
  if (!sectionId) return ''
  const name = contentId ? (await content.get(contentId)).name : (await hierarchy.get(sectionId)).name
  let sslRequest = await serverSideLink.set({
    attributes: {},
    active: false,
    fromSection: setionIdInput,
    fromContent: newId,
    toContent: contentId || 0,
    language: 'en',
    toSection: sectionId,
    linkText: name,
    useDefaultLinkText: true
  })
  if (!Object.keys(sslRequest).length) throw Error(`Failed to set server side link to ${sectionId}`)
  sslRequest = await serverSideLink.set({...sslRequest, active: true})
  console.log(sslRequest)
  return `<t4 sslink_id=\"${sslRequest.id}\" type=\"sslink\" />`
}

async function parseImageUpload(fileName, id) {
  const returnObj = { existingFile: false }
  if (!fileName.includes('.')) return parseInt(fileName) || returnObj
  const path = resolve(`./media/${fileName}`)
  if (!await stat(path)) throw Error(`${path} does not exist!`)
  const uploadData = await upload.add({
    file: path,
    filename: fileName,
    elementID: id
  })
  returnObj.preferredFilename = uploadData.name
  returnObj.code = uploadData.code
  return returnObj
}
