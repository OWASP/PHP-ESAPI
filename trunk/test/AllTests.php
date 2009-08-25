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

require_once dirname(__FILE__).'/../lib/simpletest/unit_tester.php';
require_once dirname(__FILE__).'/../lib/simpletest/reporter.php';

error_reporting(E_ALL | ~E_STRICT); 		// TODO - Fix when SimpleTest > 1.0.1 comes out.  

require_once dirname(__FILE__).'/../src/ESAPI.php';

$ESAPI = new ESAPI(dirname(__FILE__)."/testresources/ESAPI.xml");

//// Reset the users file for the tests.
//$data = array();
//$data[] = "# This is the user file associated with the ESAPI library from http://www.owasp.org";
//$data[] = "# accountName | hashedPassword | roles | locked | enabled | rememberToken | csrfToken | oldPasswordHashes | lastPasswordChangeTime | lastLoginTime | lastFailedLoginTime | expirationTime | failedLoginCount";
//$data[] = "";
//file_put_contents($ESAPI->SecurityConfiguration()->getResourceDirectory() . 'users.txt', $data);

// Milestones

		$test = new GroupTest('Finished');
$test->addTestFile(dirname(__FILE__).'/errors/EnterpriseSecurityExceptionTest.php');	// AJV
$test->addTestFile(dirname(__FILE__).'/reference/AccessReferenceMapTest.php');			// AJV
$test->addTestFile(dirname(__FILE__).'/reference/IntegerAccessReferenceMapTest.php');	// AJV
$test->addTestFile(dirname(__FILE__).'/reference/SecurityConfigurationTest.php');		// AJV
$test->addTestFile(dirname(__FILE__).'/reference/RandomizerTest.php');					// AJV
$test->run(new HtmlReporter());

		$test = new GroupTest('Allocated');
$test->addTestFile(dirname(__FILE__).'/reference/AccessControllerTest.php');	// Abius X
$test->addTestFile(dirname(__FILE__).'/reference/UserTest.php');				// AbiusX

$test->addTestFile(dirname(__FILE__).'/reference/AuthenticatorTest.php');		// Bipin

$test->addTestFile(dirname(__FILE__).'/reference/EncoderTest.php');				// Johannes 2nd priority
$test->addTestFile(dirname(__FILE__).'/reference/ValidatorTest.php'); 			// Johannes 1st priority

$test->addTestFile(dirname(__FILE__).'/reference/EncryptorTest.php');			// AJV
$test->addTestFile(dirname(__FILE__).'/reference/EncryptedPropertiesTest.php'); // AJV
$test->addTestFile(dirname(__FILE__).'/reference/StringUtilitiesTest.php');		// AJV
$test->addTestFile(dirname(__FILE__).'/reference/LoggerTest.php'); //Laura

$test->run(new HtmlReporter());

$test = new GroupTest('Unallocated');

$test->addTestFile(dirname(__FILE__).'/reference/ExecutorTest.php');
$test->addTestFile(dirname(__FILE__).'/reference/HTTPUtilitiesTest.php');
$test->addTestFile(dirname(__FILE__).'/reference/IntrusionDetectorTest.php');
$test->addTestFile(dirname(__FILE__).'/reference/SafeFileTest.php');
$test->run(new HtmlReporter());
?>