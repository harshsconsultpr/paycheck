<?php
/**
 * @file
 * Contains Attribute and Role Mapping for miniOrange SAML Login Module.
 */

 /**
 * Showing Settings form.
 */
namespace Drupal\miniorange_saml_idp\Form;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

 class Mapping extends FormBase {
	 
  public function getFormId() {
    return 'miniorange_saml_mapping';
  }

	 
 public function buildForm(array $form, \Drupal\Core\Form\FormStateInterface $form_state) {

  if (\Drupal::config('miniorange_saml_idp.settings')->get('miniorange_saml_customer_admin_email') == NULL || \Drupal::config('miniorange_saml_idp.settings')->get('miniorange_saml_customer_id') == NULL
    || \Drupal::config('miniorange_saml_idp.settings')->get('miniorange_saml_customer_admin_token') == NULL || \Drupal::config('miniorange_saml_idp.settings')->get('miniorange_saml_customer_api_key') == NULL)  {
    $form['header'] = array(
      '#markup' => '<center><h3>You need to register with miniOrange before using this module.</h3></center>',
    );

    return $form;
  }
  else if(\Drupal::config('miniorange_saml_idp.settings')->get('miniorange_saml_license_key') == NULL) {
   

      $form['header'] = array(
      '#markup' => '<center><h3>You need to verify your license key before using this module.</h3></center>',
      );
       return $form;
  }
  
  
  global $base_url;
 
  $form['markup_idp_attr_header'] = array(
    '#markup' => '<h3>Attribute Mapping(Optional)</h3>',
  );
  
  $form['miniorange_saml_idp_nameid_attr_map'] = array(
    '#type' => 'select',
	'#title' => t('NameID Attribute:'),
	'#options' => array(
	  '' => t('Select a NameID attribute value to be sent in the SAML Response'),
	  'emailAddress' => t('Drupal Email Address'),
	  'username' => t('Drupal Username'),
	),
	'#default_value' =>\Drupal::config('miniorange_saml_idp.settings')->get('miniorange_saml_idp_nameid_attr_map'),
	'#description' => t('(<b>NOTE:</b> This attribute value is sent in SAML Response. Users in your Service Provider<br />'
	. ' will be searched (existing users) or created (new users) based on this attribute.<br />'
	. ' Use <b>EmailAddress</b> by default.)'),
  );

  $form['markup_idp_attr_header2'] = array(
    '#markup' => '<h3>2. Attribute Statement(Optional)</h3>',
  );

  $form['miniorange_saml_idp_attr1_name'] = array(
  '#type' => 'textfield',
  '#title' => t('Attribute Name 1'),
  '#default_value' => \Drupal::config('miniorange_saml_idp.settings')->get('miniorange_saml_idp_attr1_name'),
  '#attributes' => array('placeholder' => 'Enter Attribute Name'),
  '#required' => FALSE,
  );
  
  
  $form['miniorange_saml_idp_attr1_value'] = array(
  '#type' => 'select',
  '#title' => t('Attribute Value'),
  '#options' => array(
    '' => t('Select Attribute Value'),
    'mail' => t('Email Address'),
    'name' => t('Username'),
    'roles' => t('User Roles'),
  ),
  '#default_value' =>\Drupal::config('miniorange_saml_idp.settings')->get('miniorange_saml_idp_attr1_value'), 
  );

  $form['miniorange_saml_idp_attr2_name'] = array(
  '#type' => 'textfield',
  '#title' => t('Attribute Name 2'),
  '#default_value' =>\Drupal::config('miniorange_saml_idp.settings')->get('miniorange_saml_idp_attr2_name'),
  '#attributes' => array('placeholder' => 'Enter Attribute Name'),
  '#required' => FALSE,
  );
  
  $form['miniorange_saml_idp_attr2_value'] = array(
  '#type' => 'select',
  '#title' => t('Attribute Value'),
  '#options' => array(
    '' => t('Select Attribute Value'),
    'mail' => t('Email Address'),
    'name' => t('Username'),
    
    'roles' => t('User Roles'),
  ),
  '#default_value' =>\Drupal::config('miniorange_saml_idp.settings')->get('miniorange_saml_idp_attr2_value'), 
  );
  
  $form['miniorange_saml_idp_attr3_name'] = array(
  '#type' => 'textfield',
  '#title' => t('Attribute Name 3'),
  '#default_value' => \Drupal::config('miniorange_saml_idp.settings')->get('miniorange_saml_idp_attr3_name'),
  '#attributes' => array('placeholder' => 'Enter Attribute Name'),
  '#required' => FALSE,
  );
  
  $form['miniorange_saml_idp_attr3_value'] = array(
  '#type' => 'select',
  '#title' => t('Attribute Value'),
  '#options' => array(
    '' => t('Select Attribute Value'),
    'mail' => t('Email Address'),
    'name' => t('Username'),
   
    'roles' => t('User Roles'),
  ),
  '#default_value' =>  \Drupal::config('miniorange_saml_idp.settings')->get('miniorange_saml_idp_attr3_value'),
  );
 
  $counter = 0;

  $userProfileAttribues = \Drupal::config('miniorange_saml_idp.settings')->get('miniorange_saml_idp_user_attributes');
  if(isset($userProfileAttribues) && !empty($userProfileAttribues)){
  $userProfileAttribues = json_decode($userProfileAttribues, true);
  foreach($userProfileAttribues as $profileAttribute){
    $form['markup_idp_user_attr_list_' . $counter] = array(
        '#markup' => '<div class="row userAttr" style="padding-bottom:1%;" id="uparow_' . $counter . '" ><div style="width:20%;display:inline-block;"><input type="text" name="user_profile_attr_name[' . $counter . ']" value="' . $profileAttribute["attr_name"] .'" class="form-text" /></div><div style="width:30%;display:inline-block;"><input type="text" name="user_profile_attr_value[' . $counter . ']" value="' . $profileAttribute["attr_value"] .'" class="form-text" /></div></div>'
      );
    $counter+=1;
  }
  }
   
  $form['markup_idp_user_attr_header2'] = array(
    '#markup' => '</div><br />',
  );
  
  $form['miniorange_saml_idp_attr_map_submit'] = array(
    '#type' => 'submit',
    '#value' => t('Save'),
   
  );
  
  return $form;

 }
 
  public function submitForm(array &$form, \Drupal\Core\Form\FormStateInterface $form_state) {
  


global $base_url;
  
 $mo_admin_email =\Drupal::config('miniorange_saml_idp.settings')->get('miniorange_saml_customer_admin_email');
  $attr1_name = $form['miniorange_saml_idp_attr1_name']['#value'];
  $attr1_value = $form['miniorange_saml_idp_attr1_value']['#value'];
  $attr2_name = $form['miniorange_saml_idp_attr2_name']['#value'];
  $attr2_value = $form['miniorange_saml_idp_attr2_value']['#value'];
  $attr3_name = $form['miniorange_saml_idp_attr3_name']['#value'];
  $attr3_value = $form['miniorange_saml_idp_attr3_value']['#value'];
  
  if(!isset($mo_admin_email)){
    drupal_set_message(t('Please register with miniOrange to enable Drupal as IDP.'));
	
  $test=  $base_url . '/admin/config/people/miniorange_saml_idp/Mapping';
  $response = new RedirectResponse($test);
    $response->send();
  }
  
  $nameid_attr = $form['miniorange_saml_idp_nameid_attr_map']['#value'];
  if($nameid_attr == ''){
    $nameid_attr = 'emailAddress';
  }
/* Updated User Profile Attributes*/
  $user_profile_attr_names = array_key_exists('user_profile_attr_name', $_POST) ? $_POST['user_profile_attr_name'] : array();
  $user_profile_attr_values   = array_key_exists('user_profile_attr_value', $_POST) ? $_POST['user_profile_attr_value'] : array();
  $attribute_mapping = array();
  foreach($user_profile_attr_names as $key => $value){
  if(!empty(trim($value))){
    if(!empty(trim($user_profile_attr_values[$key]))){
      $anArray = array();
      $anArray['attr_name'] = trim($value);
      $anArray['attr_value'] = trim($user_profile_attr_values[$key]);
      array_push($attribute_mapping, $anArray);
    }
  }
  }
  $attribute_mapping = json_encode($attribute_mapping);
  \Drupal::configFactory()->getEditable('miniorange_saml_idp.settings')->set('miniorange_saml_idp_nameid_attr_map', $nameid_attr)->save();

\Drupal::configFactory()->getEditable('miniorange_saml_idp.settings')->set('miniorange_saml_idp_attr1_name', $attr1_name)->save();
\Drupal::configFactory()->getEditable('miniorange_saml_idp.settings')->set('miniorange_saml_idp_attr1_value', $attr1_value)->save();
\Drupal::configFactory()->getEditable('miniorange_saml_idp.settings')->set('miniorange_saml_idp_attr2_name', $attr2_name)->save();
\Drupal::configFactory()->getEditable('miniorange_saml_idp.settings')->set('miniorange_saml_idp_attr2_value', $attr2_value)->save();
 \Drupal::configFactory()->getEditable('miniorange_saml_idp.settings')->set('miniorange_saml_idp_attr3_name', $attr3_name)->save();
 \Drupal::configFactory()->getEditable('miniorange_saml_idp.settings')->set('miniorange_saml_idp_attr3_value', $attr3_value)->save();
 
 \Drupal::configFactory()->getEditable('miniorange_saml_idp.settings')->set('miniorange_saml_idp_attr4_name', $attr4_name)->save();
   \Drupal::configFactory()->getEditable('miniorange_saml_idp.settings')->set('miniorange_saml_idp_user_attributes', $attribute_mapping)->save();
  
 

  drupal_set_message(t('Your settings are saved successfully.'));


  }
  }
