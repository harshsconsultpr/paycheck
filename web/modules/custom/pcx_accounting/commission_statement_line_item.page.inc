<?php

/**
 * @file
 * Contains commission_statement_line_item.page.inc.
 *
 * Page callback for Commission statement line item entities.
 */

use Drupal\Core\Render\Element;

/**
 * Prepares variables for Commission statement line item templates.
 *
 * Default template: commission_statement_line_item.html.twig.
 *
 * @param array $variables
 *   An associative array containing:
 *   - elements: An associative array containing the user information and any
 *   - attributes: HTML attributes for the containing element.
 */
function template_preprocess_commission_statement_line_item(array &$variables) {
  // Fetch CommissionStatementLineItem Entity Object.
  $commission_statement_line_item = $variables['elements']['#commission_statement_line_item'];

  // Helpful $content variable for templates.
  foreach (Element::children($variables['elements']) as $key) {
    $variables['content'][$key] = $variables['elements'][$key];
  }
}
