<?php

namespace Drupal\Tests\commerce_migrate_ubercart\Kernel\Migrate\d6;

use Drupal\commerce_store\Entity\Store;
use Drupal\Tests\commerce_migrate\Kernel\CommerceMigrateTestTrait;

/**
 * Tests store migration.
 *
 * @group commerce_migrate
 * @group commerce_migrate_ubercart_d6
 */
class StoreTest extends Ubercart6TestBase {

  use CommerceMigrateTestTrait;

  /**
   * Modules to enable.
   *
   * @var array
   */
  public static $modules = ['commerce_store'];

  /**
   * {@inheritdoc}
   */
  protected function setUp() {
    parent::setUp();
    $this->installEntitySchema('commerce_store');
    $this->migrateStore();
  }

  /**
   * Test store migration from Drupal 6 to 8.
   */
  public function testStore() {
    $this->assertStoreEntity(1, 'Awesome Stuff', 'awesome_stuff@example.com', 'NZD', 'online', '1');

    $store = Store::load(1);
    $address = $store->getAddress();
    $this->assertAddressItem($address, 'US', NULL, 'Betelgeuse', NULL, '4242', NULL, '123 First Street', '456 Second Street', NULL, NULL, NULL, NULL);
  }

}
