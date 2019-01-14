<?php

namespace Drupal\pcx_order_cancel;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityListBuilder;
use Drupal\Core\Routing\LinkGeneratorTrait;
use Drupal\Core\Url;

/**
 * Defines a class to build a listing of Order Cancel entities.
 *
 * @ingroup pcx_order_cancel
 */
class OrderCancelListBuilder extends EntityListBuilder {

  use LinkGeneratorTrait;

  /**
   * {@inheritdoc}
   */
  public function buildHeader() {
    $header['id'] = $this->t('Order Cancel ID');
    $header['order_id'] = $this->t('Order');
    $header['user_id'] = $this->t('User');
    $header['created'] = $this->t('Created');
    $header['status'] = $this->t('Exported');
    return $header + parent::buildHeader();
  }

  /**
   * {@inheritdoc}
   */
  public function buildRow(EntityInterface $entity) {
    /* @var $entity \Drupal\pcx_order_cancel\Entity\OrderCancel */

    $order = $entity
      ->get('order_id')
      ->first()
      ->get('entity')
      ->getTarget()
      ->getValue()
    ;

    $user = $entity
      ->get('user_id')
      ->first()
      ->get('entity')
      ->getTarget()
      ->getValue()
    ;

    $row['id'] = $entity->id();
    $row['order_id'] = $order->id();
    $row['user_id'] = $user->get('name')->value;
    $row['created'] = date('Y-m-d', $entity->get('created')->value);
    $row['status'] = $entity->get('status')->value ? 'True' : 'False';

    return $row + parent::buildRow($entity);
  }

}
