<?php

namespace Drupal\commerce_migrate_commerce\Plugin\migrate\source\d7;

use Drupal\migrate_drupal\Plugin\migrate\source\DrupalSqlBase;

/**
 * Gets Commerce order item types from database.
 *
 * @MigrateSource(
 *   id = "d7_order_item_type",
 *   source_module = "commerce_order"
 * )
 */
class OrderItemType extends DrupalSqlBase {

  /**
   * {@inheritdoc}
   */
  public function query() {
    return $this->select('commerce_line_item', 'cli')
      ->fields('cli', ['type'])
      ->distinct();
  }

  /**
   * {@inheritdoc}
   */
  public function fields() {
    return [
      'type' => t('Type'),
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function getIds() {
    $ids['type']['type'] = 'string';
    return $ids;
  }

}
