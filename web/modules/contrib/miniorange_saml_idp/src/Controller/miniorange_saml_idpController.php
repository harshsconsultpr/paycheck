<?php /**
 * @file
 * Contains \Drupal\miniorange_saml_idp\Controller\DefaultController.
 */
 
namespace Drupal\miniorange_saml_idp\Controller;
 
use Drupal\miniorange_saml_idp\GenerateResponse;
use Drupal\user\Entity\User;
use Drupal\Core\Session;
use Drupal\Core\Controller\ControllerBase;
use Drupal\user\RoleInterface;
use Drupal\user\UserInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Drupal\miniorange_saml_idp\LogoutRequest;
use Drupal\miniorange_saml_idp\Utilities;
use Drupal\miniorange_saml_idp\MiniOrangeAuthnRequest;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Cookie;
use Drupal\miniorange_saml_idp\XMLSecurityKey;
use Drupal\miniorange_saml_idp\XMLSecurityDSig;
use Drupal\Core\DependencyInjection;
use DOMElement;
use DOMDocument;
 
class miniorange_saml_idpController extends ControllerBase {
 
function miniorange_saml_idp_metadata(){
 
    $this->_generate_metadata();
  
}
 
function _generate_metadata(){
  
  global $base_url; 
   
  $site_url = $base_url . '/';
   
  $entity_id = $site_url . '?q=admin/config/people/miniorange_saml_idp/';
  $login_url = $site_url . 'initiatelogon';
  $logout_url = $site_url . 'user/logout';
   
  define('DRUPAL_BASE_ROOT', dirname(__FILE__));
  $module_path = drupal_get_path('module', 'miniorange_saml_idp');
  $certificate = file_get_contents( \Drupal::root() . DIRECTORY_SEPARATOR . 'modules' . DIRECTORY_SEPARATOR . 'miniorange_saml_idp' . DIRECTORY_SEPARATOR .  'resources' . DIRECTORY_SEPARATOR . 'idp-signing.crt' );
   
  $certificate = preg_replace("/[\r\n]+/", "", $certificate);
  $certificate = str_replace( "-----BEGIN CERTIFICATE-----", "", $certificate );
  $certificate = str_replace( "-----END CERTIFICATE-----", "", $certificate );
  $certificate = str_replace( " ", "", $certificate );
   
header('Content-Type: text/xml');
echo'<?xml version="1.0" encoding="UTF-8"?><md:EntityDescriptor xmlns:md="urn:oasis:names:tc:SAML:2.0:metadata" entityID="'.$entity_id.'"><md:IDPSSODescriptor WantAuthnRequestsSigned="true" protocolSupportEnumeration="urn:oasis:names:tc:SAML:2.0:protocol"><md:KeyDescriptor 
      use="signing">
      <ds:KeyInfo 
        xmlns:ds="http://www.w3.org/2000/09/xmldsig#">
        <ds:X509Data>
          <ds:X509Certificate>'.$certificate.'</ds:X509Certificate>
        </ds:X509Data>
      </ds:KeyInfo>
    </md:KeyDescriptor>
 
    <md:NameIDFormat>urn:oasis:names:tc:SAML:1.1:nameid-format:emailAddress</md:NameIDFormat>
    <md:NameIDFormat>urn:oasis:names:tc:SAML:1.1:nameid-format:unspecified</md:NameIDFormat>
    <md:SingleSignOnService 
      Binding="urn:oasis:names:tc:SAML:2.0:bindings:HTTP-POST"
      Location="'.$login_url.'"/>
    <md:SingleSignOnService 
      Binding="urn:oasis:names:tc:SAML:2.0:bindings:HTTP-Redirect"
      Location="'.$login_url.'"/>
    <md:SingleLogoutService 
      Binding="urn:oasis:names:tc:SAML:2.0:bindings:HTTP-Redirect"
      Location="' . $logout_url . '"/>
    <md:SingleLogoutService 
      Binding="urn:oasis:names:tc:SAML:2.0:bindings:HTTP-POST"
      Location="' . $logout_url . '"/>
  </md:IDPSSODescriptor>
</md:EntityDescriptor>';
exit;
}
 
function saml_logout() {
    
   global $base_url;
   unset($_COOKIE['response_params']);
   setcookie('response_params', '', time() - 3600, '/');
   $issuer = $base_url . '/?q=admin/config/people/miniorange_saml_idp/';
   $logout_url = \Drupal::config('miniorange_saml_idp.settings')->get('miniorange_saml_idp_logout_url');
   if($logout_url=='') {
     session_destroy();
  $response = new RedirectResponse($base_url);
    $response->send();
  return;
   }
 
   if(array_key_exists('SAMLRequest', $_REQUEST) && !empty($_REQUEST['SAMLRequest'])){  
      $this->_read_samllogout_request($_REQUEST,$_GET);
    }elseif (array_key_exists('SAMLResponse', $_REQUEST) && !empty($_REQUEST['SAMLResponse'])){
      $this->_read_saml_response($_REQUEST,$_GET);
    }
    $nameid = \Drupal::config('miniorange_saml_idp.settings')->get('miniorange_saml_idp_nameid_attr_map');
    $this->mo_idp_send_logout_request($nameid, $issuer, $logout_url);
    return;
}  
 
