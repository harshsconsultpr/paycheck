<?php

namespace Drupal\commerce_migrate\EventSubscriber;

use Drupal\commerce_order\Entity\OrderItem;
use Drupal\migrate\Event\MigrateEvents;
use Drupal\migrate\Event\MigratePostRowSaveEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class MigrateOrderItem implements EventSubscriberInterface {

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

    // If the destination is a line item we need to ensure the reference to
    // the order exists.
    if ($destination_config['plugin'] == 'entity:commerce_order_item') {
      $order_item = OrderItem::load($event->getRow()->getDestinationProperty('order_item_id'));
      $order = $order_item->getOrder();
      if ($order) {
        $order->addItem($order_item);
        $order->save();
      }
    }
  }

}
