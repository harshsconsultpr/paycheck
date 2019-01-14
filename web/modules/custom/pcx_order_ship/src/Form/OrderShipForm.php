<?php

namespace Drupal\pcx_order_ship\Form;

use Drupal\Core\Entity\ContentEntityForm;
use Drupal\Core\Form\FormStateInterface;

/**
 * Form controller for Order Ship edit forms.
 *
 * @ingroup pcx_order_ship
 */
class OrderShipForm extends ContentEntityForm {

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    /* @var $entity \Drupal\pcx_order_ship\Entity\OrderShip */
    $form = parent::buildForm($form, $form_state);

    $entity = $this->entity;

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state) {
    $entity = &$this->entity;

    $status = parent::save($form, $form_state);

    switch ($status) {
      case SAVED_NEW:
        drupal_set_message($this->t('Created the %label Order Ship.', [
          '%label' => $entity->label(),
        ]));
        break;

      default:
        drupal_set_message($this->t('Saved the %label Order Ship.', [
          '%label' => $entity->label(),
        ]));
    }
    $form_state->setRedirect('entity.order_ship.canonical', ['order_ship' => $entity->id()]);
  }

}
