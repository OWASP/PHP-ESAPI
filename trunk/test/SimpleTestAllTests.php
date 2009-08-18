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
 * @author Jeff Williams <a href="http://www.aspectsecurity.com">Aspect Security</a>
 * @package org.owasp.esapi
 * @since 2007
 */

error_reporting(E_ALL);

require_once dirname(__FILE__).'/../src/ESAPI.php';
$ESAPI = new ESAPI(dirname(__FILE__)."/testresources/ESAPI.xml");

class myGroupTest extends GroupTest {
  function myGroupTest() {
    parent::GroupTest('AllTests');
		$this->addTestFile(dirname(__FILE__).'/errors/EnterpriseSecurityExceptionTest.php');	
        $this->addTestFile(dirname(__FILE__).'/reference/AccessReferenceMapTest.php');			
        $this->addTestFile(dirname(__FILE__).'/reference/IntegerAccessReferenceMapTest.php');	
        $this->addTestFile(dirname(__FILE__).'/reference/SecurityConfigurationTest.php');		
		
		// In progress

		$this->addTestFile(dirname(__FILE__).'/reference/EncryptorTest.php');
		$this->addTestFile(dirname(__FILE__).'/reference/EncryptedPropertiesTest.php');

		
		$this->addTestFile(dirname(__FILE__).'/reference/AccessControllerTest.php');	
		$this->addTestFile(dirname(__FILE__).'/reference/AuthenticatorTest.php');		
		$this->addTestFile(dirname(__FILE__).'/reference/EncoderTest.php');				
        $this->addTestFile(dirname(__FILE__).'/reference/ValidatorTest.php'); 			
        $this->addTestFile(dirname(__FILE__).'/reference/UserTest.php'); 

		// Unallocated
		
        $this->addTestFile(dirname(__FILE__).'/reference/ExecutorTest.php');
		$this->addTestFile(dirname(__FILE__).'/reference/HTTPUtilitiesTest.php');
		$this->addTestFile(dirname(__FILE__).'/reference/LoggerTest.php');
        $this->addTestFile(dirname(__FILE__).'/reference/IntrusionDetectorTest.php');
		$this->addTestFile(dirname(__FILE__).'/reference/RandomizerTest.php');
		$this->addTestFile(dirname(__FILE__).'/reference/SafeFileTest.php');
		$this->addTestFile(dirname(__FILE__).'/reference/StringUtilitiesTest.php');
  }
}        

?>