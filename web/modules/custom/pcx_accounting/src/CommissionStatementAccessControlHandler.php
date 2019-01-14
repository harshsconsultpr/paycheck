<?php

namespace Drupal\pcx_accounting;

use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;

/**
 * Access controller for the Commission Statement entity.
 *
 * @see \Drupal\pcx_accounting\Entity\CommissionStatement.
 */
class CommissionStatementAccessControlHandler extends EntityAccessControlHandler {

  /**
   * {@inheritdoc}
   */
  protected function checkAccess(EntityInterface $entity, $operation, AccountInterface $account) {
    /** @var \Drupal\pcx_accounting\Entity\CommissionStatementInterface $entity */
    switch ($operation) {
      case 'view':
        if (!$entity->isPublished()) {
          return AccessResult::allowedIfHasPermission($account, 'view unpublished commission statement entities');
        }
        return AccessResult::allowedIfHasPermission($account, 'view published commission statement entities');

      case 'update':
        return AccessResult::allowedIfHasPermission($account, 'edit commission statement entities');

      case 'delete':
        return AccessResult::allowedIfHasPermission($account, 'delete commission statement entities');
    }

    // Unknown operation, no opinion.
    return AccessResult::neutral();
  }

  /**
   * {@inheritdoc}
   */
  protected function checkCreateAccess(AccountInterface $account, array $context, $entity_bundle = NULL) {
    return AccessResult::allowedIfHasPermission($account, 'add commission statement entities');
  }

}
