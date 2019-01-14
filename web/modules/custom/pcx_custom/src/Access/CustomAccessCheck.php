<?php

namespace Drupal\pcx_custom\Access;

use Drupal\Core\Access\AccessResult;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Routing\Access\AccessInterface;
use Drupal\Core\Session\AccountInterface;
use Symfony\Component\Routing\Route;
use Drupal\profile\Entity\ProfileTypeInterface;
use Drupal\user\Entity\Role;

/**
 * Checks access for displaying profile tabs per assigned role
 */
class CustomAccessCheck implements AccessInterface {
  /**
   * A custom access check.
   *
   * @param \Drupal\Core\Session\AccountInterface $account
   *   Run access checks for this account.
   */

  /**
   * The entity manager.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * Constructs a ProfileAccessCheck object.
   *
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager
   *   The entity manager.
   */
  public function __construct(EntityTypeManagerInterface $entity_type_manager) {
    $this->entityTypeManager = $entity_type_manager;
  }

  public function access(Route $route, AccountInterface $account, AccountInterface $user, ProfileTypeInterface $profile_type) {

    switch($profile_type->get('id')) {
      case 'referral_partner':
        if (!$user->hasRole('referral_partner')) {
          return AccessResult::forbidden()->addCacheableDependency($user);
        }
        break;
      case 'employee':
        if (!$user->hasRole('employee')) {
          return AccessResult::forbidden()->addCacheableDependency($user);
        }
        break;
    }

    if ($account->hasPermission('administer profile types')) {
      return AccessResult::allowed()->cachePerPermissions();
    }

    $own_any = ($account->id() == $user->id()) ? 'own' : 'any';
    $operation = ($profile_type->getMultiple()) ? 'view' : 'update';

    return AccessResult::allowedIfHasPermission($account, "$operation $own_any {$profile_type->id()} profile");
  }
}