<?php

namespace Drupal\pcx_order_ship\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Class OrderShipSettingsForm.
 *
 * @package Drupal\pcx_order_ship\Form
 *
 * @ingroup pcx_order_ship
 */
class OrderShipSettingsForm extends FormBase {

  /**
   * Returns a unique string identifying the form.
   *
   * @return string
   *   The unique string identifying the form.
   */
  public function getFormId() {
    return 'OrderShip_settings';
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
    $config = \Drupal::service('config.factory')->getEditable('pcx_order_ship.settings');
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
    $form['OrderShip_settings']['#markup'] = 'Settings form for Order Ship entities. Manage field settings here.';

    $config = \Drupal::service('config.factory')->getEditable('pcx_order_ship.settings');

    $integration_path = $config->get('integration_path');
    $form['OrderShip_settings']['integration_path'] = array(
     '#type' => 'textfield',
     '#title' => t('Integration Path'),
     '#default_value' => $integration_path ?: "integrations/import/order",
     '#description' => "Path in which to search for ship CSV files. Omit leading slash.",
     '#size' => 100,
     '#maxlength' => 255
    );

    $integration_glob = $config->get('integration_glob');
    $form['OrderShip_settings']['integration_glob'] = array(
     '#type' => 'textfield',
     '#title' => t('Integration Glob'),
     '#default_value' => $integration_glob ?: "shipping_update*.csv",
     '#description' => "File glob to search for ship CSV files.",
     '#size' => 100,
     '#maxlength' => 255
    );

    $processed_path = $config->get('processed_path');
    $form['OrderShip_settings']['processed_path'] = array(
     '#type' => 'textfield',
     '#title' => t('Processed Path'),
     '#default_value' => $processed_path ?: "integrations/processed",
     '#description' => "Directory to move processed CSV files to.",
     '#size' => 100,
     '#maxlength' => 255
    );

    $form['OrderShip_settings']['submit'] = array(
      '#type' => 'submit',
      '#value' => t('Submit'),
    );

    return $form;
  }

}
