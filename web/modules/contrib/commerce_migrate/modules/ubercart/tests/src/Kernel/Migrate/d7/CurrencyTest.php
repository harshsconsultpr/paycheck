<?php

namespace Drupal\Tests\commerce_migrate_ubercart\Kernel\Migrate\d7;

use Drupal\Tests\commerce_migrate\Kernel\CommerceMigrateTestTrait;

/**
 * Tests currency migration.
 *
 * @group commerce_migrate
 * @group commerce_migrate_ubercart_d7
 */
class CurrencyTest extends Ubercart7TestBase {

  use CommerceMigrateTestTrait;

  /**
   * {@inheritdoc}
   */
  protected function setUp() {
    parent::setUp();
    $this->executeMigration('ubercart_currency');
  }

  /**
   * Test currency migration from Drupal 7 to 8.
   */
  public function testCurrency() {
    $this->assertCurrencyEntity('USD', 'USD', 'US Dollar', '840', 2, '$');
  }

}
