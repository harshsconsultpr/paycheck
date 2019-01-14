<?php
namespace Drupal\pcx_checkout\Plugin\views\field;

use Drupal\views\ResultRow;
use Drupal\views\Plugin\views\field\FieldPluginBase;

/**
 * A handler to provide proper displays for profile current company.
 *
 * @ingroup views_field_handlers
 *
 * @ViewsField("pcx_checkout_view_balance")
 */
class PCXCheckoutViewBalance extends FieldPluginBase {

  /**
   * {@inheritdoc}
   */
  public function render(ResultRow $values) {
    $relationship_entities = $values->_relationship_entities;

    if (isset($relationship_entities['commerce_order'])) {
      $order = $relationship_entities['commerce_order'];
    }
    else {
      $order = $values->_entity;
    }

    $totals = pcx_checkout_get_order_totals($values->order_id);

    return "$".number_format($totals->owed, 2, '.', ',');
  }

  /**
   * {@inheritdoc}
   */
  public function query() {
    // This function exists to override parent query function.
    // Do nothing.
  }
}
