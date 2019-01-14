<?php

namespace Drupal\pcx_accounting;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityListBuilder;
use Drupal\Core\Routing\LinkGeneratorTrait;
use Drupal\Core\Url;

/**
 * Defines a class to build a listing of Organization invoice line item entities.
 *
 * @ingroup pcx_accounting
 */
class OrganizationInvoiceLineItemListBuilder extends EntityListBuilder {

  use LinkGeneratorTrait;

  /**
   * {@inheritdoc}
   */
  public function buildHeader() {
    $header['id'] = $this->t('Organization invoice line item ID');
    $header['name'] = $this->t('Name');
    return $header + parent::buildHeader();
  }

  /**
   * {@inheritdoc}
   */
  public function buildRow(EntityInterface $entity) {
    /* @var $entity \Drupal\pcx_accounting\Entity\OrganizationInvoiceLineItem */
    $row['id'] = $entity->id();
    $row['name'] = $this->l(
      $entity->label(),
      new Url(
        'entity.organization_invoice_line_item.edit_form', [
          'organization_invoice_line_item' => $entity->id(),
        ]
      )
    );
    return $row + parent::buildRow($entity);
  }

}
