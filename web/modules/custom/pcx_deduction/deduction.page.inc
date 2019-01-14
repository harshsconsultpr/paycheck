<?php

/**
 * @file
 * Contains deduction.page.inc.
 *
 * Page callback for Deduction entities.
 */

use Drupal\Core\Render\Element;

/**
 * Prepares variables for Deduction templates.
 *
 * Default template: deduction.html.twig.
 *
 * @param array $variables
 *   An associative array containing:
 *   - elements: An associative array containing the user information and any
 *   - attributes: HTML attributes for the containing element.
 */
function template_preprocess_deduction(array &$variables) {
  // Fetch Deduction Entity Object.
  $deduction = $variables['elements']['#deduction'];

  // Helpful $content variable for templates.
  foreach (Element::children($variables['elements']) as $key) {
    $variables['content'][$key] = $variables['elements'][$key];
  }
}
