<?php

namespace Drupal\pcx_orgs;

use Drupal\Core\Entity\Sql\SqlContentEntityStorage;
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
class OrganizationStorage extends SqlContentEntityStorage implements OrganizationStorageInterface {

  /**
   * {@inheritdoc}
   */
  public function revisionIds(OrganizationInterface $entity) {
    return $this->database->query(
      'SELECT vid FROM {organization_revision} WHERE id=:id ORDER BY vid',
      [':id' => $entity->id()]
    )->fetchCol();
  }

  /**
   * {@inheritdoc}
   */
  public function userRevisionIds(AccountInterface $account) {
    return $this->database->query(
      'SELECT vid FROM {organization_field_revision} WHERE uid = :uid ORDER BY vid',
      [':uid' => $account->id()]
    )->fetchCol();
  }

  /**
   * {@inheritdoc}
   */
  public function countDefaultLanguageRevisions(OrganizationInterface $entity) {
    return $this->database->query('SELECT COUNT(*) FROM {organization_field_revision} WHERE id = :id AND default_langcode = 1', [':id' => $entity->id()])
      ->fetchField();
  }

  /**
   * {@inheritdoc}
   */
  public function clearRevisionsLanguage(LanguageInterface $language) {
    return $this->database->update('organization_revision')
      ->fields(['langcode' => LanguageInterface::LANGCODE_NOT_SPECIFIED])
      ->condition('langcode', $language->getId())
      ->execute();
  }

}
