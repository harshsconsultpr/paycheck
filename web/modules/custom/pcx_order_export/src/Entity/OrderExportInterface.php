<?php

namespace Drupal\pcx_order_export\Entity;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface for defining Order Export entities.
 *
 * @ingroup pcx_order_export
 */
interface OrderExportInterface extends  ContentEntityInterface, EntityChangedInterface, EntityOwnerInterface {

  // Add get/set methods for your configuration properties here.

  /**
   * Gets the Order Export name.
   *
   * @return string
   *   Name of the Order Export.
   */
  public function getName();

  /**
   * Sets the Order Export name.
   *
   * @param string $name
   *   The Order Export name.
   *
   * @return \Drupal\pcx_order_export\Entity\OrderExportInterface
   *   The called Order Export entity.
   */
  public function setName($name);

  /**
   * Gets the Order Export creation timestamp.
   *
   * @return int
   *   Creation timestamp of the Order Export.
   */
  public function getCreatedTime();

  /**
   * Sets the Order Export creation timestamp.
   *
   * @param int $timestamp
   *   The Order Export creation timestamp.
   *
   * @return \Drupal\pcx_order_export\Entity\OrderExportInterface
   *   The called Order Export entity.
   */
  public function setCreatedTime($timestamp);

  /**
   * Returns the Order Export published status indicator.
   *
   * Unpublished Order Export are only visible to restricted users.
   *
   * @return bool
   *   TRUE if the Order Export is published.
   */
  public function isPublished();

  /**
   * Sets the published status of a Order Export.
   *
   * @param bool $published
   *   TRUE to set this Order Export to published, FALSE to set it to unpublished.
   *
   * @return \Drupal\pcx_order_export\Entity\OrderExportInterface
   *   The called Order Export entity.
   */
  public function setPublished($published);

}
