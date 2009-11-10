<?php
/**
 * OWASP Enterprise Security API (ESAPI)
 *
 * This file is part of the Open Web Application Security Project (OWASP)
 * Enterprise Security API (ESAPI) project. For details, please see
 * http://www.owasp.org/esapi.
 *
 * Copyright (c) 2007 - The OWASP Foundation
 *
 * The ESAPI is published by OWASP under the LGPL. You should read and accept the
 * LICENSE before you use, modify, and/or redistribute this software.
 *
 * @author Andrew van der Stock < van der aj (at) owasp.org > 
 * @package org.owasp.esapi.test
 * @since 1.6
 */

error_reporting(E_ALL | ~E_STRICT); 		// TODO - Fix when SimpleTest > 1.0.1 comes out.  

require_once dirname(__FILE__).'/../src/ESAPI.php';
$ESAPI = new ESAPI(dirname(__FILE__)."/testresources/ESAPI.xml");

class myGroupTest extends GroupTest {
    function myGroupTest() {
        parent::GroupTest('AllTests');
       
        	$this->addTestFile(dirname(__FILE__).'/errors/EnterpriseSecurityExceptionTest.php');	// AJV
			$this->addTestFile(dirname(__FILE__).'/reference/RandomAccessReferenceMapTest.php');	// AJV
			$this->addTestFile(dirname(__FILE__).'/reference/IntegerAccessReferenceMapTest.php');	// AJV
			$this->addTestFile(dirname(__FILE__).'/reference/SecurityConfigurationTest.php');		// AJV
			$this->addTestFile(dirname(__FILE__).'/reference/RandomizerTest.php');					// AJV
			$this->addTestFile(dirname(__FILE__).'/reference/StringUtilitiesTest.php');				// AJV
			
			// In progress
			
			$this->addTestFile(dirname(__FILE__).'/reference/EncryptorTest.php');					// AJV
			$this->addTestFile(dirname(__FILE__).'/reference/EncryptedPropertiesTest.php'); 		// AJV
			$this->addTestFile(dirname(__FILE__).'/reference/IntrusionDetectorTest.php');			// Aung Khant
			$this->addTestFile(dirname(__FILE__).'/reference/AuthenticatorTest.php');				// Bipin
			$this->addTestFile(dirname(__FILE__).'/reference/ValidatorTest.php'); 					// Johannes Ullrich
			$this->addTestFile(dirname(__FILE__).'/reference/LoggerTest.php'); 						// Laura
			$this->addTestFile(dirname(__FILE__).'/reference/ExecutorTest.php');					// Laura
			$this->addTestFile(dirname(__FILE__).'/reference/HTTPUtilitiesTest.php');				// Laura
			$this->addTestFile(dirname(__FILE__).'/reference/EncoderTest.php');						// Linden
			$this->addTestFile(dirname(__FILE__).'/codecs/Base64CodecTest.php');
			$this->addTestFile(dirname(__FILE__).'/codecs/CSSCodecTest.php');
			$this->addTestFile(dirname(__FILE__).'/codecs/HTMLEntityCodecTest.php');
			$this->addTestFile(dirname(__FILE__).'/codecs/JavaScriptCodecTest.php');
			$this->addTestFile(dirname(__FILE__).'/codecs/LDAPCodecTest.php');
			$this->addTestFile(dirname(__FILE__).'/codecs/MySQLCodecTest.php');
			$this->addTestFile(dirname(__FILE__).'/codecs/OracleCodecTest.php');
			$this->addTestFile(dirname(__FILE__).'/codecs/PercentCodecTest.php');
			$this->addTestFile(dirname(__FILE__).'/codecs/UnixCodecTest.php');
			$this->addTestFile(dirname(__FILE__).'/codecs/VBScriptCodecTest.php');
			$this->addTestFile(dirname(__FILE__).'/codecs/WindowsCodecTest.php');
			
			// Unallocated
			
			$this->addTestFile(dirname(__FILE__).'/reference/AccessControllerTest.php');	
			$this->addTestFile(dirname(__FILE__).'/reference/UserTest.php');				
			$this->addTestFile(dirname(__FILE__).'/reference/SafeFileTest.php');
        
    }
}        

?>