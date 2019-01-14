<?php

namespace Drupal\pcx_catalog_cleanup\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Class CatalogCleanupSettingsForm.
 *
 * @package Drupal\pcx_catalog_cleanup\Form
 *
 * @ingroup pcx_catalog_cleanup
 */
class CatalogCleanupSettingsForm extends FormBase {

  /**
   * Returns a unique string identifying the form.
   *
   * @return string
   *   The unique string identifying the form.
   */
  public function getFormId() {
    return 'catalog_cleanup_settings';
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
    $config = \Drupal::service('config.factory')->getEditable('pcx_catalog_cleanup.settings');
    $config->set('integration_path', $form_state->getValue('integration_path'))->save();
    $config->set('integration_glob', $form_state->getValue('integration_glob'))->save();
    $config->set('processed_path', $form_state->getValue('processed_path'))->save();
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
    $form['catalog_cleanup_settings']['#markup'] = 'Settings form for Catalog Cleanup.';

    $config = \Drupal::service('config.factory')->getEditable('pcx_catalog_cleanup.settings');

    $integration_path = $config->get('integration_path');
    $form['catalog_cleanup_settings']['integration_path'] = array(
     '#type' => 'textfield',
     '#title' => t('Integration Path'),
     '#default_value' => $integration_path ?: "integrations/import/catalog/",
     '#description' => "Path in which to search for remove CSV files. Omit leading slash.",
     '#size' => 100,
     '#maxlength' => 255
    );

    $integration_glob = $config->get('integration_glob');
    $form['catalog_cleanup_settings']['integration_glob'] = array(
     '#type' => 'textfield',
     '#title' => t('Integration Glob'),
     '#default_value' => $integration_glob ?: "abt_inventory_remove_*.csv",
     '#description' => "File glob to search for remove CSV files.",
     '#size' => 100,
     '#maxlength' => 255
    );

    $processed_path = $config->get('processed_path');
    $form['catalog_cleanup_settings']['processed_path'] = array(
     '#type' => 'textfield',
     '#title' => t('Processed Path'),
     '#default_value' => $processed_path ?: "integrations/processed",
     '#description' => "Directory to move processed CSV files to.",
     '#size' => 100,
     '#maxlength' => 255
    );

    $form['catalog_cleanup_settings']['submit'] = array(
      '#type' => 'submit',
      '#value' => t('Submit'),
    );

    return $form;
  }

}
