<?php

namespace Drupal\pcx_order_cancel\Form;

use Drupal\Core\Entity\ContentEntityForm;
use Drupal\Core\Form\FormStateInterface;

/**
 * Form controller for Order Cancel edit forms.
 *
 * @ingroup pcx_order_cancel
 */
class OrderCancelForm extends ContentEntityForm {

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    /* @var $entity \Drupal\pcx_order_cancel\Entity\OrderCancel */
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
        drupal_set_message($this->t('Created the %label Order Cancel.', [
          '%label' => $entity->label(),
        ]));
        break;

      default:
        drupal_set_message($this->t('Saved the %label Order Cancel.', [
          '%label' => $entity->label(),
        ]));
    }
    $form_state->setRedirect('entity.order_cancel.canonical', ['order_cancel' => $entity->id()]);
  }

}
