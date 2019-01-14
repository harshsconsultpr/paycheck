<?php

/**
 * @file
 * Contains commission_rate.page.inc.
 *
 * Page callback for Commission rate entities.
 */

use Drupal\Core\Render\Element;

/**
 * Prepares variables for Commission rate templates.
 *
 * Default template: commission_rate.html.twig.
 *
 * @param array $variables
 *   An associative array containing:
 *   - elements: An associative array containing the user information and any
 *   - attributes: HTML attributes for the containing element.
 */
function template_preprocess_commission_rate(array &$variables) {
  // Fetch CommissionRate Entity Object.
  $commission_rate = $variables['elements']['#commission_rate'];

  // Helpful $content variable for templates.
  foreach (Element::children($variables['elements']) as $key) {
    $variables['content'][$key] = $variables['elements'][$key];
  }
}
