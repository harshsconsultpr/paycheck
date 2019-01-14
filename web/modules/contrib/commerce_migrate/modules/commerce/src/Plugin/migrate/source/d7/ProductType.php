<?php

namespace Drupal\commerce_migrate_commerce\Plugin\migrate\source\d7;

use Drupal\migrate_drupal\Plugin\migrate\source\DrupalSqlBase;
use Drupal\migrate\Row;

/**
 * Drupal 7 commerce_product_type source from database.
 *
 * @MigrateSource(
 *   id = "d7_product_type",
 *   source_module = "commerce_product"
 * )
 */
class ProductType extends DrupalSqlBase {

  /**
   * {@inheritdoc}
   */
  public function fields() {
    return [
      'type' => t('Type'),
      'name' => t('Name'),
      'description' => t('Description'),
      'help' => t('Help'),
      'revision' => t('Revision'),
      'line_item_type' => t('Line item type'),
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function getIds() {
    $ids['type']['type'] = 'string';
    $ids['type']['alias'] = 'pt';
    return $ids;
  }

  /**
   * {@inheritdoc}
   */
  public function prepareRow(Row $row) {
    // Get the line item type for this product type.
    $data = unserialize($row->getSourceProperty('data'));
    $line_item_type = isset($data['display']['default']['settings']['line_item_type']) ? $data['display']['default']['settings']['line_item_type'] : '';
    $row->setSourceProperty('line_item_type', $line_item_type);
    return parent::prepareRow($row);
  }

  /**
   * {@inheritdoc}
   */
  public function query() {
    $query = $this->select('commerce_product_type', 'pt')
      ->fields('pt', ['type', 'name', 'description', 'help', 'revision'])
      ->fields('fci', ['data'])
      ->condition('data', '%line_item_type%', 'LIKE');
    $query->leftJoin('field_config_instance', 'fci', 'fci.bundle = pt.type');
    return $query;
  }

}
