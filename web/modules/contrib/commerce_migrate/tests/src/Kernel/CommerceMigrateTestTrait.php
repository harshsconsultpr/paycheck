<?php

namespace Drupal\Tests\commerce_migrate\Kernel;

use Drupal\address\AddressInterface;
use Drupal\address\Plugin\Field\FieldType\AddressItem;
use Drupal\commerce_order\Entity\Order;
use Drupal\commerce_order\Entity\OrderItem;
use Drupal\commerce_price\Entity\Currency;
use Drupal\commerce_price\Entity\CurrencyInterface;
use Drupal\commerce_product\Entity\Product;
use Drupal\commerce_product\Entity\ProductType;
use Drupal\commerce_product\Entity\ProductAttribute;
use Drupal\commerce_product\Entity\ProductAttributeValue;
use Drupal\commerce_product\Entity\ProductVariation;
use Drupal\commerce_product\Entity\ProductVariationType;
use Drupal\commerce_store\Entity\Store;
use Drupal\commerce_tax\Entity\TaxType;
use Drupal\profile\Entity\Profile;
use Drupal\commerce_order\Entity\OrderItemType;

/**
 * Helper function to test migrations.
 */
trait CommerceMigrateTestTrait {

  /**
   * Asserts an address field.
   *
   * @param array $address
   *   The address id.
   * @param string $country_code
   *   The country code.
   * @param string $administrative_area
   *   The administrative area.
   * @param string $locality
   *   The locality.
   * @param string $dependent_locality
   *   The dependent locality.
   * @param string $postal_code
   *   The postal code.
   * @param string $sorting_code
   *   The sorting code.
   * @param string $address_line_1
   *   Address line 1.
   * @param string $address_line_2
   *   Address line 2.
   * @param string $given_name
   *   The given name.
   * @param string $additional_name
   *   Any additional names.
   * @param string $family_name
   *   The family name.
   * @param string $organization
   *   The organization string.
   */
  public function assertAddressField(array $address, $country_code, $administrative_area, $locality, $dependent_locality, $postal_code, $sorting_code, $address_line_1, $address_line_2, $given_name, $additional_name, $family_name, $organization) {
    $this->assertSame($country_code, $address['country_code']);
    $this->assertSame($administrative_area, $address['administrative_area']);
    $this->assertSame($locality, $address['locality']);
    $this->assertSame($dependent_locality, $address['dependent_locality']);
    $this->assertSame($postal_code, $address['postal_code']);
    $this->assertSame($sorting_code, $address['sorting_code']);
    $this->assertSame($address_line_1, $address['address_line1']);
    $this->assertSame($address_line_2, $address['address_line2']);
    $this->assertSame($given_name, $address['given_name']);
    $this->assertSame($additional_name, $address['additional_name']);
    $this->assertSame($family_name, $address['family_name']);
    $this->assertSame($organization, $address['organization']);
  }

  /**
   * Asserts an address field.
   *
   * @param \Drupal\address\AddressInterface $address
   *   The address id.
   * @param string $country
   *   The country code.
   * @param string $administrative_area
   *   The administrative area.
   * @param string $locality
   *   The locality.
   * @param string $dependent_locality
   *   The dependent locality.
   * @param string $postal_code
   *   The postal code.
   * @param string $sorting_code
   *   The sorting code.
   * @param string $address_line_1
   *   Address line 1.
   * @param string $address_line_2
   *   Address line 2.
   * @param string $given_name
   *   The given name.
   * @param string $additional_name
   *   Any additional names.
   * @param string $family_name
   *   The family name.
   * @param string $organization
   *   The organization string.
   */
  public function assertAddressItem(AddressInterface $address, $country, $administrative_area, $locality, $dependent_locality, $postal_code, $sorting_code, $address_line_1, $address_line_2, $given_name, $additional_name, $family_name, $organization) {
    $this->assertInstanceOf(AddressItem::class, $address);
    $this->assertSame($country, $address->getCountryCode());
    $this->assertSame($administrative_area, $address->getAdministrativeArea());
    $this->assertSame($locality, $address->getLocality());
    $this->assertSame($dependent_locality, $address->getDependentLocality());
    $this->assertSame($postal_code, $address->getPostalCode());
    $this->assertSame($sorting_code, $address->getSortingCode());
    $this->assertSame($address_line_1, $address->getAddressLine1());
    $this->assertSame($address_line_2, $address->getAddressLine2());
    $this->assertSame($given_name, $address->getGivenName());
    $this->assertSame($additional_name, $address->getAdditionalName());
    $this->assertSame($family_name, $address->getFamilyName());
    $this->assertSame($organization, $address->getOrganization());
  }

