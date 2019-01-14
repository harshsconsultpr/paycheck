<?php

/**
 * @file
 * Contains \Drupal\miniorange_saml_idp\Form\MiniorangeIDPSetup.
 */

namespace Drupal\miniorange_saml_idp\Form;

use Drupal\miniorange_saml_idp\MiniorangeSAMLCustomer;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Render\Element;

class MiniorangeIDPSetup extends FormBase {

    public function getFormId() {
        return 'miniorange_saml_idp_setup';
    }

    public function buildForm(array $form, \Drupal\Core\Form\FormStateInterface $form_state) {


        if (\Drupal::config('miniorange_saml_idp.settings')->get('miniorange_saml_customer_admin_email') == NULL || \Drupal::config('miniorange_saml_idp.settings')->get('miniorange_saml_customer_id') == NULL || \Drupal::config('miniorange_saml_idp.settings')->get('miniorange_saml_customer_admin_token') == NULL || \Drupal::config('miniorange_saml_idp.settings')->get('miniorange_saml_customer_api_key') == NULL) {
            $form['header'] = array(
                '#markup' => '<center><h3>You need to register with miniOrange before using this module.</h3></center>',
            );

            return $form;
        } else if (\Drupal::config('miniorange_saml_idp.settings')->get('miniorange_saml_license_key') == NULL) {
            $form['header'] = array(
                '#markup' => '<center><h3>You need to verify your license key before using this module.</h3></center>',
            );
            return $form;
        }

        global $base_url;
        $url = \Drupal::config('miniorange_saml_idp.settings')->get('miniorange_saml_base_url');
        $issuer = \Drupal::config('miniorange_saml_idp.settings')->get('miniorange_saml_entity_id');

        $b_url = isset($url) && !empty($url) ? $url : $base_url;
        $issuer_id = isset($issuer) && !empty($issuer) ? $issuer : $base_url;


        $acs_url = $b_url . '/samlassertion';
        //$logout_url = $b_url . '/user/logout';

        $form['markup_idp_header'] = array(
            '#markup' => '<h3>Configure Identity Provider</h3>',
        );


        $form['markup_idp_note'] = array(
            '#markup' => '<div>Please note down the following information from your Service Provider'
            . ' and keep it handy to configure your Identity Provider.</div>',
        );

        $form['markup_idp_list'] = array(
            '#markup' => '<b><ol><li>SP Entity ID / Issuer</li>'
            . ' <li>ACS URL</li>'
            . ' <li>NameID Format</li></ol></b><br />',
        );

        if (!isset($mo_admin_email)) {
            $form['markup_saml_idp_disabled'] = array(
                '#markup' => '<div style="position: absolute;margin: 7% 33%;font-weight: bold;z-index: 1;">'
                . ' </div><div style="background-color: rgba(218, 218, 218, 0);padding: 2%;opacity: 0.3;">',
            );
        }

        $form['miniorange_saml_idp_name'] = array(
            '#type' => 'textfield',
            '#title' => t('Service Provider Name'),
            '#default_value' => \Drupal::config('miniorange_saml_idp.settings')->get('miniorange_saml_idp_name'),
            '#attributes' => array('placeholder' => 'Service Provider Name'),
            '#required' => TRUE,
        );

        $form['miniorange_saml_idp_entity_id'] = array(
            '#type' => 'textfield',
            '#title' => t('SP Entity ID or Issuer'),
            '#default_value' => \Drupal::config('miniorange_saml_idp.settings')->get('miniorange_saml_idp_entity_id'),
            '#attributes' => array('placeholder' => 'SP Entity ID or Issuer'),
            '#required' => TRUE,
        );

        $form['miniorange_saml_idp_acs_url'] = array(
            '#type' => 'textfield',
            '#title' => t('ACS URL'),
            '#default_value' => \Drupal::config('miniorange_saml_idp.settings')->get('miniorange_saml_idp_acs_url'),
            '#attributes' => array('placeholder' => 'ACS URL'),
            '#required' => TRUE,
        );

        $form['miniorange_saml_idp_binding'] = array(
            '#type' => 'radios',
            '#title' => t('HTTP Binding'),
            '#default_value' => (\Drupal::config('miniorange_saml_idp.settings')->get('miniorange_saml_idp_http_binding') == 'HTTP-POST') ? 1 : 0,
            '#options' => array(
                t('HTTP-Redirect'),
                t('HTTP-POST')),
        );

        $form['miniorange_saml_idp_logout_url'] = array(
            '#type' => 'textfield',
            '#title' => t('SAML Logout URL'),
            '#default_value' => \Drupal::config('miniorange_saml_idp.settings')->get('miniorange_saml_idp_logout_url'),
            '#attributes' => array('placeholder' => 'SAML Logout URL'),
        );

        $form['miniorange_saml_idp_relay_state'] = array(
            '#type' => 'textfield',
            '#title' => t('Relay State'),
            '#default_value' => \Drupal::config('miniorange_saml_idp.settings')->get('miniorange_saml_idp_relay_state'),
            '#attributes' => array('placeholder' => 'Relay State (optional)'),
        );


        $form['miniorange_saml_idp_nameid_format'] = array(
            '#type' => 'select',
            '#title' => t('NameID Format:'),
            '#options' => array(
                '1.1:nameid-format:emailAddress' => t('urn:oasis:names:tc:SAML:1.1:nameid-format:emailAddress'),
                '1.1:nameid-format:unspecified' => t('urn:oasis:names:tc:SAML:1.1:nameid-format:unspecified'),
                '2.0:nameid-format:transient' => t('urn:oasis:names:tc:SAML:1.1:nameid-format:transient'),
                '2.0:nameid-format:persistent' => t('urn:oasis:names:tc:SAML:1.1:nameid-format:persistent'),
            ),
            '#default_value' => \Drupal::config('miniorange_saml_idp.settings')->get('miniorange_saml_idp_nameid_format'),
            '#description' => t('(<b>NOTE:</b> urn:oasis:names:tc:SAML:1.1:nameid-format:emailAddress is selected by default)'),
        );


        $form['miniorange_saml_idp_response_signed'] = array(
            '#type' => 'checkbox',
            '#title' => t('Response Signed (Check If you want to sign SAML Response.)'),
            '#default_value' => \Drupal::config('miniorange_saml_idp.settings')->get('miniorange_saml_idp_response_signed'),
        );


        $form['miniorange_saml_idp_assertion_signed'] = array(
            '#type' => 'checkbox',
            '#title' => t('Assertion Signed (Check If you want to sign SAML Assertion.)'),
            '#default_value' => \Drupal::config('miniorange_saml_idp.settings')->get('miniorange_saml_idp_assertion_signed'),
        );


        $form['miniorange_saml_idp_config_submit'] = array(
            '#type' => 'submit',
            '#value' => t('Save Configuration'),
        );

        $testConfigUrl = "\'" . $this->getTestUrl() . "\'";

        $form['miniorange_saml_idp_test_config_button'] = array('#attached' => array(
                'library' => 'miniorange_saml_idp/miniorange_saml_idp.test',
            ),
            '#markup' => '<a id="testConfigButton" class="btn btn-primary btn-large" style="padding:6px 12px;" onclick="testIdpConfig($testConfigUrl);">Test Configuration</a><br><br>'
        );

        $form['miniorange_saml_idp_config_delete'] = array(
            '#type' => 'submit',
            '#value' => t('Delete Configuration'),
            '#submit' => array('::miniorange_saml_idp_delete_idp_config'),
        );

        return $form;
    }

