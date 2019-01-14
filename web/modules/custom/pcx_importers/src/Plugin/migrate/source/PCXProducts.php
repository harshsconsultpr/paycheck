<?php

/**
 * @file
 * Contains \Drupal\pcx_importers\Plugin\migrate\source\PCXProducts.
 */

namespace Drupal\pcx_importers\Plugin\migrate\source;

use Drupal\migrate\MigrateException;
use Drupal\migrate\Plugin\migrate\source\SourcePluginBase;
use Drupal\migrate\Plugin\MigrationInterface;
use Drupal\migrate_source_csv\Plugin\migrate\source\CSV;
use Drupal\migrate\Row;
use Drupal\taxonomy\Entity\Term;
use Drupal\commerce_price\Price;

/**
 * Source plugin for Product Entities
 *
 * @MigrateSource(
 *   id = "pcx_products"
 * )
 */
class PCXProducts extends CSV {

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
    // The variation SKU
    $sku = $row->getSourceProperty('VSN');
    $this->log(date("d-m-Y h:i:s")." : Preparing row for import: ".$sku, true);

    // All categories split into an array
    $categories = explode('|', $row->getSourceProperty('Category'));
    // The last/top-level category
    $last_category = array_values(array_slice($categories, -1))[0];

    // Skip product if catalog term doesn't exist already
    if ( $term = $this->pcx_importers_products_getTidByName($last_category, 'catalog_category') ) {
      $row->setSourceProperty('Category', $last_category);
      $this->log("CATEGORY:\n".print_r($last_category,true));

      // The price of the variation.
      $msrp = new \Drupal\commerce_price\Price($row->getSourceProperty('MSRP') , 'USD');
      $row->setSourceProperty('MSRP', $msrp);

      $shipping_price = $row->getSourceProperty('Shipping Price');

      // get taxonomy for price markup
      $terms = \Drupal::entityManager()->getStorage('taxonomy_term')->loadByProperties( ['name' => $last_category] );
      $term = reset($terms);
      $term_id = $term ? $term->id() : false;

      // markup the price
      $price = $this->pcx_price_markup($msrp, $shipping_price, $term_id);
      $this->log("PRICE:\n".print_r($price,true));

      $title = $row->getSourceProperty('Title');

      // set status using stock
      $status = ($row->getSourceProperty('InStock') == 'Y');
      $row->setSourceProperty('InStock', $status);

      if ( $variation = \Drupal::entityManager()->getStorage('commerce_product_variation')->loadByProperties(array('sku' => $sku)) ){
        $this->log("variation already exists, update it");
        $variation = reset($variation);
        $variation->setPrice($price);
      }
      else {
        $this->log("variation does not exist, create one");
        $variation = \Drupal\commerce_product\Entity\ProductVariation::create([
            'type' => 'default',
            'sku' => $sku,
            'status' => 1,
            'price' => $price,
            'title' => $title,
        ]);
      }

      $variation->save();
      $row->setSourceProperty('variation', $variation->id());

      // images
      $images = [];
      foreach ([ 'Main Image', 'ALT Image 1', 'ALT Image 2', 'ALT Image 3', 'ALT Image 4', 'ALT Image 5' ] as $key) {
        if ($image = $row->getSourceProperty($key)) {
          $images[] = [ 'uri' => utf8_encode($image), 'title' => $title, 'alt' => $title ];
        }
      }
      $row->setSourceProperty('Images', $images);
      $this->log("IMAGES:\n".print_r($images,true));

      // attributes
      $features = [];
      $pairs = explode('~', $row->getSourceProperty('Attributes'));
      foreach ($pairs as $pair) {
        $features[] = ['value' => utf8_encode(str_replace("^", ":\t", $pair)."\n")];
      }
      $row->setSourceProperty('Features', $features);
      $this->log("FEATURES:\n".print_r($features,true));
    }
    else {
      $row->setSourceProperty('Category', null);
      $this->log("Term does not exist, skipping product: #{$sku}");
    }

    $this->log("ROW:\n".print_r($row,true));
  }

  public function log($notice="", $force=false) {
    if ( $force || \Drupal\Core\Site\Settings::get('client_debug')) {
      \Drupal::logger('pcx_importers.pcx_catalog')->notice("<pre>".utf8_encode($notice)."</pre>");
    }
  }

  public function pcx_price_markup($price, $ship, $tid = FALSE) {
    $price = $price->getNumber();

    //Override price by category
    if ($tid && $term = \Drupal\taxonomy\Entity\Term::load($tid)) {
      try {
        $markup_override = $term->get('field_category_markup_override')->getString();
      } catch (\Exception $e) {
        $this->log("Caught error while attempting to find category markup: ".$e, true);
      }
    }

    if (isset($markup_override) && is_numeric($markup_override)){
      $markup = $markup_override;
      $this->log("TID MARKUP OVERRIDE:\n{$markup_override}");
    } else {
      $markup = (\Drupal::config('pcx_price_calc.settings')->get('global_markup'));
      $this->log("GLOBAL MARKUP:\n{$markup}");
    }

    $markup = $price * ($markup / 100);
    $price += $markup;
    $price += $ship;
    $price = round($price,2);
    $new_price = new Price(strval($price), 'USD');
    $this->log("NEW PRICE:\n".print_r($new_price,true));
    return $new_price;
  }

  public function pcx_importers_products_getTidByName($name = NULL, $vid = NULL) {
    $properties = [];
    if (!empty($name)) {
      $properties['name'] = $name;
    }
    if (!empty($vid)) {
      $properties['vid'] = $vid;
    }
    $terms = \Drupal::entityManager()->getStorage('taxonomy_term')->loadByProperties($properties);
    $term = reset($terms);

    return !empty($term) ? $term : 0;
  }

}
