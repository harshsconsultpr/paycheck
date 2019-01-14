<?php /**
 * @file
 * Contains \Drupal\miniorange_saml\Controller\DefaultController.
 */
 /**
 * Default controller for the miniorange_saml module.
 */

namespace Drupal\miniorange_saml\Controller;

use Symfony\Component\HttpFoundation\Response;
use Drupal\Core\Session\AccountInterface;
use Symfony\Component\HttpFoundation\Request;
use Drupal\user\Entity\User;
use Drupal\Core\Controller\ControllerBase;
use Drupal\user\RoleInterface;
use Drupal\user\UserInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Drupal\miniorange_saml\AESEncryption;
use Drupal\miniorange_saml\Utilities;
use Drupal\miniorange_saml\MiniOrangeAuthnRequest;
use Drupal\miniorange_saml\MiniOrangeAcs;
use Drupal\miniorange_saml\XMLSecurityKey;
use Drupal\Core\DependencyInjection;
use DOMElement;


class miniorange_samlController extends ControllerBase {

  public function saml_login($relay_state='') {
	global $base_url;
	$b_url = \Drupal::config('miniorange_saml.settings')->get('miniorange_saml_base_url');
	$base_site_url = isset($b_url)? $b_url : $base_url;
    $entityID = \Drupal::config('miniorange_saml.settings')->get('miniorange_saml_entity_id');
	$issuer = isset($entityID)? $entityID : $base_site_url;
	$relay_state = \Drupal::config('miniorange_saml.settings')->get('miniorange_saml_default_relaystate');
	$request_signed = \Drupal::config('miniorange_saml.settings')->get('miniorange_saml_request_signed');
	$http_binding = \Drupal::config('miniorange_saml.settings')->get('miniorange_saml_http_binding');
	
	if(empty($relay_state)){
	$relay_state = $_SERVER['HTTP_REFERER'];
	}
	
	$current_link = \Drupal::config('miniorange_saml.settings')->get('current_link');
	
	if(empty($relay_state)){
	$relay_state = $current_link ;
	\Drupal::configFactory()->getEditable('miniorange_saml.settings')->set('current_link','')->save();  
	}
	
	if(empty($relay_state)) {
		$query = isset($_GET['q'])? $_GET['q'] : '';
		$relay_state = $base_url . '/' . $query;
	}
		
    $acs_url = $base_site_url . '/samlassertion';
    $sso_url = \Drupal::config('miniorange_saml.settings')->get('miniorange_saml_idp_login_url');
	$nameid_format = \Drupal::config('miniorange_saml.settings')->get('miniorange_saml_nameid_format');
    $authn_request = new MiniOrangeAuthnRequest();
    $authn_request->initiateLogin($acs_url, $sso_url, $issuer, $relay_state, $nameid_format, $http_binding, $request_signed);
	return new Response();
  }

