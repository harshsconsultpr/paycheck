<?php

namespace Drupal\pcx_accounting;

use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;

/**
 * Access controller for the Deduction file line item entity.
 *
 * @see \Drupal\pcx_accounting\Entity\DeductionFileLineItem.
 */
class DeductionFileLineItemAccessControlHandler extends EntityAccessControlHandler {

  /**
   * {@inheritdoc}
   */
  protected function checkAccess(EntityInterface $entity, $operation, AccountInterface $account) {
    /** @var \Drupal\pcx_accounting\Entity\DeductionFileLineItemInterface $entity */
    switch ($operation) {
      case 'view':
        if (!$entity->isPublished()) {
          return AccessResult::allowedIfHasPermission($account, 'view unpublished deduction file line item entities');
        }
        return AccessResult::allowedIfHasPermission($account, 'view published deduction file line item entities');

      case 'update':
        return AccessResult::allowedIfHasPermission($account, 'edit deduction file line item entities');

      case 'delete':
        return AccessResult::allowedIfHasPermission($account, 'delete deduction file line item entities');
    }

    // Unknown operation, no opinion.
    return AccessResult::neutral();
  }

  /**
   * {@inheritdoc}
   */
  protected function checkCreateAccess(AccountInterface $account, array $context, $entity_bundle = NULL) {
    return AccessResult::allowedIfHasPermission($account, 'add deduction file line item entities');
  }

}
