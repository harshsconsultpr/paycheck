<?php

namespace Drupal\commerce_migrate\EventSubscriber;

use Drupal\commerce_product\Entity\Product;
use Drupal\migrate\Event\MigrateEvents;
use Drupal\migrate\Event\MigratePostRowSaveEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Handles migrate post save event for products.
 *
 * @package Drupal\commerce_migrate\EventSubscriber
 */
class MigrateProduct implements EventSubscriberInterface {

  /**
   * {@inheritdoc}
   */
  public static function getSubscribedEvents() {
    $events[MigrateEvents::POST_ROW_SAVE][] = 'postRowSave';
    return $events;
  }

  /**
   * Updates the product variation product_id after the product migration.
   *
   * @param \Drupal\migrate\Event\MigratePostRowSaveEvent $event
   *   The event.
   */
  public function postRowSave(MigratePostRowSaveEvent $event) {
    $destination_config = $event->getMigration()->getDestinationConfiguration();

    // Ensure the back reference to the product is correct for all variations
    // of this product.
    if ($event->getMigration()->id() == 'd7_commerce_product') {
      $product_id = $event->getRow()->getDestinationProperty('product_id');
      if ($product = Product::load($product_id)) {
        // Save updates the back-reference on each product variation.
        $product->save();
      }
    }
  }

}
