<?php

namespace Drupal\pcx_order_ship\Entity;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface for defining Order Ship entities.
 *
 * @ingroup pcx_order_ship
 */
interface OrderShipInterface extends  ContentEntityInterface, EntityChangedInterface, EntityOwnerInterface {

  // Add get/set methods for your configuration properties here.

  /**
   * Gets the Order Ship name.
   *
   * @return string
   *   Name of the Order Ship.
   */
  public function getName();

  /**
   * Sets the Order Ship name.
   *
   * @param string $name
   *   The Order Ship name.
   *
   * @return \Drupal\pcx_order_ship\Entity\OrderShipInterface
   *   The called Order Ship entity.
   */
  public function setName($name);

  /**
   * Gets the Order Ship creation timestamp.
   *
   * @return int
   *   Creation timestamp of the Order Ship.
   */
  public function getCreatedTime();

  /**
   * Sets the Order Ship creation timestamp.
   *
   * @param int $timestamp
   *   The Order Ship creation timestamp.
   *
   * @return \Drupal\pcx_order_ship\Entity\OrderShipInterface
   *   The called Order Ship entity.
   */
  public function setCreatedTime($timestamp);

  /**
   * Returns the Order Ship published status indicator.
   *
   * Unpublished Order Ship are only visible to restricted users.
   *
   * @return bool
   *   TRUE if the Order Ship is published.
   */
  public function isPublished();

  /**
   * Sets the published status of a Order Ship.
   *
   * @param bool $published
   *   TRUE to set this Order Ship to published, FALSE to set it to unpublished.
   *
   * @return \Drupal\pcx_order_ship\Entity\OrderShipInterface
   *   The called Order Ship entity.
   */
  public function setPublished($published);

}
