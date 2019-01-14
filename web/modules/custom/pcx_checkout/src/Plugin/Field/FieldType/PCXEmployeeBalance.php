<?php

namespace Drupal\pcx_checkout\Plugin\Field\FieldType;

use Drupal\Core\Field\Plugin\Field\FieldType\DecimalItem;

/**
 * Variant of the 'link' field that links to the current company.
 *
 * @FieldType(
 *   id = "employee_balance",
 *   label = @Translation("Employee Balance"),
 *   description = @Translation("The balance remaining on all orders for the employee."),
 *   default_widget = "number",
 *   default_formatter = "number_decimal"
 * )
 */
class PCXEmployeeBalance extends DecimalItem {

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
