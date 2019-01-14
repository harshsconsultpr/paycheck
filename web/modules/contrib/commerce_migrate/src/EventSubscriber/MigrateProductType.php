<?php

namespace Drupal\commerce_migrate\EventSubscriber;

use Drupal\commerce_product\Entity\ProductType;
use Drupal\migrate\Event\MigrateEvents;
use Drupal\migrate\Event\MigratePostRowSaveEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Handles postrowsave event for product variation types.
 *
 * @package Drupal\commerce_migrate\EventSubscriber
 */
class MigrateProductType implements EventSubscriberInterface {

  /**
   * {@inheritdoc}
   */
  public static function getSubscribedEvents() {
    $events[MigrateEvents::POST_ROW_SAVE][] = 'postRowSave';
    return $events;
  }

  /**
   * Reacts to the POST_ROW_SAVE event.
   *
   * @param \Drupal\migrate\Event\MigratePostRowSaveEvent $event
   *   The event.
   */
  public function postRowSave(MigratePostRowSaveEvent $event) {
    $destination_config = $event->getMigration()->getDestinationConfiguration();

    // If the destination is a product type we need to ensure some fields.
    // @see \Drupal\commerce_product\Form\ProductTypeForm::postSave()
    if ($destination_config['plugin'] == 'entity:commerce_product_type') {
      $product_type = ProductType::load($event->getRow()->getDestinationProperty('id'));
      commerce_product_add_stores_field($product_type);
      commerce_product_add_body_field($product_type);
      commerce_product_add_variations_field($product_type);
    }
  }

}
