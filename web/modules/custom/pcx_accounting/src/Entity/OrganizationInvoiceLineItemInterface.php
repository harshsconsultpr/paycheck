<?php

namespace Drupal\pcx_accounting\Entity;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface for defining Organization invoice line item entities.
 *
 * @ingroup pcx_accounting
 */
interface OrganizationInvoiceLineItemInterface extends  ContentEntityInterface, EntityChangedInterface, EntityOwnerInterface {

  // Add get/set methods for your configuration properties here.

  /**
   * Gets the Organization invoice line item name.
   *
   * @return string
   *   Name of the Organization invoice line item.
   */
  public function getName();

  /**
   * Sets the Organization invoice line item name.
   *
   * @param string $name
   *   The Organization invoice line item name.
   *
   * @return \Drupal\pcx_accounting\Entity\OrganizationInvoiceLineItemInterface
   *   The called Organization invoice line item entity.
   */
  public function setName($name);

  /**
   * Gets the Organization invoice line item creation timestamp.
   *
   * @return int
   *   Creation timestamp of the Organization invoice line item.
   */
  public function getCreatedTime();

  /**
   * Sets the Organization invoice line item creation timestamp.
   *
   * @param int $timestamp
   *   The Organization invoice line item creation timestamp.
   *
   * @return \Drupal\pcx_accounting\Entity\OrganizationInvoiceLineItemInterface
   *   The called Organization invoice line item entity.
   */
  public function setCreatedTime($timestamp);

  /**
   * Returns the Organization invoice line item published status indicator.
   *
   * Unpublished Organization invoice line item are only visible to restricted users.
   *
   * @return bool
   *   TRUE if the Organization invoice line item is published.
   */
  public function isPublished();

  /**
   * Sets the published status of a Organization invoice line item.
   *
   * @param bool $published
   *   TRUE to set this Organization invoice line item to published, FALSE to set it to unpublished.
   *
   * @return \Drupal\pcx_accounting\Entity\OrganizationInvoiceLineItemInterface
   *   The called Organization invoice line item entity.
   */
  public function setPublished($published);

}
