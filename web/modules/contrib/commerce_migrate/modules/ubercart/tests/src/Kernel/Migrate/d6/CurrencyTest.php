<?php

namespace Drupal\Tests\commerce_migrate_ubercart\Kernel\Migrate\d6;

use Drupal\Tests\commerce_migrate\Kernel\CommerceMigrateTestTrait;

/**
 * Tests currency migration.
 *
 * @group commerce_migrate
 * @group commerce_migrate_ubercart_d6
 */
class CurrencyTest extends Ubercart6TestBase {

  use CommerceMigrateTestTrait;

  /**
   * {@inheritdoc}
   */
  protected function setUp() {
    parent::setUp();
    $this->executeMigration('ubercart_currency');
  }

  /**
   * Test currency migration from Drupal 6 to 8.
   */
  public function testCurrency() {
    $this->assertCurrencyEntity('NZD', 'NZD', 'New Zealand Dollar', '554', 2, '$');
  }

}
