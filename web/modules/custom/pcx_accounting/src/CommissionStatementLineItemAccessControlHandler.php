<?php

namespace Drupal\pcx_accounting;

use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;

/**
 * Access controller for the Commission statement line item entity.
 *
 * @see \Drupal\pcx_accounting\Entity\CommissionStatementLineItem.
 */
class CommissionStatementLineItemAccessControlHandler extends EntityAccessControlHandler {

  /**
   * {@inheritdoc}
   */
  protected function checkAccess(EntityInterface $entity, $operation, AccountInterface $account) {
    /** @var \Drupal\pcx_accounting\Entity\CommissionStatementLineItemInterface $entity */
    switch ($operation) {
      case 'view':
        if (!$entity->isPublished()) {
          return AccessResult::allowedIfHasPermission($account, 'view unpublished commission statement line item entities');
        }
        return AccessResult::allowedIfHasPermission($account, 'view published commission statement line item entities');

      case 'update':
        return AccessResult::allowedIfHasPermission($account, 'edit commission statement line item entities');

      case 'delete':
        return AccessResult::allowedIfHasPermission($account, 'delete commission statement line item entities');
    }

    // Unknown operation, no opinion.
    return AccessResult::neutral();
  }

  /**
   * {@inheritdoc}
   */
  protected function checkCreateAccess(AccountInterface $account, array $context, $entity_bundle = NULL) {
    return AccessResult::allowedIfHasPermission($account, 'add commission statement line item entities');
  }

}
