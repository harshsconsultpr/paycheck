<?php

namespace Drupal\pcx_order_cancel\Entity;

use Drupal\views\EntityViewsData;

/**
 * Provides Views data for Order Cancel entities.
 */
class OrderCancelViewsData extends EntityViewsData {

  /**
   * {@inheritdoc}
   */
  public function getViewsData() {
    $data = parent::getViewsData();

    // Additional information for Views integration, such as table joins, can be
    // put here.

    return $data;
  }

}
