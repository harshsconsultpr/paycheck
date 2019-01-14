<?php

namespace Drupal\pcx_order_cancel;

use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;

/**
 * Access controller for the Order Cancel entity.
 *
 * @see \Drupal\pcx_order_cancel\Entity\OrderCancel.
 */
class OrderCancelAccessControlHandler extends EntityAccessControlHandler {

  /**
   * {@inheritdoc}
   */
  protected function checkAccess(EntityInterface $entity, $operation, AccountInterface $account) {
    /** @var \Drupal\pcx_order_cancel\Entity\OrderCancelInterface $entity */
    switch ($operation) {
      case 'view':
        if (!$entity->isPublished()) {
          return AccessResult::allowedIfHasPermission($account, 'view unpublished order cancel entities');
        }
        return AccessResult::allowedIfHasPermission($account, 'view published order cancel entities');

      case 'update':
        return AccessResult::allowedIfHasPermission($account, 'edit order cancel entities');

      case 'delete':
        return AccessResult::allowedIfHasPermission($account, 'delete order cancel entities');
    }

    // Unknown operation, no opinion.
    return AccessResult::neutral();
  }

  /**
   * {@inheritdoc}
   */
  protected function checkCreateAccess(AccountInterface $account, array $context, $entity_bundle = NULL) {
    return AccessResult::allowedIfHasPermission($account, 'add order cancel entities');
  }

}
