<?php

namespace Drupal\pcx_accounting;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityListBuilder;
use Drupal\Core\Routing\LinkGeneratorTrait;
use Drupal\Core\Url;

/**
 * Defines a class to build a listing of Commission statement line item entities.
 *
 * @ingroup pcx_accounting
 */
class CommissionStatementLineItemListBuilder extends EntityListBuilder {

  use LinkGeneratorTrait;

  /**
   * {@inheritdoc}
   */
  public function buildHeader() {
    $header['id'] = $this->t('Commission statement line item ID');
    $header['name'] = $this->t('Name');
    return $header + parent::buildHeader();
  }

  /**
   * {@inheritdoc}
   */
  public function buildRow(EntityInterface $entity) {
    /* @var $entity \Drupal\pcx_accounting\Entity\CommissionStatementLineItem */
    $row['id'] = $entity->id();
    $row['name'] = $this->l(
      $entity->label(),
      new Url(
        'entity.commission_statement_line_item.edit_form', [
          'commission_statement_line_item' => $entity->id(),
        ]
      )
    );
    return $row + parent::buildRow($entity);
  }

}
