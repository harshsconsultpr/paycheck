<?php

namespace Drupal\commerce_migrate_ubercart\Plugin\migrate\source\d6;

use Drupal\migrate_drupal\Plugin\migrate\source\DrupalSqlBase;
use Drupal\migrate\Row;

/**
 * Drupal 6 ubercart order product source.
 *
 * @MigrateSource(
 *   id = "d6_ubercart_order_product",
 *   source_module = "uc_order",
 * )
 */
class OrderProduct extends DrupalSqlBase {

  /**
   * {@inheritdoc}
   */
  public function query() {
    $query = $this->select('uc_order_products', 'uop')
      ->fields('uop', [
        'order_product_id',
        'order_id',
        'nid',
        'title',
        'qty',
        'price',
        'data',
      ]);
    $query->innerJoin('uc_orders', 'uo', 'uop.order_id = uo.order_id');
    $query->fields('uo', ['created', 'modified']);

    /** @var \Drupal\Core\Database\Schema $db */
    if ($this->getDatabase()->schema()->fieldExists('uc_orders', 'currency')) {
      // Currency column is in the source.
      $query->addField('uo', 'currency');
    }
    else {
      // If the currency column does not exist, add it as an expression to
      // normalize the query results.
      $currency_code = $this->variableGet('uc_currency_code', 'USD');
      $query->addExpression(':currency_code', 'currency', [':currency_code' => $currency_code]);
    }
    return $query;
  }

  /**
   * {@inheritdoc}
   */
  public function fields() {
    $fields = [
      'order_product_id' => $this->t('Line item ID'),
      'order_id' => $this->t('Order ID'),
      'nid' => $this->t('Product ID'),
      'title' => $this->t('Product name'),
      'qty' => $this->t('Quantity sold'),
      'price' => $this->t('Price of product sold'),
    ];

    return $fields;
  }

  /**
   * {@inheritdoc}
   */
  public function prepareRow(Row $row) {

    // The Migrate API automatically serializes arrays for storage in longblob
    // fields so we unserialize them here.
    $attributes = unserialize($row->getSourceProperty('data'));

    // In Ubercart, the module key is set to 'uc_order' so it's removed for
    // D8 Commerce.
    unset($attributes['module']);

    $row->setSourceProperty('attributes', $attributes);

    return parent::prepareRow($row);
  }

  /**
   * {@inheritdoc}
   */
  public function getIds() {
    return [
      'order_product_id' => [
        'type' => 'integer',
        'alias' => 'uop',
      ],
    ];
  }

}
