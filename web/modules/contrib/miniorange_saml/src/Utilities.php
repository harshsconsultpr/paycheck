<?php
namespace Drupal\miniorange_saml;

use DOMElement;
use DOMNode;
use DOMXPath;
use DOMDocument;

class Utilities {

    public static function isCurlInstalled() {
      if (in_array('curl', get_loaded_extensions())) {
        return 1;
      }
      else {
        return 0;
      }
    }
	
	public static function generateID() {
		return '_' . self::stringToHex(self::generateRandomBytes(21));
	}
	
	public static function stringToHex($bytes) {
		$ret = '';
		for($i = 0; $i < strlen($bytes); $i++) {
			$ret .= sprintf('%02x', ord($bytes[$i]));
		}
		return $ret;
	}
	
	public static function generateRandomBytes($length, $fallback = TRUE) {
		return openssl_random_pseudo_bytes($length);
	}
	
	public static function insertSignature(
        XMLSecurityKey $key,
        array $certificates,
        DOMElement $root = NULL,
        DOMNode $insertBefore = NULL
    ) {
        $objXMLSecDSig = new XMLSecurityDSig();
        $objXMLSecDSig->setCanonicalMethod(XMLSecurityDSig::EXC_C14N);

        switch ($key->type) {
            case XMLSecurityKey::RSA_SHA256:
                $type = XMLSecurityDSig::SHA256;
                break;
            case XMLSecurityKey::RSA_SHA384:
                $type = XMLSecurityDSig::SHA384;
                break;
            case XMLSecurityKey::RSA_SHA512:
                $type = XMLSecurityDSig::SHA512;
                break;
            default:
                $type = XMLSecurityDSig::SHA1;
        }

        $objXMLSecDSig->addReferenceList(
            array($root),
            $type,
            array('http://www.w3.org/2000/09/xmldsig#enveloped-signature', XMLSecurityDSig::EXC_C14N),
            array('id_name' => 'ID', 'overwrite' => FALSE)
        );

        $objXMLSecDSig->sign($key);

        foreach ($certificates as $certificate) {
            $objXMLSecDSig->add509Cert($certificate, TRUE);
        }

        $objXMLSecDSig->insertSignature($root, $insertBefore);
    }
	
	public static function postSAMLRequest($url, $samlRequestXML, $relayState) {
		echo "<html><head><script src='https://code.jquery.com/jquery-1.11.3.min.js'></script><script type=\"text/javascript\">$(function(){document.forms['saml-request-form'].submit();});</script></head><body>Please wait...<form action=\"" . $url . "\" method=\"post\" id=\"saml-request-form\"><input type=\"hidden\" name=\"SAMLRequest\" value=\"" . $samlRequestXML . "\" /><input type=\"hidden\" name=\"RelayState\" value=\"" . htmlentities($relayState) . "\" /></form></body></html>";
		exit;
	}
	
