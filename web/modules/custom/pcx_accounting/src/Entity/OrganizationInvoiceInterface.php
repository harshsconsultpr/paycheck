<?php

namespace Drupal\pcx_accounting\Entity;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface for defining Organization Invoice entities.
 *
 * @ingroup pcx_accounting
 */
interface OrganizationInvoiceInterface extends  ContentEntityInterface, EntityChangedInterface, EntityOwnerInterface {

  // Add get/set methods for your configuration properties here.

  /**
   * Gets the Organization Invoice name.
   *
   * @return string
   *   Name of the Organization Invoice.
   */
  public function getName();

  /**
   * Sets the Organization Invoice name.
   *
   * @param string $name
   *   The Organization Invoice name.
   *
   * @return \Drupal\pcx_accounting\Entity\OrganizationInvoiceInterface
   *   The called Organization Invoice entity.
   */
  public function setName($name);

  /**
   * Gets the Organization Invoice creation timestamp.
   *
   * @return int
   *   Creation timestamp of the Organization Invoice.
   */
  public function getCreatedTime();

  /**
   * Sets the Organization Invoice creation timestamp.
   *
   * @param int $timestamp
   *   The Organization Invoice creation timestamp.
   *
   * @return \Drupal\pcx_accounting\Entity\OrganizationInvoiceInterface
   *   The called Organization Invoice entity.
   */
  public function setCreatedTime($timestamp);

  /**
   * Returns the Organization Invoice published status indicator.
   *
   * Unpublished Organization Invoice are only visible to restricted users.
   *
   * @return bool
   *   TRUE if the Organization Invoice is published.
   */
  public function isPublished();

  /**
   * Sets the published status of a Organization Invoice.
   *
   * @param bool $published
   *   TRUE to set this Organization Invoice to published, FALSE to set it to unpublished.
   *
   * @return \Drupal\pcx_accounting\Entity\OrganizationInvoiceInterface
   *   The called Organization Invoice entity.
   */
  public function setPublished($published);

}
