<?php

namespace Drupal\pcx_accounting;

use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;

/**
 * Access controller for the Organization invoice line item entity.
 *
 * @see \Drupal\pcx_accounting\Entity\OrganizationInvoiceLineItem.
 */
class OrganizationInvoiceLineItemAccessControlHandler extends EntityAccessControlHandler {

  /**
   * {@inheritdoc}
   */
  protected function checkAccess(EntityInterface $entity, $operation, AccountInterface $account) {
    /** @var \Drupal\pcx_accounting\Entity\OrganizationInvoiceLineItemInterface $entity */
    switch ($operation) {
      case 'view':
        if (!$entity->isPublished()) {
          return AccessResult::allowedIfHasPermission($account, 'view unpublished organization invoice line item entities');
        }
        return AccessResult::allowedIfHasPermission($account, 'view published organization invoice line item entities');

      case 'update':
        return AccessResult::allowedIfHasPermission($account, 'edit organization invoice line item entities');

      case 'delete':
        return AccessResult::allowedIfHasPermission($account, 'delete organization invoice line item entities');
    }

    // Unknown operation, no opinion.
    return AccessResult::neutral();
  }

  /**
   * {@inheritdoc}
   */
  protected function checkCreateAccess(AccountInterface $account, array $context, $entity_bundle = NULL) {
    return AccessResult::allowedIfHasPermission($account, 'add organization invoice line item entities');
  }

}
