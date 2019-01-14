<?php

/**
 * @file
 * Contains order_export.page.inc.
 *
 * Page callback for Order Export entities.
 */

use Drupal\Core\Render\Element;

/**
 * Prepares variables for Order Export templates.
 *
 * Default template: order_export.html.twig.
 *
 * @param array $variables
 *   An associative array containing:
 *   - elements: An associative array containing the user information and any
 *   - attributes: HTML attributes for the containing element.
 */
function template_preprocess_order_export(array &$variables) {
  // Fetch OrderExport Entity Object.
  $order_export = $variables['elements']['#order_export'];

  // Helpful $content variable for templates.
  foreach (Element::children($variables['elements']) as $key) {
    $variables['content'][$key] = $variables['elements'][$key];
  }
}
