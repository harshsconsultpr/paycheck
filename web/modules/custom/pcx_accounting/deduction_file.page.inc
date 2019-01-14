<?php

/**
 * @file
 * Contains deduction_file.page.inc.
 *
 * Page callback for Deduction File entities.
 */

use Drupal\Core\Render\Element;

/**
 * Prepares variables for Deduction File templates.
 *
 * Default template: deduction_file.html.twig.
 *
 * @param array $variables
 *   An associative array containing:
 *   - elements: An associative array containing the user information and any
 *   - attributes: HTML attributes for the containing element.
 */
function template_preprocess_deduction_file(array &$variables) {
  // Fetch DeductionFile Entity Object.
  $deduction_file = $variables['elements']['#deduction_file'];

  // Helpful $content variable for templates.
  foreach (Element::children($variables['elements']) as $key) {
    $variables['content'][$key] = $variables['elements'][$key];
  }
}
