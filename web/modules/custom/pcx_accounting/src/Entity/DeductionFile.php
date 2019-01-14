<?php

namespace Drupal\pcx_accounting\Entity;

use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\EntityChangedTrait;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\user\UserInterface;
use Drupal\Core\Field\FieldStorageDefinitionInterface;

/**
 * Defines the Deduction File entity.
 *
 * @ingroup pcx_accounting
 *
 * @ContentEntityType(
 *   id = "deduction_file",
 *   label = @Translation("Deduction File"),
 *   handlers = {
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\pcx_accounting\DeductionFileListBuilder",
 *     "views_data" = "Drupal\pcx_accounting\Entity\DeductionFileViewsData",
 *
 *     "form" = {
 *       "default" = "Drupal\pcx_accounting\Form\DeductionFileForm",
 *       "add" = "Drupal\pcx_accounting\Form\DeductionFileForm",
 *       "edit" = "Drupal\pcx_accounting\Form\DeductionFileForm",
 *       "delete" = "Drupal\pcx_accounting\Form\DeductionFileDeleteForm",
 *     },
 *     "access" = "Drupal\pcx_accounting\DeductionFileAccessControlHandler",
 *     "route_provider" = {
 *       "html" = "Drupal\pcx_accounting\DeductionFileHtmlRouteProvider",
 *     },
 *   },
 *   base_table = "deduction_file",
 *   admin_permission = "administer deduction file entities",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "name",
 *     "uuid" = "uuid",
 *     "uid" = "user_id",
 *     "langcode" = "langcode",
 *     "status" = "status",
 *   },
 *   links = {
 *     "canonical" = "/admin/structure/deduction_file/{deduction_file}",
 *     "add-form" = "/admin/structure/deduction_file/add",
 *     "edit-form" = "/admin/structure/deduction_file/{deduction_file}/edit",
 *     "delete-form" = "/admin/structure/deduction_file/{deduction_file}/delete",
 *     "collection" = "/admin/structure/deduction_file",
 *   },
 *   field_ui_base_route = "deduction_file.settings"
 * )
 */
class DeductionFile extends ContentEntityBase implements DeductionFileInterface {

  use EntityChangedTrait;

