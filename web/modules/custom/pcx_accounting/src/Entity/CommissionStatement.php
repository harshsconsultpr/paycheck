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
 * Defines the Commission Statement entity.
 *
 * @ingroup pcx_accounting
 *
 * @ContentEntityType(
 *   id = "commission_statement",
 *   label = @Translation("Commission Statement"),
 *   handlers = {
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\pcx_accounting\CommissionStatementListBuilder",
 *     "views_data" = "Drupal\pcx_accounting\Entity\CommissionStatementViewsData",
 *
 *     "form" = {
 *       "default" = "Drupal\pcx_accounting\Form\CommissionStatementForm",
 *       "add" = "Drupal\pcx_accounting\Form\CommissionStatementForm",
 *       "edit" = "Drupal\pcx_accounting\Form\CommissionStatementForm",
 *       "delete" = "Drupal\pcx_accounting\Form\CommissionStatementDeleteForm",
 *     },
 *     "access" = "Drupal\pcx_accounting\CommissionStatementAccessControlHandler",
 *     "route_provider" = {
 *       "html" = "Drupal\pcx_accounting\CommissionStatementHtmlRouteProvider",
 *     },
 *   },
 *   base_table = "commission_statement",
 *   admin_permission = "administer commission statement entities",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "name",
 *     "uuid" = "uuid",
 *     "uid" = "user_id",
 *     "langcode" = "langcode",
 *     "status" = "status",
 *   },
 *   links = {
 *     "canonical" = "/admin/structure/commission_statement/{commission_statement}",
 *     "add-form" = "/admin/structure/commission_statement/add",
 *     "edit-form" = "/admin/structure/commission_statement/{commission_statement}/edit",
 *     "delete-form" = "/admin/structure/commission_statement/{commission_statement}/delete",
 *     "collection" = "/admin/structure/commission_statement",
 *   },
 *   field_ui_base_route = "commission_statement.settings"
 * )
 */
class CommissionStatement extends ContentEntityBase implements CommissionStatementInterface {

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

    $fields['referral_partner_id'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('Referral Partner'))
      ->setDescription(t('The referral partner for the commission statement.'))
      ->setRevisionable(TRUE)
      ->setSetting('target_type', 'user')
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
        ->setDescription(t('The line items for the commission statement.'))
        ->setRevisionable(TRUE)
        ->setSetting('target_type', 'commission_statement_line_item')
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
    ->setDescription(t('The year for the commission statement.'))
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
    ->setDescription(t('The month for the commission statement.'))
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
      ->setDescription(t('The total amount owed for the commission.'))
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
      ->setDescription(t('The user ID of author of the Commission Statement entity.'))
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
      ->setDescription(t('The name of the Commission Statement entity.'))
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
      ->setDescription(t('A boolean indicating whether the Commission Statement is published.'))
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
