<?php

namespace Drupal\pcx_order_export\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Class OrderExportSettingsForm.
 *
 * @package Drupal\pcx_order_export\Form
 *
 * @ingroup pcx_order_export
 */
class OrderExportSettingsForm extends FormBase {

  /**
   * Returns a unique string identifying the form.
   *
   * @return string
   *   The unique string identifying the form.
   */
  public function getFormId() {
    return 'OrderExport_settings';
  }

  /**
   * Form submission handler.
   *
   * @param array $form
   *   An associative array containing the structure of the form.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   The current state of the form.
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    // Empty implementation of the abstract submit class.
    $config = \Drupal::service('config.factory')->getEditable('pcx_order_export.settings');
    $config->set('integration_path', $form_state->getValue('integration_path'))->save();
  }

  /**
   * Defines the settings form for Order Export entities.
   *
   * @param array $form
   *   An associative array containing the structure of the form.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   The current state of the form.
   *
   * @return array
   *   Form definition array.
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form['OrderExport_settings']['#markup'] = 'Settings form for Order Export entities. Manage field settings here.';

    $config = \Drupal::service('config.factory')->getEditable('pcx_order_export.settings');
    $integration_path = $config->get('integration_path');
    $form['OrderExport_settings']['integration_path'] = array(
     '#type' => 'textfield',
     '#title' => t('Integration Path'),
     '#default_value' => $integration_path ?: "integrations/export/order",
     '#description' => "Path in which to save exported CSV files. Omit leading slash. If path does not exist, it will be created.",
     '#size' => 100,
     '#maxlength' => 255
    );

    $form['OrderExport_settings']['submit'] = array(
      '#type' => 'submit',
      '#value' => t('Submit'),
    );

    return $form;
  }

}
