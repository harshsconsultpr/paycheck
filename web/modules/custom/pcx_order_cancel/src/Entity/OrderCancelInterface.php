<?php

namespace Drupal\pcx_order_cancel\Entity;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface for defining Order Cancel entities.
 *
 * @ingroup pcx_order_cancel
 */
interface OrderCancelInterface extends  ContentEntityInterface, EntityChangedInterface, EntityOwnerInterface {

  // Add get/set methods for your configuration properties here.

  /**
   * Gets the Order Cancel name.
   *
   * @return string
   *   Name of the Order Cancel.
   */
  public function getName();

  /**
   * Sets the Order Cancel name.
   *
   * @param string $name
   *   The Order Cancel name.
   *
   * @return \Drupal\pcx_order_cancel\Entity\OrderCancelInterface
   *   The called Order Cancel entity.
   */
  public function setName($name);

  /**
   * Gets the Order Cancel creation timestamp.
   *
   * @return int
   *   Creation timestamp of the Order Cancel.
   */
  public function getCreatedTime();

  /**
   * Sets the Order Cancel creation timestamp.
   *
   * @param int $timestamp
   *   The Order Cancel creation timestamp.
   *
   * @return \Drupal\pcx_order_cancel\Entity\OrderCancelInterface
   *   The called Order Cancel entity.
   */
  public function setCreatedTime($timestamp);

  /**
   * Returns the Order Cancel published status indicator.
   *
   * Unpublished Order Cancel are only visible to restricted users.
   *
   * @return bool
   *   TRUE if the Order Cancel is published.
   */
  public function isPublished();

  /**
   * Sets the published status of a Order Cancel.
   *
   * @param bool $published
   *   TRUE to set this Order Cancel to published, FALSE to set it to unpublished.
   *
   * @return \Drupal\pcx_order_cancel\Entity\OrderCancelInterface
   *   The called Order Cancel entity.
   */
  public function setPublished($published);

}
