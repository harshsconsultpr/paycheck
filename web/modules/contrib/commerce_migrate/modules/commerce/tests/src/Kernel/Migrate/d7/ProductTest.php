<?php

namespace Drupal\Tests\commerce_migrate_commerce\Kernel\Migrate\d7;

use Drupal\Tests\commerce_migrate\Kernel\CommerceMigrateTestTrait;

/**
 * Tests line item migration.
 *
 * @group commerce_migrate
 * @group commerce_migrate_commerce_d7
 */
class ProductTest extends Commerce1TestBase {

  use CommerceMigrateTestTrait;

  /**
   * Modules to enable.
   *
   * @var array
   */
  public static $modules = [
    'path',
    'commerce_product',
  ];

  /**
   * {@inheritdoc}
   */
  protected function setUp() {
    parent::setUp();
    $this->installEntitySchema('view');
    $this->installEntitySchema('commerce_product_variation');
    $this->installEntitySchema('commerce_product');
    $this->migrateStore();
    $this->executeMigrations([
      'd7_user_role',
      'd7_user',
      'd7_commerce_product_variation_type',
      'd7_commerce_product_variation',
      'd7_commerce_product_type',
    ]);

    $this->executeMigrations([
      'd7_commerce_product',
    ]);
  }

  /**
   * Test product migration from Drupal 7 to 8.
   */
  public function testProduct() {
    $this->assertProductEntity(15, '1', 'Go green with Drupal Commerce Reusable Tote Bag', TRUE, ['1'], ['1']);

    // Tests a product with multiple variations.
    $this->assertProductEntity(26, '1', 'Commerce Guys USB Key', TRUE, ['1'], ['28', '29', '30']);
  }

}
