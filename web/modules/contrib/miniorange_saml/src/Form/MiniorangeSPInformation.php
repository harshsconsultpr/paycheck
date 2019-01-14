<?php

/**
 * @file
 * Contains \Drupal\miniorange_saml\Form\MiniorangeSPInformation.
 */

namespace Drupal\miniorange_saml\Form;

use DOMDocument;
use DOMNode;
use Drupal\miniorange_saml\Controller\DefaultController;
use Drupal\miniorange_saml\MiniorangeSAMLConstants;
use Drupal\miniorange_saml\Utilities;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Render\Element;
use Drupal\miniorange_saml\MetadataReader;

class MiniorangeSPInformation extends FormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'miniorange_sp_setup';
  }

  public function buildForm(array $form, \Drupal\Core\Form\FormStateInterface $form_state) {
	  
	  
  if (\Drupal::config('miniorange_saml.settings')->get('miniorange_saml_customer_admin_email') == NULL || \Drupal::config('miniorange_saml.settings')->get('miniorange_saml_customer_id') == NULL
    || \Drupal::config('miniorange_saml.settings')->get('miniorange_saml_customer_admin_token') == NULL || \Drupal::config('miniorange_saml.settings')->get('miniorange_saml_customer_api_key') == NULL) {
    $form['header'] = array(
      '#markup' => '<center><h3>You need to register with miniOrange before using this module.</h3></center>',
    );

    return $form;
  }else if(\Drupal::config('miniorange_saml.settings')->get('miniorange_saml_license_key') == NULL) {
      $form['header'] = array(
      '#markup' => '<center><h3>You need to verify your license key before using this module.</h3></center>',
      );
       return $form;
  }
  
  $form['miniorange_saml_IDP_tab'] = array(
  '#attached' => array(
	'library' => 'miniorange_saml/miniorange_saml.test',
	),
  '#markup' => '<div id="tabhead"><h5> Enter the information gathered from your Identity Provider &nbsp; OR &nbsp;&nbsp;<a id="showMetaButton" class="btn btn-primary btn-sm" onclick="testConfig()">'
    . 'Upload Metadata URL </a><br><br> </h5></div>',
  );
  
  $form['metadata_1'] = array(
  '#attached' => array(
	'library' => 'miniorange_saml/miniorange_saml.test',
	'library' => 'miniorange_saml/miniorange_saml.admin',
	),
	'#markup' =>'<div border="1" id="upload_metadata_form" class="mo_saml_meta_upload">'
  .'				<h1>Upload IDP Metadata'
  .'		<span class="mo_saml_cancel_upload"><a id="hideMetaButton" class="btn btn-sm btn-danger" onclick = "testConfig()">Cancel</a></span>',
  );
  
  $form['metadata_2'] = array(
   '#markup' =>' <br>'
  .'				</h1>'
  .'				<h4>Upload Metadata  :</h4>',
  );
  
  $form['metadata_file'] = array(
    '#type' => 'file',
  );
  
  $form['metadata_upload'] = array(
    '#type' => 'submit',
    '#value' => t('Upload'),
    '#submit' => array('::miniorange_saml_upload_file'),
  );
  
  $form['metadata_3'] = array(
    '#markup' =>'<p>&emsp;&emsp;&emsp;&emsp;<b>OR</b></p>'
	.'			<h4>Enter metadata URL:</h4>',
  );
  
  $form['metadata_URL'] = array(
    '#type' => 'textfield',
	'#attributes' => array('placeholder' => 'Enter metadata URL of your IdP.'),
  );
	
   $form['metadata_fetch'] = array(
	'#type' => 'submit',
    '#value' => t('Fetch Metadata'),
    '#submit' => array('::miniorange_saml_fetch_metadata'),
   
   );
   
   $form['metadata_5'] = array(
   '#markup' =>'<br><br><hr></div>'
	.'<div id="idpdata">',
   );
   
  $form['miniorange_saml_idp_name'] = array(
    '#type' => 'textfield',
    '#title' => t('Identity Provider Name'),
    '#default_value' => \Drupal::config('miniorange_saml.settings')->get('miniorange_saml_idp_name'),
    '#attributes' => array(
		'placeholder' => 'Identity Provider Name'
		),
  );

  $form['miniorange_saml_idp_issuer'] = array(
    '#type' => 'textfield',
    '#title' => t('IdP Entity ID or Issuer'),
    '#default_value' => \Drupal::config('miniorange_saml.settings')->get('miniorange_saml_idp_issuer'),
    '#attributes' => array(
		'placeholder' => 'IdP Entity ID or Issuer'
		),
	'#description' => t('<b>Note :</b> You can find the EntityID in Your IdP-Metadata XML file enclosed in <code>EntityDescriptor</code> tag having attribute as <code>entityID</code>'),
  );
  
   $form['miniorange_saml_sign_request'] = array(
    '#type' => 'checkbox',
    '#title' => t('Sign SAML Request'),
	'#default_value' => \Drupal::config('miniorange_saml.settings')->get('miniorange_saml_request_signed'),
  );
  
  $form['miniorange_saml_nameid_format'] = array(
	'#type' => 'select',
    '#title' => t('NameID Format'),
    '#options' => array(
		'urn:oasis:names:tc:SAML:1.1:nameid-format:emailAddress' => t('urn:oasis:names:tc:SAML:1.1:nameid-format:emailAddress'),
        'urn:oasis:names:tc:SAML:1.1:nameid-format:unspecified' => t('urn:oasis:names:tc:SAML:1.1:nameid-format:unspecified'),
        'urn:oasis:names:tc:SAML:1.1:nameid-format:transient' => t('urn:oasis:names:tc:SAML:1.1:nameid-format:transient'),
    ),
    '#default_value' => \Drupal::config('miniorange_saml.settings')->get('miniorange_saml_nameid_format'),
  );
  
  $form['miniorange_saml_idp_binding'] = array(
	'#type' => 'radios',
	'#title' => t('HTTP Binding'),
	'#default_value' => (\Drupal::config('miniorange_saml.settings')->get('miniorange_saml_http_binding')== 'HTTP-POST')? 1 : 0,
	'#options' => array(
	t('HTTP-Redirect'),
	t('HTTP-POST')),
  );

  $form['miniorange_saml_idp_login_url'] = array(
    '#type' => 'textfield',
    '#title' => t('SAML Login URL'),
    '#default_value' => \Drupal::config('miniorange_saml.settings')->get('miniorange_saml_idp_login_url'),
    '#description' => t('<b>Note :</b> You can find the SAML Login URL in Your IdP-Metadata XML file enclosed in <code>SingleSignOnService</code> tag'),
	'#attributes' => array(
		'placeholder' => 'SAML Login URL'
		),
  );
  
  $form['miniorange_saml_idp_logout_url'] = array(
    '#type' => 'textfield',
    '#title' => t('SAML Logout URL'),
    '#default_value' => \Drupal::config('miniorange_saml.settings')->get('miniorange_saml_idp_logout_url'),
    '#description' => t('<b>Note :</b> You can find the SAML Login URL in Your IdP-Metadata XML file enclosed in <code>SingleLogoutService</code> tag'),
	'#attributes' => array(
		'placeholder' => 'SAML Logout URL'
		),
  );

  $form['miniorange_saml_idp_x509_certificate'] = array(
    '#type' => 'textarea',
    '#title' => t('x.509 Certificate Value'),
    '#cols' => '10',
    '#rows' => '5',
    '#default_value' => \Drupal::config('miniorange_saml.settings')->get('miniorange_saml_idp_x509_certificate'),
    '#attributes' => array('placeholder' => 'Enter x509 Certificate Value'),
  );

  $form['markup_1'] = array(
    '#markup' => '<b>NOTE:</b> Format of the certificate:<br><b>-----BEGIN CERTIFICATE-----<br>'
  );

  $form['markup_2'] = array(
    '#markup' => 'XXXXXXXXXXXXXXXXXXXXXXXXXXX<br>-----END CERTIFICATE-----</b><br><br>'
  );

  $form['miniorange_saml_response_signed'] = array(
    '#type' => 'checkbox',
    '#title' => t('Check if your IdP is signing SAML response. Leave checked by default'),
    '#default_value' => \Drupal::config('miniorange_saml.settings')->get('miniorange_saml_response_signed'),
  );

  $form['miniorange_saml_assertion_signed'] = array(
    '#type' => 'checkbox',
    '#title' => t('Check if your IdP is signing SAML assertion. Leave unchecked by default'),
    '#default_value' => \Drupal::config('miniorange_saml.settings')->get('miniorange_saml_assertion_signed'),
  );  

  $form['miniorange_saml_enable_login'] = array(
    '#type' => 'checkbox',
    '#title' => t('Enable login with SAML'),
    '#default_value' => \Drupal::config('miniorange_saml.settings')->get('miniorange_saml_enable_login'),
  ); 
  
  $testConfigUrl = "\'" . $this->getTestUrl() . "\'";
  
  $form['miniorange_saml_idp_config_submit'] = array(
    '#type' => 'submit',
    '#value' => t('Save Configuration'),
  );

  $form['miniorange_saml_test_config_button'] = array(
	'#attached' => array(
	'library' => 'miniorange_saml/miniorange_saml.test',
	),
    '#markup' => '&nbsp;&nbsp;&nbsp;&nbsp;<a id="testConfigButton" class="btn btn-primary btn-sm" onclick="testConfig($testConfigUrl);">Test Configuration</a>'
  );

  return $form;

 }
 public function miniorange_saml_form_alter(array &$form, \Drupal\Core\Form\FormStateInterface $form_state, $form_id) {
	  $form['actions']['submit']['#submit'][] = 'test';
	  // var_dump($form);exit();
	  return $form;
  }
   
 public function getTestUrl() {
  global $base_url;
  $host_name = MiniorangeSAMLConstants::BASE_URL;
  $customer_key = \Drupal::config('miniorange_saml.settings')->get('miniorange_saml_customer_id');
  $customer_token = \Drupal::config('miniorange_saml.settings')->get('miniorange_saml_customer_admin_token');
  $url = $host_name . '/moas/idptest/?id=' . $customer_key . '&key=' . $customer_token;

  $testUrl = $base_url . '/?q=testConfig';

  return $testUrl;
 }

 /**
 * Configure IdP.
 */
 public function submitForm(array &$form, \Drupal\Core\Form\FormStateInterface $form_state) {
  global $base_url;
  $issuer = $form['miniorange_saml_idp_issuer']['#value'];
  $idp_name = $form['miniorange_saml_idp_name']['#value'];
  $nameid_format = $form['miniorange_saml_nameid_format']['#value'];
  $login_url = $form['miniorange_saml_idp_login_url']['#value'];
  $logout_url = $form['miniorange_saml_idp_logout_url']['#value'];
  $x509_cert_value = Utilities::sanitize_certificate($form['miniorange_saml_idp_x509_certificate']['#value']);
  $response_signed_value = $form['miniorange_saml_response_signed']['#value'];
  $assertion_signed_value = $form['miniorange_saml_assertion_signed']['#value'];
  $enable_login = $form['miniorange_saml_enable_login']['#value'];
  $request_signed_value = $form['miniorange_saml_sign_request']['#value'];
  $http_binding_value = $form['miniorange_saml_idp_binding']['#value'];
  
  if($http_binding_value == 1) {
	  $http_binding = 'HTTP-POST';
  }
  else {
	  $http_binding = 'HTTP-Redirect';
  }
  
  if ($request_signed_value == 1) {
    $request_signed = TRUE;
  }
  else {
    $request_signed = FALSE;
  }

  if ($response_signed_value == 1) {
    $response_signed = TRUE;
  }
  else {
    $response_signed = FALSE;
  }

  if ($assertion_signed_value == 1) {
    $assertion_signed = TRUE;
  }
  else {
    $assertion_signed = FALSE;
  }

  if ($enable_login == 1) {
    $enable_login = TRUE;
  }
  else {
    $enable_login = FALSE;
  }

  $sp_issuer = $base_url;
  
  \Drupal::configFactory()->getEditable('miniorange_saml.settings')->set('miniorange_saml_idp_name', $idp_name)->save();
  \Drupal::configFactory()->getEditable('miniorange_saml.settings')->set('miniorange_saml_sp_issuer', $sp_issuer)->save();
  \Drupal::configFactory()->getEditable('miniorange_saml.settings')->set('miniorange_saml_idp_issuer', $issuer)->save();
  \Drupal::configFactory()->getEditable('miniorange_saml.settings')->set('miniorange_saml_nameid_format', $nameid_format)->save();
  \Drupal::configFactory()->getEditable('miniorange_saml.settings')->set('miniorange_saml_request_signed', $request_signed)->save();
  \Drupal::configFactory()->getEditable('miniorange_saml.settings')->set('miniorange_saml_http_binding', $http_binding)->save();
  \Drupal::configFactory()->getEditable('miniorange_saml.settings')->set('miniorange_saml_idp_login_url', $login_url)->save();
  \Drupal::configFactory()->getEditable('miniorange_saml.settings')->set('miniorange_saml_idp_logout_url', $logout_url)->save();
  \Drupal::configFactory()->getEditable('miniorange_saml.settings')->set('miniorange_saml_idp_x509_certificate', $x509_cert_value)->save();
  \Drupal::configFactory()->getEditable('miniorange_saml.settings')->set('miniorange_saml_response_signed', $response_signed)->save();
  \Drupal::configFactory()->getEditable('miniorange_saml.settings')->set('miniorange_saml_assertion_signed', $assertion_signed)->save();
  \Drupal::configFactory()->getEditable('miniorange_saml.settings')->set('miniorange_saml_enable_login', $enable_login)->save();

  drupal_set_message(t('Identity Provider Configuration successfully saved'));

 } 
 
 function miniorange_saml_upload_file(array &$form, \Drupal\Core\Form\FormStateInterface $form_state) {
	
	$file_name = $_FILES['files']['tmp_name']['metadata_file'];
	
	$file = file_get_contents($file_name);

	$this->upload_metadata($file);
}

