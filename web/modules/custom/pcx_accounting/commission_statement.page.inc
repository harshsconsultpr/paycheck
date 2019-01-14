<?php

/**
 * @file
 * Contains commission_statement.page.inc.
 *
 * Page callback for Commission Statement entities.
 */

use Drupal\Core\Render\Element;

/**
 * Prepares variables for Commission Statement templates.
 *
 * Default template: commission_statement.html.twig.
 *
 * @param array $variables
 *   An associative array containing:
 *   - elements: An associative array containing the user information and any
 *   - attributes: HTML attributes for the containing element.
 */
function template_preprocess_commission_statement(array &$variables) {
  // Fetch CommissionStatement Entity Object.
  $commission_statement = $variables['elements']['#commission_statement'];

  // Helpful $content variable for templates.
  foreach (Element::children($variables['elements']) as $key) {
    $variables['content'][$key] = $variables['elements'][$key];
  }
}
