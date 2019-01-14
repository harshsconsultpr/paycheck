<?php

namespace Drupal\pcx_order_export;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityListBuilder;
use Drupal\Core\Routing\LinkGeneratorTrait;
use Drupal\Core\Url;

/**
 * Defines a class to build a listing of Order Export entities.
 *
 * @ingroup pcx_order_export
 */
class OrderExportListBuilder extends EntityListBuilder {

  use LinkGeneratorTrait;

  /**
   * {@inheritdoc}
   */
  public function buildHeader() {
    $header['id'] = $this->t('Order Export ID');
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
    /* @var $entity \Drupal\pcx_order_export\Entity\OrderExport */
    // \Drupal::logger('pcx_order_export')->notice("Processing line : #{$entity->id()}\n<pre>".print_r($entity));
//     $uid = $entity->get('user_id')->getValue()[0]['target_id'];
//     $user=user_load($uid);
// $username=$user->name;
//     \Drupal::logger('pcx_order_export')->notice("Processing line111111111 : #{$entity->id()}\n<pre>".print_r($username,true));   
   
     
    // $order = $entity
    //   ->get('order_id')
    //   ->first()
    //   ->get('entity')
    //   ->getTarget()
    //   ->getValue();

    // $user = $entity
    //   ->get('user_id')
    //   ->first()
    //   ->get('entity')
    //   ->getTarget()
    //   ->getValue()
    // ;
    $uid = $entity->get('user_id')->getValue()[0]['target_id'];
    
    $account = \Drupal\user\Entity\User::load($uid); // pass your uid
     $name = $account->getUsername();
  // echo '<pre>';
  // print_r( $user->name);
 // \Drupal::logger('pcx_order_export')->notice("Processing line user : #{$uid}\n<pre>".print_r($users,true));
    $row['id'] = $entity->id();
    $row['order_id'] = $entity->get('order_id')->getValue()[0]['target_id'];
    $row['user_id'] = $name ;
    $row['created'] = date('Y-m-d', $entity->get('created')->value);
    $row['status'] = $entity->get('status')->value ? 'True' : 'False';
    return $row + parent::buildRow($entity);
 
  }

}
