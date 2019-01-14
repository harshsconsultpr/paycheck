<?php

namespace Drupal\commerce_migrate_ubercart\Plugin\migrate\source\d6;

use Drupal\migrate\Plugin\migrate\source\SqlBase;

/**
 * Provides migration source for attributes.
 *
 * @MigrateSource(
 *   id = "d6_ubercart_attribute",
 *   source_module = "uc_attribute"
 * )
 */
class Attribute extends SqlBase {

  /**
   * {@inheritdoc}
   */
  public function query() {
    return $this->select('uc_attributes', 'uca')->fields('uca');
  }

  /**
   * {@inheritdoc}
   */
  public function fields() {
    $fields =
      [
        'aid' => $this->t('Attribute id'),
        'name' => $this->t('Name'),
        'label' => $this->t('Label'),
        'ordering' => $this->t('Attribute display order'),
        'required' => $this->t('Attribute field required'),
        'display' => $this->t('Display type'),
        'description' => $this->t('Attribute description'),
      ];
    return $fields;
  }

  /**
   * {@inheritdoc}
   */
  public function getIds() {
    return [
      'aid' => [
        'type' => 'integer',
      ],
    ];
  }

}
