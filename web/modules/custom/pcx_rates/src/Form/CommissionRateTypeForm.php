<?php

namespace Drupal\pcx_rates\Form;

use Drupal\Core\Entity\EntityForm;
use Drupal\Core\Form\FormStateInterface;

/**
 * Class CommissionRateTypeForm.
 */
class CommissionRateTypeForm extends EntityForm {

  /**
   * {@inheritdoc}
   */
  public function form(array $form, FormStateInterface $form_state) {
    $form = parent::form($form, $form_state);

    $commission_rate_type = $this->entity;
    $form['label'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Label'),
      '#maxlength' => 255,
      '#default_value' => $commission_rate_type->label(),
      '#description' => $this->t("Label for the Commission rate type."),
      '#required' => TRUE,
    ];

    $form['id'] = [
      '#type' => 'machine_name',
      '#default_value' => $commission_rate_type->id(),
      '#machine_name' => [
        'exists' => '\Drupal\pcx_rates\Entity\CommissionRateType::load',
      ],
      '#disabled' => !$commission_rate_type->isNew(),
    ];

    /* You will need additional form elements for your custom properties. */

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state) {
    $commission_rate_type = $this->entity;
    $status = $commission_rate_type->save();

    switch ($status) {
      case SAVED_NEW:
        drupal_set_message($this->t('Created the %label Commission rate type.', [
          '%label' => $commission_rate_type->label(),
        ]));
        break;

      default:
        drupal_set_message($this->t('Saved the %label Commission rate type.', [
          '%label' => $commission_rate_type->label(),
        ]));
    }
    $form_state->setRedirectUrl($commission_rate_type->toUrl('collection'));
  }

}
