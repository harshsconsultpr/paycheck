<?php

namespace Drupal\pcx_rates\Form;

use Drupal\Core\Entity\ContentEntityForm;
use Drupal\Core\Form\FormStateInterface;

/**
 * Form controller for Commission rate edit forms.
 *
 * @ingroup pcx_rates
 */
class CommissionRateForm extends ContentEntityForm {

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    /* @var $entity \Drupal\pcx_rates\Entity\CommissionRate */
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
        drupal_set_message($this->t('Created the %label Commission rate.', [
          '%label' => $entity->label(),
        ]));
        break;

      default:
        drupal_set_message($this->t('Saved the %label Commission rate.', [
          '%label' => $entity->label(),
        ]));
    }
    $form_state->setRedirect('entity.commission_rate.canonical', ['commission_rate' => $entity->id()]);
  }

}
