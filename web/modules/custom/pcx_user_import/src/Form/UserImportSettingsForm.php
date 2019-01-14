<?php

namespace Drupal\pcx_user_import\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Class UserImportSettingsForm.
 *
 * @package Drupal\pcx_user_import\Form
 *
 * @ingroup pcx_user_import
 */
class UserImportSettingsForm extends FormBase {

  /**
   * Returns a unique string identifying the form.
   *
   * @return string
   *   The unique string identifying the form.
   */
  public function getFormId() {
    return 'UserImport_settings';
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
    $config = \Drupal::service('config.factory')->getEditable('pcx_user_import.settings');
    $config->set('integration_path', $form_state->getValue('integration_path'))->save();
    $config->set('integration_file', $form_state->getValue('integration_file'))->save();
  }

  /**
   * Defines the settings form for Order Ship entities.
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
    $form['UserImport_settings']['#markup'] = 'Settings form for Paycheck Exchange User Import.';

    $config = \Drupal::service('config.factory')->getEditable('pcx_user_import.settings');

    $integration_path = $config->get('integration_path');
    $form['UserImport_settings']['integration_path'] = array(
     '#type' => 'textfield',
     '#title' => t('Integration Path'),
     '#default_value' => $integration_path ?: "integrations/import/user",
     '#description' => "Path in which to search for user CSV file. Omit leading slash.",
     '#size' => 100,
     '#maxlength' => 255
    );

    $integration_file = $config->get('integration_file');
    $form['UserImport_settings']['integration_file'] = array(
     '#type' => 'textfield',
     '#title' => t('Filename'),
     '#default_value' => $integration_file ?: "employees.csv",
     '#size' => 100,
     '#maxlength' => 255
    );

    $form['UserImport_settings']['submit'] = array(
      '#type' => 'submit',
      '#value' => t('Submit'),
    );

    return $form;
  }

}
