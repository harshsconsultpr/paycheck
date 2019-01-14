<?php

/**
 * @file
 * Contains order_cancel.page.inc.
 *
 * Page callback for Order Cancel entities.
 */

use Drupal\Core\Render\Element;

/**
 * Prepares variables for Order Cancel templates.
 *
 * Default template: order_cancel.html.twig.
 *
 * @param array $variables
 *   An associative array containing:
 *   - elements: An associative array containing the user information and any
 *   - attributes: HTML attributes for the containing element.
 */
function template_preprocess_order_cancel(array &$variables) {
  // Fetch OrderCancel Entity Object.
  $order_cancel = $variables['elements']['#order_cancel'];

  // Helpful $content variable for templates.
  foreach (Element::children($variables['elements']) as $key) {
    $variables['content'][$key] = $variables['elements'][$key];
  }
}