  public function saml_response() {
	global $base_url;
	$b_url = \Drupal::config('miniorange_saml.settings')->get('miniorange_saml_base_url');
	$base_site_url = isset($b_url)? $b_url : $base_url;  
	$acs_url = $base_site_url . '/samlassertion';
    $cert_fingerprint = \Drupal::config('miniorange_saml.settings')->get('miniorange_saml_idp_x509_certificate');
    $issuer = \Drupal::config('miniorange_saml.settings')->get('miniorange_saml_idp_issuer');
	$entityID = \Drupal::config('miniorange_saml.settings')->get('miniorange_saml_entity_id');
	$sp_entity_id = isset($entityID)? $entityID : $base_site_url;

    $login_by = \Drupal::config('miniorange_saml.settings')->get('miniorange_saml_login_by');

    if ($login_by == 1) {
      $username_attribute = \Drupal::config('miniorange_saml.settings')->get('miniorange_saml_username_attribute');
    }
    else {
      $username_attribute = \Drupal::config('miniorange_saml.settings')->get('miniorange_saml_email_attribute');
    }

    if (isset($_GET['SAMLResponse'])) {
	  $request = \Drupal::request();
	  $request->getSession()->clear();
	  $response = new RedirectResponse($base_site_url);
	  $response->send();
	  return new Response();
    }
	else {
		
	  /*Custom Attributes*/
	  $attrs = array();
	  $sp_attr1 = \Drupal::config('miniorange_saml.settings')->get('miniorange_saml_attr1_name');
	  $idp_attr1 = \Drupal::config('miniorange_saml.settings')->get('miniorange_saml_idp_attr1_name', '');
	  $sp_attr2 = \Drupal::config('miniorange_saml.settings')->get('miniorange_saml_attr2_name');
	  $idp_attr2 = \Drupal::config('miniorange_saml.settings')->get('miniorange_saml_idp_attr2_name');
	  $sp_attr3 = \Drupal::config('miniorange_saml.settings')->get('miniorange_saml_attr3_name');
	  $idp_attr3 = \Drupal::config('miniorange_saml.settings')->get('miniorange_saml_idp_attr3_name');
	  $sp_attr4 = \Drupal::config('miniorange_saml.settings')->get('miniorange_saml_attr4_name');
	  $idp_attr4 = \Drupal::config('miniorange_saml.settings')->get('miniorange_saml_idp_attr4_name');
	  $sp_attr5 = \Drupal::config('miniorange_saml.settings')->get('miniorange_saml_attr5_name');
	  $idp_attr5 = \Drupal::config('miniorange_saml.settings')->get('miniorange_saml_idp_attr5_name');
	  
	  if(!empty($sp_attr1) && !empty($idp_attr1)){
		 $attrs[$sp_attr1] =  $idp_attr1;
	  }
	  if(!empty($sp_attr2) && !empty($idp_attr2)){
		 $attrs[$sp_attr2] =  $idp_attr2;
	  }
	  if(!empty($sp_attr3) && !empty($idp_attr3)){
		 $attrs[$sp_attr3] =  $idp_attr3;
	  }
	  if(!empty($sp_attr4) && !empty($idp_attr4)){
		 $attrs[$sp_attr4] =  $idp_attr4;
	  }
	  if(!empty($sp_attr5) && !empty($idp_attr5)){
		 $attrs[$sp_attr5] =  $idp_attr5;
	  }
        /*** Custom Role ***/

	  $role = array();
	  $role = \Drupal::config('miniorange_saml.settings')->get('rolemap');
      $response_obj = new MiniOrangeAcs();

      $fraud_check = \Drupal::config('miniorange_saml.settings')->get('minorange_saml_customer_admin_fraud_check');
      $key_value = \Drupal::config('miniorange_saml.settings')->get('miniorange_saml_customer_admin_token');
      $username = \Drupal::config('miniorange_saml.settings')->get('miniorange_saml_customer_admin_email');

      global $base_url;
      global $base_path;
      $current_path = $_SERVER['DOCUMENT_ROOT'].$base_path;
      $home_url = trim($base_url, '/');
        if (!preg_match('#^http(s)?://#', $home_url)) {
            $home_url = 'http://' . $home_url;
        }
      $current_urlParts = parse_url($home_url);
      $current_domain = preg_replace('/^www\./', '', $current_urlParts['host'].$current_urlParts['path']);

      $current_path_domain = $current_path . $current_domain;
      $current_check = AESEncryption::encrypt_data($current_path_domain, $key_value);

        if($current_check == $fraud_check && $username != null && $username != '')
        {
            $response = $response_obj->processSamlResponse($_POST, $acs_url, $cert_fingerprint, $issuer, $base_site_url, $sp_entity_id, $username_attribute, $attrs, $role);
        }
        else {
            if($username != null && $username != '') {
                echo '<div style="font-family:Calibri;padding:0 3%;">';
                echo '<div style="color: #a94442;background-color: #f2dede;padding: 15px;margin-bottom: 20px;text-align:center;border:1px solid #E6B3B2;font-size:18pt;"> ERROR</div>
                            <div style="color: #a94442;font-size:14pt; margin-bottom:20px;"><p><strong>Error: </strong>License key you have entered has already been used.</p>
                                <p>Please enter a key which has not been used before on any other instance or if you have exausted all your keys then buy more license from Licensing.</p>
                            </div>
                            <div style="margin:3%;display:block;text-align:center;"></div>
                            <div style="margin:3%;display:block;text-align:center;">
                                <input style="padding:1%;width:100px;background: #0091CD none repeat scroll 0% 0%;cursor: pointer;font-size:15px;border-width: 1px;border-style: solid;border-radius: 3px;white-space: nowrap;box-sizing: border-box;border-color: #0073AA;box-shadow: 0px 1px 0px rgba(120, 200, 230, 0.6) inset;color: #FFF;"type="button" value="Done" onClick="self.close();">
                            </div>';
                exit;
            }
            else if($username == null || $username == ''){
                echo '<div style="font-family:Calibri;padding:0 3%;">';
                echo '<div style="color: #a94442;background-color: #f2dede;padding: 15px;margin-bottom: 20px;text-align:center;border:1px solid #E6B3B2;font-size:18pt;"> ERROR</div>
                            <div style="color: #a94442;font-size:14pt; margin-bottom:20px;"><p><strong>Error: </strong>You are not logged in.</p>
                                <p>Please login first to activate single sign on.</p>
                                <p><strong>Possible Cause: </strong>Make sure you have logged in/ Register in to plugin.</p>
                            </div>
                            <div style="margin:3%;display:block;text-align:center;"></div>
                            <div style="margin:3%;display:block;text-align:center;">
                                <input style="padding:1%;width:100px;background: #0091CD none repeat scroll 0% 0%;cursor: pointer;font-size:15px;border-width: 1px;border-style: solid;border-radius: 3px;white-space: nowrap;box-sizing: border-box;border-color: #0073AA;box-shadow: 0px 1px 0px rgba(120, 200, 230, 0.6) inset;color: #FFF;"type="button" value="Done" onClick="self.close();">
                            </div>';
                exit;
            }
        }

      if (\Drupal::config('miniorange_saml.settings')->get('miniorange_saml_login_by') == 1) {
        $account = user_load_by_name($response['username']);
      }
      else {
        $account = user_load_by_mail($response['username']);
      }
	  $default_role = \Drupal::config('miniorange_saml.settings')->get('miniorange_saml_default_role');
      // Create user if not already present.
      if ($account == NULL) {
        $disable_autocreate_users = \Drupal::config('miniorange_saml.settings')->get('miniorange_saml_disable_autocreate_users');
        if ($disable_autocreate_users) {
          echo 'Account does not exist with your username. Close this browser and try with different user.';
          exit();
		  return new Response();
        }
        else {
		
          $random_password = user_password(8);
          $new_user = [
            'name' => $response['username'],
            'mail' => $response['email'],
            'pass' => $random_password,
            'status' => 1,
          ];
          
		   $account = User::create($new_user);
           $account -> save();

        }
      }
	  
	  $customFieldAttributes = array();
      $customFieldAttributes = $response['customFieldAttributes'];
	  
      foreach ($customFieldAttributes as $key => $value) {
		$account = \Drupal\user\Entity\User::load($account->id());
		$account->{$key} = $value;
        $account->save();
      }
	  
	  $customFieldRoles = array();
	  $customFieldRoles = $response['customFieldRoles'];

	  if(!is_null($account)) {

	      $disable_role_update = \Drupal::configFactory()->getEditable('miniorange_saml.settings')->get('miniorange_saml_disable_role_update');
          $account = \Drupal\user\Entity\User::load($account->id());
	      $user_roles = $account->getRoles();
	      $inter = array();
          if($disable_role_update) {
              $inter = array_intersect($user_roles,$role);
          }

	      foreach($user_roles as $key=>$value) {
              if(empty($inter)) {
                  $account->removeRole($value);
              }
              elseif(in_array($value,$inter)) {
                  $account->removeRole($value);
              }
	      }

	      if(isset($customFieldRoles) && !empty($customFieldRoles)) {
	          foreach($customFieldRoles as $key=>$value){
	              if(array_key_exists($value,$role)){
	                  $account -> addRole(strtolower($role[$value]));
				      $account -> save();
			      }
	          }
	      }

	      if(sizeof($account->getRoles())==1) {
	          $account -> addRole(strtolower($default_role));
		      $account -> save();
	      }
	  }
	   if (user_is_blocked($response['username']) == FALSE) {
		  if (array_key_exists('relay_state', $response) && !empty($response['relay_state'])) {
          $rediectUrl = $response['relay_state'];
		}
		
		if(empty($rediectUrl)) {
			$rediectUrl = $base_site_url;
		}
		
        $_SESSION['sessionIndex'] = $response['sessionIndex'];
        $_SESSION['NameID'] = $response['NameID'];
        $_SESSION['mo_saml']['logged_in_with_idp'] = TRUE;

        user_login_finalize($account);
        $response = new RedirectResponse($rediectUrl);
        $response->send();
		return new Response();
      }
      else {
        echo("User Blocked By Administrator");
        exit;
		return new Response();
      }
	}
    }
  
