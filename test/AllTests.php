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

require_once('../lib/simpletest/unit_tester.php');
require_once('../lib/simpletest/reporter.php');

error_reporting(E_ALL);

require_once('../src/ESAPI.php');

$ESAPI = new ESAPI();

//// Reset the users file for the tests.
//$data = array();
//$data[] = "# This is the user file associated with the ESAPI library from http://www.owasp.org";
//$data[] = "# accountName | hashedPassword | roles | locked | enabled | rememberToken | csrfToken | oldPasswordHashes | lastPasswordChangeTime | lastLoginTime | lastFailedLoginTime | expirationTime | failedLoginCount";
//$data[] = "";
//file_put_contents($ESAPI->SecurityConfiguration()->getResourceDirectory() . 'users.txt', $data);

// Milestones

$test = &new GroupTest('Milestone 1');
		$test->addTestFile('errors/EnterpriseSecurityExceptionTest.php');
        $test->addTestFile('reference/AccessReferenceMapTest.php');
        $test->addTestFile('reference/IntegerAccessReferenceMapTest.php');
        $test->addTestFile('reference/EncoderTest.php');
		$test->addTestFile('reference/HTTPUtilitiesTest.php');
		$test->addTestFile('reference/StringUtilitiesTest.php');
        $test->addTestFile('reference/ValidatorTest.php');
$test->run(new HtmlReporter());
        
$test = &new GroupTest('Milestone 2');
        $test->addTestFile('reference/EncryptedPropertiesTest.php');
        $test->addTestFile('reference/EncryptorTest.php');
        $test->addTestFile('reference/ExecutorTest.php');
		$test->addTestFile('reference/LoggerTest.php');
        $test->addTestFile('reference/IntrusionDetectorTest.php');
		$test->addTestFile('reference/RandomizerTest.php');
		$test->addTestFile('reference/SafeFileTest.php');
$test->run(new HtmlReporter());

$test = &new GroupTest('Milestone 3');
		$test->addTestFile('reference/AccessControllerTest.php');
		$test->addTestFile('reference/AuthenticatorTest.php');
        $test->addTestFile('reference/UserTest.php');
$test->run(new HtmlReporter());

?>