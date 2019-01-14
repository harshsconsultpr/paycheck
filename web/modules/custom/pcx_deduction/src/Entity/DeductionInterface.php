<?php

namespace Drupal\pcx_deduction\Entity;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface for defining Deduction entities.
 *
 * @ingroup pcx_deduction
 */
interface DeductionInterface extends  ContentEntityInterface, EntityChangedInterface, EntityOwnerInterface {

  // Add get/set methods for your configuration properties here.

  /**
   * Gets the Deduction name.
   *
   * @return string
   *   Name of the Deduction.
   */
  public function getName();

  /**
   * Sets the Deduction name.
   *
   * @param string $name
   *   The Deduction name.
   *
   * @return \Drupal\pcx_deduction\Entity\DeductionInterface
   *   The called Deduction entity.
   */
  public function setName($name);

  /**
   * Gets the Deduction creation timestamp.
   *
   * @return int
   *   Creation timestamp of the Deduction.
   */
  public function getCreatedTime();

  /**
   * Sets the Deduction creation timestamp.
   *
   * @param int $timestamp
   *   The Deduction creation timestamp.
   *
   * @return \Drupal\pcx_deduction\Entity\DeductionInterface
   *   The called Deduction entity.
   */
  public function setCreatedTime($timestamp);

  /**
   * Returns the Deduction published status indicator.
   *
   * Unpublished Deduction are only visible to restricted users.
   *
   * @return bool
   *   TRUE if the Deduction is published.
   */
  public function isPublished();

  /**
   * Sets the published status of a Deduction.
   *
   * @param bool $published
   *   TRUE to set this Deduction to published, FALSE to set it to unpublished.
   *
   * @return \Drupal\pcx_deduction\Entity\DeductionInterface
   *   The called Deduction entity.
   */
  public function setPublished($published);

}
