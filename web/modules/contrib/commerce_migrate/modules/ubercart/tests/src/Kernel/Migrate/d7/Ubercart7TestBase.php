<?php

namespace Drupal\Tests\commerce_migrate_ubercart\Kernel\Migrate\d7;

use Drupal\Tests\migrate_drupal\Kernel\d7\MigrateDrupal7TestBase;

/**
 * Test base for Ubercart D7 tests.
 */
abstract class Ubercart7TestBase extends MigrateDrupal7TestBase {

  /**
   * Modules to enable.
   *
   * @var array
   */
  public static $modules = [
    'action',
    'address',
    'commerce',
    'commerce_price',
    'commerce_store',
    'commerce_order',
    'commerce_migrate',
    'entity',
    'entity_reference_revisions',
    'inline_entity_form',
    'profile',
    'state_machine',
    'text',
    'views',
    'commerce_migrate_ubercart',
    'telephone',
  ];

  /**
   * {@inheritdoc}
   */
  protected function setUp() {
    parent::setUp();
    $this->installEntitySchema('commerce_store');
    $this->installConfig(static::$modules);
  }

  /**
   * Gets the path to the fixture file.
   */
  protected function getFixtureFilePath() {
    return __DIR__ . '/../../../../fixtures/uc7.php';
  }

  /**
   * Creates a default store.
   */
  protected function createDefaultStore() {
    $currency_importer = \Drupal::service('commerce_price.currency_importer');
    /** @var \Drupal\commerce_store\StoreStorage $store_storage */
    $store_storage = \Drupal::service('entity_type.manager')->getStorage('commerce_store');

    $currency_importer->import('USD');
    $store_values = [
      'type' => 'default',
      'uid' => 1,
      'name' => 'Demo store',
      'mail' => 'admin@example.com',
      'address' => [
        'country_code' => 'US',
      ],
      'default_currency' => 'USD',
    ];
    $store = $store_storage->create($store_values);
    $store->save();
    $store_storage->markAsDefault($store);
  }

}