  function mo_idp_send_logout_request($nameid, $issuer, $logout_url){
    global $base_url;
    $relayState = $base_url;
    $logout_binding_type = \Drupal::config('miniorange_saml_idp.settings')->get('miniorange_saml_idp_http_binding');
    if($logout_binding_type == 'HTTP-POST') {
    $samlRequest = Utilities::createLogoutRequest($nameId, '', $issuer, $logout_url, 'HttpPost');
    $privateKeyPath = dirname(dirname(dirname(__FILE__))) . DIRECTORY_SEPARATOR . 'resources' . DIRECTORY_SEPARATOR . 'idp-signing.key';
    $publicCertPath = dirname(dirname(dirname(__FILE__))) . DIRECTORY_SEPARATOR . 'resources' . DIRECTORY_SEPARATOR . 'idp-signing.crt';
    $base64EncodedXML = Utilities::signXML( $samlRequest, $publicCertPath, $privateKeyPath);
    $this->_send_logout_request($base64EncodedXML, $relayState, $logout_url);
    return;
  }
  else {
       
    $samlRequest = Utilities::createLogoutRequest($nameId, '', $issuer, $logout_url, 'HTTP-Redirect');
    $samlRequest = 'SAMLRequest=' . $samlRequest . '&RelayState=' . urlencode($relayState) . '&SigAlg='. urlencode(XMLSecurityKey::RSA_SHA256);
    $param =array( 'type' => 'private');
    $key = new XMLSecurityKey(XMLSecurityKey::RSA_SHA256, $param);
    $certFilePath = dirname(dirname(dirname(__FILE__))) . DIRECTORY_SEPARATOR . 'resources' . DIRECTORY_SEPARATOR . 'idp-signing.key';
    $key->loadKey($certFilePath, TRUE);
    $objXmlSecDSig = new XMLSecurityDSig();
    $signature = $key->signData($samlRequest);
    $signature = base64_encode($signature);
    $redirect = $logout_url;
    if (strpos($logout_url,'?') !== false) {
        $redirect .= '&';
    } else {
        $redirect .= '?';
    }
    $redirect .= $samlRequest . '&Signature=' . urlencode($signature);
    $response = new RedirectResponse($redirect);
    $response->send();
    return;
  }
  }
   
