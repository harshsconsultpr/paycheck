<?php
namespace Drupal\pcx_auth\EventSubscriber;

use Drupal;
use Drupal\Core\Url;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class AuthSubscriber implements EventSubscriberInterface {

  public function checkForRedirection(GetResponseEvent $event) {
    if (!(\Drupal::currentUser()->isAnonymous()) && \Drupal::service('path.matcher')->isFrontPage()) {
      if ($path = \Drupal::config('pcx_auth.settings')->get('field_auth_front_path')) {
        $path = '/'.ltrim($path, '/');
        $event->setResponse( new RedirectResponse($path) );
      }
      else {
        \Drupal::logger('pcx_auth')->notice("Failed to fetch path from Basic Site Settings.");
      }
    }
  }

  /**
   * {@inheritdoc}
   */
  public static function getSubscribedEvents() {
    $events[KernelEvents::REQUEST][] = array('checkForRedirection');
    return $events;
  }
}

?>
