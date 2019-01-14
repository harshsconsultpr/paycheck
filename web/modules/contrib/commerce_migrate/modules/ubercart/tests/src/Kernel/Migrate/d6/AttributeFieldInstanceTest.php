<?php

namespace Drupal\Tests\commerce_migrate_ubercart\Kernel\Migrate\d6;

use Drupal\field\Entity\FieldConfig;
use Drupal\field\FieldConfigInterface;
use Drupal\Tests\commerce_migrate\Kernel\CommerceMigrateTestTrait;

/**
 * Tests attribute field instance migration.
 *
 * @group commerce_migrate
 * @group commerce_migrate_ubercart_d6
 */
class MigrateAttributeFieldInstanceTest extends Ubercart6TestBase {

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
    $this->installConfig(['commerce_product']);
    $this->executeMigrations([
      'd6_ubercart_field_attribute',
      'd6_ubercart_field_attribute_instance',
    ]);
  }

  /**
   * Asserts various aspects of a field config entity.
   *
   * @param string $name
   *   The field instance machine name.
   * @param string $type
   *   The field type.
   * @param string $label
   *   The field label.
   * @param string $description
   *   The field description.
   * @param bool $translatable
   *   Indicates if the field is translatable.
   */
  protected function assertEntity($name, $type, $label, $description, $translatable) {
    $id = 'commerce_product_variation.default.attribute_' . $name;
    /** @var \Drupal\field\FieldConfigInterface $field */
    $field = FieldConfig::load($id);
    $this->assertTrue($field instanceof FieldConfigInterface);
    $this->assertSame($type, $field->getType());
    $this->assertSame($label, $field->label());
    $this->assertSame($description, $field->getDescription());
    $this->assertSame('default:commerce_product_attribute_value', $field->getSetting('handler'));
    $this->assertSame([], $field->getSetting('handler_settings'));
    $this->assertSame('commerce_product_attribute_value', $field->getSetting('target_type'));
    $this->assertEquals($translatable, $field->isTranslatable());
    $this->assertSame('commerce_product_variation', $field->getTargetEntityTypeId());
  }

  /**
   * Test attribute field instance migration.
   */
  public function testMigrateAttributeTest() {
    $this->assertEntity('design', 'entity_reference', 'Cool Designs for your towel', 'Select a design', TRUE);
    $this->assertEntity('color', 'entity_reference', 'Color', 'Available towel colors', TRUE);
    $this->assertEntity('model_size', 'entity_reference', 'Model size', 'Select your starship model size.', TRUE);
    $this->assertEntity('name', 'entity_reference', 'Name', 'Enter a name to be written on the cake.', TRUE);
  }
}