  /**
   * Asserts a billing profile entity.
   *
   * @param int $id
   *   The profile id.
   * @param int $owner_id
   *   The uid for this billing profile.
   * @param string $is_active
   *   The active state of the profile.
   * @param string $created_time
   *   The time the profile was created..
   * @param string $changed_time
   *   The time the profile was last changed.
   */
  public function assertBillingProfile($id, $owner_id, $is_active, $created_time, $changed_time) {
    $profile = Profile::load($id);
    $this->assertInstanceOf(Profile::class, $profile);
    // Billing profiles are always 'customer' bundle.
    $this->assertSame('customer', $profile->bundle());
    $this->assertSame($owner_id, $profile->getOwnerId());
    $this->assertSame($is_active, $profile->isActive());
    $this->assertSame($created_time, ($profile->getCreatedTime()));
    $this->assertSame($changed_time, $profile->getChangedTime());
  }

  /**
   * Asserts a Currency entity.
   *
   * @param int $id
   *   The currency id.
   * @param string $currency_code
   *   The currency code.
   * @param string $name
   *   The name of the currency.
   * @param string $numeric_code
   *   The numeric code for the currency.
   * @param string $fraction_digits
   *   The number of fraction digits for this currency.
   * @param string $symbol
   *   The currency symbol.
   */
  public function assertCurrencyEntity($id, $currency_code, $name, $numeric_code, $fraction_digits, $symbol) {
    /** @var \Drupal\commerce_price\Entity\CurrencyInterface $currency */
    $currency = Currency::load($id);
    $this->assertInstanceOf(CurrencyInterface::class, $currency);
    $this->assertSame($currency_code, $currency->getCurrencyCode());
    $this->assertSame($name, $currency->getName());
    $this->assertSame($fraction_digits, $currency->getFractionDigits());
    $this->assertSame($numeric_code, $currency->getNumericCode());
    $this->assertSame($symbol, $currency->getSymbol());
  }

  public function assertDefaultStore() {
    $defaultStore = $this->container->get('commerce_store.default_store_resolver')->resolve();
    $this->assertInstanceOf(Store::class, $defaultStore);
  }

  /**
   * Asserts an order entity.
   *
   * @param array $order
   *   An array of order information.
   *   - id: The order id.
   *   - number: The order number.
   *   - store_id: The store id.
   *   - created_time: The time the order was created.
   *   - changed_time:  The time the order was changed.
   *   - email: The email address for this order.
   *   - label: The label for this order.
   *   - ip_address: The ip address used to create this order.
   *   - customer_id: The customer id.
   *   - placed_time: The time the order was placed.
   *   - adjustments: An array of adjustments.
   */
  public function assertOrder(array $order) {
    $order_instance = Order::load($order['id']);
    $this->assertInstanceOf(Order::class, $order_instance);
    $this->assertSame($order['number'], $order_instance->getOrderNumber());
    $this->assertSame($order['store_id'], $order_instance->getStoreId());
    $this->assertSame($order['created_time'], $order_instance->getCreatedTime());
    // Using a NULL to skip the test is a work around because Commerce 1 order
    // migration modifies the changed time.
    //@TODO https://www.drupal.org/node/2916939
    if ($order['changed_time'] != NULL) {
      $this->assertSame($order['changed_time'], $order_instance->getChangedTime());
    }
    $this->assertSame($order['label'], $order_instance->getState()->value);
    $this->assertSame($order['email'], $order_instance->getEmail());
    $this->assertInstanceOf(Profile::class, $order_instance->getBillingProfile());
    $this->assertSame($order['customer_id'], $order_instance->getCustomerId());
    $this->assertSame($order['ip_address'], $order_instance->getIpAddress());
    $this->assertSame($order['placed_time'], $order_instance->getPlacedTime());
    $this->assertSame($order['adjustments'], $order_instance->getAdjustments());
  }

  /**
   * Asserts an order item.
   *
   * @param int $id
   *   The order item id.
   * @param int $order_id
   *   The order id for this item.
   * @param int $purchased_entity_id
   *   The id of the purchased entity.
   * @param string $quantity
   *   The order quantity.
   * @param string $title
   *   The title of the item.
   * @param string $unit_price
   *   The unit price of the item.
   * @param string $unit_price_currency
   *   The unit price currency code.
   * @param string $total_price
   *   The total price of this item.
   * @param string $total_price_currency
   *   The total price currency code.
   */
  public function assertOrderItem($id, $order_id, $purchased_entity_id, $quantity, $title, $unit_price, $unit_price_currency, $total_price, $total_price_currency) {
    $order_item = OrderItem::load($id);
    $this->assertInstanceOf(OrderItem::class, $order_item);
    $this->assertSame($quantity, $order_item->getQuantity());
    $this->assertEquals($title, $order_item->getTitle());
    $this->assertEquals($unit_price, $order_item->getUnitPrice()->getNumber());
    $this->assertEquals($unit_price_currency, $order_item->getUnitPrice()->getCurrencyCode());
    $this->assertEquals($total_price, $order_item->getTotalPrice()->getNumber());
    $this->assertEquals($total_price_currency, $order_item->getTotalPrice()->getCurrencyCode());
    $this->assertEquals($purchased_entity_id, $order_item->getPurchasedEntityId());
    $this->assertEquals($order_id, $order_item->getOrderId());
  }

