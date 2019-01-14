<?php

namespace Drupal\pcx_rates\Entity;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface for defining Commission rate entities.
 *
 * @ingroup pcx_rates
 */
interface CommissionRateInterface extends  ContentEntityInterface, EntityChangedInterface, EntityOwnerInterface {

  // Add get/set methods for your configuration properties here.

  /**
   * Gets the Commission rate name.
   *
   * @return string
   *   Name of the Commission rate.
   */
  public function getName();

  /**
   * Sets the Commission rate name.
   *
   * @param string $name
   *   The Commission rate name.
   *
   * @return \Drupal\pcx_rates\Entity\CommissionRateInterface
   *   The called Commission rate entity.
   */
  public function setName($name);

  /**
   * Gets the Commission rate creation timestamp.
   *
   * @return int
   *   Creation timestamp of the Commission rate.
   */
  public function getCreatedTime();

  /**
   * Sets the Commission rate creation timestamp.
   *
   * @param int $timestamp
   *   The Commission rate creation timestamp.
   *
   * @return \Drupal\pcx_rates\Entity\CommissionRateInterface
   *   The called Commission rate entity.
   */
  public function setCreatedTime($timestamp);

  /**
   * Returns the Commission rate published status indicator.
   *
   * Unpublished Commission rate are only visible to restricted users.
   *
   * @return bool
   *   TRUE if the Commission rate is published.
   */
  public function isPublished();

  /**
   * Sets the published status of a Commission rate.
   *
   * @param bool $published
   *   TRUE to set this Commission rate to published, FALSE to set it to unpublished.
   *
   * @return \Drupal\pcx_rates\Entity\CommissionRateInterface
   *   The called Commission rate entity.
   */
  public function setPublished($published);

}
