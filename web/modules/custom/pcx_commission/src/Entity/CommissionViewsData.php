<?php

namespace Drupal\pcx_commission\Entity;

use Drupal\views\EntityViewsData;

/**
 * Provides Views data for Commission entities.
 */
class CommissionViewsData extends EntityViewsData {

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
