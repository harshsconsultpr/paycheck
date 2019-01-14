<?php
/**
 * @file
 * Contains Login Settings for miniOrange SAML Login Module.
 */

 /**
 * Showing Settings form.
 */
 namespace Drupal\miniorange_saml_idp\Form;
 
 use Drupal\Core\Form\FormBase;
 
 class MiniorangeSignonSettings extends FormBase {
	 
  public function getFormId() {
    return 'miniorange_saml_login_setting';
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


 $form['markup_idp_login_header'] = array(
    '#markup' => '<h3>IDP Initiated Login</h3>',
  );
  
  $form['markup_idp_login_info'] = array(
    '#markup' => '<div><b>Add a link to user dashboard for login into your Service Provider.</b></div>',
  );
  
  $form['markup_idp_login_note'] = array(
    '#markup' => '<div><b>Note: </b>Add the following link such that it is only visible to your logged in users only.</div>',
  );
  


$form['markup_idp_login_link'] = array(
    '#markup' => '<div style="color:#3071a9;"><b><span class="site-url">' . $base_url . '</span>/saml_user_login'.'?sp=&lt;Your SP Name&gt;</b></div>',
  );


 $form['markup_idp_login_header1'] = array(
    '#markup' => '<br /><h3>Drupal Login Page Url</h3>Enter your site`s login page url. If it is empty then user will be redirected to base url of your site for login.',
  );
  

$form['miniorange_saml_default_relaystate'] = array(
  '#type' => 'textfield',
    '#title' => t('Default Redirect URL after login'),
  '#default_value' => \Drupal::config('miniorange_saml_idp.settings')->get('miniorange_saml_default_relaystate'),
  '#attributes' => array('placeholder' => 'Enter Default Redirect URL'),
  );
  

    
  $form['miniorange_saml_gateway_config_submit'] = array(
    '#type' => 'submit',
    '#value' => t('Save Configuration'),
  );
  
  return $form;

 }

  public function submitForm(array &$form, \Drupal\Core\Form\FormStateInterface $form_state) {

  $default_relaystate = $form['miniorange_saml_default_relaystate']['#value'];
  
  \Drupal::configFactory()->getEditable('miniorange_saml_idp.settings')->set('miniorange_saml_default_relaystate', $default_relaystate)->save();
 
  
  drupal_set_message(t('Signin Settings successfully saved'));

 }
 }