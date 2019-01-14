<?php

namespace Drupal\pcx_accounting\Entity;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface for defining Deduction file line item entities.
 *
 * @ingroup pcx_accounting
 */
interface DeductionFileLineItemInterface extends  ContentEntityInterface, EntityChangedInterface, EntityOwnerInterface {

  // Add get/set methods for your configuration properties here.

  /**
   * Gets the Deduction file line item name.
   *
   * @return string
   *   Name of the Deduction file line item.
   */
  public function getName();

  /**
   * Sets the Deduction file line item name.
   *
   * @param string $name
   *   The Deduction file line item name.
   *
   * @return \Drupal\pcx_accounting\Entity\DeductionFileLineItemInterface
   *   The called Deduction file line item entity.
   */
  public function setName($name);

  /**
   * Gets the Deduction file line item creation timestamp.
   *
   * @return int
   *   Creation timestamp of the Deduction file line item.
   */
  public function getCreatedTime();

  /**
   * Sets the Deduction file line item creation timestamp.
   *
   * @param int $timestamp
   *   The Deduction file line item creation timestamp.
   *
   * @return \Drupal\pcx_accounting\Entity\DeductionFileLineItemInterface
   *   The called Deduction file line item entity.
   */
  public function setCreatedTime($timestamp);

  /**
   * Returns the Deduction file line item published status indicator.
   *
   * Unpublished Deduction file line item are only visible to restricted users.
   *
   * @return bool
   *   TRUE if the Deduction file line item is published.
   */
  public function isPublished();

  /**
   * Sets the published status of a Deduction file line item.
   *
   * @param bool $published
   *   TRUE to set this Deduction file line item to published, FALSE to set it to unpublished.
   *
   * @return \Drupal\pcx_accounting\Entity\DeductionFileLineItemInterface
   *   The called Deduction file line item entity.
   */
  public function setPublished($published);

}
