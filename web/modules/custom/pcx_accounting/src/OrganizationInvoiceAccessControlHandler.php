<?php

namespace Drupal\pcx_accounting;

use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;

/**
 * Access controller for the Organization Invoice entity.
 *
 * @see \Drupal\pcx_accounting\Entity\OrganizationInvoice.
 */
class OrganizationInvoiceAccessControlHandler extends EntityAccessControlHandler {

  /**
   * {@inheritdoc}
   */
  protected function checkAccess(EntityInterface $entity, $operation, AccountInterface $account) {
    /** @var \Drupal\pcx_accounting\Entity\OrganizationInvoiceInterface $entity */
    switch ($operation) {
      case 'view':
        if (!$entity->isPublished()) {
          return AccessResult::allowedIfHasPermission($account, 'view unpublished organization invoice entities');
        }
        return AccessResult::allowedIfHasPermission($account, 'view published organization invoice entities');

      case 'update':
        return AccessResult::allowedIfHasPermission($account, 'edit organization invoice entities');

      case 'delete':
        return AccessResult::allowedIfHasPermission($account, 'delete organization invoice entities');
    }

    // Unknown operation, no opinion.
    return AccessResult::neutral();
  }

  /**
   * {@inheritdoc}
   */
  protected function checkCreateAccess(AccountInterface $account, array $context, $entity_bundle = NULL) {
    return AccessResult::allowedIfHasPermission($account, 'add organization invoice entities');
  }

}
