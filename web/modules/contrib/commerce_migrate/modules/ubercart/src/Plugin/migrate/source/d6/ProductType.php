<?php

namespace Drupal\commerce_migrate_ubercart\Plugin\migrate\source\d6;

use Drupal\migrate_drupal\Plugin\migrate\source\DrupalSqlBase;

/**
 * Gets Drupal 6 ubercart product classes from database.
 *
 * In Ubercart 6 product classes refers to node types that are  product types.
 * @link http://www.ubercart.org/docs/user/10963/understanding_product_classes @endlink
 *
 * @MigrateSource(
 *   id = "d6_ubercart_product_type",
 *   source_module = "uc_product"
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
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function getIds() {
    $ids['type']['type'] = 'string';
    $ids['type']['alias'] = 'nt';
    return $ids;
  }

  /**
   * {@inheritdoc}
   */
  public function query() {
    $query = $this->select('node_type', 'nt')
      ->fields('nt')
      ->condition('module', 'uc_product%', 'LIKE');
    return $query;
  }

}