  function _read_samllogout_request($REQUEST,$GET){
     
    $samlRequest = $REQUEST['SAMLRequest'];
    $relay_State = '/';
    if(array_key_exists('RelayState', $REQUEST)) {
            $relay_state = $REQUEST['RelayState'];
    }
     
    $samlRequest = base64_decode($samlRequest);
    if(array_key_exists('SAMLRequest', $GET) && !empty($GET['SAMLRequest'])) {
      $samlRequest = gzinflate($samlRequest);
    }
     
    $sigAlg = array_key_exists('SigAlg',$REQUEST) ? $REQUEST['SigAlg'] : null;
    $signature = array_key_exists('Signature', $REQUEST) ? $REQUEST['Signature'] : null;
     
    $document = new DOMDocument();
    $document->loadXML($samlRequest);
    $samlRequestXML = $document->firstChild;
     
    if( $samlRequestXML->localName == 'LogoutRequest' ){
$logoutRequest = new LogoutRequest( $samlRequestXML );
            $logout_request_id = $logoutRequest->getId();
            $issuer = $logoutRequest->getIssuer();
            $destination = \Drupal::config('miniorange_saml_idp.settings')->get('miniorange_saml_idp_logout_url');
             
      $logoutResponse = Utilities::createLogoutResponse($logout_request_id, $issuer, $destination, 'HttpPost');
      $privateKeyPath = dirname(dirname(dirname(__FILE__))) . DIRECTORY_SEPARATOR . 'resources' . DIRECTORY_SEPARATOR . 'idp-signing.key';
      $publicCertPath = dirname(dirname(dirname(__FILE__))) . DIRECTORY_SEPARATOR . 'resources' . DIRECTORY_SEPARATOR . 'idp-signing.crt';
      $base64EncodedXML = Utilities::signXML( $logoutResponse, $publicCertPath, $privateKeyPath, 'NameID' );
      $this->_send_logout_response($base64EncodedXML, $relay_state, $destination);
    }
  }
   
   function _read_saml_response($REQUEST,$GET) {
     
    $samlResponse = $REQUEST['SAMLResponse'];
    $relayState = '/';
    if(array_key_exists('RelayState', $REQUEST)) {
      $relayState = $REQUEST['RelayState'];
    }
 
    $samlResponse = base64_decode($samlResponse);
    if(array_key_exists('SAMLResponse', $GET) && !empty($GET['SAMLResponse'])) {
      $samlResponse = gzinflate($samlResponse);
    }
     
    $document = new DOMDocument();
    $document->loadXML($samlResponse);
    $samlResponseXML = $document->firstChild;
    if( $samlResponseXML->localName == 'LogoutResponse' ) {
      session_destroy();
      $response = new RedirectResponse($relayState);
            $response->send();  
    }
  }
   
   function _send_logout_response($base64EncodedXML, $relayState, $logout_url){
   
    $response = htmlspecialchars($base64EncodedXML);
    echo '<form id="responseform" action="'. $logout_url .'" method="post">
      <input type="hidden" name="SAMLResponse" value="'. $response .'" />
      <input type="hidden" name="RelayState" value="'. $relayState .'" />
    </form>
    <script>
      document.getElementById("responseform").submit(); 
    </script>';
        session_destroy();
    exit;
  }
   
  function test_configuration() {
 
  $relayState = '/';
  $acs = \Drupal::config('miniorange_saml_idp.settings')->get("miniorange_saml_idp_acs_url");
  
  $sp_issuer =\Drupal::config('miniorange_saml_idp.settings')->get("miniorange_saml_idp_entity_id");
   
  if($acs == '' || is_null($acs) || $sp_issuer == '' || is_null($sp_issuer)){
    drupal_set_message(t('Please configure your SP configurations first and then click on Test Configuration.'));
         
    }
 
  $this->mo_idp_authorize_user($acs, $sp_issuer,$relayState );
  }
   
