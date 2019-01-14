<?php

namespace Drupal\commerce_migrate_commerce\Plugin\migrate\process\d7;

use Drupal\migrate\MigrateExecutableInterface;
use Drupal\migrate\ProcessPluginBase;
use Drupal\migrate\Row;

/**
 * Migrate commerce price from D7 to D8.
 *
 * @MigrateProcessPlugin(
 *   id = "commerce_migrate_commerce_price"
 * )
 */
class CommercePrice extends ProcessPluginBase {

  /**
   * {@inheritdoc}
   */
  public function transform($value, MigrateExecutableInterface $migrate_executable, Row $row, $destination_property) {
    /** @var \Drupal\commerce_price\Entity\CurrencyInterface $currency */
    $currency = \Drupal::service('commerce_price.currency_importer')->import($value['currency_code']);
    // The old value was stored in the minor unit, divide it to get the decimal.
    $new_value = [
      'number' => bcdiv($value['amount'], '100', $currency->getFractionDigits()),
      'currency_code' => $value['currency_code'],
    ];

    return $new_value;
  }

}
