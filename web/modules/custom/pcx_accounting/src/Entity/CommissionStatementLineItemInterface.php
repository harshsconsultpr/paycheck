<?php

namespace Drupal\pcx_accounting\Entity;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface for defining Commission statement line item entities.
 *
 * @ingroup pcx_accounting
 */
interface CommissionStatementLineItemInterface extends  ContentEntityInterface, EntityChangedInterface, EntityOwnerInterface {

  // Add get/set methods for your configuration properties here.

  /**
   * Gets the Commission statement line item name.
   *
   * @return string
   *   Name of the Commission statement line item.
   */
  public function getName();

  /**
   * Sets the Commission statement line item name.
   *
   * @param string $name
   *   The Commission statement line item name.
   *
   * @return \Drupal\pcx_accounting\Entity\CommissionStatementLineItemInterface
   *   The called Commission statement line item entity.
   */
  public function setName($name);

  /**
   * Gets the Commission statement line item creation timestamp.
   *
   * @return int
   *   Creation timestamp of the Commission statement line item.
   */
  public function getCreatedTime();

  /**
   * Sets the Commission statement line item creation timestamp.
   *
   * @param int $timestamp
   *   The Commission statement line item creation timestamp.
   *
   * @return \Drupal\pcx_accounting\Entity\CommissionStatementLineItemInterface
   *   The called Commission statement line item entity.
   */
  public function setCreatedTime($timestamp);

  /**
   * Returns the Commission statement line item published status indicator.
   *
   * Unpublished Commission statement line item are only visible to restricted users.
   *
   * @return bool
   *   TRUE if the Commission statement line item is published.
   */
  public function isPublished();

  /**
   * Sets the published status of a Commission statement line item.
   *
   * @param bool $published
   *   TRUE to set this Commission statement line item to published, FALSE to set it to unpublished.
   *
   * @return \Drupal\pcx_accounting\Entity\CommissionStatementLineItemInterface
   *   The called Commission statement line item entity.
   */
  public function setPublished($published);

}
