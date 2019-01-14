<?php
// pcx_orders/src/EventSubscriber/PCXOrdersEventSubscriber.php
namespace Drupal\pcx_orders\EventSubscriber;

use Drupal\state_machine\Event\WorkflowTransitionEvent;
use Drupal\Core\Language\LanguageManagerInterface;
use Drupal\Core\Mail\MailManagerInterface;
use Drupal\Core\StringTranslation\StringTranslationTrait;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class PCXOrdersEventSubscriber implements EventSubscriberInterface {

  /**
   * Constructs a new OrderFulfillmentSubscriber object.
   *
   * @param \Drupal\Core\Language\LanguageManagerInterface $language_manager
   *   The language manager.
   * @param \Drupal\Core\Mail\MailManagerInterface $mail_manager
   *   The mail manager.
   */
  public function __construct(
    LanguageManagerInterface $language_manager,
    MailManagerInterface $mail_manager
  ) {
    $this->languageManager = $language_manager;
    $this->mailManager = $mail_manager;
  }

  /**
   * {@inheritdoc}
   */
  public static function getSubscribedEvents() {
    return [
      'commerce_order.place.post_transition' => 'onOrderPlace',
      'commerce_order.export.post_transition' => 'onOrderExport',
      'commerce_order.update.post_transition' => 'onOrderUpdate',
      'commerce_order.ship.post_transition' => 'onOrderShip',
      'commerce_order.payroll.post_transition' => 'onOrderPayroll',
      'commerce_order.cancel.post_transition' => 'onOrderCancel',
      'commerce_order.collect.post_transition' => 'onOrderCollections',
      'commerce_order.complete.post_transition' => 'onOrderComplete'
    ];
  }

  public function onOrderPlace(WorkflowTransitionEvent $event) {
    $this->log($event);
  }
  public function onOrderExport(WorkflowTransitionEvent $event) {
    $this->log($event);
  }
  public function onOrderUpdate(WorkflowTransitionEvent $event) {
    $this->log($event);
  }
  public function onOrderShip(WorkflowTransitionEvent $event) {
    $this->log($event);
  }
  public function onOrderPayroll(WorkflowTransitionEvent $event) {
    $this->log($event);
  }
  public function onOrderCancel(WorkflowTransitionEvent $event) {
    $this->log($event);
  }
  public function onOrderCollections(WorkflowTransitionEvent $event) {
    $this->log($event);
  }
  public function onOrderComplete(WorkflowTransitionEvent $event) {
    $this->log($event);
  }

  /**
   * Log event.
   *
   * @param \Drupal\state_machine\Event\WorkflowTransitionEvent $event
   *   The transition event.
   */
  public function log(WorkflowTransitionEvent $event, $notice="") {
    \Drupal::logger('pcx_orders')->notice("Order status changed: @notice", [
      '@notice' => $notice
    ]);
  }

  /**
   * Send email.
   *
   * @param \Drupal\state_machine\Event\WorkflowTransitionEvent $event
   *   The transition event.
   */
  public function sendEmail(WorkflowTransitionEvent $event) {
    // Create the email.
    $order = $event->getEntity();
    $to = $order->getEmail();
    $params = [
      'from' => $order->getStore()->getEmail(),
      'subject' => $this->t(
        'Order [#@number]',
        ['@number' => $order->getOrderNumber()]
      ),
      'body' => $this->t(
        'Your order status has changed'
      ),
    ];

    // Set the language that will be used in translations.
    if ($customer = $order->getCustomer()) {
      $langcode = $customer->getPreferredLangcode();
    }
    else {
      $langcode = $this->languageManager->getDefaultLanguage()->getId();
    }

    // Send the email.
    $this->mailManager->mail('commerce_order', 'receipt', $to, $langcode, $params);
  }
}
