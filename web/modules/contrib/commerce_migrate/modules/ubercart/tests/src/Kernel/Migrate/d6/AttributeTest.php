<?php

namespace Drupal\Tests\commerce_migrate_ubercart\Kernel\Migrate\d6;

use Drupal\field\Entity\FieldStorageConfig;
use Drupal\field\FieldStorageConfigInterface;
use Drupal\Tests\commerce_migrate\Kernel\CommerceMigrateTestTrait;

/**
 * Tests attribute field storage migration.
 *
 * @group commerce_migrate
 * @group commerce_migrate_ubercart_d6
 */
class AttributeTest extends Ubercart6TestBase {

  use CommerceMigrateTestTrait;

  /**
   * Modules to enable.
   *
   * @var array
   */
  public static $modules = [
    'commerce_product',
    'commerce_migrate_ubercart',
  ];

  /**
   * {@inheritdoc}
   */
  protected function setUp() {
    parent::setUp();
    $this->executeMigration('d6_ubercart_field_attribute');
  }

  /**
   * Asserts various aspects of a field_storage_config entity.
   *
   * @param string $id
   *   The entity ID in the form ENTITY_TYPE.FIELD_NAME.
   * @param string $type
   *   The expected field type.
   * @param bool $translatable
   *   Whether or not the field is expected to be translatable.
   * @param int $cardinality
   *   The expected cardinality of the field.
   * @param array $dependencies
   *   The field's dependencies.
   */
  protected function assertEntity($id, $type, $translatable, $cardinality, array $dependencies) {
    list ($entity_type, $name) = explode('.', $id);

    /** @var \Drupal\field\FieldStorageConfigInterface $field */
    $field = FieldStorageConfig::load($id);
    $this->assertTrue($field instanceof FieldStorageConfigInterface);
    $this->assertSame($type, $field->getType());
    $this->assertEquals($translatable, $field->isTranslatable());
    $this->assertSame($entity_type, $field->getTargetEntityTypeId());
    $this->assertSame($dependencies, $field->getDependencies());
    if ($cardinality === 1) {
      $this->assertFalse($field->isMultiple());
    }
    else {
      $this->assertTrue($field->isMultiple());
    }
    $this->assertSame($cardinality, $field->getCardinality());
  }

  /**
   * Test currency migration from Drupal 6 to 8.
   */
  public function testMigrateAttributeTest() {
    $dependencies = [
      'module' => ['commerce_product'],
    ];
    $this->assertEntity('commerce_product_variation.attribute_design', 'entity_reference', TRUE, 1, $dependencies);
    $this->assertEntity('commerce_product_variation.attribute_color', 'entity_reference', TRUE, -1, $dependencies);
    $this->assertEntity('commerce_product_variation.attribute_model_size', 'entity_reference', TRUE, 1, $dependencies);
    $this->assertEntity('commerce_product_variation.attribute_name', 'entity_reference', TRUE, 1, $dependencies);
  }

}
