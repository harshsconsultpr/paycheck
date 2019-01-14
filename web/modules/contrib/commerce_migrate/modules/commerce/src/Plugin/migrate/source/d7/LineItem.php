<?php

namespace Drupal\commerce_migrate_commerce\Plugin\migrate\source\d7;

use Drupal\migrate\Row;
use Drupal\migrate_drupal\Plugin\migrate\source\d7\FieldableEntity;

/**
 * Drupal 7 commerce_line_item source from database.
 *
 * @MigrateSource(
 *   id = "d7_line_item",
 *   source_module = "commerce_order"
 * )
 */
class LineItem extends FieldableEntity {

  /**
   * {@inheritdoc}
   */
  public function fields() {
    return [
      'line_item_id' => t('Line Item ID'),
      'title' => t('Product title'),
      'order_id' => t('Order ID'),
      'type' => t('Type'),
      'line_item_label' => t('Line Item Label'),
      'quantity' => t('Quantity'),
      'created' => t('Created'),
      'changed' => t('Changes'),
      'data' => t('Data'),
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function getIds() {
    $ids['line_item_id']['type'] = 'integer';
    $ids['line_item_id']['alias'] = 'li';

    return $ids;
  }

  /**
   * {@inheritdoc}
   */
  public function query() {
    $query = $this->select('commerce_line_item', 'li')
      ->fields('li');
    $query->leftJoin('commerce_product', 'cp', 'cp.sku = li.line_item_label');
    $query->addField('cp', 'title');
    return $query;
  }

  /**
   * {@inheritdoc}
   */
  public function prepareRow(Row $row) {
    // Get Field API field values.
    $line_item_id = $row->getSourceProperty('line_item_id');
    $revision_id = $row->getSourceProperty('revision_id');
    foreach (array_keys($this->getFields('commerce_line_item', $row->getSourceProperty('type'))) as $field) {
      $row->setSourceProperty($field, $this->getFieldValues('commerce_line_item', $field, $line_item_id, $revision_id));
    }
    return parent::prepareRow($row);
  }

}
