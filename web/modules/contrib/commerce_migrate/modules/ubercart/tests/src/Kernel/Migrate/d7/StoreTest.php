<?php

namespace Drupal\Tests\commerce_migrate_ubercart\Kernel\Migrate\d7;

use Drupal\commerce_store\Entity\Store;
use Drupal\Tests\commerce_migrate\Kernel\CommerceMigrateTestTrait;

/**
 * Tests store migration.
 *
 * @group commerce_migrate
 * @group commerce_migrate_ubercart_d7
 */
class StoreTest extends Ubercart7TestBase {

  use CommerceMigrateTestTrait;

  /**
   * {@inheritdoc}
   */
  protected function setUp() {
    parent::setUp();
    $this->installEntitySchema('commerce_store');
    $this->executeMigrations([
      'd7_filter_format',
      'd7_user_role',
      'd7_user',
      'ubercart_currency',
      'd7_ubercart_store',
    ]);
  }

  /**
   * Test store migration from Drupal 7 to 8.
   */
  public function testStore() {
    $this->assertStoreEntity(1, "Quark's", 'quark@example.com', 'USD', 'online', '1');

    $store = Store::load(1);
    $address = $store->getAddress();
    $this->assertAddressItem($address, 'CA', NULL, 'Deep Space 9', NULL, '9999', NULL, '47 The Promenade', 'Lower Level', NULL, NULL, NULL, NULL);
  }

}
