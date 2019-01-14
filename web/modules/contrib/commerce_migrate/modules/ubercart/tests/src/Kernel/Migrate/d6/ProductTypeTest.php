<?php

namespace Drupal\Tests\commerce_migrate_ubercart\Kernel\Migrate\d6;

use Drupal\Tests\commerce_migrate\Kernel\CommerceMigrateTestTrait;

/**
 * Tests product type migration.
 *
 * @group commerce_migrate
 * @group commerce_migrate_ubercart_d6
 */
class ProductTypeTest extends Ubercart6TestBase {

  use CommerceMigrateTestTrait;

  /**
   * Modules to enable.
   *
   * @var array
   */
  public static $modules = [
    'path',
    'commerce_product',
    'commerce_migrate_ubercart',
  ];

  /**
   * {@inheritdoc}
   */
  protected function setUp() {
    parent::setUp();
    $this->installEntitySchema('view');
    $this->installEntitySchema('commerce_product_variation');
    $this->installEntitySchema('commerce_product');
    $this->installConfig(['commerce_product']);
    $this->migrateStore();
    $this->executeMigrations([
      'd6_ubercart_product_variation',
      'd6_ubercart_product',
      'd6_ubercart_product_type',
    ]);
  }

  /**
   * Test product type migration from Drupal 6 to 8.
   */
  public function testProductType() {
    $description = 'A type of spacecraft capable of traveling to the solar systems of other stars';
    $this->assertProductTypeEntity('ship', 'Ship', $description, 'default');
    $description = 'This node displays the representation of a product for sale on the website. It includes all the unique information that can be attributed to a specific model number.';
    $this->assertProductTypeEntity('product', 'Product', $description, 'default');
  }

}
