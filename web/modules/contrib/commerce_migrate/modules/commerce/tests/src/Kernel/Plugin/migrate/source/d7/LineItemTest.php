<?php

namespace Drupal\Tests\commerce_migrate_commerce\Kernel\Plugin\migrate\source\d7;

use Drupal\Tests\migrate\Kernel\MigrateSqlSourceTestBase;

/**
 * Tests the d7 commerce line item source plugin.
 *
 * @covers \Drupal\commerce_migrate_commerce\Plugin\migrate\source\d7\LineItem
 *
 * @group commerce_migrate
 * @group commerce_migrate_commerce_d7
 */
class LineItemTest extends MigrateSqlSourceTestBase {

  /**
   * {@inheritdoc}
   */
  public static $modules = ['migrate_drupal', 'commerce_migrate_commerce'];

  /**
   * {@inheritdoc}
   */
  public function providerSource() {
    $tests = [];
    $tests[0]['source_data']['commerce_line_item'] = [
      [
        'line_item_id' => '1',
        'order_id' => '2',
        'type' => 'product',
        'line_item_label' => 'sku 1',
        'quantity' => '2',
        'data' => 'a:0{}',
        'created' => '1492868907',
        'changed' => '1498620003',
      ],
    ];
    $tests[0]['source_data']['commerce_product'] = [
      [
        'product_id' => '2',
        'revision_id' => '3',
        'sku' => 'sku 1',
        'title' => 'Product A title',
        'type' => 'shirts',
        'language' => 'und',
        'uid' => '3',
        'status' => '1',
        'created' => '1493287314',
        'changed' => '1493287314',
        'data' => NULL,
      ],
    ];
    $tests[0]['source_data']['field_config_instance'] = [
      [
        'id' => '',
        'field_id' => '',
        'field_name' => '',
        'entity_type' => '',
        'bundle' => '',
        'data' => '',
        'deleted' => '',
      ],
    ];
    // The expected results.
    $tests[0]['expected_data'] = [
      [
        'line_item_id' => '1',
        'order_id' => '2',
        'type' => 'product',
        'line_item_label' => 'sku 1',
        'quantity' => '2',
        'data' => 'a:0{}',
        'created' => '1492868907',
        'changed' => '1498620003',
        'title' => 'Product A title',
      ],
    ];

    return $tests;
  }

}
