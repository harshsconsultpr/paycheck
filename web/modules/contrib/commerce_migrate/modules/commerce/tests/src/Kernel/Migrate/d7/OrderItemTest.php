<?php

namespace Drupal\Tests\commerce_migrate_commerce\Kernel\Migrate\d7;

use Drupal\commerce_order\Entity\OrderItem;
use Drupal\Tests\commerce_migrate\Kernel\CommerceMigrateTestTrait;

/**
 * Tests order item migration.
 *
 * @group commerce_migrate
 * @group commerce_migrate_commerce_d7
 */
class OrderItemTest extends Commerce1TestBase {

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
    $this->installEntitySchema('commerce_order_item');
    $this->migrateStore();
    // We need to install config so we have a default order item type.
    // @todo provide way to migrate line item types properly.
    $this->installConfig(['commerce_order']);
    // @todo Execute the d7_field and d7_field_instance migrations?
    $this->executeMigrations([
      'd7_commerce_product_variation_type',
      'd7_commerce_product_variation',
      'd7_commerce_order_item_type',
      'd7_commerce_order_item',
    ]);
  }

  /**
   * Test line item migration from Drupal 7 to 8.
   */
  public function testOrderItem() {
    $order = [
      'id' => 1,
      'order_id' => NULL,
      'purchased_entity_id' => '12',
      'quantity' => '1.00',
      'title' => 'Hat 2',
      'unit_price' => '12.000000',
      'unit_price_currency' => 'USD',
      'total_price' => '12.000000',
      'total_price_currency' => 'USD',
    ];
    $this->assertOrderItem($order['id'], $order['order_id'], $order['purchased_entity_id'], $order['quantity'], $order['title'], $order['unit_price'], $order['unit_price_currency'], $order['total_price'], $order['total_price_currency']);
    $order = [
      'id' => 2,
      'order_id' => NULL,
      'purchased_entity_id' => '12',
      'quantity' => '1.00',
      'title' => 'Hat 2',
      'unit_price' => '12.000000',
      'unit_price_currency' => 'USD',
      'total_price' => '12.000000',
      'total_price_currency' => 'USD',
    ];
    $this->assertOrderItem($order['id'], $order['order_id'], $order['purchased_entity_id'], $order['quantity'], $order['title'], $order['unit_price'], $order['unit_price_currency'], $order['total_price'], $order['total_price_currency']);
    $order = [
      'id' => 3,
      'order_id' => NULL,
      'purchased_entity_id' => '45',
      'quantity' => '1.00',
      'title' => 'Tshirt 3',
      'unit_price' => '38.000000',
      'unit_price_currency' => 'USD',
      'total_price' => '38.000000',
      'total_price_currency' => 'USD',
    ];
    $this->assertOrderItem($order['id'], $order['order_id'], $order['purchased_entity_id'], $order['quantity'], $order['title'], $order['unit_price'], $order['unit_price_currency'], $order['total_price'], $order['total_price_currency']);

    // Test time stamps.
    $order_item = OrderItem::load(1);
    $this->assertEquals($order_item->getCreatedTime(), 1493287435);
    $this->assertEquals($order_item->getChangedTime(), 1493287440);
    $order_item = OrderItem::load(2);
    $this->assertEquals($order_item->getCreatedTime(), 1493287445);
    $this->assertEquals($order_item->getChangedTime(), 1493287450);
    $order_item = OrderItem::load(3);
    $this->assertEquals($order_item->getCreatedTime(), 1493287455);
    $this->assertEquals($order_item->getChangedTime(), 1493287460);
  }

}