  /**
   * {@inheritdoc}
   */
  public static function preCreate(EntityStorageInterface $storage_controller, array &$values) {
    parent::preCreate($storage_controller, $values);
    $values += [
      'user_id' => \Drupal::currentUser()->id(),
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function getName() {
    return $this->get('name')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setName($name) {
    $this->set('name', $name);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getCreatedTime() {
    return $this->get('created')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setCreatedTime($timestamp) {
    $this->set('created', $timestamp);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getOwner() {
    return $this->get('user_id')->entity;
  }

  /**
   * {@inheritdoc}
   */
  public function getOwnerId() {
    return $this->get('user_id')->target_id;
  }

  /**
   * {@inheritdoc}
   */
  public function setOwnerId($uid) {
    $this->set('user_id', $uid);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function setOwner(UserInterface $account) {
    $this->set('user_id', $account->id());
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function isPublished() {
    return (bool) $this->getEntityKey('status');
  }

  /**
   * {@inheritdoc}
   */
  public function setPublished($published) {
    $this->set('status', $published ? TRUE : FALSE);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public static function baseFieldDefinitions(EntityTypeInterface $entity_type) {
    $fields = parent::baseFieldDefinitions($entity_type);

    $fields['organization_id'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('Organization'))
      ->setDescription(t('The organization for the Deduction File.'))
      ->setRevisionable(TRUE)
      ->setSetting('target_type', 'organization')
      ->setDisplayOptions('view', [
        'label' => 'above',
        'type' => 'string',
        'weight' => 0,
      ])
      ->setDisplayOptions('form', [
        'type' => 'entity_reference_autocomplete',
        'weight' => 5,
        'settings' => [
          'match_operator' => 'CONTAINS',
          'size' => '60',
          'autocomplete_type' => 'tags',
          'placeholder' => '',
        ],
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

      $fields['lines'] = BaseFieldDefinition::create('entity_reference')
        ->setLabel(t('Lines'))
        ->setDescription(t('The line items for the Deduction File.'))
        ->setRevisionable(TRUE)
        ->setSetting('target_type', 'deduction_file_line_item')
        ->setSetting('handler', 'default')
        ->setCardinality(FieldStorageDefinitionInterface::CARDINALITY_UNLIMITED)
        ->setDisplayOptions('view', [
          'label' => 'above',
          'type' => 'entity_reference_label',
          'weight' => 0,
        ])
        ->setDisplayOptions('form', array(
          'type' => 'entity_reference_autocomplete',
          'settings' => array(
            'match_operator' => 'CONTAINS',
            'size' => 60,
            'autocomplete_type' => 'tags',
            'placeholder' => '',
          ),
          'weight' => 99,
        ))
        ->setDisplayConfigurable('form', TRUE)
        ->setDisplayConfigurable('view', TRUE);

  $fields['year'] = BaseFieldDefinition::create('integer')
    ->setLabel(t('Year'))
    ->setDescription(t('The year for the Deduction File.'))
    ->setDisplayOptions('view', [
      'label' => 'above',
      'type' => 'string',
      'weight' => 0,
    ])
    ->setDisplayOptions('form', [
      'type' => 'textfield',
      'weight' => 5,
      'settings' => [
        'size' => '20'
      ],
    ])
    ->setDisplayConfigurable('form', TRUE)
    ->setDisplayConfigurable('view', TRUE);

  $fields['month'] = BaseFieldDefinition::create('integer')
    ->setLabel(t('Month'))
    ->setDescription(t('The month for the Deduction File.'))
    ->setDisplayOptions('view', [
      'label' => 'above',
      'type' => 'string',
      'weight' => 0,
    ])
    ->setDisplayOptions('form', [
      'type' => 'textfield',
      'weight' => 5,
      'settings' => [
        'size' => '20'
      ],
    ])
    ->setDisplayConfigurable('form', TRUE)
    ->setDisplayConfigurable('view', TRUE);

    $fields['amount'] = BaseFieldDefinition::create('decimal')
      ->setLabel(t('Amount'))
      ->setDescription(t('The total amount deducted for the organization.'))
      ->setDisplayOptions('view', [
        'label' => 'above',
        'type' => 'string',
        'weight' => 0,
      ])
      ->setDisplayOptions('form', [
        'type' => 'textfield',
        'weight' => 5,
        'settings' => [
          'size' => '20'
        ],
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['user_id'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('Authored by'))
      ->setDescription(t('The user ID of author of the Deduction File entity.'))
      ->setRevisionable(TRUE)
      ->setSetting('target_type', 'user')
      ->setSetting('handler', 'default')
      ->setTranslatable(TRUE)
      ->setDisplayOptions('view', [
        'label' => 'hidden',
        'type' => 'author',
        'weight' => 0,
      ])
      ->setDisplayOptions('form', [
        'type' => 'entity_reference_autocomplete',
        'weight' => 5,
        'settings' => [
          'match_operator' => 'CONTAINS',
          'size' => '60',
          'autocomplete_type' => 'tags',
          'placeholder' => '',
        ],
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['name'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Name'))
      ->setDescription(t('The name of the Deduction File entity.'))
      ->setSettings([
        'max_length' => 50,
        'text_processing' => 0,
      ])
      ->setDefaultValue('')
      ->setDisplayOptions('view', [
        'label' => 'above',
        'type' => 'string',
        'weight' => -4,
      ])
      ->setDisplayOptions('form', [
        'type' => 'string_textfield',
        'weight' => -4,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['status'] = BaseFieldDefinition::create('boolean')
      ->setLabel(t('Publishing status'))
      ->setDescription(t('A boolean indicating whether the Deduction File is published.'))
      ->setDefaultValue(TRUE);

    $fields['created'] = BaseFieldDefinition::create('created')
      ->setLabel(t('Created'))
      ->setDescription(t('The time that the entity was created.'));

    $fields['changed'] = BaseFieldDefinition::create('changed')
      ->setLabel(t('Changed'))
      ->setDescription(t('The time that the entity was last edited.'));

    return $fields;
  }

}
