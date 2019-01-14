<?php

namespace Drupal\pcx_order_export;

use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;

/**
 * Access controller for the Order Export entity.
 *
 * @see \Drupal\pcx_order_export\Entity\OrderExport.
 */
class OrderExportAccessControlHandler extends EntityAccessControlHandler {

  /**
   * {@inheritdoc}
   */
  protected function checkAccess(EntityInterface $entity, $operation, AccountInterface $account) {
    /** @var \Drupal\pcx_order_export\Entity\OrderExportInterface $entity */
    switch ($operation) {
      case 'view':
        if (!$entity->isPublished()) {
          return AccessResult::allowedIfHasPermission($account, 'view unpublished order export entities');
        }
        return AccessResult::allowedIfHasPermission($account, 'view published order export entities');

      case 'update':
        return AccessResult::allowedIfHasPermission($account, 'edit order export entities');

      case 'delete':
        return AccessResult::allowedIfHasPermission($account, 'delete order export entities');
    }

    // Unknown operation, no opinion.
    return AccessResult::neutral();
  }

  /**
   * {@inheritdoc}
   */
  protected function checkCreateAccess(AccountInterface $account, array $context, $entity_bundle = NULL) {
    return AccessResult::allowedIfHasPermission($account, 'add order export entities');
  }

}
