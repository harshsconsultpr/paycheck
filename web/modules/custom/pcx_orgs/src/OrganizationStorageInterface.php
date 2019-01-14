<?php

namespace Drupal\pcx_orgs;

use Drupal\Core\Entity\ContentEntityStorageInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Language\LanguageInterface;
use Drupal\pcx_orgs\Entity\OrganizationInterface;

/**
 * Defines the storage handler class for Organization entities.
 *
 * This extends the base storage class, adding required special handling for
 * Organization entities.
 *
 * @ingroup pcx_orgs
 */
interface OrganizationStorageInterface extends ContentEntityStorageInterface {

  /**
   * Gets a list of Organization revision IDs for a specific Organization.
   *
   * @param \Drupal\pcx_orgs\Entity\OrganizationInterface $entity
   *   The Organization entity.
   *
   * @return int[]
   *   Organization revision IDs (in ascending order).
   */
  public function revisionIds(OrganizationInterface $entity);

  /**
   * Gets a list of revision IDs having a given user as Organization author.
   *
   * @param \Drupal\Core\Session\AccountInterface $account
   *   The user entity.
   *
   * @return int[]
   *   Organization revision IDs (in ascending order).
   */
  public function userRevisionIds(AccountInterface $account);

  /**
   * Counts the number of revisions in the default language.
   *
   * @param \Drupal\pcx_orgs\Entity\OrganizationInterface $entity
   *   The Organization entity.
   *
   * @return int
   *   The number of revisions in the default language.
   */
  public function countDefaultLanguageRevisions(OrganizationInterface $entity);

  /**
   * Unsets the language for all Organization with the given language.
   *
   * @param \Drupal\Core\Language\LanguageInterface $language
   *   The language object.
   */
  public function clearRevisionsLanguage(LanguageInterface $language);

}
