<?php

namespace Drupal\pcx_accounting;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityListBuilder;
use Drupal\Core\Routing\LinkGeneratorTrait;
use Drupal\Core\Url;

/**
 * Defines a class to build a listing of Deduction file line item entities.
 *
 * @ingroup pcx_accounting
 */
class DeductionFileLineItemListBuilder extends EntityListBuilder {

  use LinkGeneratorTrait;

  /**
   * {@inheritdoc}
   */
  public function buildHeader() {
    $header['id'] = $this->t('Deduction file line item ID');
    $header['name'] = $this->t('Name');
    return $header + parent::buildHeader();
  }

  /**
   * {@inheritdoc}
   */
  public function buildRow(EntityInterface $entity) {
    /* @var $entity \Drupal\pcx_accounting\Entity\DeductionFileLineItem */
    $row['id'] = $entity->id();
    $row['name'] = $this->l(
      $entity->label(),
      new Url(
        'entity.deduction_file_line_item.edit_form', [
          'deduction_file_line_item' => $entity->id(),
        ]
      )
    );
    return $row + parent::buildRow($entity);
  }

}
