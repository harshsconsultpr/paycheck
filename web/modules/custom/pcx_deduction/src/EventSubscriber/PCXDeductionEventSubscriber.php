<?php
// pcx_deduction/src/EventSubscriber/PCXDeductionEventSubscriber.php
namespace Drupal\pcx_deduction\EventSubscriber;

use Drupal\state_machine\Event\WorkflowTransitionEvent;
use Drupal\Core\Language\LanguageManagerInterface;
use Drupal\Core\Mail\MailManagerInterface;
use Drupal\Core\StringTranslation\StringTranslationTrait;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class PCXDeductionEventSubscriber implements EventSubscriberInterface {

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
      'commerce_order.ship.post_transition' => 'onOrderShip',
      'commerce_order.cancel.post_transition' => 'onOrderCancel'
    ];
  }

  public function onOrderShip(WorkflowTransitionEvent $event) {
    $order = $event->getEntity();
    // create deduction entities
  }

  public function onOrderCancel(WorkflowTransitionEvent $event) {
    $order = $event->getEntity();
    // disable deduction entities
  }
}