	public static function createAuthnRequest($acsUrl, $issuer, $nameid_format, $destination, $force_authn = 'false', $sso_binding_type) {
		$requestXmlStr = '<?xml version="1.0" encoding="UTF-8"?>' .
						'<samlp:AuthnRequest xmlns:samlp="urn:oasis:names:tc:SAML:2.0:protocol" ID="' . self::generateID() . 
						'" Version="2.0" IssueInstant="' . self::generateTimestamp() . '"';
		if( $force_authn == 'true') {
			$requestXmlStr .= ' ForceAuthn="true"';
		}
		$requestXmlStr .= ' ProtocolBinding="urn:oasis:names:tc:SAML:2.0:bindings:HTTP-POST" AssertionConsumerServiceURL="' . $acsUrl . 
						'" Destination="' . $destination . '"><saml:Issuer xmlns:saml="urn:oasis:names:tc:SAML:2.0:assertion">' . $issuer . '</saml:Issuer><samlp:NameIDPolicy AllowCreate="true" Format="'.$nameid_format.'"
                        /></samlp:AuthnRequest>';
		
		if(empty($sso_binding_type) || $sso_binding_type == 'HTTP-Redirect') {
		
		$deflatedStr = gzdeflate($requestXmlStr);
		$base64EncodedStr = base64_encode($deflatedStr);
		$urlEncoded = urlencode($base64EncodedStr);
		$requestXmlStr = $urlEncoded;
		}
		
		return $requestXmlStr;
	}
	
	
	public static function mo_saml_logout() {
		
		$logout_url = \Drupal::config('miniorange_saml.settings')->get("miniorange_saml_idp_logout_url");
				
		$logout_binding_type = '';
		if( !empty($logout_url) )  {
			if( ! session_id() || session_id() == '' || !isset($_SESSION) ) {
				session_start();
			}
			if(isset($_SESSION['mo_saml_logout_request'])) {
				//Utilities::createLogoutResponseAndRedirect($logout_url, $logout_binding_type);

			} elseif (isset($_SESSION['mo_saml']['logged_in_with_idp'])) {
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
				} 
			}
		}
	
	}
	
	public static function signXML($xml, $publicCertPath, $privateKeyPath, $insertBeforeTagName = "") {
					
		$param =array( 'type' => 'private');
		$key = new XMLSecurityKey(XMLSecurityKey::RSA_SHA256, $param);
		$key->loadKey($privateKeyPath, TRUE);
		$publicCertificate = file_get_contents( $publicCertPath );
		$document = new DOMDocument();
		
		$document->loadXML($xml);
		$element = $document->firstChild;
		if( !empty($insertBeforeTagName) ) {
			$domNode = $document->getElementsByTagName( $insertBeforeTagName )->item(0);
			
			self::insertSignature($key, array ( $publicCertificate ), $element, $domNode);
		} else {
			self::insertSignature($key, array ( $publicCertificate ), $element);
		}
		$requestXML = $element->ownerDocument->saveXML($element);
		$base64EncodedXML = base64_encode($requestXML);
		return $base64EncodedXML;
	}
	
	public static function createLogoutRequest($nameId, $sessionIndex = '', $issuer, $destination, $slo_binding_type = 'HttpRedirect'){
		
		$requestXmlStr='<?xml version="1.0" encoding="UTF-8"?>' .
						'<samlp:LogoutRequest xmlns:samlp="urn:oasis:names:tc:SAML:2.0:protocol" xmlns:saml="urn:oasis:names:tc:SAML:2.0:assertion" ID="'. self::generateID() .
						'" IssueInstant="' . self::generateTimestamp() .
						'" Version="2.0" Destination="'. $destination . '">
						<saml:Issuer xmlns:saml="urn:oasis:names:tc:SAML:2.0:assertion">' . $issuer . '</saml:Issuer>
						<saml:NameID xmlns:saml="urn:oasis:names:tc:SAML:2.0:assertion">'. $nameId . '</saml:NameID>';
		if(!empty($sessionIndex)) {
			$requestXmlStr .= '<samlp:SessionIndex>' . $sessionIndex . '</samlp:SessionIndex>';
		}
		$requestXmlStr .= '</samlp:LogoutRequest>';
				
		if(empty($slo_binding_type) || $slo_binding_type == 'HttpRedirect') {
			$deflatedStr = gzdeflate($requestXmlStr);
			$base64EncodedStr = base64_encode($deflatedStr);
			$urlEncoded = urlencode($base64EncodedStr);
			$requestXmlStr = $urlEncoded;
		}
		return $requestXmlStr;
	}
	
	public static function generateTimestamp($instant = NULL) {
		if($instant === NULL) {
			$instant = time();
		}
		return gmdate('Y-m-d\TH:i:s\Z', $instant);
	}
	
	public static function xpQuery(DOMNode $node, $query)
    {
        static $xpCache = NULL;

        if ($node instanceof DOMDocument) {
            $doc = $node;
        } else {
            $doc = $node->ownerDocument;
        }

        if ($xpCache === NULL || !$xpCache->document->isSameNode($doc)) {
            $xpCache = new DOMXPath($doc);
            $xpCache->registerNamespace('soap-env', 'http://schemas.xmlsoap.org/soap/envelope/');
            $xpCache->registerNamespace('saml_protocol', 'urn:oasis:names:tc:SAML:2.0:protocol');
            $xpCache->registerNamespace('saml_assertion', 'urn:oasis:names:tc:SAML:2.0:assertion');
            $xpCache->registerNamespace('saml_metadata', 'urn:oasis:names:tc:SAML:2.0:metadata');
            $xpCache->registerNamespace('ds', 'http://www.w3.org/2000/09/xmldsig#');
            $xpCache->registerNamespace('xenc', 'http://www.w3.org/2001/04/xmlenc#');
        }

        $results = $xpCache->query($query, $node);
        $ret = array();
        for ($i = 0; $i < $results->length; $i++) {
            $ret[$i] = $results->item($i);
        }

		return $ret;
    }
	
	public static function parseNameId(DOMElement $xml)
    {
        $ret = array('Value' => trim($xml->textContent));

        foreach (array('NameQualifier', 'SPNameQualifier', 'Format') as $attr) {
            if ($xml->hasAttribute($attr)) {
                $ret[$attr] = $xml->getAttribute($attr);
            }
        }

        return $ret;
    }
	
	public static function xsDateTimeToTimestamp($time)
    {
        $matches = array();

        // We use a very strict regex to parse the timestamp.
        $regex = '/^(\\d\\d\\d\\d)-(\\d\\d)-(\\d\\d)T(\\d\\d):(\\d\\d):(\\d\\d)(?:\\.\\d+)?Z$/D';
        if (preg_match($regex, $time, $matches) == 0) {
            echo sprintf("nvalid SAML2 timestamp passed to xsDateTimeToTimestamp: ".$time);
            exit;
        }

        // Extract the different components of the time from the  matches in the regex.
        // intval will ignore leading zeroes in the string.
        $year   = intval($matches[1]);
        $month  = intval($matches[2]);
        $day    = intval($matches[3]);
        $hour   = intval($matches[4]);
        $minute = intval($matches[5]);
        $second = intval($matches[6]);

        // We use gmmktime because the timestamp will always be given
        //in UTC.
        $ts = gmmktime($hour, $minute, $second, $month, $day, $year);

        return $ts;
    }
	
	public static function extractStrings(DOMElement $parent, $namespaceURI, $localName)
    {
        
        $ret = array();
        for ($node = $parent->firstChild; $node !== NULL; $node = $node->nextSibling) {
            if ($node->namespaceURI !== $namespaceURI || $node->localName !== $localName) {
                continue;
            }
            $ret[] = trim($node->textContent);
        }

        return $ret;
    }
	
	public static function validateElement(DOMElement $root)
    {
    	
        /* Create an XML security object. */
        $objXMLSecDSig = new XMLSecurityDSig();

        /* Both SAML messages and SAML assertions use the 'ID' attribute. */
        $objXMLSecDSig->idKeys[] = 'ID';
		
       
        /* Locate the XMLDSig Signature element to be used. */
        $signatureElement = self::xpQuery($root, './ds:Signature');
        
        if (count($signatureElement) === 0) {
            /* We don't have a signature element to validate. */
            return FALSE;
        } elseif (count($signatureElement) > 1) {
        	echo sprintf("XMLSec: more than one signature element in root.");
        	exit;
        }/*  elseif ((in_array('Response', $signatureElement) && $ocurrence['Response'] > 1) ||
            (in_array('Assertion', $signatureElement) && $ocurrence['Assertion'] > 1) ||
            !in_array('Response', $signatureElement) && !in_array('Assertion', $signatureElement)
        ) {
            return false;
        } */
       
        $signatureElement = $signatureElement[0];
        $objXMLSecDSig->sigNode = $signatureElement;
		
        /* Canonicalize the XMLDSig SignedInfo element in the message. */
        $objXMLSecDSig->canonicalizeSignedInfo();
		
       /* Validate referenced xml nodes. */
        if (!$objXMLSecDSig->validateReference()) { 
        	echo sprintf("XMLsec: digest validation failed");
			exit;
        }
		
		/* Check that $root is one of the signed nodes. */
        $rootSigned = FALSE;
        /** @var DOMNode $signedNode */
        foreach ($objXMLSecDSig->getValidatedNodes() as $signedNode) {
            if ($signedNode->isSameNode($root)) {
                $rootSigned = TRUE;
                break;
            } elseif ($root->parentNode instanceof DOMElement && $signedNode->isSameNode($root->ownerDocument)) {
                /* $root is the root element of a signed document. */
                $rootSigned = TRUE;
                break;
            }
        }
		
		if (!$rootSigned) {
			echo sprintf("XMLSec: The root element is not signed.");
			exit;
        }

        /* Now we extract all available X509 certificates in the signature element. */
        $certificates = array();
        foreach (self::xpQuery($signatureElement, './ds:KeyInfo/ds:X509Data/ds:X509Certificate') as $certNode) {
            $certData = trim($certNode->textContent);
            $certData = str_replace(array("\r", "\n", "\t", ' '), '', $certData);
            $certificates[] = $certData;
			//echo "CertDate: " . $certData . "<br />";
        }
	
        $ret = array(
            'Signature' => $objXMLSecDSig,
            'Certificates' => $certificates,
            );
					
        return $ret;
    }
	

	
	public static function validateSignature(array $info, XMLSecurityKey $key)
    {
        
        /** @var XMLSecurityDSig $objXMLSecDSig */
        $objXMLSecDSig = $info['Signature'];

        $sigMethod = self::xpQuery($objXMLSecDSig->sigNode, './ds:SignedInfo/ds:SignatureMethod');
        if (empty($sigMethod)) {
            echo sprintf('Missing SignatureMethod element');
            exit();
        }
        $sigMethod = $sigMethod[0];
        if (!$sigMethod->hasAttribute('Algorithm')) {
            echo sprintf('Missing Algorithm-attribute on SignatureMethod element.');
            exit;
        }
        $algo = $sigMethod->getAttribute('Algorithm');

        if ($key->type === XMLSecurityKey::RSA_SHA1 && $algo !== $key->type) {
            $key = self::castKey($key, $algo);
        }
		
        /* Check the signature. */
        if (! $objXMLSecDSig->verify($key)) {
        	echo sprintf('Unable to validate Sgnature');
        	exit;
        }
    }
	
    public static function castKey(XMLSecurityKey $key, $algorithm, $type = 'public')
    {
    	
    	// do nothing if algorithm is already the type of the key
    	if ($key->type === $algorithm) {
    		return $key;
    	}
    
    	$keyInfo = openssl_pkey_get_details($key->key);
    	if ($keyInfo === FALSE) {
    		echo sprintf('Unable to get key details from XMLSecurityKey.');
    		exit;
    	}
    	if (!isset($keyInfo['key'])) {
    		echo sprintf('Missing key in public key details.');
    		exit;
    	}
    
    	$newKey = new XMLSecurityKey($algorithm, array('type'=>$type));
    	$newKey->loadKey($keyInfo['key']);
    
    	return $newKey;
    }
    
	public static function processResponse($currentURL, $certFingerprint, $signatureData, SAML2_Response $response)
    {
		$ResCert = $signatureData['Certificates'][0];		
		/* Validate Response-element destination. */
		$msgDestination = $response->getDestination();
		if ($msgDestination !== NULL && $msgDestination !== $currentURL) {
			echo sprintf('Destination in response doesn\'t match the current URL. Destination is "' .
				$msgDestination . '", current URL is "' . $currentURL . '".');
			exit;
		}
		
		$responseSigned = self::checkSign($certFingerprint, $signatureData, $ResCert);
		
		/* Returning boolean $responseSigned */
		return $responseSigned;
	}
	
	public static function checkSign($certFingerprint, $signatureData, $ResCert) {
		$certificates = $signatureData['Certificates'];	

		if (count($certificates) === 0) {
			return FALSE;
		} else{
			$fpArray = array();
			$fpArray[] = $certFingerprint;
			$pemCert = self::findCertificate($fpArray, $certificates, $ResCert);
		}
		
		$lastException = NULL;
		
		$key = new XMLSecurityKey(XMLSecurityKey::RSA_SHA1, array('type'=>'public'));
		$key->loadKey($pemCert);
				
		try {
			/*
			 * Make sure that we have a valid signature
			 */
			self::validateSignature($signatureData, $key);			
			return TRUE;
		} catch (Exception $e) {
			$lastException = $e;
		}
		
		
		/* We were unable to validate the signature with any of our keys. */
		if ($lastException !== NULL) {
			throw $lastException;
		} else {
			return FALSE;
		}
	
	}
	
	public static function validateIssuerAndAudience($samlResponse, $spEntityId, $issuerToValidateAgainst, $base_url) {
		$issuer = current($samlResponse->getAssertions())->getIssuer();
		$audience = current(current($samlResponse->getAssertions())->getValidAudiences());
		if(strcmp($issuerToValidateAgainst, $issuer) === 0) {
			if(strcmp($audience, $spEntityId) === 0) {
				return TRUE;
			} else {
				if (array_key_exists ( 'RelayState', $_REQUEST ) && ($_REQUEST['RelayState'] == 'testValidate')) {
				    echo '<div style="font-family:Calibri;padding:0 3%;">';
                    echo '<div style="color: #a94442;background-color: #f2dede;padding: 15px;margin-bottom: 20px;text-align:center;border:1px solid #E6B3B2;font-size:18pt;"> ERROR</div>
                    <div style="color: #a94442;font-size:14pt; margin-bottom:20px;"><p><strong>Error: </strong>Invalid Audience URI.</p>
                    <p>Please contact your administrator and report the following error:</p>
                    <p><strong>Possible Cause: </strong>The value of \'Audience URI\' field on Identity Provider\'s side is incorrect</p>
                    <p>Expected one of the Audiences to be: '.$spEntityId.'<p>
                    </div>
                    <div style="margin:3%;display:block;text-align:center;">
                    <div style="margin:3%;display:block;text-align:center;"><input style="padding:1%;width:100px;background: #0091CD none repeat scroll 0% 0%;cursor: pointer;font-size:15px;border-width: 1px;border-style: solid;border-radius: 3px;white-space: nowrap;box-sizing: border-box;border-color: #0073AA;box-shadow: 0px 1px 0px rgba(120, 200, 230, 0.6) inset;color: #FFF;"type="button" value="Done" onClick="self.close();"></div>';
				}else{
					echo '<div style="color: #a94442;font-size:14pt; margin-bottom:20px;"><p><b>Error: </b>We could not sign you in. Please contact your Administrator.</p></div>';
				}
				exit;
			}
		} else {
			if (array_key_exists ( 'RelayState', $_REQUEST ) && ($_REQUEST['RelayState'] == 'testValidate')) {
				echo '<div style="font-family:Calibri;padding:0 3%;">';
                echo '<div style="color: #a94442;background-color: #f2dede;padding: 15px;margin-bottom: 20px;text-align:center;border:1px solid #E6B3B2;font-size:18pt;"> ERROR</div>
					<div style="color: #a94442;font-size:14pt; margin-bottom:20px;"><p><strong>Error: </strong>Issuer cannot be verified.</p>
					<p>Please contact your administrator and report the following error:</p>
					<p><strong>Possible Cause: </strong>The value in \'IdP Entity ID or Issuer\' field in Service Provider Settings is incorrect</p>
					<p><strong>Expected Entity ID: </strong>'.$issuer.'<p>
					<p><strong>Entity ID Found: </strong>'.$issuerToValidateAgainst.'</p>
					</div>
					<div style="margin:3%;display:block;text-align:center;">
					<div style="margin:3%;display:block;text-align:center;"><input style="padding:1%;width:100px;background: #0091CD none repeat scroll 0% 0%;cursor: pointer;font-size:15px;border-width: 1px;border-style: solid;border-radius: 3px;white-space: nowrap;box-sizing: border-box;border-color: #0073AA;box-shadow: 0px 1px 0px rgba(120, 200, 230, 0.6) inset;color: #FFF;"type="button" value="Done" onClick="self.close();"></div>';
			}else{
				echo '<div style="color: #a94442;font-size:14pt; margin-bottom:20px;"><p><b>Error: </b>We could not sign you in. Please contact your Administrator.</p></div>';
			}
			exit;
		}
	}
	
	private static function findCertificate(array $certFingerprints, array $certificates, $ResCert) {

		$candidates = array();
		
		foreach ($certificates as $cert) {
			$fp = strtolower(sha1(base64_decode($cert)));
			if (!in_array($fp, $certFingerprints, TRUE)) {
				$candidates[] = $fp;
				continue;
			}

			/* We have found a matching fingerprint. */
			$pem = "-----BEGIN CERTIFICATE-----\n" .
				chunk_split($cert, 64) .
				"-----END CERTIFICATE-----\n";
			
			return $pem;
		}
		if (array_key_exists ( 'RelayState', $_REQUEST ) && ($_REQUEST['RelayState'] == 'testValidate')) {
			echo '<div style="font-family:Calibri;padding:0 3%;">';
			echo '<div style="color: #a94442;background-color: #f2dede;padding: 15px;margin-bottom: 20px;text-align:center;border:1px solid #E6B3B2;font-size:18pt;"> ERROR</div>
				<div style="color: #a94442;font-size:14pt; margin-bottom:20px;"><p><strong>Error: </strong>Unable to find a certificate matching the configured fingerprint.</p>
				<p><strong>Possible Cause: </strong>Content of \'X.509 Certificate\' field in Service Provider Settings is incorrect</p>
				<p><b>Expected value:</b>' . $ResCert . '</p>';
			echo str_repeat('&nbsp;', 15);
			echo'</div>
				<div style="margin:3%;display:block;text-align:center;">
				<form action="index.php">
				<div style="margin:3%;display:block;text-align:center;"><input style="padding:1%;width:100px;background: #0091CD none repeat scroll 0% 0%;cursor: pointer;font-size:15px;border-width: 1px;border-style: solid;border-radius: 3px;white-space: nowrap;box-sizing: border-box;border-color: #0073AA;box-shadow: 0px 1px 0px rgba(120, 200, 230, 0.6) inset;color: #FFF;"type="button" value="Done" onClick="self.close();"></div>';
		} else {
			echo ' <div style="color: #a94442;font-size:14pt; margin-bottom:20px;"><p><b>Error: </b>We could not sign you in. Please contact your Administrator.</p></div>';		
		}
		exit;
	}
	
	  /**
     * Decrypt an encrypted element.
     *
     * This is an internal helper function.
     *
     * @param  DOMElement     $encryptedData The encrypted data.
     * @param  XMLSecurityKey $inputKey      The decryption key.
     * @param  array          &$blacklist    Blacklisted decryption algorithms.
     * @return DOMElement     The decrypted element.
     * @throws Exception
     */
    private static function doDecryptElement(DOMElement $encryptedData, XMLSecurityKey $inputKey, array &$blacklist)
    {
        $enc = new XMLSecEnc();
        $enc->setNode($encryptedData);

        $enc->type = $encryptedData->getAttribute("Type");
        $symmetricKey = $enc->locateKey($encryptedData);

        if (!$symmetricKey) {
        	echo sprintf('Could not locate key algorithm in encrypted data.');
        	exit;
        }

        $symmetricKeyInfo = $enc->locateKeyInfo($symmetricKey);
        if (!$symmetricKeyInfo) {
			echo sprintf('Could not locate <dsig:KeyInfo> for the encrypted key.');
			exit;
        }
        $inputKeyAlgo = $inputKey->getAlgorith();
        if ($symmetricKeyInfo->isEncrypted) {
            $symKeyInfoAlgo = $symmetricKeyInfo->getAlgorith();
            if (in_array($symKeyInfoAlgo, $blacklist, TRUE)) {
                echo sprintf('Algorithm disabled: ' . var_export($symKeyInfoAlgo, TRUE));
                exit;
            }
            if ($symKeyInfoAlgo === XMLSecurityKey::RSA_OAEP_MGF1P && $inputKeyAlgo === XMLSecurityKey::RSA_1_5) {
                /*
                 * The RSA key formats are equal, so loading an RSA_1_5 key
                 * into an RSA_OAEP_MGF1P key can be done without problems.
                 * We therefore pretend that the input key is an
                 * RSA_OAEP_MGF1P key.
                 */
                $inputKeyAlgo = XMLSecurityKey::RSA_OAEP_MGF1P;
            }
            /* Make sure that the input key format is the same as the one used to encrypt the key. */
            if ($inputKeyAlgo !== $symKeyInfoAlgo) {
                echo sprintf( 'Algorithm mismatch between input key and key used to encrypt ' .
                    ' the symmetric key for the message. Key was: ' .
                    var_export($inputKeyAlgo, TRUE) . '; message was: ' .
                    var_export($symKeyInfoAlgo, TRUE));
                exit;
            }
            /** @var XMLSecEnc $encKey */
            $encKey = $symmetricKeyInfo->encryptedCtx;
            $symmetricKeyInfo->key = $inputKey->key;
            $keySize = $symmetricKey->getSymmetricKeySize();
            if ($keySize === NULL) {
                /* To protect against "key oracle" attacks, we need to be able to create a
                 * symmetric key, and for that we need to know the key size.
                 */
				echo sprintf('Unknown key size for encryption algorithm: ' . var_export($symmetricKey->type, TRUE));
				exit;
            }
            try {
                $key = $encKey->decryptKey($symmetricKeyInfo);
                if (strlen($key) != $keySize) {
                	echo sprintf('Unexpected key size (' . strlen($key) * 8 . 'bits) for encryption algorithm: ' .
                        var_export($symmetricKey->type, TRUE));
                	exit;
                }
            } catch (Exception $e) {
                /* We failed to decrypt this key. Log it, and substitute a "random" key. */

                /* Create a replacement key, so that it looks like we fail in the same way as if the key was correctly padded. */
                /* We base the symmetric key on the encrypted key and private key, so that we always behave the
                 * same way for a given input key.
                 */
                $encryptedKey = $encKey->getCipherValue();
                $pkey = openssl_pkey_get_details($symmetricKeyInfo->key);
                $pkey = sha1(serialize($pkey), TRUE);
                $key = sha1($encryptedKey . $pkey, TRUE);
                /* Make sure that the key has the correct length. */
                if (strlen($key) > $keySize) {
                    $key = substr($key, 0, $keySize);
                } elseif (strlen($key) < $keySize) {
                    $key = str_pad($key, $keySize);
                }
            }
            $symmetricKey->loadkey($key);
        } else {
            $symKeyAlgo = $symmetricKey->getAlgorith();
            /* Make sure that the input key has the correct format. */
            if ($inputKeyAlgo !== $symKeyAlgo) {
            	echo sprintf( 'Algorithm mismatch between input key and key in message. ' .
                    'Key was: ' . var_export($inputKeyAlgo, TRUE) . '; message was: ' .
                    var_export($symKeyAlgo, TRUE));
            	exit;
            }
            $symmetricKey = $inputKey;
        }
        $algorithm = $symmetricKey->getAlgorith();
        if (in_array($algorithm, $blacklist, TRUE)) {
            echo sprintf('Algorithm disabled: ' . var_export($algorithm, TRUE));
            exit;
        }
        /** @var string $decrypted */
        $decrypted = $enc->decryptNode($symmetricKey, FALSE);
        /*
         * This is a workaround for the case where only a subset of the XML
         * tree was serialized for encryption. In that case, we may miss the
         * namespaces needed to parse the XML.
         */
        $xml = '<root xmlns:saml="urn:oasis:names:tc:SAML:2.0:assertion" '.
                     'xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">' .
            $decrypted .
            '</root>';
        $newDoc = new DOMDocument();
        if (!@$newDoc->loadXML($xml)) {
        	echo sprintf('Failed to parse decrypted XML. Maybe the wrong sharedkey was used?');
        	throw new Exception('Failed to parse decrypted XML. Maybe the wrong sharedkey was used?');
        }
        $decryptedElement = $newDoc->firstChild->firstChild;
        if ($decryptedElement === NULL) {
        	echo sprintf('Missing encrypted element.');
        	throw new Exception('Missing encrypted element.');
        }

        if (!($decryptedElement instanceof DOMElement)) {
        	echo sprintf('Decrypted element was not actually a DOMElement.');
        }

        return $decryptedElement;
    }
	
    /**
     * Decrypt an encrypted element.
     *
     * @param  DOMElement     $encryptedData The encrypted data.
     * @param  XMLSecurityKey $inputKey      The decryption key.
     * @param  array          $blacklist     Blacklisted decryption algorithms.
     * @return DOMElement     The decrypted element.
     * @throws Exception
     */
    public static function decryptElement(DOMElement $encryptedData, XMLSecurityKey $inputKey, array $blacklist = array(), XMLSecurityKey $alternateKey = NULL)
    {
        try {
        	
            return self::doDecryptElement($encryptedData, $inputKey, $blacklist);
        } catch (Exception $e) {
        	//Try with alternate key
        	try {
        		return self::doDecryptElement($encryptedData, $alternateKey, $blacklist);
        	} catch(Exception $t) {

        	}
        	/*
        	 * Something went wrong during decryption, but for security
        	 * reasons we cannot tell the user what failed.
        	 */
        	echo sprintf('Failed to decrypt XML element.');
        	exit;
        }
    }
	
	 /**
     * Generates the metadata of the SP based on the settings
     * @return string SAML Metadata XML
     */
    public static function metadata_builder($siteUrl)
    {
		$xml = new DOMDocument();
		$url = plugins_url().'/miniorange-saml-20-single-sign-on/sp-metadata.xml';
		
		$xml->load($url);
		
		$xpath = new DOMXPath($xml);
		$elements = $xpath->query('//md:EntityDescriptor[@entityID="http://{path-to-your-site}/wp-content/plugins/miniorange-saml-20-single-sign-on/"]');
		
		 if ($elements->length >= 1) {
		    $element = $elements->item(0);
		    $element->setAttribute('entityID', $siteUrl.'/wp-content/plugins/miniorange-saml-20-single-sign-on/');
		}
		
		$elements = $xpath->query('//md:AssertionConsumerService[@Location="http://{path-to-your-site}"]');
		if ($elements->length >= 1) {
		    $element = $elements->item(0);
		    $element->setAttribute('Location', $siteUrl.'/');
		}
		 
		//re-save
		$xml->save(plugins_url()."/miniorange-saml-20-single-sign-on/sp-metadata.xml");
    }
	
	public static function get_mapped_groups($saml_params, $saml_groups)
	{
			$groups = array();

		if (!empty($saml_groups)) {
			$saml_mapped_groups = array();
			$i=1;
			while ($i < 10) {
				$saml_mapped_groups_value = $saml_params->get('group'.$i.'_map');
				
				$saml_mapped_groups[$i] = explode(';', $saml_mapped_groups_value);
				$i++;
			}
		}

		foreach ($saml_groups as $saml_group) {
			if (!empty($saml_group)) {
				$i = 0;
				$found = false;
				
				while ($i < 9 && !$found) {
					if (!empty($saml_mapped_groups[$i]) && in_array($saml_group, $saml_mapped_groups[$i])) {
						$groups[] = $saml_params->get('group'.$i);
						$found = true;
					}
					$i++;
				}
			}
		}
		
		return array_unique($groups);
	}


	public static function getEncryptionAlgorithm($method){
		switch($method){
			case 'http://www.w3.org/2001/04/xmlenc#tripledes-cbc':
				return XMLSecurityKey::TRIPLEDES_CBC;
				break;
			
			case 'http://www.w3.org/2001/04/xmlenc#aes128-cbc':
				return XMLSecurityKey::AES128_CBC;
				
			case 'http://www.w3.org/2001/04/xmlenc#aes192-cbc':
				return XMLSecurityKey::AES192_CBC;
				break;
			
			case 'http://www.w3.org/2001/04/xmlenc#aes256-cbc':
				return XMLSecurityKey::AES256_CBC;
				break;
				
			case 'http://www.w3.org/2001/04/xmlenc#rsa-1_5':
				return XMLSecurityKey::RSA_1_5;
				break;
			
			case 'http://www.w3.org/2001/04/xmlenc#rsa-oaep-mgf1p':
				return XMLSecurityKey::RSA_OAEP_MGF1P;
				break;
				
			case 'http://www.w3.org/2000/09/xmldsig#dsa-sha1':
				return XMLSecurityKey::DSA_SHA1;
				break;

			case 'http://www.w3.org/2000/09/xmldsig#rsa-sha1':
				return XMLSecurityKey::RSA_SHA1;
				break;
			
			case 'http://www.w3.org/2001/04/xmldsig-more#rsa-sha256':
				return XMLSecurityKey::RSA_SHA256;
				break;
				
			case 'http://www.w3.org/2001/04/xmldsig-more#rsa-sha384':
				return XMLSecurityKey::RSA_SHA384;
				break;
			
			case 'http://www.w3.org/2001/04/xmldsig-more#rsa-sha512':
				return XMLSecurityKey::RSA_SHA512;
				break;
			
			default:
				echo sprintf('Invalid Encryption Method: '.$method);
				exit;
				break;
		}
	}
	
	public static function sanitize_certificate( $certificate ) {
		$certificate = preg_replace("/[\r\n]+/", "", $certificate);
		$certificate = str_replace( "-", "", $certificate );
		$certificate = str_replace( "BEGIN CERTIFICATE", "", $certificate );
		$certificate = str_replace( "END CERTIFICATE", "", $certificate );
		$certificate = str_replace( " ", "", $certificate );
		$certificate = chunk_split($certificate, 64, "\r\n");
		$certificate = "-----BEGIN CERTIFICATE-----\r\n" . $certificate . "-----END CERTIFICATE-----";
		return $certificate;
	}
	
	public static function desanitize_certificate( $certificate ) {
		$certificate = preg_replace("/[\r\n]+/", "", $certificate);
		$certificate = str_replace( "-----BEGIN CERTIFICATE-----", "", $certificate );
		$certificate = str_replace( "-----END CERTIFICATE-----", "", $certificate );
		$certificate = str_replace( " ", "", $certificate );
		return $certificate;
	}
}
