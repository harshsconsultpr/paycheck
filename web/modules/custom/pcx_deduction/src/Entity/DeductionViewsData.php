<?php

namespace Drupal\pcx_deduction\Entity;

use Drupal\views\EntityViewsData;

/**
 * Provides Views data for Deduction entities.
 */
class DeductionViewsData extends EntityViewsData {

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
