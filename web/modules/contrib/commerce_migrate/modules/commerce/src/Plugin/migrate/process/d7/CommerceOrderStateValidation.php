<?php

namespace Drupal\commerce_migrate_commerce\Plugin\migrate\process\d7;

/**
 * Migrate commerce 1.x order states to commerce 2.x.
 *
 * @MigrateProcessPlugin(
 *   id = "commerce_migrate_order_state_with_validation"
 * )
 */
class CommerceOrderStateValidation extends CommerceOrderStateProcessBase {

  /**
   * Provides the mapping to be used in the migration process.
   *
   * The array keys (draft, canceled, completed) are the local states, every
   * array element is the one we will match again. To provide your own mapping
   * you can extend CommerceOrderStateDefault and implement the getMapping()
   * method.
   *
   * When there is a missing mapping, an exception will be thrown so that you
   * can provide a correct mapping.
   */
  public function getMapping() {
    return [
      'draft' => [
        'checkout_checkout',
        'checkout_review',
        'checkout_payment',
        'checkout_complete',
        'cart',
      ],
      'validation' => [
        'pending',
        'processing',
      ],
      'canceled' => [
        'canceled',
      ],
      'completed' => [
        'completed',
      ],
    ];
  }

}
