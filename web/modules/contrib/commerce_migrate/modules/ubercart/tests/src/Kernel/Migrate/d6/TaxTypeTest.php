<?php

namespace Drupal\Tests\commerce_migrate_ubercart\Kernel\Migrate\d6;

use Drupal\Tests\commerce_migrate\Kernel\CommerceMigrateTestTrait;

/**
 * Tests tax type migration.
 *
 * @group commerce_migrate
 * @group commerce_migrate_ubercart_d6
 */
class TaxTypeTest extends Ubercart6TestBase {

  use CommerceMigrateTestTrait;

  /**
   * Modules to enable.
   *
   * @var array
   */
  public static $modules = ['commerce_tax'];

  /**
   * {@inheritdoc}
   */
  protected function setUp() {
    parent::setUp();
    $this->installEntitySchema('commerce_tax_type');
    $this->executeMigration('d6_ubercart_tax_type');
  }

  /**
   * Test tax migration from Drupal 6 to 8.
   */
  public function testTaxType() {
    $this->assertTaxType('handling', 'Handling', 'custom', '0.04');
  }

}
