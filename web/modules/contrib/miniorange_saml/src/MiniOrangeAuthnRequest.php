<?php
namespace Drupal\miniorange_saml;

use DOMElement;
use Symfony\Component\HttpFoundation\RedirectResponse;

/**
 * The MiniOrangeAuthnRequest class.
 */
class MiniOrangeAuthnRequest {

  /**
   * The function initiateLogin.
   */
   public function initiateLogin($acs_url, $sso_url, $issuer, $relay_state, $nameid_format, $sso_binding_type, $saml_request_sign) {
	  
    $saml_request = Utilities::createAuthnRequest($acs_url, $issuer , $nameid_format, $sso_url, 'false', $sso_binding_type);
	$this->sendSamlRequestByBindingType($saml_request, $sso_binding_type, $relay_state, $sso_url, $saml_request_sign);
	
  }
  
  function sendSamlRequestByBindingType($samlRequest, $sso_binding_type, $sendRelayState, $ssoUrl,$saml_request_sign){
	  
		$module_path = drupal_get_path('module', 'miniorange_saml');
		
		if(empty($sso_binding_type) || $sso_binding_type == 'HTTP-Redirect') {
						
			$samlRequest = "SAMLRequest=" . $samlRequest . "&RelayState=" . $sendRelayState;
			$param =array( 'type' => 'private');
			$key = new XMLSecurityKey(XMLSecurityKey::RSA_SHA256, $param);
			$certFilePath = \Drupal::root() . DIRECTORY_SEPARATOR . $module_path . DIRECTORY_SEPARATOR . 'resources' . DIRECTORY_SEPARATOR . 'sp-key.key';
			$key->loadKey($certFilePath, TRUE);
			$objXmlSecDSig = new XMLSecurityDSig();
			$signature = $key->signData($samlRequest);
			$signature = base64_encode($signature);
			$redirect = $ssoUrl;
			if (strpos($ssoUrl,'?') !== false) {
				$redirect .= '&';
			} else {
				$redirect .= '?';
			}
			if($saml_request_sign)
			   $redirect .=  $samlRequest .'&SigAlg='. urlencode(XMLSecurityKey::RSA_SHA256). '&Signature=' . urlencode($signature);
			else
			   $redirect .=  $samlRequest;
			   $response = new RedirectResponse($redirect);
			   $response->send();
		 
		}else{
			if(!$saml_request_sign)
			{
				$base64EncodedXML = base64_encode($samlRequest);
				Utilities::postSAMLRequest($ssoUrl, $base64EncodedXML, $sendRelayState);
				exit();
			}
		
			$privateKeyPath = \Drupal::root() . DIRECTORY_SEPARATOR . $module_path . DIRECTORY_SEPARATOR . 'resources' . DIRECTORY_SEPARATOR . 'sp-key.key';
			$publicCertPath = \Drupal::root() . DIRECTORY_SEPARATOR . $module_path . DIRECTORY_SEPARATOR . 'resources' . DIRECTORY_SEPARATOR . 'sp-certificate.crt';
			
			$base64EncodedXML = Utilities::signXML( $samlRequest, $publicCertPath, $privateKeyPath, 'NameIDPolicy' );
			Utilities::postSAMLRequest($ssoUrl, $base64EncodedXML, $sendRelayState);
		}
	}
}
