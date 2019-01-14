<?php

namespace Drupal\pcx_accounting\Entity;

use Drupal\views\EntityViewsData;

/**
 * Provides Views data for Commission statement line item entities.
 */
class CommissionStatementLineItemViewsData extends EntityViewsData {

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
