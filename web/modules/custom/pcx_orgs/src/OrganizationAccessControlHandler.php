<?php

namespace Drupal\pcx_orgs;

use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;

/**
 * Access controller for the Organization entity.
 *
 * @see \Drupal\pcx_orgs\Entity\Organization.
 */
class OrganizationAccessControlHandler extends EntityAccessControlHandler {

  /**
   * {@inheritdoc}
   */
  protected function checkAccess(EntityInterface $entity, $operation, AccountInterface $account) {
    /** @var \Drupal\pcx_orgs\Entity\OrganizationInterface $entity */
    switch ($operation) {
      case 'view':
        if (!$entity->isPublished()) {
          return AccessResult::allowedIfHasPermission($account, 'view unpublished organization entities');
        }
        return AccessResult::allowedIfHasPermission($account, 'view published organization entities');

      case 'update':
        return AccessResult::allowedIfHasPermission($account, 'edit organization entities');

      case 'delete':
        return AccessResult::allowedIfHasPermission($account, 'delete organization entities');
    }

    // Unknown operation, no opinion.
    return AccessResult::neutral();
  }

  /**
   * {@inheritdoc}
   */
  protected function checkCreateAccess(AccountInterface $account, array $context, $entity_bundle = NULL) {
    return AccessResult::allowedIfHasPermission($account, 'add organization entities');
  }

}
