<?php

namespace Drupal\commerce_migrate_ubercart\Plugin\migrate\source;

use Drupal\migrate\Plugin\migrate\source\SqlBase;

/**
 * Gets the Ubercart tax rates.
 *
 * @MigrateSource(
 *   id = "d6_ubercart_tax_type",
 *   source_module = "uc_taxes"
 * )
 */
class TaxType extends SqlBase {

  /**
   * {@inheritdoc}
   */
  public function query() {
    $query = $this->select('uc_taxes', 'ut')
      ->fields('ut', array_keys($this->fields()));

    return $query;
  }

  /**
   * {@inheritdoc}
   */
  public function fields() {
    $fields = [
      'id' => $this->t('TaxType ID'),
      'name' => $this->t('TaxType Name'),
      'rate' => $this->t('TaxType Rate'),
    ];

    return $fields;
  }

  /**
   * {@inheritdoc}
   */
  public function getIds() {
    return [
      'id' => [
        'type' => 'integer',
        'alias' => 'ut',
      ],
    ];
  }

}