  function samllogout() {
	  global $base_url;
	  $b_url = \Drupal::config('miniorange_saml.settings')->get('miniorange_saml_base_url');
	  $base_site_url = isset($b_url)? $b_url : $base_url;  
	  
	  if(is_null($_REQUEST['RelayState'])) {
		  $relayState = $base_site_url;
	  } else {
		  $relayState = $_REQUEST['RelayState'];
	  }
	  
	  \Drupal::service('session_manager')->destroy();
	  $request = \Drupal::request();
	  $request->getSession()->clear();
	  $response = new RedirectResponse($relayState);
	  $response->send();
	  return new Response();
  }
	
  function saml_logout() {
		global $base_url;
		$b_url = \Drupal::config('miniorange_saml.settings')->get('miniorange_saml_base_url');
		$base_site_url = isset($b_url)? $b_url : $base_url;  
			
		if(isset($_GET['q']) && $_GET['q']=='samllogout') {
			
			if(is_null($_REQUEST['RelayState'])) {
				$relayState = $base_site_url;
			} else {
				$relayState = $_REQUEST['RelayState'];
			}
			
			$request = \Drupal::request();
			$request->getSession()->clear();
			$response = new RedirectResponse($relayState);
			$response->send();
			return new Response();
		}
		
	  	$logout_url = \Drupal::config('miniorange_saml.settings')->get("miniorange_saml_idp_logout_url");
		$logout_binding_type = '';
		
		if( !empty($logout_url) )  {
			if( !\Drupal::service('session')->getId() || \Drupal::service('session')->getId() == '' || !isset($_SESSION) ) {  
				session_start();
				return new Response();
			}
			else {
				if (isset($_SESSION['mo_saml']['logged_in_with_idp'])) {
				global $base_url;
				unset($_SESSION['mo_saml']);
				$sessionIndex = $_SESSION['sessionIndex'];
				$nameId = $_SESSION['NameID'];
				
				if(!is_null($_SESSION['logout']) && !empty($_SESSION['logout'])) {
				$sp_base_url = $_SESSION['logout'];
				}
				else{
					$sp_base_url = $base_url;
				}
				$sp_entity_id = $base_url;
				$destination = $logout_url;
				$sendRelayState = $sp_base_url;
				$samlRequest = Utilities::createLogoutRequest($nameId, $sessionIndex, $sp_entity_id, $destination, $logout_binding_type);
				
				if(empty($logout_binding_type) || $logout_binding_type == 'HttpRedirect') {
					if(strpos($logout_url, '?') !== false)
						$samlRequest = '&SAMLRequest=' . $samlRequest . '&RelayState=' . urlencode($sendRelayState);
					else
						$samlRequest = '?SAMLRequest=' . $samlRequest . '&RelayState=' . urlencode($sendRelayState);
					$redirect = $logout_url;
					$redirect .= $samlRequest;
					$response = new RedirectResponse($redirect);
					$response->send();
					return new Response();
				} 
			  }
			  global $base_url;
			$b_url = \Drupal::config('miniorange_saml.settings')->get('miniorange_saml_base_url');
			$base_site_url = isset($b_url)? $b_url : $base_url;  
			
			if(is_null($_REQUEST['RelayState'])) {
				$relayState = $base_site_url;
			} else {
				$relayState = $_REQUEST['RelayState'];
			}
			
			$request = \Drupal::request();
			$request->getSession()->clear();
			$response = new RedirectResponse($relayState);
			$response->send();
			return new Response();
			}
		}
		else{
			$request = \Drupal::request();
			$request->getSession()->clear();
			$response = new RedirectResponse($base_site_url);
			$response->send();
			return new Response();
		}
  }

/**
* Test configuration callback
*/  
  
