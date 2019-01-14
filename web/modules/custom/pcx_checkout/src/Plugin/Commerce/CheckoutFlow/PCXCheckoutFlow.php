<?php

namespace Drupal\pcx_checkout\Plugin\Commerce\CheckoutFlow;

use Drupal\commerce_checkout\Plugin\Commerce\CheckoutFlow\CheckoutFlowWithPanesBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * @CommerceCheckoutFlow(
 *  id = "pcx_checkout_flow_plugin",
 *  label = @Translation("PCX Checkout Flow"),
 * )
 */
class PCXCheckoutFlow extends CheckoutFlowWithPanesBase {

  /**
   * {@inheritdoc}
   */
  public function getSteps() {
    return [
        'information' => [
          'label' => $this->t('Checkout'),
          'previous_label' => $this->t('Change Re-Payment Plan'),
          'has_order_summary' => TRUE,
        ],
        'confirm' => [
          'label' => $this->t('Confirm and Submit Order'),
          'next_label' => $this->t('Review Order'),
          'has_order_summary' => TRUE,
        ],
        'complete' => [
          'label' => $this->t('Complete'),
          'next_label' => $this->t('Submit Order'),
          'has_order_summary' => FALSE,
        ]
      ];
  }

}