    function getTestUrl() {
        global $base_url;
        $testUrl = $base_url . '/?q=kofe-config';

        return $testUrl;
    }

    function submitForm(array &$form, \Drupal\Core\Form\FormStateInterface $form_state) {
        global $base_url;
        $mo_admin_email = \Drupal::config('miniorange_saml_idp.settings')->get('miniorange_saml_idp_customer_admin_email');
        $sp_name = $form['miniorange_saml_idp_name']['#value'];
        $issuer = $form['miniorange_saml_idp_entity_id']['#value'];
        $acs_url = $form['miniorange_saml_idp_acs_url']['#value'];
        $relay_state = $form['miniorange_saml_idp_relay_state']['#value'];
        $nameid_format = $form['miniorange_saml_idp_nameid_format']['#value'];
        $is_response_signed = $form['miniorange_saml_idp_response_signed']['#value'] == 1 ? TRUE : FALSE;
        $logout_url = $form['miniorange_saml_idp_logout_url']['#value'];
        $is_assertion_signed = $form['miniorange_saml_idp_assertion_signed']['#value'] == 1 ? TRUE : FALSE;
        $http_binding_value = $form['miniorange_saml_idp_binding']['#value'];

        if ($http_binding_value == 1) {
            $http_binding = 'HTTP-POST';
        } else {
            $http_binding = 'HTTP-Redirect';
        }

        \Drupal::configFactory()->getEditable('miniorange_saml_idp.settings')->set('miniorange_saml_idp_name', $sp_name)->save();
        \Drupal::configFactory()->getEditable('miniorange_saml_idp.settings')->set('miniorange_saml_idp_http_binding', $http_binding)->save();
        \Drupal::configFactory()->getEditable('miniorange_saml_idp.settings')->set('miniorange_saml_idp_entity_id', $issuer)->save();
        \Drupal::configFactory()->getEditable('miniorange_saml_idp.settings')->set('miniorange_saml_idp_acs_url', $acs_url)->save();
        \Drupal::configFactory()->getEditable('miniorange_saml_idp.settings')->set('miniorange_saml_idp_relay_state', $relay_state)->save();
        \Drupal::configFactory()->getEditable('miniorange_saml_idp.settings')->set('miniorange_saml_idp_nameid_format', $nameid_format)->save();
        \Drupal::configFactory()->getEditable('miniorange_saml_idp.settings')->set('miniorange_saml_idp_logout_url', $logout_url)->save();

        \Drupal::configFactory()->getEditable('miniorange_saml_idp.settings')->set('miniorange_saml_idp_assertion_signed', $is_assertion_signed)->save();

        \Drupal::configFactory()->getEditable('miniorange_saml_idp.settings')->set('miniorange_saml_idp_response_signed', $is_response_signed)->save();


        drupal_set_message(t('Your Service Provider Configuration are successfully saved. You can click on Test Configuration button below to test these configurations.'));
    }

    function miniorange_saml_idp_delete_idp_config($form, &$form_state) {

        \Drupal::configFactory()->getEditable('miniorange_saml_idp.settings')->set('miniorange_saml_idp_name', '')->save();

        \Drupal::configFactory()->getEditable('miniorange_saml_idp.settings')->set('miniorange_saml_idp_logout_url', '')->save();

        \Drupal::configFactory()->getEditable('miniorange_saml_idp.settings')->set('miniorange_saml_idp_entity_id', '')->save();

        \Drupal::configFactory()->getEditable('miniorange_saml_idp.settings')->set('miniorange_saml_idp_acs_url', '')->save();

        \Drupal::configFactory()->getEditable('miniorange_saml_idp.settings')->set('miniorange_saml_idp_relay_state', '')->save();

        \Drupal::configFactory()->getEditable('miniorange_saml_idp.settings')->set('miniorange_saml_idp_nameid_format', '')->save();

        \Drupal::configFactory()->getEditable('miniorange_saml_idp.settings')->set('miniorange_saml_idp_assertion_signed', '')->save();


        drupal_set_message(t('Your Service Provider Configuration is successfully deleted.'));
    }

}
