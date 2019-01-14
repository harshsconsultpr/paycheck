<?php

namespace Drupal\Tests\commerce_migrate_ubercart\Kernel\Migrate\d6;

use Drupal\commerce_order\Entity\OrderItem;
use Drupal\Tests\commerce_migrate\Kernel\CommerceMigrateTestTrait;

/**
 * Tests order item migration.
 *
 * @group commerce_migrate
 * @group commerce_migrate_ubercart_d6
 */
class OrderItemTest extends Ubercart6TestBase {

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
    $this->installEntitySchema('profile');
    $this->installEntitySchema('commerce_product');
    $this->installEntitySchema('commerce_product_variation');
    $this->installEntitySchema('commerce_order');
    $this->installEntitySchema('commerce_order_item');
    $this->installConfig(['commerce_order']);
    $this->installConfig(['commerce_product']);
    $this->migrateStore();
    $this->startCollectingMessages();
    $this->executeMigrations([
      'd6_ubercart_billing_profile',
      'd6_ubercart_order',
      'd6_ubercart_product_variation',
      'd6_ubercart_product',
      'd6_ubercart_order_product',
    ]);
  }

  /**
   * Test order item migration from Drupal 6 to 8.
   */
  public function testOrderItem() {
    $this->assertOrderItem(2, 2, 3, '1.00', 'Fairy cake', 1500.0000, 'NZD', 1500.000, 'NZD');
    $this->assertOrderItem(3, 1, 1, '1.00', 'Bath Towel', 20.000000, 'NZD', 20.000000, 'NZD');
    $this->assertOrderItem(4, 1, 2, '1.00', 'Beach Towel', 15.000000, 'NZD', 15.000000, 'NZD');

    $order_item = OrderItem::load(2);
    // Test that both product and order are linked.
    $product = $order_item->getPurchasedEntity();
    $this->assertNotNull($product);
    $this->assertEquals(3, $product->id());

    $order = $order_item->getOrder();
    $this->assertNotNull($order);
    $this->assertEquals(2, $order->id());
  }

}
