<?php

namespace Drupal\pcx_accounting\Form;

use Drupal\Core\Entity\ContentEntityForm;
use Drupal\Core\Form\FormStateInterface;

/**
 * Form controller for Commission statement line item edit forms.
 *
 * @ingroup pcx_accounting
 */
class CommissionStatementLineItemForm extends ContentEntityForm {

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    /* @var $entity \Drupal\pcx_accounting\Entity\CommissionStatementLineItem */
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
        drupal_set_message($this->t('Created the %label Commission statement line item.', [
          '%label' => $entity->label(),
        ]));
        break;

      default:
        drupal_set_message($this->t('Saved the %label Commission statement line item.', [
          '%label' => $entity->label(),
        ]));
    }
    $form_state->setRedirect('entity.commission_statement_line_item.canonical', ['commission_statement_line_item' => $entity->id()]);
  }

}
