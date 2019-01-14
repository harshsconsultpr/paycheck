<?php

namespace Drupal\Tests\commerce_migrate_commerce\Kernel\Migrate\d7;

use Drupal\Tests\migrate_drupal\Kernel\d7\MigrateDrupal7TestBase;

/**
 * Base calls for Commerce migration tests.
 *
 * @package Drupal\Tests\commerce_migrate_commerce\Kernel\Migrate\d7
 */
abstract class Commerce1TestBase extends MigrateDrupal7TestBase {

  /**
   * Modules to enable.
   *
   * @var array
   */
  public static $modules = [
    'action',
    'profile',
    'address',
    'entity',
    'entity_reference_revisions',
    'inline_entity_form',
    'state_machine',
    'text',
    'views',
    'commerce',
    'commerce_price',
    'commerce_store',
    'commerce_order',
    'commerce_migrate',
    'commerce_migrate_commerce',
  ];

  /**
   * Gets the path to the fixture file.
   */
  protected function getFixtureFilePath() {
    return __DIR__ . '/../../../../fixtures/ck2.php';
  }

  /**
   * Executes store migrations.
   */
  protected function migrateStore() {
    $this->installEntitySchema('commerce_store');
    $this->executeMigrations([
      'd7_filter_format',
      'd7_user_role',
      'd7_user',
      'd7_commerce_currency',
      'd7_commerce_store',
      'd7_commerce_default_store',
    ]);
  }

}
