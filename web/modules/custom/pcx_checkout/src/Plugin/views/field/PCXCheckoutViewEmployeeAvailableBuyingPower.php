<?php
namespace Drupal\pcx_checkout\Plugin\views\field;

use Drupal\views\ResultRow;
use Drupal\views\Plugin\views\field\FieldPluginBase;

/**
 * A handler to provide proper displays for profile current company.
 *
 * @ingroup views_field_handlers
 *
 * @ViewsField("pcx_checkout_view_employee_available_buying_power")
 */
class PCXCheckoutViewEmployeeAvailableBuyingPower extends FieldPluginBase {

  /**
   * {@inheritdoc}
   */
  public function render(ResultRow $values) {
    $relationship_entities = $values->_relationship_entities;

    if (isset($relationship_entities['profile'])) {
      $profile = $relationship_entities['profile'];
    }
    else {
      $profile = $values->_entity;
    }

    $user_id = $profile->get('uid')->getvalue();
    $buying_power = pcx_checkout_get_employee_available_buying_power($user_id[0]['target_id']);

    return "$".number_format($buying_power, 2, '.', ',');
  }

  /**
   * {@inheritdoc}
   */
  public function query() {
    // This function exists to override parent query function.
    // Do nothing.
  }
}
