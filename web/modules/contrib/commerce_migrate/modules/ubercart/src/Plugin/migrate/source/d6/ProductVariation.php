<?php

namespace Drupal\commerce_migrate_ubercart\Plugin\migrate\source\d6;

use Drupal\migrate\Row;
use Drupal\migrate_drupal\Plugin\migrate\source\DrupalSqlBase;

/**
 * Drupal 6 ubercart product variation sourcee.
 *
 * @MigrateSource(
 *   id = "d6_ubercart_product_variation",
 *   source_module = "uc_product"
 * )
 */
class ProductVariation extends DrupalSqlBase {

  /**
   * {@inheritdoc}
   */
  public function query() {
    $query = $this->select('node', 'n')->fields('n', [
      'nid', 'vid', 'type', 'title', 'uid', 'created',
      'changed', 'status',
    ]);
    $query->innerJoin('uc_products', 'ucp', 'n.nid = ucp.nid AND n.vid = ucp.vid');
    $query->fields('ucp', ['model', 'sell_price']);
    $query->condition('type', 'product', '=');
    $query->distinct();

    return $query;
  }

  /**
   * {@inheritdoc}
   */
  public function fields() {
    $fields = [
      'nid' => $this->t('Node ID'),
      'title' => $this->t('Product title'),
      'model' => $this->t('SKU code'),
      'sell_price' => $this->t('Product price'),
    ];

    return $fields;
  }

  /**
   * {@inheritdoc}
   */
  public function prepareRow(Row $row) {
    // Set the currency for each variation based on Ubercart global setting.
    $row->setSourceProperty('currency', $this->getUbercartCurrency());

    return parent::prepareRow($row);
  }

  /**
   * {@inheritdoc}
   */
  public function getIds() {
    return [
      'nid' => [
        'type' => 'integer',
        'alias' => 'n',
      ],
    ];
  }

  /**
   * Gets the Ubercart global currency.
   *
   * @return string
   *   The currency.
   */
  public function getUbercartCurrency() {
    static $currency;

    if (empty($currency)) {
      $currency = $this->variableGet('uc_currency_code', 'USD');
    }
    return $currency;
  }

}
