<?php
// pcx_order_export/src/EventSubscriber/PCXOrderExportEventSubscriber.php
namespace Drupal\pcx_order_export\EventSubscriber;

use Drupal\state_machine\Event\WorkflowTransitionEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Drupal\commerce_order\Entity\Order;

class PCXOrderExportEventSubscriber implements EventSubscriberInterface {
  /**
   * {@inheritdoc}
   */
  public static function getSubscribedEvents() {
    return [
      'commerce_order.place.post_transition' => 'onOrderPlace'
    ];
  }

  public function onOrderPlace(WorkflowTransitionEvent $event) {
    $order = $event->getEntity();

    \Drupal::logger('pcx_order_export')->notice("Creating Order Export for order: #{$order->id()}");

    $order_export = \Drupal\pcx_order_export\Entity\OrderExport::create([
      'order_id' => $order->id(),
      'status' => 0
    ]);
    $order_export->save();
  }
}
