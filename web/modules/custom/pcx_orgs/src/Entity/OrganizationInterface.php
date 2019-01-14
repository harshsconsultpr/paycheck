<?php

namespace Drupal\pcx_orgs\Entity;

use Drupal\Core\Entity\RevisionLogInterface;
use Drupal\Core\Entity\RevisionableInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface for defining Organization entities.
 *
 * @ingroup pcx_orgs
 */
interface OrganizationInterface extends RevisionableInterface, RevisionLogInterface, EntityChangedInterface, EntityOwnerInterface {

  // Add get/set methods for your configuration properties here.

  /**
   * Gets the Organization name.
   *
   * @return string
   *   Name of the Organization.
   */
  public function getName();

  /**
   * Sets the Organization name.
   *
   * @param string $name
   *   The Organization name.
   *
   * @return \Drupal\pcx_orgs\Entity\OrganizationInterface
   *   The called Organization entity.
   */
  public function setName($name);

  /**
   * Gets the Organization creation timestamp.
   *
   * @return int
   *   Creation timestamp of the Organization.
   */
  public function getCreatedTime();

  /**
   * Sets the Organization creation timestamp.
   *
   * @param int $timestamp
   *   The Organization creation timestamp.
   *
   * @return \Drupal\pcx_orgs\Entity\OrganizationInterface
   *   The called Organization entity.
   */
  public function setCreatedTime($timestamp);

  /**
   * Returns the Organization published status indicator.
   *
   * Unpublished Organization are only visible to restricted users.
   *
   * @return bool
   *   TRUE if the Organization is published.
   */
  public function isPublished();

  /**
   * Sets the published status of a Organization.
   *
   * @param bool $published
   *   TRUE to set this Organization to published, FALSE to set it to unpublished.
   *
   * @return \Drupal\pcx_orgs\Entity\OrganizationInterface
   *   The called Organization entity.
   */
  public function setPublished($published);

  /**
   * Gets the Organization revision creation timestamp.
   *
   * @return int
   *   The UNIX timestamp of when this revision was created.
   */
  public function getRevisionCreationTime();

  /**
   * Sets the Organization revision creation timestamp.
   *
   * @param int $timestamp
   *   The UNIX timestamp of when this revision was created.
   *
   * @return \Drupal\pcx_orgs\Entity\OrganizationInterface
   *   The called Organization entity.
   */
  public function setRevisionCreationTime($timestamp);

  /**
   * Gets the Organization revision author.
   *
   * @return \Drupal\user\UserInterface
   *   The user entity for the revision author.
   */
  public function getRevisionUser();

  /**
   * Sets the Organization revision author.
   *
   * @param int $uid
   *   The user ID of the revision author.
   *
   * @return \Drupal\pcx_orgs\Entity\OrganizationInterface
   *   The called Organization entity.
   */
  public function setRevisionUserId($uid);

}
