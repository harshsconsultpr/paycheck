<?php

namespace Drupal\pcx_rates\Entity;

use Drupal\views\EntityViewsData;

/**
 * Provides Views data for Commission rate entities.
 */
class CommissionRateViewsData extends EntityViewsData {

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
