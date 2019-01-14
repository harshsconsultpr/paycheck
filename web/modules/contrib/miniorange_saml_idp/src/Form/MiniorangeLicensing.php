<?php
/**
 * @file
 * Contains Licensing information for miniOrange SAML Login Module.
 */

 /**
 * Showing Licensing form info.
 */
namespace Drupal\miniorange_saml_idp\Form;
use Drupal\miniorange_saml_idp\MiniorangeSAMLCustomer;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
 

class MiniorangeLicensing extends FormBase {
  
public function getFormId() {
    return 'miniorange_saml_licensing';
  }
 
public function buildForm(array $form, \Drupal\Core\Form\FormStateInterface $form_state) {

  $dyi_plan = "'drupal_miniorange_saml_basic_plan'";
  $premium_plan = "'drupal_miniorange_saml_premium_plan'";
  $more_instances_plan = "'drupal_miniorange_saml_upgrade_instances_plan'";
  $admin_username = "'" . \Drupal::config('miniorange_saml_idp.settings')->get('miniorange_saml_customer_admin_email') . "'";

  $UserCount = \Drupal::config('miniorange_saml_idp.settings')->get('miniOrange_saml_idp_user_count');
  
  $connection = \Drupal::database();
          $query = $connection->query("SELECT count(*) as count FROM miniorange_saml_idp_user where UserIn=1");
          $result = $query->fetchAll();

          $UserInCount= $result[0]->count ;

  if(\Drupal::config('miniorange_saml_idp.settings')->get('miniorange_saml_license_key') == NULL){
    $form['markup_1'] = array(
      '#markup' => '<div class="mo_saml_table_layout">'
      . '<table class="mo_saml_local_pricing_table">'
      . '<h2>Licensing Plans</h2><hr>'
      . '<tr style="vertical-align:top;">',
    );

  $form['markup_15'] = array(
    '#markup' => '<h2>Licensing Plans</h2><p>Total SSO Users Allowed:' . $UserCount . '</p><p>Current SSO Users:' .$UserInCount . '</p>',
  );

  $form['CheckLicense'] = array(
    '#type' => 'submit',
    '#value' => t('Check License'),
    // '#submit' => array('miniorange_saml_check_license'),
   );


    $form['markup_2'] = array(
      '#markup' => '<td><div class="mo_saml_local_thumbnail mo_saml_local_pricing_paid_tab" align="center" >'
      . '<h3 class="mo_saml_local_pricing_header">Do it yourself</h3><p></p>'
      . '<h4 class="mo_saml_local_pricing_sub_header" style="padding-bottom:8px !important;">'
      .'<a class="btn btn-primary btn-large" style="padding:5px;" href="https://auth.miniorange.com/moas/initializepayment">Click to upgrade</a>*<br></h4><hr>',
    );

    $form['Do_it_yourself_pricing'] = array(
  '#type' => 'select',
  '#title' => t('<p class="mo_saml_local_pricing_text">Users :</p>'),
  '#options' => array(
    '200' => t('200 - $99'),
    '400' => t('400 - $199'),
    '600' => t('600 - $249'),
    '800' => t('800 - $299'),
    '5000' => t('5000 - $499'),
    '10000' => t('10000 - $799'),
    '10000+' => t('10000+ Users - Contact Us'),
 ),
  
  );

 $form['markup_10'] = array(
      '#markup' => '<hr><p class="mo_saml_local_pricing_text">'
      . 'Authentication with Multiple Service Providers<br>SP Initiated Login<br>IDP Initiated Login<br>'
      . 'Customized Role Mapping<br>Customized Attribute Mapping<br>Signed Assertion<br>Signed Response<br>'
      . 'Encrypted Assertion<br>HTTP-POST Binding<br>Metadata XML File<br><br><br/></p><hr>'
      . '<p class="mo_saml_local_pricing_text" >Basic Support by Email</p></div></td>',
    );
    
  } else{

    $form['markup_1'] = array(
      '#markup' => '<div class="mo_saml_table_layout">'
      . '<table class="mo_saml_local_pricing_table">'
      . '<h2>Licensing Plans</h2><hr>'
      . '<tr style="vertical-align:top;">',
    );

 $form['markup_15'] = array(
    '#markup' => '<h2>Licensing Plans</h2><p>Total SSO Users Allowed:' . $UserCount . '</p><p>Current SSO Users:' .$UserInCount . '</p>',
  );

$form['CheckLicense'] = array(
    '#type' => 'submit',
    '#value' => t('Check License'),
    // '#submit' => array('miniorange_saml_check_license'),
   );

    $form['markup_2'] = array(
      '#markup' => '<td><div class="mo_saml_local_thumbnail mo_saml_local_pricing_paid_tab" align="center" >'
      . '<h3 class="mo_saml_local_pricing_header">Do it yourself</h3><p></p>'
      . '<h4 class="mo_saml_local_pricing_sub_header" style="padding-bottom:8px !important;">'
      .'<a class="btn btn-primary btn-large" style="padding:5px;" href="https://auth.miniorange.com/moas/initializepayment">Click to upgrade</a>*</h4><br><hr>',
    );
 

    $form['Do_it_yourself_pricing'] = array(
  '#type' => 'select',
  '#title' => t('<p class="mo_saml_local_pricing_text">Users :</p>'),
  '#options' => array(
    '200' => t('200 - $99'),
    '400' => t('400 - $199'),
    '600' => t('600 - $249'),
    '800' => t('800 - $299'),
    '5000' => t('5000 - $499'),
    '10000' => t('10000 - $799'),
    '10000+' => t('10000+ Users - Contact Us'),
 ),
  
  );

 $form['markup_10'] = array(
      '#markup' => '<hr><p class="mo_saml_local_pricing_text">'
      . 'Authentication with Multiple Service Providers<br>SP Initiated Login<br>IDP Initiated Login<br>'
      . 'Customized Role Mapping<br>Customized Attribute Mapping<br>Signed Assertion<br>Signed Response<br>'
      . 'Encrypted Assertion<br>HTTP-POST Binding<br>Metadata XML File<br><br><br/></p><hr>'
      . '<p class="mo_saml_local_pricing_text" >Basic Support by Email</p></div></td>',
    );
  }


  $form['markup_3'] = array(
    '#markup' => '<td><div class="mo_saml_local_thumbnail mo_saml_local_pricing_free_tab" align="center">'
    . '<h2 class="mo_saml_local_pricing_header">Premium</h2><p></p>'
    . '<h4 class="mo_saml_local_pricing_sub_header" style="padding-bottom:8px !important;">'
    . '<a class="btn btn-primary btn-large" style="padding:5px;" href="https://auth.miniorange.com/moas/initializepayment">Click to upgrade</a>*</h4><br><hr>',

  );


  $form['Premium_pricing'] = array(
  '#type' => 'select',
  '#title' => t('<p class="mo_saml_local_pricing_text">Users :</p>'),
  '#options' => array(
    '200' => t('200 - $99'),
    '400' => t('400 - $199'),
    '600' => t('600 - $249'),
    '800' => t('800 - $299'),
    '5000' => t('5000 - $499'),
    '10000' => t('10000 - $799'),
    '10000+' => t('10000+ Users - Contact Us'),
 ),
  
  );


    $form['markup_11'] = array(
      '#markup' => '<hr><p class="mo_saml_local_pricing_text">Authentication with Multiple Service Providers<br>'
    . 'Customized Attribute Mapping<br>Single Logout<br>Signed Assertion<br>Signed Response
<br>Encrypted Assertion<br>'
    . 'HTTP-POST Binding<br>Metadata XML File<br><br><br>'
    . 'End to End Identity Provider Configuration **<br><br></p><hr><p class="mo_saml_local_pricing_text">Premium Support Plans Available</p>'
    . '</div></td></tr></table>'
  );


  $form['markup_5'] = array(
    '#markup' => '<h3>Steps to Upgrade to Premium Plugin</h3>'
    . '<ol><li>You will be redirected to miniOrange Login Console. Enter your password with which you created an'
    . ' account with us. After that you will be redirected to payment page.</li>'
    . '<li>Enter you card details and complete the payment. On successful payment completion, you will see the '
    . 'link to download the premium module.</li>'
    . 'Once you download the premium module, just unzip it and replace the folder with existing module. Clear Drupal Cache.
 </li></ol>'
  );

  $form['markup_6'] = array(
    '#markup' => '<h3>** End to End Identity Provider Integration</h3>'
    . 'We will setup a Conference Call / Gotomeeting and do end to end configuration for you to setup drupal as IDP. '
    . 'We provide services to do the configuration on your behalf.'
  );

  $form['markup_7'] = array(
    '#markup' => '<div><br/>If you have any doubts regarding the licensing plans, you can mail us at info@miniorange.com or submit a query using the support form on right.'
    
  );

  return $form;
 }
 
  public function submitForm(array &$form, \Drupal\Core\Form\FormStateInterface $form_state) {

    $user_count = \Drupal::config('miniorange_saml_idp.settings')->get('miniOrange_saml_idp_user_count',0);
   $customer = new MiniorangeSAMLCustomer(NULL, NULL, NULL, NULL);
      
       $content = json_decode($customer->ccl(), true);
        $noOfUsers=$content['noOfUsers'];
          
      if($noOfUsers != $user_count)
      {

         \Drupal::configFactory()->getEditable('miniorange_saml_idp.settings')->set('miniOrange_saml_idp_user_count', $noOfUsers)->save();
      
      }
   
  }


  
 }

