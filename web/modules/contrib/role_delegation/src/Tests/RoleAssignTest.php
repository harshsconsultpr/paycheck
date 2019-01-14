<?php

/**
 * @file
 * Contains \Drupal\role_delegation\Tests\RoleAssignTest.
 */

namespace Drupal\role_delegation\Tests;

use Drupal\simpletest\WebTestBase;
use Drupal\user\Entity\User;

/**
 * Functional tests for assigning roles.
 *
 * @group role_delegation
 */
class RoleAssignTest extends WebTestBase {

  /**
   * Modules to enable.
   *
   * @var array
   */
  public static $modules = ['user', 'role_delegation'];

  /**
   * Ensure we can only see the roles we have permission to assign.
   */
  public function testRoleAccess() {
    $rid1 = $this->drupalCreateRole([]);
    $rid2 = $this->drupalCreateRole([]);
    $rid3 = $this->drupalCreateRole([]);

    // Only 2 of the 3 roles appear on the roles edit page.
    $current_user = $this->drupalCreateUser(["assign $rid1 role", "assign $rid2 role"]);
    $this->drupalLogin($current_user);
    $account = $this->drupalCreateUser();
    $this->drupalGet(sprintf('/user/%s/roles', $account->id()));
    $this->assertFieldByName("role_change[$rid1]", NULL, 'Role 1 delegation option found on roles edit page.');
    $this->assertFieldByName("role_change[$rid2]", NULL, 'Role 2 delegation option found on roles edit page.');
    $this->assertNoFieldByName("role_change[$rid3]", NULL, 'Role 3 delegation option not found on roles edit page.');

    // A user who can access the real roles field should not see the role
    // delegation field.
    $current_user = $this->drupalCreateUser(['administer users', 'administer permissions', 'assign all roles']);
    $this->drupalLogin($current_user);
    $this->drupalGet(sprintf('/user/%s/edit', $account->id()));
    $this->assertFieldByName("roles[$rid1]", NULL, 'Role field found on user edit page.');
    $this->assertNoFieldByName("role_change[$rid1]", NULL, 'Role delegation field not found on user edit page.');

    // A user who can edit a user, but does not have access to the real role
    // field, but can delegate should see the role delegation field.
    $current_user = $this->drupalCreateUser(['administer users', 'assign all roles']);
    $this->drupalLogin($current_user);
    $this->drupalGet(sprintf('/user/%s/edit', $account->id()));
    $this->assertNoFieldByName("roles[$rid1]", NULL, 'Role field not found on user edit page.');
    $this->assertFieldByName("role_change[$rid1]", NULL, 'Role delegation field found on user edit page.');

    // Similar, but single role permissions rather than assigning all roles.
    $current_user = $this->drupalCreateUser(['administer users', "assign $rid1 role"]);
    $this->drupalLogin($current_user);
    $this->drupalGet(sprintf('/user/%s/edit', $account->id()));
    $this->assertNoFieldByName("roles[$rid1]", NULL, 'Role field not found on user edit page when granted an individual permission.');
    $this->assertFieldByName("role_change[$rid1]", NULL, 'Role delegation option found on user edit page when granted the individual permission.');
    $this->assertNoFieldByName("role_change[$rid2]", NULL, 'Role delegation option not found on user edit page when not granted the individual permission.');

    $access = $account->get('role_change')->access('edit', $current_user);
  }

  /**
   * Test that we can assign roles we have access to via the Roles form.
   */
  public function testRoleAssignRolesForm() {
    $rid1 = $this->drupalCreateRole([]);
    $rid2 = $this->drupalCreateRole([]);
    $current_user = $this->drupalCreateUser(["assign $rid1 role", "assign $rid2 role"]);
    $this->drupalLogin($current_user);

    // Go to the users roles edit page.
    $account = $this->drupalCreateUser();
    $this->drupalGet(sprintf('/user/%s/roles', $account->id()));

    // The form element field id and name.
    $field_id = "edit-role-change-$rid1";
    $field_name = "role_change[$rid1]";

    // Ensure its disabled by default.
    $this->assertFalse($account->hasPermission("assign $rid1 role"), "The target user does not have the role by default.");
    $this->assertNoFieldChecked($field_id, "Lack of role by default is reflected in checkbox.");

    // Assign the role and ensure its now checked and assigned.
    $this->drupalPostForm(NULL, [$field_name => $rid1], 'Save');
    \Drupal::entityTypeManager()->clearCachedDefinitions();
    $account = User::load($account->id());
    $this->assertTrue($account->hasRole($rid1), "The target user has been granted the role.");
    $this->assertFieldChecked($field_id, "The role grant is reflected in the checkbox.");

    // Revoke the role.
    $this->drupalPostForm(NULL, [$field_name => FALSE], 'Save');
    \Drupal::entityTypeManager()->clearCachedDefinitions();
    $account = User::load($account->id());
    $this->assertFalse($account->hasRole($rid1), "The target user has gotten the role revoked.");
    $this->assertNoFieldChecked($field_id, "The role revocation is reflected in the checkbox.");
  }

  /**
   * Test that we can assign roles we have access to via the user edit form.
   */
  public function testRoleAssignUserForm() {
    $rid1 = $this->drupalCreateRole([]);
    $rid2 = $this->drupalCreateRole([]);
    $current_user = $this->drupalCreateUser(['administer users', 'assign all roles']);
    $this->drupalLogin($current_user);

    // Go to the users roles edit page.
    $account = $this->drupalCreateUser();
    $this->drupalGet(sprintf('/user/%s/edit', $account->id()));

    // The form element field id and name.
    $field_id = "edit-role-change-$rid1";
    $field_name = "role_change[$rid1]";

    // Ensure its disabled by default.
    $this->assertFalse($account->hasPermission("assign $rid1 role"), "The target user does not have the role by default.");
    $this->assertNoFieldChecked($field_id, "Lack of role by default is reflected in checkbox.");

    // Assign the role and ensure its now checked and assigned.
    $this->drupalPostForm(NULL, [$field_name => $rid1], 'Save');
    \Drupal::entityTypeManager()->clearCachedDefinitions();
    $account = User::load($account->id());
    $this->assertTrue($account->hasRole($rid1), "The target user has been granted the role.");
    $this->assertFieldChecked($field_id, "The role grant is reflected in the checkbox.");

    // Revoke the role.
    $this->drupalPostForm(NULL, [$field_name => FALSE], 'Save');
    \Drupal::entityTypeManager()->clearCachedDefinitions();
    $account = User::load($account->id());
    $this->assertFalse($account->hasRole($rid1), "The target user has gotten the role revoked.");
    $this->assertNoFieldChecked($field_id, "The role revocation is reflected in the checkbox.");
  }

}
