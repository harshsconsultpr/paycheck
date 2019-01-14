<?php

namespace Drupal\pcx_price_calc\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Configure pcx_price_calc settings for this site.
 */
class PCXPriceCalcAdminForm extends ConfigFormBase {
    /**
     * {@inheritdoc}
     */
    public function getFormId() {
        return 'pcx_price_calc_admin_settings';
    }

    /**
     * {@inheritdoc}
     */
    protected function getEditableConfigNames() {
        return [
            'pcx_price_calc.settings',
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(array $form, FormStateInterface $form_state) {
        $config = $this->config('pcx_price_calc.settings');


        $form['pcx_price_calc_global_markup'] = array(
            '#type' => 'textfield',
            '#title' => $this->t('Global Markup Amount'),
            '#default_value' => $config->get('global_markup'),
            '#description' => 'Enter the percentage amount to markup all products by. For example for a 20% increase enter 20.',
            '#required' => TRUE,
        );

        return parent::buildForm($form, $form_state);
    }

    /**
     * {@inheritdoc}
     */
    public function validateForm(array &$form, FormStateInterface $form_state) {
        if (!is_numeric($form_state->getValue('pcx_price_calc_global_markup'))) {
            $form_state->setErrorByName('pcx_price_calc_global_markup', $this->t('Markup must be numeric.'));
        }
    }
    /**
     * {@inheritdoc}
     */
    public function submitForm(array &$form, FormStateInterface $form_state) {
        // Retrieve the configuration
        \Drupal::configFactory()->getEditable('pcx_price_calc.settings')
            // Set the submitted configuration setting
            ->set('global_markup', $form_state->getValue('pcx_price_calc_global_markup'))
            // You can set multiple configurations at once by making
            // multiple calls to set()

            ->save();

        parent::submitForm($form, $form_state);
    }
}
