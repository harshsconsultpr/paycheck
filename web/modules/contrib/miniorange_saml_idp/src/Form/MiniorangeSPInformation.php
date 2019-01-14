<?php

/**
 * @file
 * Contains \Drupal\miniorange_saml_idp\Form\MiniorangeSPInformation.
 */

namespace Drupal\miniorange_saml_idp\Form;

use Drupal\miniorange_saml_idp\Controller\DefaultController;
use Drupal\miniorange_saml_idp\MiniorangeSAMLIdpConstants;
use Drupal\miniorange_saml_idp\Utilities;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Render\Element;

class MiniorangeSPInformation extends FormBase {


  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'miniorange_sp_setup';
  }
public function buildForm(array $form, \Drupal\Core\Form\FormStateInterface $form_state) {
    
    
  if (\Drupal::config('miniorange_saml_idp.settings')->get('miniorange_saml_customer_admin_email') == NULL || \Drupal::config('miniorange_saml_idp.settings')->get('miniorange_saml_customer_id') == NULL
    || \Drupal::config('miniorange_saml_idp.settings')->get('miniorange_saml_customer_admin_token') == NULL || \Drupal::config('miniorange_saml_idp.settings')->get('miniorange_saml_customer_api_key') == NULL) {
    $form['header'] = array(
      '#markup' => '<center><h3>You need to register with miniOrange before using this module.</h3></center>',
    );

    return $form;
  }else if(\Drupal::config('miniorange_saml_idp.settings')->get('miniorange_saml_license_key') == NULL) {
      $form['header'] = array(
      '#markup' => '<center><h3>You need to verify your license key before using this module.</h3></center>',
      );
       return $form;
  }
  
   
   global $base_url;
  $url = \Drupal::config('miniorange_saml_idp.settings')->get('miniorange_saml_idp_base_url');
  $issuer =\Drupal::config('miniorange_saml_idp.settings')->get('miniorange_saml_idp_entity_id');
  
  $url = isset($url) && !empty($url)? $url:$base_url;
  $acs_url = $url  . '/samlassertion';
  $logout_url =$url  . '/user/logout';
 

  $login_url = $url . '/initiatelogon';
  $issuer = $base_url . '/?q=admin/config/people/miniorange_saml_idp/';
  $module_path = drupal_get_path('module', 'miniorange_saml_idp');
 
 
 $form['header'] = array(
    '#markup' => '<center><h3>You will need the following information to'
    . ' configure your Service Provider. Copy it and keep it handy</h3></center>',
  );

  $header = array(
    'attribute' => array('data' => t('Attribute')),
    'value' => array('data' => t('Value')),
  );

  $options = array();

  $options[0] = array(
    'attribute' => t('IDP-Entity ID / Issuer'),
    'value' => $issuer,
  );

  $options[1] = array(
    'attribute' => t('SAML Login URL'),
    'value' => $login_url,
  );

  
  
  $options[2] = array(
    'attribute' => t('SAML Logout URL'),
    'value' => $logout_url,
  );

  $options[3] = array(
    'attribute' => t('Certificate (Optional)'),
    'value' => t('<a href="' . $base_url . '/' . $module_path . '/resources/idp-signing.crt">Download</a>'),
  );

  $options[4] = array(
    'attribute' => t('Response Signed'),
    'value' => t('You can choose to sign your response in
   <a href="' . $base_url . '/admin/config/people/miniorange_saml_idp/idp_setup">Identity Provider</a>'),
  );

  $options[5] = array(
    'attribute' => t('Assertion Signed'),
    'value' => t('You can choose to sign your response in
   <a href="' . $base_url . '/admin/config/people/miniorange_saml_idp/idp_setup">Identity Provider</a>'),
  );

  $form['fieldset']['spinfo'] = array(
    '#theme' => 'table',
    '#header' => $header,
    '#rows' => $options,
  );

  $form['markup_idp_sp'] = array(
    '#markup' => '<center><h2>OR</h2></center>',
  );

  $form['markup_idp_sp_1'] = array(
    '#markup' => 'You can provide this metadata URL to your Service Provider.<br />',
  );

  $form['markup_idp_sp_2'] = array(
    '#markup' => '<code style="background-color:gainsboro;"><b>'
  . '<a target="_blank" href="' . $base_url . '/moidp_metadata">' . $base_url . '/moidp_metadata</a></b></code>',
  );

  return $form;
 }
 
 public function submitForm(array &$form, \Drupal\Core\Form\FormStateInterface $form_state) {
 }
}