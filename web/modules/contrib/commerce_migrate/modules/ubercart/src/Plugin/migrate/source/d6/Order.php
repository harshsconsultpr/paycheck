<?php

namespace Drupal\commerce_migrate_ubercart\Plugin\migrate\source\d6;

use Drupal\migrate\Plugin\migrate\source\SqlBase;
use Drupal\migrate\Row;

/**
 * Provides migration source for orders.
 *
 * @MigrateSource(
 *   id = "d6_ubercart_order",
 *   source_module = "uc_order"
 * )
 */
class Order extends SqlBase {

  /**
   * {@inheritdoc}
   */
  public function query() {
    return $this->select('uc_orders', 'uo')
      ->fields('uo', array_keys($this->fields()));
  }

  /**
   * {@inheritdoc}
   */
  public function fields() {
    $fields = [
      'order_id' => $this->t('Order ID'),
      'uid' => $this->t('User ID of order'),
      'order_status' => $this->t('Order status'),
      'primary_email' => $this->t('Email associated with order'),
      'host' => $this->t('IP address of customer'),
      'data' => $this->t('Order attributes'),
      'created' => $this->t('Date/time of order creation'),
      'modified' => $this->t('Date/time of last order modification'),
    ];

    return $fields;
  }

  /**
   * {@inheritdoc}
   */
  public function prepareRow(Row $row) {

    // The Migrate API automatically serializes arrays for storage in longblob
    // fields so we unserialize them here.
    $data = unserialize($row->getSourceProperty('data'));
    // Ubercart 6 stores credit card information in a hash. Since this probably
    // isn't necessary so I removed it here.
    unset($data['cc_data']);

    $row->setSourceProperty('data', serialize($data));

    return parent::prepareRow($row);
  }

  /**
   * {@inheritdoc}
   */
  public function getIds() {
    return [
      'order_id' => [
        'type' => 'integer',
        'alias' => 'uo',
      ],
    ];
  }

}
