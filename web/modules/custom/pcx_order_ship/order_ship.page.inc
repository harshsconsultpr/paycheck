<?php

/**
 * @file
 * Contains order_ship.page.inc.
 *
 * Page callback for Order Ship entities.
 */

use Drupal\Core\Render\Element;

/**
 * Prepares variables for Order Ship templates.
 *
 * Default template: order_ship.html.twig.
 *
 * @param array $variables
 *   An associative array containing:
 *   - elements: An associative array containing the user information and any
 *   - attributes: HTML attributes for the containing element.
 */
function template_preprocess_order_ship(array &$variables) {
  // Fetch OrderShip Entity Object.
  $order_ship = $variables['elements']['#order_ship'];

  // Helpful $content variable for templates.
  foreach (Element::children($variables['elements']) as $key) {
    $variables['content'][$key] = $variables['elements'][$key];
  }
}
