<?php
/**
 * @package    miniOrange
 * @subpackage Plugins
 * @license    GNU/GPLv3
 * @copyright  Copyright 2015 miniOrange. All Rights Reserved.
 *
 *
 * This file is part of miniOrange Joomla SAML IDP plugin.
 *
 * miniOrange Joomla SAML IDP plugin is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 * 
 * miniOrange Joomla IDP plugin is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with miniOrange SAML plugin.  If not, see <http://www.gnu.org/licenses/>.
 */
namespace Drupal\miniorange_saml_idp; 

use DOMDocument;
use DOMElement;

use Drupal\miniorange_saml_idp\XMLSecurityKey;

	class GenerateResponse{
		
		
		private $xml;
		private $acsUrl;
		private $issuer;
		private $audience;
		private $username;
		private $email;
		private $my_sp;
		private $name_id_attr_format;
		private $inResponseTo;
		private $mo_idp_assertion_signed;
		private $subject;
		private $mo_idp_response_signed;
		private $attributes;
		function __construct($email,$username, $acs_url, $issuer, $audience, $inResponseTo=null, $name_id_attr=null, $mo_idp_response_signed=null,$attributes = array(),$name_id_attr_format=null, $mo_idp_assertion_signed=null){
			$this->xml = new DOMDocument("1.0", "utf-8");
			$this->acsUrl = $acs_url;		
			$this->issuer = $issuer;		
			$this->audience = $audience;
			$this->email = $email;
			$this->username = $username;
			$this->my_sp = $name_id_attr;
			$this->mo_idp_response_signed = $mo_idp_response_signed;
			$this->name_id_attr_format = $name_id_attr_format;
			$this->inResponseTo = $inResponseTo;
			$this->attributes = $attributes;
			$this->mo_idp_assertion_signed = $mo_idp_assertion_signed;
		}
		
		function createSamlResponse(){
			
			 $this->licenseCheck();
			$response_params = $this->getResponseParams();

			//Create Response Element
			$resp = $this->createResponseElement($response_params);
			$this->xml->appendChild($resp);
			
			//Build Issuer
			$issuer = $this->buildIssuer();
			$resp->appendChild($issuer);
			
			//Build Status
			$status = $this->buildStatus();
			$resp->appendChild($status);
			
			//Build Status Code
			$statusCode = $this->buildStatusCode();
			$status->appendChild($statusCode);
			
			//Build Assertion
			$assertion = $this->buildAssertion($response_params);
			$resp->appendChild($assertion);
			
			//Sign Assertion
			if($this->mo_idp_assertion_signed){
				$private_key = dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR . 'resources' . DIRECTORY_SEPARATOR . 'idp-signing.key';
				$this->signNode($private_key, $assertion, $this->subject,$response_params);
			}

			//Sign Response
			if($this->mo_idp_response_signed){
				$private_key = dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR . 'resources' . DIRECTORY_SEPARATOR . 'idp-signing.key';
				$this->signNode($private_key, $resp, $status,$response_params);
			}

			$samlResponse = $this->xml->saveXML();

			return $samlResponse;								
			
		}
		
		function getResponseParams(){
			$response_params = array();
			$time = time();
			$response_params['IssueInstant'] = str_replace('+00:00','Z',gmdate("c",$time));
			$response_params['NotOnOrAfter'] = str_replace('+00:00','Z',gmdate("c",$time+300));
			$response_params['NotBefore'] = str_replace('+00:00','Z',gmdate("c",$time-30));
			$response_params['AuthnInstant'] = str_replace('+00:00','Z',gmdate("c",$time-120));
			$response_params['SessionNotOnOrAfter'] = str_replace('+00:00','Z',gmdate("c",$time+3600*8));
			$response_params['ID'] = $this->generateUniqueID(40);
			$response_params['AssertID'] = $this->generateUniqueID(40);
			$response_params['Issuer'] = $this->issuer;
			$module_path = drupal_get_path('module', 'miniorange_saml_idp');
			$public_key = $module_path . '/resources/idp-signing.crt';
			$objKey = new XMLSecurityKey(XMLSecurityKey::RSA_SHA256,array( 'type' => 'public'));
			$objKey->loadKey($public_key, TRUE,TRUE);
			$response_params['x509'] = $objKey->getX509Certificate();
			$response_params['Attributes'] = $this->attributes;
			return $response_params;
		}
		
		function createResponseElement($response_params){
			$resp = $this->xml->createElementNS('urn:oasis:names:tc:SAML:2.0:protocol','samlp:Response');
			$resp->setAttribute('ID',$response_params['ID']);
			$resp->setAttribute('Version','2.0');
			$resp->setAttribute('IssueInstant',$response_params['IssueInstant']);
			$resp->setAttribute('Destination',$this->acsUrl);
			if(isset($this->inResponseTo) && !is_null($this->inResponseTo)){
				$resp->setAttribute('InResponseTo',$this->inResponseTo);
			}
			return $resp;
		}
		
		function buildIssuer(){
			$issuer = $this->xml->createElementNS('urn:oasis:names:tc:SAML:2.0:assertion','saml:Issuer',$this->issuer);
			return $issuer;
		}
		
		function buildStatus(){
			$status = $this->xml->createElementNS('urn:oasis:names:tc:SAML:2.0:protocol','samlp:Status');
			return $status;
		}
		
		function buildStatusCode(){
			$statusCode = $this->xml->createElementNS('urn:oasis:names:tc:SAML:2.0:protocol','samlp:StatusCode');
			$statusCode->setAttribute('Value', 'urn:oasis:names:tc:SAML:2.0:status:Success');
			return $statusCode;
		}
		
		function buildAssertion($response_params){
			$assertion = $this->xml->createElementNS('urn:oasis:names:tc:SAML:2.0:assertion','saml:Assertion');
			$assertion->setAttribute('ID',$response_params['AssertID']);
			$assertion->setAttribute('IssueInstant',$response_params['IssueInstant']);
			$assertion->setAttribute('Version','2.0');
			
			//Build Issuer
			$issuer = $this->buildIssuer($response_params);
			$assertion->appendChild($issuer);

			//Build Subject
			$subject = $this->buildSubject($response_params);
			$assertion->appendChild($subject);
			
			//Build Condition
			$condition = $this->buildCondition($response_params);
			$assertion->appendChild($condition);
			
			//Build AuthnStatement
			$authnstat = $this->buildAuthnStatement($response_params);
			$assertion->appendChild($authnstat);

			$attributes = $response_params['Attributes'];
		    if(!empty($attributes)) {
				$attrStatement = $this->buildAttrStatement($response_params);
				$assertion->appendChild($attrStatement);
		    }

			return $assertion;
		}
		function buildAttrStatement($response_params){
			$attrStatement = $this->xml->createElement('saml:AttributeStatement');
			$my_sp_attr = $response_params['Attributes'];
			foreach ($my_sp_attr as $attr => $value) {
				$attrs = $this->buildAttribute($attr,$value);
				$attrStatement->appendChild($attrs);
			}
			return $attrStatement;
		}
		function buildAttribute($attrName, $attrValue){
			$attrs = $this->xml->createElement('saml:Attribute');
		   
			$attrs->setAttribute('Name',$attrName);  
			$attrs->setAttribute('NameFormat','urn:oasis:names:tc:SAML:2.0:attrname-format:basic');
		   
			if(is_array($attrValue)){
				foreach ($attrValue as $key => $val) {
				    $attrsValueElement = $this->xml->createElement('saml:AttributeValue',$val);
				    $attrs->appendChild($attrsValueElement);
				}
			}else{
				$attrsValueElement = $this->xml->createElement('saml:AttributeValue',$attrValue);
				$attrs->appendChild($attrsValueElement);
			}
			return $attrs;
	   }
		function buildSubject($response_params){

			$subject = $this->xml->createElement('saml:Subject');
			$nameid = $this->buildNameIdentifier();
			// print_r($nameid);
			// exit;
			
			$subject->appendChild($nameid);
			$confirmation = $this->buildSubjectConfirmation($response_params);
			$subject->appendChild($confirmation);
			return $subject;
		}
		
		function signNode($private_key, $node, $subject,$response_params){
			//Private KEY	
			$objKey = new XMLSecurityKey(XMLSecurityKey::RSA_SHA256,array( 'type' => 'private'));
			$objKey->loadKey($private_key, TRUE);
						
			//Sign the Assertion
			$objXMLSecDSig = new XMLSecurityDSig();
			$objXMLSecDSig->setCanonicalMethod(XMLSecurityDSig::EXC_C14N);

			$objXMLSecDSig->addReferenceList(array($node), XMLSecurityDSig::SHA256,
				array('http://www.w3.org/2000/09/xmldsig#enveloped-signature', XMLSecurityDSig::EXC_C14N),array('id_name'=>'ID','overwrite'=>false));
			$objXMLSecDSig->sign($objKey);
			$objXMLSecDSig->add509Cert($response_params['x509']);
			$objXMLSecDSig->insertSignature($node,$subject);
		}
		
		function buildNameIdentifier(){
			
			if($this->my_sp==="emailAddress")
				$nameid = $this->xml->createElement('saml:NameID',$this->email);
			else
				$nameid = $this->xml->createElement('saml:NameID',$this->username);
			if(empty($this->name_id_attr_format)) {
				$nameid->setAttribute('Format','urn:oasis:names:tc:SAML:1.1:nameid-format:emailAddress');
			} else {
				$nameid->setAttribute('Format','urn:oasis:names:tc:SAML:'.$this->name_id_attr_format);
			}
			$nameid->setAttribute('SPNameQualifier',$this->audience);

			return $nameid;
		}
		
		function buildSubjectConfirmation($response_params){
			$confirmation = $this->xml->createElement('saml:SubjectConfirmation');
			$confirmation->setAttribute('Method','urn:oasis:names:tc:SAML:2.0:cm:bearer');
			$confirmationdata = $this->getSubjectConfirmationData($response_params);
			$confirmation->appendChild($confirmationdata);
			return $confirmation;
		}
		
		function getSubjectConfirmationData($response_params){
			$confirmationdata = $this->xml->createElement('saml:SubjectConfirmationData');
			$confirmationdata->setAttribute('NotOnOrAfter',$response_params['NotOnOrAfter']);
			$confirmationdata->setAttribute('Recipient',$this->acsUrl);
			if(isset($this->inResponseTo) && !is_null($this->inResponseTo)){
				$confirmationdata->setAttribute('InResponseTo',$this->inResponseTo);
			}
			return $confirmationdata;
		}
		
		function buildCondition($response_params){
			$condition = $this->xml->createElement('saml:Conditions');
			$condition->setAttribute('NotBefore',$response_params['NotBefore']);
			$condition->setAttribute('NotOnOrAfter',$response_params['NotOnOrAfter']);
			
			//Build AudienceRestriction
			$audiencer = $this->buildAudienceRestriction();
			$condition->appendChild($audiencer);
			
			return $condition;
		}
		
		function buildAudienceRestriction(){
			$audiencer = $this->xml->createElement('saml:AudienceRestriction');
			$audience = $this->xml->createElement('saml:Audience',$this->audience);
			$audiencer->appendChild($audience);
			return $audiencer;
		}
		
		function buildAuthnStatement($response_params){
			$authnstat = $this->xml->createElement('saml:AuthnStatement');
			$authnstat->setAttribute('AuthnInstant',$response_params['AuthnInstant']);
			$authnstat->setAttribute('SessionIndex','_'.$this->generateUniqueID(30));
			$authnstat->setAttribute('SessionNotOnOrAfter',$response_params['SessionNotOnOrAfter']);
			
			$authncontext = $this->xml->createElement('saml:AuthnContext');
			$authncontext_ref = $this->xml->createElement('saml:AuthnContextClassRef','urn:oasis:names:tc:SAML:2.0:ac:classes:PasswordProtectedTransport');
			$authncontext->appendChild($authncontext_ref);
			$authnstat->appendChild($authncontext);
			
			return $authnstat;
		}
		
		function generateUniqueID($length) {
			$chars = "abcdef0123456789";
			$chars_len = strlen($chars);
			$uniqueID = "";
			for ($i = 0; $i < $length; $i++)
				$uniqueID .= substr($chars,rand(0,15),1);
			return 'a'.$uniqueID;
		}

	function licenseCheck() {
			
				$UserInCount= 0;
				 global $base_url;
				
				$connection = \Drupal::database();
				$query = $connection->query("SELECT UserIn FROM miniorange_saml_idp_user where mail = '$this->email'");
				
				$UserIn = $query->fetchAssoc();

				
				$customer = new MiniorangeSAMLCustomer(NULL, NULL, NULL, NULL);
						
			 	
					$UserCount =\Drupal::config('miniorange_saml_idp.settings')->get('miniOrange_saml_idp_user_count');
				
					$expire =\Drupal::config('miniorange_saml_idp.settings')->get('miniOrange_saml_idp_l_exp');
					$te_count = \Drupal::config('miniorange_saml_idp.settings')->get('te_count');
					$ue_count =  \Drupal::config('miniorange_saml_idp.settings')->get('ue_count');
					$dcheck = \Drupal::config('miniorange_saml_idp.settings')->get('dcheck');
					$tmp_exp = \Drupal::config('miniorange_saml_idp.settings')->get('tmp_exp');
					
						
					$connection = \Drupal::database();
					$query = $connection->query("SELECT count(*) as count FROM miniorange_saml_idp_user where UserIn=1");
					$result = $query->fetchAll();

					$UserInCount= $result[0]->count ;
				
				
				if($UserIn){
					
					return;
					}
		
				else if($UserInCount>=$UserCount)
				{
					
					$day_diff=abs($tmp_exp - time())/60/60/24;
					
					if($day_diff!=$dcheck)
					{	
					   if(Utilities::checkupdate($UserCount))				
						{
							
							$ue_count=0;

							\Drupal::configFactory()->getEditable('miniorange_saml_idp.settings')->set('ue_count', $ue_count)->save();
							
							$connection = \Drupal::database();
							$query = $connection->query("SELECT count(*) FROM miniorange_saml_idp_user where mail = '$this->email'");
				
							$userExists= $query->fetchAll();
				 
				 				if($userExists > 0){
								
						        $connection = \Drupal::database();
  					            $update  = $connection->update('miniorange_saml_idp_user') 
								->fields([
    										'UserIn' => 1,
 										 ])
 								 ->condition('mail', $this->email,'=')
  								->execute();
							
							     }else{

									$connection = \Drupal::database();
									$result = $connection->insert('miniorange_saml_idp_user')
  										->fields([
   											  'mail' => $this->email,
								  			  'UserIn' => 1,
  												])
 										 ->execute();				
							   }
						}
						
					\Drupal::configFactory()->getEditable('miniorange_saml_idp.settings')->set('dcheck', $day_diff)->save();
						
					}
					if(time()<$tmp_exp){
						
						if(time()>$tmp_exp-1296000 && $ue_count==0) {
							
							Utilities::limitmid($UserCount);							// 15 days after limit
							$ue_count++;
							\Drupal::configFactory()->getEditable('miniorange_saml_idp.settings')->set('ue_count', $ue_count)->save();
							
						}

						return;
					}
					else{
						
						if($ue_count==1) {
							Utilities::limitend($UserCount); 							// 30 days after limit
							$ue_count++;
							\Drupal::configFactory()->getEditable('miniorange_saml_idp.settings')->set('ue_count', $ue_count)->save();
														
						}
						echo("SSO Failed. Maximum limit reached.Please contact your Administrator for more details.");
						exit;
					}   
				}
		
		else {	
						$connection = \Drupal::database();
				        $query = $connection->query("SELECT count(*) as count FROM miniorange_saml_idp_user where mail = '$this->email'");
				

				        $result= $query->fetchAll();
				        $userExists= $result[0]->count ;
				 					
							if($userExists > 0){

							$connection = \Drupal::database();
				
				         $update = $connection->update('miniorange_saml_idp_user') 
				  				    ->fields([
    										'UserIn' => 1,
 										 ])
 								    ->condition('mail', $this->email,'=')
  								   ->execute();

							}else{

								$connection = \Drupal::database();
								$result = $connection->insert('miniorange_saml_idp_user')
  										->fields([
   											  'mail' => $this->email,
								  			  'UserIn' => 1,
  												])
 										 ->execute();
							}
				
				   $UserInCount = $UserInCount+1;
				 
				   $per=(floor($UserCount*(0.80)));
				   $per1=(floor($UserCount*(0.90)));
				 
					if($UserInCount == $per) {
							//send mail to customer and info@miniorange.com
							Utilities::peruser(80,$UserCount);    								// mail for 80%
					}
					
					else if($UserInCount == $per1) {
							if(Utilities::checkupdate($UserCount)) {		
								return;
							}
							else {
							//send mail to customer and info@miniorange.com
							Utilities::peruser(90,$UserCount);									// mail for 90%
							}
					}
					
					else if($UserCount-$UserInCount==10) {
							if(Utilities::checkupdate($UserCount)) {
								return;
							}
							else {
						// Send mail to customer that only 10 user left to reach the limit
							Utilities::tenuser($UserCount,$UserInCount);						// mail for 10 users left
							}
					}
						
					else if($UserInCount == $UserCount){
							
							if(Utilities::checkupdate($UserCount)) {
								return;
							}
							else {
								$dcheck=0;
								\Drupal::configFactory()->getEditable('miniorange_saml_idp.settings')->set('dcheck', $dcheck)->save();
								// 'tmp_exp',time()+2592000
								\Drupal::configFactory()->getEditable('miniorange_saml_idp.settings')->set('tmp_exp',time()+60)->save();
								
							
						    //send mail to customer and info@miniorange.com
							Utilities::limitreach($UserCount,$UserInCount);              // mail for limit reached
							}
					}
						
				}
			}
	}