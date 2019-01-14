<?php

/**
 * @file
 * Contains organization_invoice_line_item.page.inc.
 *
 * Page callback for Organization invoice line item entities.
 */

use Drupal\Core\Render\Element;

/**
 * Prepares variables for Organization invoice line item templates.
 *
 * Default template: organization_invoice_line_item.html.twig.
 *
 * @param array $variables
 *   An associative array containing:
 *   - elements: An associative array containing the user information and any
 *   - attributes: HTML attributes for the containing element.
 */
function template_preprocess_organization_invoice_line_item(array &$variables) {
  // Fetch OrganizationInvoiceLineItem Entity Object.
  $organization_invoice_line_item = $variables['elements']['#organization_invoice_line_item'];

  // Helpful $content variable for templates.
  foreach (Element::children($variables['elements']) as $key) {
    $variables['content'][$key] = $variables['elements'][$key];
  }
}