  function test_configuration() {
	global $base_url;
	$b_url = \Drupal::config('miniorange_saml.settings')->get('miniorange_saml_base_url');
	$base_site_url = isset($b_url)? $b_url : $base_url;  
	$sendRelayState = "testValidate";
    $ssoUrl = \Drupal::config('miniorange_saml.settings')->get("miniorange_saml_idp_login_url");
    $acsUrl = $base_site_url . "/samlassertion";
	$entityID = \Drupal::config('miniorange_saml.settings')->get('miniorange_saml_entity_id');
	$issuer = isset($entityID)? $entityID : $base_site_url;
	$request_signed = \Drupal::config('miniorange_saml.settings')->get('miniorange_saml_request_signed');
	$http_binding = \Drupal::config('miniorange_saml.settings')->get('miniorange_saml_http_binding');
	$nameid_format = \Drupal::config('miniorange_saml.settings')->get('miniorange_saml_nameid_format');
	
    $samlRequest = Utilities::createAuthnRequest($acsUrl, $issuer, $nameid_format, $ssoUrl, 'false', $http_binding);
	$authn_request = new MiniOrangeAuthnRequest();
    $authn_request->sendSamlRequestByBindingType($samlRequest, $http_binding, $sendRelayState, $ssoUrl, $request_signed);
	return new Response();
  }
}