  /**
   * Asserts an order item type configuration entity.
   *
   * @param string $id
   *   The order item type id.
   * @param string $expected_label
   *   The expected label.
   */
  public function assertOrderItemType($id, $expected_label) {
    $order_item_type = OrderItemType::load($id);
    $this->assertInstanceOf(OrderItemType::class, $order_item_type);
    $this->assertSame($expected_label, $order_item_type->label());
  }
  
  /**
   * Asserts a product attribute entity.
   *
   * @param string $id
   *   The attribute id.
   * @param string $label
   *   The expected attribute label.
   * @param string $element_type
   *   The expected element type of the attribute.
   */
  protected function assertProductAttributeEntity($id, $label, $element_type) {
    list ($entity_type, $name) = explode('.', $id);
    $attribute = ProductAttribute::load($name);
    $this->assertInstanceOf(ProductAttribute::class, $attribute);
    $this->assertSame($label, $attribute->label());
    $this->assertSame($element_type, $attribute->getElementType());
  }

  /**
   * Asserts a product attribute value entity.
   *
   * @param string $id
   *   The attribute value id.
   * @param string $attribute_id
   *   The expected product attribute value id.
   * @param string $name
   *   The expected name of the product attribute value.
   * @param string $label
   *   The expected label of the product attribute value.
   * @param string $weight
   *   The expected weight of the product attribute value.
   */
  protected function assertProductAttributeValueEntity($id, $attribute_id, $name, $label, $weight) {
    $attribute_value = ProductAttributeValue::load($id);
    $this->assertInstanceOf(ProductAttributeValue::class, $attribute_value);
    $this->assertSame($attribute_id, $attribute_value->getAttributeId());
    $this->assertSame($name, $attribute_value->getName());
    $this->assertSame($label, $attribute_value->label());
    $this->assertSame($weight, $attribute_value->getWeight());
  }

  /**
   * Asserts a product.
   *
   * @param int $id
   *   The product id.
   * @param int $owner_id
   *   The uid for this billing profile.
   * @param string $title
   *   The title of the product.
   * @param string $is_published
   *   The published status of the product.
   * @param array $store_ids
   *   The ids of the stores for this product.
   * @param array $variations
   *   The variation of this product.
   */
  public function assertProductEntity($id, $owner_id, $title, $is_published, array $store_ids, array $variations) {
    $product = Product::load($id);
    $this->assertInstanceOf(Product::class, $product);
    $this->assertSame($owner_id, $product->getOwnerId());
    $this->assertSame($title, $product->getTitle());
    $this->assertSame($is_published, $product->isPublished());
    $this->assertSame($store_ids, $product->getStoreIds());
    $this->assertSame($variations, $product->getVariationIds());
  }

  /**
   * Asserts a product type entity.
   *
   * @param string $id
   *   The product type id.
   * @param string $label
   *   The expected label.
   * @param string $description
   *   The expected description.
   * @param string $variation_type_id
   *   The expected product variation type id.
   */
  public function assertProductTypeEntity($id, $label, $description, $variation_type_id) {
    $product_type = ProductType::load($id);
    $this->assertInstanceOf(ProductType::class, $product_type);
    $this->assertSame($label, $product_type->label());
    $this->assertSame($description, $product_type->getDescription());
    $this->assertSame($variation_type_id, $product_type->getVariationTypeId());
  }

