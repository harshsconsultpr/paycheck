<?php

namespace Drupal\pcx_accounting\Entity;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface for defining Commission Statement entities.
 *
 * @ingroup pcx_accounting
 */
interface CommissionStatementInterface extends  ContentEntityInterface, EntityChangedInterface, EntityOwnerInterface {

  // Add get/set methods for your configuration properties here.

  /**
   * Gets the Commission Statement name.
   *
   * @return string
   *   Name of the Commission Statement.
   */
  public function getName();

  /**
   * Sets the Commission Statement name.
   *
   * @param string $name
   *   The Commission Statement name.
   *
   * @return \Drupal\pcx_accounting\Entity\CommissionStatementInterface
   *   The called Commission Statement entity.
   */
  public function setName($name);

  /**
   * Gets the Commission Statement creation timestamp.
   *
   * @return int
   *   Creation timestamp of the Commission Statement.
   */
  public function getCreatedTime();

  /**
   * Sets the Commission Statement creation timestamp.
   *
   * @param int $timestamp
   *   The Commission Statement creation timestamp.
   *
   * @return \Drupal\pcx_accounting\Entity\CommissionStatementInterface
   *   The called Commission Statement entity.
   */
  public function setCreatedTime($timestamp);

  /**
   * Returns the Commission Statement published status indicator.
   *
   * Unpublished Commission Statement are only visible to restricted users.
   *
   * @return bool
   *   TRUE if the Commission Statement is published.
   */
  public function isPublished();

  /**
   * Sets the published status of a Commission Statement.
   *
   * @param bool $published
   *   TRUE to set this Commission Statement to published, FALSE to set it to unpublished.
   *
   * @return \Drupal\pcx_accounting\Entity\CommissionStatementInterface
   *   The called Commission Statement entity.
   */
  public function setPublished($published);

}
