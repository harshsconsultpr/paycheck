<?php

namespace Drupal\pcx_order_ship;

use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;

/**
 * Access controller for the Order Ship entity.
 *
 * @see \Drupal\pcx_order_ship\Entity\OrderShip.
 */
class OrderShipAccessControlHandler extends EntityAccessControlHandler {

  /**
   * {@inheritdoc}
   */
  protected function checkAccess(EntityInterface $entity, $operation, AccountInterface $account) {
    /** @var \Drupal\pcx_order_ship\Entity\OrderShipInterface $entity */
    switch ($operation) {
      case 'view':
        if (!$entity->isPublished()) {
          return AccessResult::allowedIfHasPermission($account, 'view unpublished order ship entities');
        }
        return AccessResult::allowedIfHasPermission($account, 'view published order ship entities');

      case 'update':
        return AccessResult::allowedIfHasPermission($account, 'edit order ship entities');

      case 'delete':
        return AccessResult::allowedIfHasPermission($account, 'delete order ship entities');
    }

    // Unknown operation, no opinion.
    return AccessResult::neutral();
  }

  /**
   * {@inheritdoc}
   */
  protected function checkCreateAccess(AccountInterface $account, array $context, $entity_bundle = NULL) {
    return AccessResult::allowedIfHasPermission($account, 'add order ship entities');
  }

}
