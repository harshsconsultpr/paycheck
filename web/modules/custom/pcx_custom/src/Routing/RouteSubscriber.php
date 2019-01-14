<?php

namespace Drupal\pcx_custom\Routing;

use Drupal\Core\Routing\RouteSubscriberBase;
use Symfony\Component\Routing\RouteCollection;

/**
 * Listens to the dynamic route events.
 */
class RouteSubscriber extends RouteSubscriberBase {

  /**
   * {@inheritdoc}
   */
  protected function alterRoutes(RouteCollection $collection) {
    // Override profile tab routing
    if ($route = $collection->get('entity.profile.type.user_profile_form')) {
      $route->setRequirement('_custom_access_check', 'ADD');
    }
  }
}


