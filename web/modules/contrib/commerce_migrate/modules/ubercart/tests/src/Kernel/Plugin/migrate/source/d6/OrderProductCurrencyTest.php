<?php

namespace Drupal\Tests\commerce_migrate_ubercart\Kernel\Plugin\migrate\source\d6;

use Drupal\Tests\migrate\Kernel\MigrateSqlSourceTestBase;

/**
 * Tests the d6 ubercart order product source plugin.
 *
 * @covers \Drupal\commerce_migrate_ubercart\Plugin\migrate\source\d6\OrderProduct
 * @group commerce_migrate
 * @group commerce_migrate_ubercart_d6
 */
class OrderProductCurrencyTest extends MigrateSqlSourceTestBase {

  /**
   * {@inheritdoc}
   */
  public static $modules = ['migrate_drupal', 'commerce_migrate_ubercart'];

  /**
   * {@inheritdoc}
   */
  public function providerSource() {
    $tests = [];
    $tests[0]['source_data']['uc_order_products'] =
      [
        [
          'order_product_id' => '1',
          'order_id' => '1',
          'nid' => '1',
          'title' => 'Product 1',
          'manufacturer' => 'Someone',
          'model' => 'Product 1 - 001',
          'qty' => '2',
          'cost' => '500.00000',
          'price' => '600.00000',
          'weight' => '2',
          'data' => 'a:2:{s:9:"shippable";s:1:"1";s:6:"module";s:10:"uc_product";}',
        ],
      ];
    $tests[0]['source_data']['uc_orders'] =
      [
        [
          'order_id' => '1',
          'uid' => '2',
          'order_status' => 'payment_received',
          'order_total' => '22.99000',
          'product_count' => '2',
          'primary_email' => 'f.bar@example.com',
          'delivery_first_name' => '',
          'delivery_last_name' => '',
          'delivery_phone' => '',
          'delivery_company' => '',
          'delivery_street1' => '',
          'delivery_street2' => '',
          'delivery_city' => '',
          'delivery_zone' => '',
          'delivery_postal_code' => '',
          'delivery_country' => '',
          'billing_first_name' => 'Foo',
          'billing_last_name' => 'Bar',
          'billing_phone' => '123-4567',
          'billing_company' => 'Acme',
          'billing_street1' => '1 Coyote Way',
          'billing_street2' => 'Utah',
          'billing_city' => 'Salt Lake',
          'billing_zone' => '58',
          'billing_postal_code' => '11111',
          'billing_country' => '840',
          'payment_method' => 'cod',
          'data' => 'a:0{}',
          'created' => '1492868907',
          'modified' => '1498620003',
          'host' => '192.168.0.2',
          'currency' => 'NZD',
        ],
      ];


    // The expected results.
    $tests[0]['expected_data'] = [
      [
        'order_id' => '1',
        'order_product_id' => '1',
        'nid' => '1',
        'title' => 'Product 1',
        'qty' => '2',
        'price' => '600.00000',
        'data' => 'a:2:{s:9:"shippable";s:1:"1";s:6:"module";s:10:"uc_product";}',
        'created' => '1492868907',
        'modified' => '1498620003',
        'currency' => 'NZD',
      ],
    ];

    return $tests;
  }

}