function miniorange_saml_fetch_metadata(array &$form, \Drupal\Core\Form\FormStateInterface $form_state) {
	$url=filter_var($form['metadata_URL']['#value'],FILTER_SANITIZE_URL);
	$file = file_get_contents($url);
	$this->upload_metadata($file);	
}

public function upload_metadata($file){
	global $base_url;
		$document = new \DOMDocument();
		$document->loadXML($file);
		restore_error_handler();
		$first_child = $document->firstChild;

		if(!empty($first_child)) {
			$metadata = new MetadataReader($document);
			$identity_providers = $metadata->getIdentityProviders();
			if(empty($identity_providers)) {
				drupal_set_message(t('Please provide a valid metadata file.'),error);
			return;
			}
			
			foreach($identity_providers as $key => $idp){

				$saml_login_url = $idp->getLoginURL('HTTP-Redirect');
				$logout_url = $idp->getLogoutURL('HTTP-Redirect');
				
				if(empty($saml_login_url)) {
					$saml_login_url = $idp->getLoginURL('HTTP-POST');
				}
				
				if(empty($logout_url)) {
					$logout_url = $idp->getLogoutURL('HTTP-POST');
				}
				
				$saml_issuer = $idp->getEntityID();
				$saml_x509_certificate = $idp->getSigningCertificate();
								
				 $sp_issuer = $base_url;
				  
				  \Drupal::configFactory()->getEditable('miniorange_saml.settings')->set('miniorange_saml_sp_issuer', $sp_issuer)->save();
				  \Drupal::configFactory()->getEditable('miniorange_saml.settings')->set('miniorange_saml_idp_issuer', $saml_issuer)->save();
				  \Drupal::configFactory()->getEditable('miniorange_saml.settings')->set('miniorange_saml_idp_login_url', $saml_login_url)->save();
				  \Drupal::configFactory()->getEditable('miniorange_saml.settings')->set('miniorange_saml_idp_logout_url', $logout_url)->save();
				  \Drupal::configFactory()->getEditable('miniorange_saml.settings')->set('miniorange_saml_idp_x509_certificate', $saml_x509_certificate[0])->save();
				  
			}
			
				  drupal_set_message(t('Identity Provider Configuration successfully saved.'));
				return;
		} else {
					drupal_set_message(t('Please provide a valid metadata file.'),error);
					return;
		}	  
				
				
}

}