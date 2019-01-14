<?php

namespace Drupal\pcx_checkout\Plugin\Field\FieldType;

use Drupal\Core\Field\Plugin\Field\FieldType\DecimalItem;

/**
 * Variant of the 'link' field that links to the current company.
 *
 * @FieldType(
 *   id = "balance",
 *   label = @Translation("Balance"),
 *   description = @Translation("The balance remaining on the order."),
 *   default_widget = "number",
 *   default_formatter = "number_decimal"
 * )
 */
class PCXOrderBalance extends DecimalItem {

  /**
   * {@inheritdoc}
   */
  public function __get($name) {
    return parent::__get($name);
  }
  /**
   * {@inheritdoc}
   */
  public function isEmpty() {
    return parent::isEmpty();
  }

  /**
   * {@inheritdoc}
   */
  public function getValue() {
    return parent::getValue();
  }

}
