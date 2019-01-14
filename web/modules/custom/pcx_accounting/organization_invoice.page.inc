<?php

/**
 * @file
 * Contains organization_invoice.page.inc.
 *
 * Page callback for Organization Invoice entities.
 */

use Drupal\Core\Render\Element;

/**
 * Prepares variables for Organization Invoice templates.
 *
 * Default template: organization_invoice.html.twig.
 *
 * @param array $variables
 *   An associative array containing:
 *   - elements: An associative array containing the user information and any
 *   - attributes: HTML attributes for the containing element.
 */
function template_preprocess_organization_invoice(array &$variables) {
  // Fetch OrganizationInvoice Entity Object.
  $organization_invoice = $variables['elements']['#organization_invoice'];

  // Helpful $content variable for templates.
  foreach (Element::children($variables['elements']) as $key) {
    $variables['content'][$key] = $variables['elements'][$key];
  }
}
