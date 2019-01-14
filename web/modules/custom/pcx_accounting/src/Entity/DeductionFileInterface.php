<?php

namespace Drupal\pcx_accounting\Entity;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface for defining Deduction File entities.
 *
 * @ingroup pcx_accounting
 */
interface DeductionFileInterface extends  ContentEntityInterface, EntityChangedInterface, EntityOwnerInterface {

  // Add get/set methods for your configuration properties here.

  /**
   * Gets the Deduction File name.
   *
   * @return string
   *   Name of the Deduction File.
   */
  public function getName();

  /**
   * Sets the Deduction File name.
   *
   * @param string $name
   *   The Deduction File name.
   *
   * @return \Drupal\pcx_accounting\Entity\DeductionFileInterface
   *   The called Deduction File entity.
   */
  public function setName($name);

  /**
   * Gets the Deduction File creation timestamp.
   *
   * @return int
   *   Creation timestamp of the Deduction File.
   */
  public function getCreatedTime();

  /**
   * Sets the Deduction File creation timestamp.
   *
   * @param int $timestamp
   *   The Deduction File creation timestamp.
   *
   * @return \Drupal\pcx_accounting\Entity\DeductionFileInterface
   *   The called Deduction File entity.
   */
  public function setCreatedTime($timestamp);

  /**
   * Returns the Deduction File published status indicator.
   *
   * Unpublished Deduction File are only visible to restricted users.
   *
   * @return bool
   *   TRUE if the Deduction File is published.
   */
  public function isPublished();

  /**
   * Sets the published status of a Deduction File.
   *
   * @param bool $published
   *   TRUE to set this Deduction File to published, FALSE to set it to unpublished.
   *
   * @return \Drupal\pcx_accounting\Entity\DeductionFileInterface
   *   The called Deduction File entity.
   */
  public function setPublished($published);

}
