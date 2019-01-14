<?php

namespace Drupal\Tests\commerce_migrate_commerce\Kernel\Migrate\d7;

use Drupal\Tests\commerce_migrate\Kernel\CommerceMigrateTestTrait;

/**
 * Tests currency migration.
 *
 * @group commerce_migrate
 * @group commerce_migrate_commerce_d7
 */
class CurrencyTest extends Commerce1TestBase {

  use CommerceMigrateTestTrait;

  /**
   * {@inheritdoc}
   */
  protected function setUp() {
    parent::setUp();
    $this->executeMigration('d7_commerce_currency');
  }

  /**
   * Test currency migration from Drupal 7 to 8.
   */
  public function testCurrency() {
    $this->assertCurrencyEntity('USD', 'USD', 'US Dollar', '840', 2, '$');
  }

}
