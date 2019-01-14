<?php

namespace Drupal\commerce_migrate_commerce\Plugin\migrate\source\d7;

use Drupal\migrate\Row;
use Drupal\migrate_drupal\Plugin\migrate\source\d7\FieldableEntity;

/**
 * Drupal 7 commerce_customer_profile source from database.
 *
 * @MigrateSource(
 *   id = "d7_billing_profile",
 *   source_module = "profile"
 * )
 */
class BillingProfile extends FieldableEntity {

  /**
   * The join options between the node and the node_revisions table.
   */
  const JOIN = 'cp.revision_id = cpr.revision_id';

  /**
   * {@inheritdoc}
   */
  public function fields() {
    return [
      'profile_id' => $this->t('Profile ID'),
      'revision_id' => $this->t('Revision ID'),
      'type' => $this->t('Type'),
      'uid' => $this->t('Owner'),
      'status' => $this->t('Status'),
      'created' => $this->t('Created timestamp'),
      'changed' => $this->t('Modified timestamp'),
      'data' => $this->t('Data blob'),
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function getIds() {
    $ids['profile_id']['type'] = 'integer';
    $ids['profile_id']['alias'] = 'cp';
    return $ids;
  }

  /**
   * {@inheritdoc}
   */
  public function query() {
    $query = $this->select('commerce_customer_profile', 'cp')
      ->fields('cp', array_keys($this->fields()));
    $query->condition('type', 'billing');

    return $query;
  }

  /**
   * {@inheritdoc}
   */
  public function prepareRow(Row $row) {
    $nid = $row->getSourceProperty('profile_id');
    $vid = $row->getSourceProperty('revision_id');
    // Get Field API field values.
    foreach (array_keys($this->getFields('commerce_customer_profile', $row->getSourceProperty('type'))) as $field) {
      $row->setSourceProperty($field, $this->getFieldValues('commerce_customer_profile', $field, $nid, $vid));

      // 1.x default bundle was `billing`, is now `customer`.
      if ($row->getSourceProperty('type') == 'billing') {
        $row->setSourceProperty('type', 'customer');
      }
    }
    return parent::prepareRow($row);
  }

}
