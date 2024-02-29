<?php
$genericFacet = \T4\PHPSearchLibrary\FacetFactory::getInstance('GenericFacet', $documentCollection, $queryHandler);
$filters = $queryHandler->getQueryValuesForPrint();
$categoryFilters = array('programType','programDestination', 'programRegion', 'programFieldofStudy','programFeatures','programFee');
$dateFilters = array('startDateAfter','startDateBefore');
$rangeFilters = array('courseCost' => '24000');
?>
<section class="su-listing">
    <div id="searchoptionsGeneric" role="search" class="su-listing--form-wrapper bg--dark global-padding--8x" data-t4-ajax-group="courseSearch">

        <div class="grid-container">
            <h2 class="h3">Find an Education Abroad Program</h2>
        </div>

        <form>
            <div class="cell initial-12 large-8">
                <label for="keywords">Search</label>
                <input type="text" name="keywords" id="keywords" placeholder="Search All Education Abroad Programs&hellip;" value="<?php echo !empty($query['keywords']) ? $query['keywords'] : ''  ?>">
            </div>
            <div class="cell medium-6 large-4">
                <fieldset>
                    <?php
                    $element = 'programType';
                    $genericFacet->setMember('element', $element);
                    $genericFacet->setMember('type', 'List');
                    $genericFacet->setMember('facetSource', 'documents');
                    $genericFacet->setMember('multipleValueState', true);
                    $genericFacet->setMember('multipleValueSeparator', '|');
                    $search = $genericFacet->displayFacet();
                    ?>
                    <legend>Filter by Program Type</legend>
                    <div class="fieldset-wrapper">
                        <?php $i = 0; ?>
                        <?php foreach ($search as $item) : ?>
                            <div class="fieldset--checkbox">
                                <input type="checkbox" id="<?php echo $element . '[' . ++$i . ']'; ?>" value="<?php echo strtolower($item['value']); ?>" data-cookie="T4_persona" name="<?php echo $element; ?>" <?php echo $item['selected'] ? 'checked' : '' ?>>
                                <label for="<?php echo $element . '[' . $i . ']'; ?>"><?php echo $item['label']; ?></label>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </fieldset>
            </div>
            <div class="cell medium-6 large-4">
                <?php
                $element = 'programDestination';
                $genericFacet->setMember('element', $element);
                $genericFacet->setMember('type', 'List');
                $genericFacet->setMember('facetSource', 'documents');
                $genericFacet->setMember('sortingState', true);
                $genericFacet->setMember('multipleValueState', true);
                $genericFacet->setMember('multipleValueSeparator', ', ');
                $search = $genericFacet->displayFacet();
                ?>
                <label for="<?php echo $element; ?>" class="label-text">Filter by Destination</label>
                <select id="<?php echo $element; ?>" name="<?php echo $element; ?>" data-cookie="T4_persona">
                    <option value="">All Destinations</option>
                    <?php foreach ($search as $item) : ?>
                        <option value="<?php echo strtolower($item['value']); ?>" <?php echo $item['selected'] ? 'selected' : '' ?>><?php echo $item['label']; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="cell medium-6 large-4">
                <?php
                $element = 'programFieldofStudy';
                $genericFacet->setMember('element', $element);
                $genericFacet->setMember('type', 'List');
                $genericFacet->setMember('facetSource', 'documents');
                $genericFacet->setMember('sortingState', true);
                $genericFacet->setMember('multipleValueState', true);
                $genericFacet->setMember('multipleValueSeparator', '|');
                $search = $genericFacet->displayFacet();
                ?>
                <label for="area" class="label-text">Filter by Field of Study</label>
                <select id="<?php echo $element; ?>" name="<?php echo $element; ?>" data-cookie="T4_persona">
                    <option value="">All Fields of Study</option>
                    <?php foreach ($search as $item) : ?>
                        <option value="<?php echo strtolower($item['value']); ?>" <?php echo $item['selected'] ? 'selected' : '' ?>><?php echo $item['label']; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="cell medium-6 large-4">
                <?php
                $element = 'programRegion';
                $genericFacet->setMember('element', $element);
                $genericFacet->setMember('type', 'List');
                $genericFacet->setMember('facetSource', 'documents');
                $genericFacet->setMember('sortingState', true);
                $genericFacet->setMember('multipleValueState', true);
                $genericFacet->setMember('multipleValueSeparator', ', ');
                $search = $genericFacet->displayFacet();
                ?>
                <label for="area" class="label-text">Filter by Regions</label>
                <select id="<?php echo $element; ?>" name="<?php echo $element; ?>" data-cookie="T4_persona">
                    <option value="">All Regions</option>
                    <?php foreach ($search as $item) : ?>
                        <option value="<?php echo strtolower($item['value']); ?>" <?php echo $item['selected'] ? 'selected' : '' ?>><?php echo $item['label']; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="cell medium-6 large-4">
                <?php
                $element = 'programFeatures';
                $genericFacet->setMember('element', $element);
                $genericFacet->setMember('type', 'List');
                $genericFacet->setMember('facetSource', 'documents');
                $genericFacet->setMember('sortingState', true);
                $genericFacet->setMember('multipleValueState', true);
                $genericFacet->setMember('multipleValueSeparator', ', ');
                $search = $genericFacet->displayFacet();
                ?>
                <label for="area" class="label-text">Filter by Features</label>
                <select id="<?php echo $element; ?>" name="<?php echo $element; ?>" data-cookie="T4_persona">
                    <option value="">All Features</option>
                    <?php foreach ($search as $item) : ?>
                        <option value="<?php echo strtolower($item['value']); ?>" <?php echo $item['selected'] ? 'selected' : '' ?>><?php echo $item['label']; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="cell initial-12">
                <input type="submit" value="Submit" class="button">
            </div>
        </form>
    </div>
        <div class="filter-feedback">
        <div class="grid-container">
            <span id="starthere"></span>          
            <div id="searchoptions-filters" class="active-filters" role="search" data-t4-ajax-group="courseSearch" aria-label="Deselect Filters">
                <div id="event-filters" class="active-filters--list" >
                    <span>Active filters:</span>
                    <?php if ($filters !== null) : ?>
                        <ul class="no-bullet">
                            <?php
                            $i = 0;
                            foreach ($categoryFilters as $key) {
                                if (isset($filters[$key]) && is_array($filters[$key])) :
                                    foreach ($filters[$key] as $value) : ?>
                                        <li class="filter-<?php echo $i++ ?>  small primary" role="button" tabindex="0" data-t4-value="<?php echo strtolower($value) ?>" data-t4-filter="<?php echo $key ?>"><?php echo $value ?><span class="remove"><i class="fa fa-times"></i></span></li>
                                    <?php
                                    endforeach;
                                elseif (isset($filters[$key])) :
                                    $value = $filters[$key]; ?>
                                    <li class="filter-<?php echo $i++ ?>  small primary" role="button" tabindex="0" data-t4-value="<?php echo strtolower($value) ?>" data-t4-filter="<?php echo $key ?>"><?php echo $value ?><span class="remove"><i class="fa fa-times"></i></span></li>
                                <?php
                                endif;
                            }
                            foreach ($rangeFilters as $key => $max) {
                                if (isset($filters[$key]) && $filters[$key] !== $max) :
                                    $value = $filters[$key]; ?>
                                    <li class="filter-<?php echo $i++ ?>  small primary" role="button" tabindex="0" data-t4-filter="<?php echo $key ?>"><?php echo '$' . $value; ?><span class="remove"><i class="fa fa-times"></i></span></li>
                                <?php
                                endif;
                            }
                            if (isset($filters['keywords'])) :
                                ?>
                                <li class="filter-<?php echo $i++ ?> small primary" role="button" tabindex="0" data-t4-filter="keywords"> <?php echo $filters['keywords'] ?><span class="remove"><i class="fa fa-times"></i></span></li>
                            <?php
                            endif; ?>
                        </ul>
                    <?php endif; ?>
                </div>
              <?php if ($i > 0) : ?>
              <div class="funderline">
                <a href="index.php" role="button" data-t4-ajax-link="true">
                  Clear Filters
                  <span class="fa fa-times"></span>
                </a>
              </div>
              <?php endif; ?>    
              <div class="search-count"><p>Showing <strong><?php echo count($results); ?> programs</strong> of <?php echo $totalResults; ?></p></div>
            </div>          
        </div>
    </div>
</section>

