<?php
// pcx_commission/src/EventSubscriber/PCXCommissionEventSubscriber.php
namespace Drupal\pcx_commission\EventSubscriber;

use Drupal\state_machine\Event\WorkflowTransitionEvent;
use Drupal\Core\Language\LanguageManagerInterface;
use Drupal\Core\Mail\MailManagerInterface;
use Drupal\Core\StringTranslation\StringTranslationTrait;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class PCXCommissionEventSubscriber implements EventSubscriberInterface {

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

  public function enqueue($item) {
    $queue = \Drupal::queue('commission');
    $queue->createItem($item);
  }

  /**
   * {@inheritdoc}
   */
  public static function getSubscribedEvents() {
    return [
      'commerce_order.place.post_transition' => 'onOrderPlace',
      'commerce_order.ship.post_transition' => 'onOrderShip',
      'commerce_order.cancel.post_transition' => 'onOrderCancel'
    ];
  }

  public function onOrderPlace(WorkflowTransitionEvent $event) {
  }

  public function onOrderShip(WorkflowTransitionEvent $event) {
    $order = $event->getEntity();

    $item = [
      'action' => "create",
      'order'  => $order->id()
    ];
    $this->enqueue($item);

    $this->log($event, "Added item to commission queue:\n".print_r($item,true));
  }

  public function onOrderCancel(WorkflowTransitionEvent $event) {
    $order = $event->getEntity();

    $item = [
      'action' => "cancel",
      'order'  => $order->id()
    ];
    $this->enqueue($item);

    $this->log($event, "Added item to commission queue:\n".print_r($item,true));
  }

  /**
   * Log event.
   *
   * @param \Drupal\state_machine\Event\WorkflowTransitionEvent $event
   *   The transition event.
   */
  public function log(WorkflowTransitionEvent $event, $notice="") {
    \Drupal::logger('pcx_commission')->notice("Order status changed: @notice", [
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
        'Your order status has changed.'
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
