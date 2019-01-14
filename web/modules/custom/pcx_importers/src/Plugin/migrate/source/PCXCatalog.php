<?php

/**
 * @file
 * Contains \Drupal\pcx_importers\Plugin\migrate\source\PCXCatalog.
 */

namespace Drupal\pcx_importers\Plugin\migrate\source;

use Drupal\migrate\MigrateException;
use Drupal\migrate\Plugin\migrate\source\SourcePluginBase;
use Drupal\migrate\Plugin\MigrationInterface;
use Drupal\migrate_source_csv\Plugin\migrate\source\CSV;
use Drupal\migrate\Row;
use Drupal\taxonomy\Entity\Term;


/**
 * Source plugin for catalog term entities
 *
 * @MigrateSource(
 *   id = "pcx_catalog"
 * )
 */
class PCXCatalog extends CSV {

  /**
   * {@inheritdoc}
   */
  public function getIDs() {
    $ids = [];
    foreach ($this->configuration['keys'] as $delta => $value) {
      if (is_array($value)) {
        $ids[$delta] = $value;
      }
      else {
        $ids[$value]['type'] = 'string';
      }
    }
    return $ids;
  }

  /**
   * {@inheritdoc}
   */
  public function prepareRow(Row $row) {
    $this->log(date("d-m-Y h:i:s")." : Preparing row for import: ".$row->getSourceProperty('Terms'), true);

    // All categories split into an array
    $categories = explode('|', $row->getSourceProperty('Terms'));

    // The last/bottom-level category
    $last_category = array_values(array_slice($categories, -1))[0];
    $this->log("LAST_CATEGORY:\t".print_r($last_category,true));

    // set images for bottom level term
    if ($image = $row->getSourceProperty('Image')) {
      $row->setSourceProperty('Images', [['uri' => $image, 'title' => $last_category, 'alt' => $last_category]]);
    }

    $parent = false;
    foreach ($categories as $category) {
      $this->log("CATEGORY:\t".print_r($category,true));

      // bottom terms handled by Migrate, only progress if child
      // if ($last_category != $category) {
        // get term by category name
        if ( $term = $this->pcx_importers_getTidByName($category, 'catalog_category') ) {
          // term exists, assign parent
          if ($parent) {
            $term->parent = [ $parent->id() ];
            $term->save();
          }
        }
        else {
          // term doesn't exist, create it
          $term = Term::create([
              'name' => $category,
              'vid' => 'catalog_category',
              'field_category_image' => $row->getSourceProperty('Image') ? [['uri' => $row->getSourceProperty('Image'), 'title' => $last_category, 'alt' => $last_category]] : [],
              'field_catalog_id' => $category,
          ]);
          if ($parent) {
            $term->parent = [ $parent->id() ];
          }
          $term->save();
        }

        $parent = $term;
      // }
    }

    $row->setSourceProperty('name', $last_category);
    $row->setSourceProperty('id', $last_category);

    if ($parent) {
      $row->setSourceProperty('parent', $parent->id());
    }

    $this->log("ROW:\t".print_r($row,true));
  }

  public function pcx_importers_getTidByName($name = NULL, $vid = NULL) {
    $properties = [];
    if (!empty($name)) {
      $properties['name'] = $name;
    }
    if (!empty($vid)) {
      $properties['vid'] = $vid;
    }
    $terms = \Drupal::entityManager()->getStorage('taxonomy_term')->loadByProperties($properties);
    $term = reset($terms);
    if (empty($term)){
    }
    return !empty($term) ? $term : 0;
  }

  public function log($notice="", $force=false) {
    if ( $force || \Drupal\Core\Site\Settings::get('pcx_debug')) {
      \Drupal::logger('pcx_importers.pcx_catalog')->notice("<pre>".$notice."</pre>");
    }
  }

}
