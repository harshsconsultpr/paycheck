<?php

namespace Drupal\pcx_deduction;

use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;

/**
 * Access controller for the Deduction entity.
 *
 * @see \Drupal\pcx_deduction\Entity\Deduction.
 */
class DeductionAccessControlHandler extends EntityAccessControlHandler {

  /**
   * {@inheritdoc}
   */
  protected function checkAccess(EntityInterface $entity, $operation, AccountInterface $account) {
    /** @var \Drupal\pcx_deduction\Entity\DeductionInterface $entity */
    switch ($operation) {
      case 'view':
        if (!$entity->isPublished()) {
          return AccessResult::allowedIfHasPermission($account, 'view unpublished deduction entities');
        }
        return AccessResult::allowedIfHasPermission($account, 'view published deduction entities');

      case 'update':
        return AccessResult::allowedIfHasPermission($account, 'edit deduction entities');

      case 'delete':
        return AccessResult::allowedIfHasPermission($account, 'delete deduction entities');
    }

    // Unknown operation, no opinion.
    return AccessResult::neutral();
  }

  /**
   * {@inheritdoc}
   */
  protected function checkCreateAccess(AccountInterface $account, array $context, $entity_bundle = NULL) {
    return AccessResult::allowedIfHasPermission($account, 'add deduction entities');
  }

}
