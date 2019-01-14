<?php

namespace Drupal\pcx_checkout\Plugin\Field\FieldItemList;

use Drupal\Core\Field\FieldItemList;

/**
 * Item list for a computed field
 *
 * @see \Drupal\pcx_checkout\Plugin\Field\FieldType\PCXEmployeeAvailableBuyingPower
 */
class PCXEmployeeAvailableBuyingPowerList extends FieldItemList {

  /**
   * {@inheritdoc}
   */
  public function getIterator() {
    return new \ArrayIterator($this->list);
  }

  /**
   * {@inheritdoc}
   */
  public function getValue($include_computed = FALSE) {
    return parent::getValue($include_computed);
  }

  /**
   * {@inheritdoc}
   */
  public function isEmpty() {
    return parent::isEmpty();
  }

  /**
   * Makes sure that the item list is never empty.
   *
   * For 'normal' fields that use database storage the field item list is
   * initially empty, but since this is a computed field this always has a
   * value.
   * Make sure the item list is always populated, so this field is not skipped
   * for rendering in EntityViewDisplay and friends.
   *
   * @todo This will no longer be necessary once #2392845 is fixed.
   *
   * @see https://www.drupal.org/node/2392845
   */
  protected function ensurePopulated() {
    if (!isset($this->list[0])) {
      $this->list[0] = $this->createItem(0);
    }
  }

}
