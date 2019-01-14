<?php

namespace Drupal\Tests\commerce_migrate_commerce\Kernel\Migrate\d7;

use Drupal\commerce_order\Entity\Order;
use Drupal\profile\Entity\Profile;
use Drupal\Tests\commerce_migrate\Kernel\CommerceMigrateTestTrait;

/**
 * Tests line item migration.
 *
 * @group commerce_migrate
 * @group commerce_migrate_commerce_d7
 */
class OrderTest extends Commerce1TestBase {

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
    $this->installEntitySchema('commerce_product_variation');
    $this->installEntitySchema('commerce_order');
    $this->installEntitySchema('commerce_order_item');
    $this->installConfig(['commerce_order']);
    $this->migrateStore();
    // @todo Execute the d7_field and d7_field_instance migrations?
    $this->executeMigrations([
      'd7_user_role',
      'd7_user',
      'd7_commerce_product_variation_type',
      'd7_commerce_product_variation',
      'd7_commerce_billing_profile',
      'd7_commerce_order_item_type',
      'd7_commerce_order_item',
      'd7_commerce_order',
    ]);
  }

  /**
   * Test line item migration from Drupal 7 to 8.
   */
  public function testOrder() {
    $order = Order::load(1);
    // Test line items.
    $order_items = $order->getItems();
    $this->assertNotNull($order_items);
    $this->assertEquals('Hat 2', $order_items[0]->label());
    $this->assertEquals('Hat 2', $order_items[1]->label());
    $this->assertEquals(24.000000, $order->getTotalPrice()->getNumber());

    $order = [
      'id' => 1,
      'number' => '1',
      'store_id' => '1',
      'created_time' => '1493287432',
      'changed_time' => NULL,
      'email' => 'customer@example.com',
      'label' => 'draft',
      'ip_address' => '127.0.0.1',
      'customer_id' => '4',
      'placed_time' => '1493287432',
      'adjustments' => [],
    ];
    $this->assertOrder($order);
    $order = [
      'id' => 2,
      'number' => '2',
      'store_id' => '1',
      'created_time' => '1493287435',
      'changed_time' => NULL,
      'email' => 'customer@example.com',
      'label' => 'completed',
      'ip_address' => '127.0.0.1',
      'customer_id' => '4',
      'placed_time' => '1493287435',
      'adjustments' => [],
    ];
    $this->assertOrder($order);
    $order = [
      'id' => 3,
      'number' => '3',
      'store_id' => '1',
      'created_time' => '1493287438',
      'changed_time' => NULL,
      'email' => 'customer@example.com',
      'label' => 'completed',
      'ip_address' => '127.0.0.1',
      'customer_id' => '4',
      'placed_time' => '1493287438',
      'adjustments' => [],
    ];
    $this->assertOrder($order);

    // Test billing profile.
    $order = Order::load(1);
    $profile = $order->getBillingProfile();
    $this->assertInstanceOf(Profile::class, $profile);
    $this->assertEquals($profile->bundle(), 'customer');
    $this->assertEquals($profile->isActive(), TRUE);

    // Test store.
    $this->assertEquals(\Drupal::service('commerce_store.default_store_resolver')
      ->resolve()
      ->id(), $order->getStoreId());

    // Tests various order migration states.
    $order_draft = Order::load(1);
    $order_pending = Order::load(2);
    $order_complete = Order::load(3);

    $this->assertEquals('Draft', $order_draft->getState()
      ->getLabel()
      ->render());
    $this->assertEquals('Completed', $order_pending->getState()
      ->getLabel()
      ->render());
    $this->assertEquals('Completed', $order_complete->getState()
      ->getLabel()
      ->render());
  }

}
