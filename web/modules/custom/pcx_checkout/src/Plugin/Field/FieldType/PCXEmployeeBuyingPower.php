<?php

namespace Drupal\pcx_checkout\Plugin\Field\FieldType;

use Drupal\Core\Field\Plugin\Field\FieldType\DecimalItem;

/**
 * Variant of the 'link' field that links to the current company.
 *
 * @FieldType(
 *   id = "employee_buying_power",
 *   label = @Translation("Employee Buying Power"),
 *   description = @Translation("The buying power for the employee."),
 *   default_widget = "number",
 *   default_formatter = "number_decimal"
 * )
 */
class PCXEmployeeBuyingPower extends DecimalItem {

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