  /**
   * Asserts a product variation.
   *
   * @param int $id
   *   The product variation id.
   * @param int $owner_id
   *   The uid for this billing profile.
   * @param string $sku
   *   The SKU.
   * @param string $price_number
   *   The price.
   * @param string $price_currency
   *   The currency code.
   * @param string $product_id
   *   The id of the product.
   * @param string $variation_title
   *   The title.
   * @param string $variation_bundle
   *   The order item type.
   * @param string $variation_created_time
   *   The title.
   * @param string $variation_changed_time
   *   The order item type.
   */
  public function assertProductVariationEntity($id, $owner_id, $sku, $price_number, $price_currency, $product_id, $variation_title, $variation_bundle, $variation_created_time, $variation_changed_time) {
    $variation = ProductVariation::load($id);
    $this->assertInstanceOf(ProductVariation::class, $variation);
    $this->assertSame($owner_id, $variation->getOwnerId());
    $this->assertSame($sku, $variation->getSku());
    $this->assertSame($price_number, $variation->getPrice()->getNumber());
    $this->assertSame($price_currency, $variation->getPrice()->getCurrencyCode());
    $this->assertSame($product_id, $variation->getProductId());
    $this->assertSame($variation_title, $variation->getOrderItemTitle());
    $this->assertSame($variation_bundle, $variation->getOrderItemTypeId());
    $this->assertSame($variation_created_time, $variation->getCreatedTime());
    if ($variation_changed_time != NULL) {
      $this->assertSame($variation_changed_time, $variation->getChangedTime());
    }
  }

  /**
   * Asserts a product variation type.
   *
   * @param string $id
   *   The product variation type.
   * @param string $label
   *   The expected label.
   * @param string $order_item_type_id
   *   The expected order item type id.
   * @param bool $is_title_generated
   *   The expected indicator that a title is generated.
   */
  public function assertProductVariationTypeEntity($id, $label, $order_item_type_id, $is_title_generated) {
    $variation_type = ProductVariationType::load($id);
    $this->assertInstanceOf(ProductVariationType::class, $variation_type);
    $this->assertSame($label, $variation_type->label());
    $this->assertSame($order_item_type_id, $variation_type->getOrderItemTypeId());
    $this->assertSame($is_title_generated, $variation_type->shouldGenerateTitle());
  }

  /**
   * Asserts a store entity.
   *
   * @param int $id
   *   The store id.
   * @param string $name
   *   The name of the store.
   * @param string $email
   *   The email address of the store.
   * @param string $default_currency_code
   *   The default currency code of the store.
   * @param string $bundle
   *   The bundle.
   * @param string $owner_id
   *   The owner id.
   */
  public function assertStoreEntity($id, $name, $email, $default_currency_code, $bundle, $owner_id) {
    $store = Store::load($id);
    $this->assertInstanceOf(Store::class, $store);
    $this->assertSame($name, $store->getName());
    $this->assertSame($email, $store->getEmail());
    $this->assertSame($default_currency_code, $store->getDefaultCurrencyCode());
    $this->assertSame($bundle, $store->bundle());
    $this->assertSame($owner_id, $store->getOwnerId());
  }

  /**
   * Asserts a tax type.
   *
   * @param int $id
   *   The TaxType id.
   * @param string $label
   *   The label for the TaxType.
   * @param string $plugin
   *   The TaxType plugin.
   * @param string $rate
   *   The TaxType rate.
   */
  public function assertTaxType($id, $label, $plugin, $rate) {
    $tax_type = TaxType::load($id);
    $this->assertInstanceOf(TaxType::class, $tax_type);
    $this->assertSame($label, $tax_type->label());
    $this->assertSame($plugin, $tax_type->getPluginId());

    $tax_type_config = $tax_type->getPluginConfiguration();
    $this->assertSame($id, $tax_type_config['rates'][0]['id']);
    $this->assertSame($label, $tax_type_config['rates'][0]['label']);
    $this->assertSame($rate, $tax_type_config['rates'][0]['percentage']);
  }

  /**
   * @param array $product
   *   Array of product and product variation data.
   */
  public function productTest(array $product) {
    $variation_ids = [];
    foreach ($product['variations'] as $variation) {
      $variation_ids[] = $variation['variation_id'];
    }
    $this->assertProductEntity($product['product_id'], $product['uid'], $product['title'], $product['published'], $product['store_ids'], $variation_ids);
    $this->productVariationTest($product);
  }

  /**
   * Helper to test a product is linked to its variations.
   *
   * @param array $product
   *   Product and product variation data.
   */
  public function productVariationTest(array $product) {
    // Test variations.
    $productInstance = Product::load($product['product_id']);
    foreach ($product['variations'] as $variation) {
      $found = FALSE;
      foreach ($productInstance->variations as $variationInstance) {
        if ($variation['variation_id'] == $variationInstance->target_id) {
          $found = TRUE;
        }
      }
      $this->assertTrue($found, "No variation exists for variation_id: {$variation['variation_id']}");
      $this->assertProductVariationEntity($variation['variation_id'], $variation['uid'], $variation['sku'], $variation['price'], $variation['currency'], $product['product_id'], $variation['title'], $variation['order_item_type'], $variation['created_time'], $variation['changed_time']);
    }
  }

}
