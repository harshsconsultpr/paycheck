<?php
/**
 * @file
 * Contains Attribute and Role Mapping for miniOrange SAML Login Module.
 */

 /**
 * Showing Settings form.
 */
namespace Drupal\miniorange_saml\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

 class Mapping extends FormBase {
	 
  public function getFormId() {
    return 'miniorange_saml_mapping';
  }
	 
 public function buildForm(array $form, \Drupal\Core\Form\FormStateInterface $form_state) {

  if (\Drupal::config('miniorange_saml.settings')->get('miniorange_saml_customer_admin_email') == NULL || \Drupal::config('miniorange_saml.settings')->get('miniorange_saml_customer_id') == NULL
    || \Drupal::config('miniorange_saml.settings')->get('miniorange_saml_customer_admin_token') == NULL || \Drupal::config('miniorange_saml.settings')->get('miniorange_saml_customer_api_key') == NULL) {
    $form['header'] = array(
      '#markup' => '<center><h3>You need to register with miniOrange before using this module.</h3></center>',
    );

    return $form;
	}
	else if(\Drupal::config('miniorange_saml.settings')->get('miniorange_saml_license_key') == NULL) {
      $form['header'] = array(
      '#markup' => '<center><h3>You need to verify your license key before using this module.</h3></center>',
      );
       return $form;
  }
  
  
global $base_url;
 
$form['miniorange_saml_account_username_by'] = array(
    '#type' => 'select',
    '#title' => t('Login/Create Drupal account by'),
    '#options' => array(
      1 => t('Username'),
      2 => t('Email'),
    ),
    '#default_value' => \Drupal::config('miniorange_saml.settings')->get('miniorange_saml_login_by'),
  );

  $form['miniorange_saml_username_attribute'] = array(
    '#type' => 'textfield',
    '#title' => t('Username Attribute'),
    '#default_value' => \Drupal::config('miniorange_saml.settings')->get('miniorange_saml_username_attribute'),
    '#attributes' => array('placeholder' => 'Enter Username attribute'),
    '#required' => TRUE,
  );

  $form['miniorange_saml_email_attribute'] = array(
    '#type' => 'textfield',
    '#title' => t('Email Attribute'),
    '#default_value' => \Drupal::config('miniorange_saml.settings')->get('miniorange_saml_email_attribute'),
    '#attributes' => array('placeholder' => 'Enter Email attribute'),
    '#required' => TRUE,
  );
  
  $form['miniorange_saml_idp_attr1_name'] = array(
	'#type' => 'textfield',
	'#title' => t('Role'),
	'#default_value' => \Drupal::config('miniorange_saml.settings')->get('miniorange_saml_idp_attr1_name'),
	'#attributes' => array('placeholder' => 'Enter Role Attribute'),
	'#required' => FALSE,
  );
  
  $form['markup_cam'] = array(
    '#markup' => '<h3>Custom Attribute Mapping</h3><p>Add the Drupal field attributes in the Attribute Name textfield and add the IdP attibutes that you need to map with the drupal attributes in the IdP Attribute Name textfield. Drupal Field Attributes will be of type text. Add the machine name of the attribute in the Drupal Attribute textfield.</p><p>For example: If the attribute name in the drupal is name then its machine name will be field_name.</p>',
  );
  
   $form['miniorange_saml_attr5_name'] = array(
	'#type' => 'textfield',
	'#title' => t('Machine Name of Attribute 1'),
	'#default_value' => \Drupal::config('miniorange_saml.settings')->get('miniorange_saml_attr5_name'),
	'#attributes' => array('placeholder' => 'Enter Attribute Name'),
	'#required' => FALSE,
  );
  
  $form['miniorange_saml_idp_attr5_name'] = array(
	'#type' => 'textfield',
	'#title' => t('IdP Attribute Name 1'),
	'#default_value' => \Drupal::config('miniorange_saml.settings')->get('miniorange_saml_idp_attr5_name'),
	'#attributes' => array('placeholder' => 'Enter IdP Attribute Name'),
	'#required' => FALSE,
  );
  
  $form['miniorange_saml_attr2_name'] = array(
	'#type' => 'textfield',
	'#title' => t('Machine Name of Attribute 2'),
	'#default_value' => \Drupal::config('miniorange_saml.settings')->get('miniorange_saml_attr2_name'),
	'#attributes' => array('placeholder' => 'Enter Attribute Name'),
	'#required' => FALSE,
  );
  
  $form['miniorange_saml_idp_attr2_name'] = array(
	'#type' => 'textfield',
	'#title' => t('IdP Attribute Name 2'),
	'#default_value' => \Drupal::config('miniorange_saml.settings')->get('miniorange_saml_idp_attr2_name'),
	'#attributes' => array('placeholder' => 'Enter IdP Attribute Name'),
	'#required' => FALSE,
  );
  
  $form['miniorange_saml_attr3_name'] = array(
	'#type' => 'textfield',
	'#title' => t('Machine Name of Attribute 3'),
	'#default_value' => \Drupal::config('miniorange_saml.settings')->get('miniorange_saml_attr3_name'),
	'#attributes' => array('placeholder' => 'Enter Attribute Name'),
	'#required' => FALSE,
  );
  
  $form['miniorange_saml_idp_attr3_name'] = array(
	'#type' => 'textfield',
	'#title' => t('IdP Attribute Name 3'),
	'#default_value' => \Drupal::config('miniorange_saml.settings')->get('miniorange_saml_idp_attr3_name'),
	'#attributes' => array('placeholder' => 'Enter IdP Attribute Name'),
	'#required' => FALSE,
  );
  
  $form['miniorange_saml_attr4_name'] = array(
	'#type' => 'textfield',
	'#title' => t('Machine Name of Attribute 4'),
	'#default_value' => \Drupal::config('miniorange_saml.settings')->get('miniorange_saml_attr4_name'),
	'#attributes' => array('placeholder' => 'Enter Attribute Name'),
	'#required' => FALSE,
  );
  
  $form['miniorange_saml_idp_attr4_name'] = array(
	'#type' => 'textfield',
	'#title' => t('IdP Attribute Name 4'),
	'#default_value' => \Drupal::config('miniorange_saml.settings')->get('miniorange_saml_idp_attr4_name'),
	'#attributes' => array('placeholder' => 'Enter IdP Attribute Name'),
	'#required' => FALSE,
  );
  
   $form['markup_role'] = array(
    '#markup' => '<h3>Custom Role Mapping </h3>',
  );

  $form['miniorange_saml_enable_rolemapping'] = array(
    '#type' => 'checkbox',
    '#title' => t('Check this option if you want to <b>enable Role Mapping</b>'),
    '#default_value' => \Drupal::config('miniorange_saml.settings')->get('miniorange_saml_enable_rolemapping'),
  );

  $form['miniorange_saml_disable_role_update'] = array(
      '#type' => 'checkbox',
      '#title' => t('Check this option if you do not want to update user role if roles not mapped'),
      '#default_value' => \Drupal::config('miniorange_saml.settings')->get('miniorange_saml_disable_role_update'),
  );
  
   $form['miniorange_saml_disable_autocreate_users'] = array(
    '#type' => 'checkbox',
    '#title' => t('Check this option if you want to disable <b>auto creation</b> of users if user does not exist.'),
    '#default_value' => \Drupal::config('miniorange_saml.settings')->get('miniorange_saml_disable_autocreate_users'),
  );
  
	$mrole = user_role_names(TRUE);
	$def_role = \Drupal::configFactory()->getEditable('miniorange_saml.settings')->get('miniorange_saml_def_role');
	
    $form['miniorange_saml_default_mapping'] = array(
    '#type' => 'select',
	'#title' => t('Select default group for the new users'),
	'#options' => array_keys($mrole),
	'#default_value' => array_search (\Drupal::config('miniorange_saml.settings')->get('miniorange_saml_default_role'),$def_role),
   );
    
	foreach($mrole as $roles) {
    $rolelabel = str_replace(' ','',$roles);
    $form['miniorange_saml_role_' . $rolelabel] = array(
	'#type' => 'textfield',
	'#title' => t($roles),
	'#maxlength' => 255,
	'#default_value' => \Drupal::config('miniorange_saml.settings')->get('miniorange_saml_role_' . $rolelabel),
	'#attributes' => array('placeholder' => 'Semi-colon(;) separated Group/Role value for ' . $roles),
	'#required' => FALSE,
  );
  }
	
  $form['miniorange_saml_gateway_config_submit'] = array(
    '#type' => 'submit',
    '#value' => t('Save Configuration'),
  );
  return $form;

 }
 
  public function submitForm(array &$form, \Drupal\Core\Form\FormStateInterface $form_state) {
	  
  $enable_rolemapping = $form['miniorange_saml_enable_rolemapping']['#value'];  
  $login_by = $form['miniorange_saml_account_username_by']['#value'];
  $username_attribute = $form['miniorange_saml_username_attribute']['#value'];
  $email_attribute = $form['miniorange_saml_email_attribute']['#value'];
  $disable_role_update = $form['miniorange_saml_disable_role_update']['#value'];

  $idp_attribute1_name = $form['miniorange_saml_idp_attr1_name']['#value'];
  $sp_attribute2_name = $form['miniorange_saml_attr2_name']['#value'];
  $idp_attribute2_name = $form['miniorange_saml_idp_attr2_name']['#value'];
  
  $sp_attribute3_name = $form['miniorange_saml_attr3_name']['#value'];
  $idp_attribute3_name = $form['miniorange_saml_idp_attr3_name']['#value'];
  
  $sp_attribute4_name = $form['miniorange_saml_attr4_name']['#value'];
  $idp_attribute4_name = $form['miniorange_saml_idp_attr4_name']['#value'];
  
  $sp_attribute5_name = $form['miniorange_saml_attr5_name']['#value'];
  $idp_attribute5_name = $form['miniorange_saml_idp_attr5_name']['#value'];
  
  $disable_autocreate_users = $form['miniorange_saml_disable_autocreate_users']['#value'];
  $default_mapping= $form['miniorange_saml_default_mapping']['#value'];  
  
  if($enable_rolemapping == 1) {
		$enable_rolemapping = TRUE;
	}
	else {
		$enable_rolemapping = FALSE;
	}
	
  if ($disable_autocreate_users == 1) {
    $disable_autocreate_users = TRUE;
  }
  else {
    $disable_autocreate_users = FALSE;
  }
  if ($disable_role_update == 1) {
      $disable_role_update = TRUE;
  }
  else {
      $disable_role_update = FALSE;
  }
  
  $mrole= user_role_names(TRUE);
  
  if($enable_rolemapping) {
	
	$i=0;
	$rolemap = array();
	foreach($mrole as $key => $value) {
		$def_role[$i++] = $value; 
		$rolelabel = str_replace(' ','',$value);
		if(!empty($form['miniorange_saml_role_' . $rolelabel]['#value'])) {
			$temp = $form['miniorange_saml_role_' . $rolelabel]['#value'];
			\Drupal::configFactory()->getEditable('miniorange_saml.settings')->set('miniorange_saml_role_' . $rolelabel,$temp)->save();
			$arr= explode(";",$temp);
			foreach($arr as $val) {	
				//$rolemap[$val] = $value;
                $rolemap[$val] = strtolower($value);
			}
		}
		else{
			\Drupal::configFactory()->getEditable('miniorange_saml.settings')->set('miniorange_saml_role_' . $rolelabel,'')->save();
		}
    }
		\Drupal::configFactory()->getEditable('miniorange_saml.settings')->set('rolemap',$rolemap)->save();
		\Drupal::configFactory()->getEditable('miniorange_saml.settings')->set('miniorange_saml_default_role',$def_role[$default_mapping]);
		\Drupal::configFactory()->getEditable('miniorange_saml.settings')->set('miniorange_saml_def_role',$def_role);
		
		 // var_dump(\Drupal::configFactory()->getEditable('miniorange_saml.settings')->get('miniorange_saml_default_role')); exit;
  }
  
  else {
  
		foreach($mrole as $key => $value) {
			$rolelabel = str_replace(' ','',$value);
			\Drupal::configFactory()->getEditable('miniorange_saml.settings')->set('miniorange_saml_role_' . $rolelabel,'')->save();
		}
	\Drupal::configFactory()->getEditable('miniorange_saml.settings')->set('rolemap','')->save();  
    \Drupal::configFactory()->getEditable('miniorange_saml.settings')->set('miniorange_saml_default_role', $mrole['authenticated'])->save();
  }
 
  \Drupal::configFactory()->getEditable('miniorange_saml.settings')->set('miniorange_saml_login_by', $login_by)->save();
  \Drupal::configFactory()->getEditable('miniorange_saml.settings')->set('miniorange_saml_username_attribute', $username_attribute)->save();
  \Drupal::configFactory()->getEditable('miniorange_saml.settings')->set('miniorange_saml_email_attribute', $email_attribute)->save();
  \Drupal::configFactory()->getEditable('miniorange_saml.settings')->set('miniorange_saml_disable_autocreate_users', $disable_autocreate_users)->save();
  \Drupal::configFactory()->getEditable('miniorange_saml.settings')->set('miniorange_saml_enable_rolemapping', $enable_rolemapping)->save();
  \Drupal::configFactory()->getEditable('miniorange_saml.settings')->set('miniorange_saml_disable_role_update', $disable_role_update)->save();

  \Drupal::configFactory()->getEditable('miniorange_saml.settings')->set('miniorange_saml_idp_attr1_name', $idp_attribute1_name)->save();
  \Drupal::configFactory()->getEditable('miniorange_saml.settings')->set('miniorange_saml_attr2_name', $sp_attribute2_name)->save();
  \Drupal::configFactory()->getEditable('miniorange_saml.settings')->set('miniorange_saml_idp_attr2_name', $idp_attribute2_name)->save();
  \Drupal::configFactory()->getEditable('miniorange_saml.settings')->set('miniorange_saml_attr3_name', $sp_attribute3_name)->save();
  \Drupal::configFactory()->getEditable('miniorange_saml.settings')->set('miniorange_saml_idp_attr3_name', $idp_attribute3_name)->save();
  \Drupal::configFactory()->getEditable('miniorange_saml.settings')->set('miniorange_saml_attr4_name', $sp_attribute4_name)->save();
  \Drupal::configFactory()->getEditable('miniorange_saml.settings')->set('miniorange_saml_idp_attr4_name', $idp_attribute4_name)->save();
  \Drupal::configFactory()->getEditable('miniorange_saml.settings')->set('miniorange_saml_attr5_name', $sp_attribute5_name)->save();
  \Drupal::configFactory()->getEditable('miniorange_saml.settings')->set('miniorange_saml_idp_attr5_name', $idp_attribute5_name)->save();
  
  drupal_set_message(t('Signin Settings successfully saved'));
  }
  }