  function _send_logout_request($base64EncodedXML, $relayState, $logout_url){
    $request = htmlspecialchars($base64EncodedXML);
    echo '<form id="requestform" action="'. $logout_url .'" method="post">
      <input type="hidden" name="SAMLRequest" value="'. $request .'" />
      <input type="hidden" name="RelayState" value="'. $relayState .'" />
    </form>
    <script>
      document.getElementById("requestform").submit();  
    </script>';
    session_destroy();
    exit;
  }
 
function miniorange_saml_idp_initiated_login(){
   
 
  global $base_url;
   
  $spName = $_REQUEST['sp'];
  
  $configured_sp =\Drupal::config('miniorange_saml_idp.settings')->get("miniorange_saml_idp_name");
 
  if($spName != $configured_sp){
     
    $redirect_url = $base_url;
    $response = new RedirectResponse($redirect_url);
 
    $response->send();
  }
   
  $acs_url = \Drupal::config('miniorange_saml_idp.settings')->get("miniorange_saml_idp_acs_url");
 
  $sp_issuer =\Drupal::config('miniorange_saml_idp.settings')->get("miniorange_saml_idp_entity_id");
 
  $relay_state = '/';
  $state =\Drupal::config('miniorange_saml_idp.settings')->get('miniorange_saml_default_relaystate');
  if(!empty($state) && !is_null($state)){
    $relay_state = $state;
  }
  $this->mo_idp_authorize_user($acs_url, $sp_issuer, $relay_state);
}
 
 
function miniorange_saml_idp_login_request() {
   
   if(array_key_exists('SAMLRequest', $_REQUEST) && !empty($_REQUEST['SAMLRequest'])) {
 
   $this->_read_saml_request($_REQUEST,$_GET);
    return new Response();   
  }
}
 
function _read_saml_request($REQUEST,$GET) {
   
  $samlRequest = $REQUEST['SAMLRequest'];
  $relayState = '/';
  if(array_key_exists('RelayState', $REQUEST)) {
  $relayState = $REQUEST['RelayState'];
  }
     
  $samlRequest = base64_decode($samlRequest);
  if(array_key_exists('SAMLRequest', $GET) && !empty($GET['SAMLRequest'])) {
    $samlRequest = gzinflate($samlRequest);
  }
     
  $document = new DOMDocument();
  $document->loadXML($samlRequest);
  $samlRequestXML = $document->firstChild;
  
  $authnRequest = new MiniOrangeAuthnRequest($samlRequestXML);
   
  $errors = '';
  if(strtotime($authnRequest->getIssueInstant()) > (time() + 60))
    $errors.= '<strong>INVALID_REQUEST: </strong>Request time is greater than the current time.<br/>';
  if($authnRequest->getVersion()!=='2.0')
    $errors.='We only support SAML 2.0! Please send a SAML 2.0 request.<br/>';
     
  $acs_url = \Drupal::config('miniorange_saml_idp.settings')->get('miniorange_saml_idp_acs_url');
  $sp_issuer = \Drupal::config('miniorange_saml_idp.settings')->get('miniorange_saml_idp_entity_id');
  $acs_url_from_request = $authnRequest->getAssertionConsumerServiceURL();
  $sp_issuer_from_request = $authnRequest->getIssuer();
   
  if(empty($acs_url) || empty($sp_issuer)){
    $errors.= '<strong>INVALID_SP: </strong>Service Provider is not configured. Please configure your Service Provider.<br/>';
  }else{
    if(strcmp($acs_url,$acs_url_from_request) !== 0 ){
      $errors.= '<strong>INVALID_ACS: </strong>Invalid ACS URL!. Please check your Service Provider Configurations.<br/>';
  }
  if(strcmp($sp_issuer,$sp_issuer_from_request) !== 0){
      $errors.='<strong>INVALID_ISSUER: </strong>Invalid Issuer! Please check your configuration.<br/>';
  }
  }
   
  $inResponseTo = $authnRequest->getRequestID(); 
   
  if(empty($errors)){
 
  $module_path = drupal_get_path('module', 'miniorange_saml_idp');
  ?>
  <div style="vertical-align:center;text-align:center;width:100%;font-size:25px;background-color:white;">
    <img src="<?php echo $module_path;?>/includes/images/loader_gif.gif"></img>
    <h3>PROCESSING...PLEASE WAIT!</h3>
  </div>
  <?php
 
 
  $this->mo_idp_authorize_user($acs_url_from_request,$sp_issuer_from_request,$relayState,$inResponseTo);
  } else{
 
  echo sprintf($errors);
  exit;
  }
}
  function mo_idp_authorize_user($acs_url,$audience,$relayState,$inResponseTo=null){
    
  $user =  \Drupal::currentUser();
  if ( \Drupal::currentUser()->isAuthenticated()) {
   $this->mo_idp_send_reponse($acs_url,$audience,$relayState,$inResponseTo);
   
  } else {
	  
    $saml_response_params = array('moIdpsendResponse' => "true" , "acs_url" => $acs_url , "audience" => $audience , "relayState" => $relayState,"inResponseTo" => $inResponseTo );
   	
	setcookie("response_params",json_encode($saml_response_params));
			
    global $base_url;
    $redirect_url = $base_url . '/user/login';
    $login_page_url = \Drupal::config('miniorange_saml_idp.settings')->get('miniorange_saml_default_relaystate') ;
 
    $redirect_url = isset($login_page_url) && !empty($login_page_url) ? $login_page_url : $redirect_url;
    $response = new RedirectResponse($redirect_url);
 
    $response->send();
 
  }
}
 
function mo_idp_send_reponse($acs_url,$audience,$relayState, $inResponseTo=null){
   
 
  $user = \Drupal::currentUser();
 
   
 
  $email = $user->getEmail();
  $username = $user->getUsername();
   
  global $base_url;
 
  $issuer = $base_url . '/?q=admin/config/people/miniorange_saml_idp/';
   
  $name_id_attr =\Drupal::config('miniorange_saml_idp.settings')->get("miniorange_saml_idp_nameid_attr_map");
  $idp_response_signed =\Drupal::config('miniorange_saml_idp.settings')->get("miniorange_saml_idp_response_signed");
  $name_id_attr_format =\Drupal::config('miniorange_saml_idp.settings')->get("miniorange_saml_idp_nameid_format"); 
  $idp_assertion_signed =\Drupal::config('miniorange_saml_idp.settings')->get("miniorange_saml_idp_assertion_signed"); 
  $state = \Drupal::config('miniorange_saml_idp.settings')->get("miniorange_saml_idp_relay_state"); 
   
  if(!empty($state) && !is_null($state)){
    $relayState = $state;
  }
 
$attributes = \Drupal\miniorange_saml_idp\Controller\miniorange_saml_idpController::mo_get_idp_attributes( $user );
 
  $saml_response_obj = new GenerateResponse($email,$username, $acs_url, $issuer, $audience,$inResponseTo, $name_id_attr,$idp_response_signed,$attributes,$name_id_attr_format,$idp_assertion_signed);
   
 
  $saml_response = $saml_response_obj->createSamlResponse();
  setcookie("response_params","");
   
   $this->_send_response($saml_response, $relayState,$acs_url);
}
  
function mo_get_idp_attributes( $user) {   
  $email = $user->getEmail();
  $username = $user->getUsername();

    $roles = $user->getRoles();
     
      $rolelist = $roles;
      $attribute = array();
     $attr1Name = \Drupal::config('miniorange_saml_idp.settings')->get('miniorange_saml_idp_attr1_name');
   $attr1Value = \Drupal::config('miniorange_saml_idp.settings')->get('miniorange_saml_idp_attr1_value');
    
     
    if(!empty($attr1Name) && !empty($attr1Value)) {
      $value = \Drupal\miniorange_saml_idp\Controller\miniorange_saml_idpController::mo_get_attribute_value($email, $username, $rolelist, $attr1Value);
      $attribute[$attr1Name] = $value;
    }
     
    $attr2Name = \Drupal::config('miniorange_saml_idp.settings')->get('miniorange_saml_idp_attr2_name');
     $attr2Value = \Drupal::config('miniorange_saml_idp.settings')->get('miniorange_saml_idp_attr2_value');
    
      
    if(!empty($attr2Name) && !empty($attr2Value)) {
      $value = \Drupal\miniorange_saml_idp\Controller\miniorange_saml_idpController::mo_get_attribute_value($email, $username, $rolelist, $attr2Value);
      $attribute[$attr2Name] = $value;
    }
     $attr3Name = \Drupal::config('miniorange_saml_idp.settings')->get('miniorange_saml_idp_attr3_name');
     $attr3Value = \Drupal::config('miniorange_saml_idp.settings')->get('miniorange_saml_idp_attr3_value');
    
    if(!empty($attr3Name) && !empty($attr3Value)) {
      $value = \Drupal\miniorange_saml_idp\Controller\miniorange_saml_idpController::mo_get_attribute_value($email, $username,  $rolelist, $attr3Value);
      $attribute[$attr3Name] = $value;
    }
     
     $attr4Name = \Drupal::config('miniorange_saml_idp.settings')->get('miniorange_saml_idp_attr4_name');
   
   if(!empty($attr4Name) && !empty($attr4Value)) {
      $value = \Drupal\miniorange_saml_idp\Controller\miniorange_saml_idpController::mo_get_attribute_value($email, $username, $rolelist, $attr4Value);
      $attribute[$attr4Name] = $value;
    }
     
    // $userProfileAttribues = variable_get('miniorange_saml_idp_user_attributes', '');
    // if(isset($userProfileAttribues) && !empty($userProfileAttribues)){
    //   $userProfileAttribues = json_decode($userProfileAttribues, true);
    //   $usr = user_load($current_user->uid);
    //   foreach($userProfileAttribues as $profileAttribute){
    //     $user_attr_name = $profileAttribute["attr_name"];
    //     $user_attr_value = $profileAttribute["attr_value"];
         
    //     if(isset($usr->{$user_attr_value})){
    //       $user_data_name = $usr->{$user_attr_value};
    //       if(isset($user_data_name['und'][0]['value'])){
    //         $value = $user_data_name['und'][0]['value'];
    //         if(!empty($value))
    //           $attribute[$user_attr_name] = $value;
    //       }
    //     }
    //   }
    // }
     
    return $attribute;
  }
   
function mo_get_attribute_value($email, $username, $roles, $attrValue) {
  switch($attrValue) {
    case 'name':
      return $username;
    case 'mail':
      return $email;
     
    case 'roles':
      return $roles;
    default:
      return '';
  }
}
 
 
function _send_response($saml_response, $ssoUrl,$acs_url){
   
  $saml_response = base64_encode($saml_response);
  ?>
  <form id="responseform" action="<?php echo $acs_url; ?>" method="post">
    <input type="hidden" name="SAMLResponse" value="<?php echo htmlspecialchars($saml_response); ?>" />
    <input type="hidden" name="RelayState" value="<?php echo $ssoUrl; ?>" />
  </form>
  <script>
    setTimeout(function(){
      document.getElementById('responseform').submit();
    }, 100);  
  </script>
<?php
 
 
  exit;
}
 
 
function miniorange_saml_idp_libraries_info() {
  $libraries['xmlseclibs'] = array(
    'name' => 'XML Encryption and Signatures',
    'vendor url' => 'https://code.google.com/p/xmlseclibs/',
    'download url' => 'https://xmlseclibs.googlecode.com/files/xmlseclibs-1.3.1.tar.gz',
    'version arguments' => array(
      'file'    => 'xmlseclibs.php',
      'pattern' => '/@version\s*(.*)$/',
      'lines'   => 100,
    ),
    'files' => array(
      'php' => array(
        'xmlseclibs.php',
      ),
    ),
  );
 
  return $libraries;
}
}