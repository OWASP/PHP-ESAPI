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

require_once('../lib/simpletest/simpletest/trunk/unit_tester.php');
require_once('../lib/simpletest/simpletest/trunk/reporter.php');

require_once('../src/org.owasp.esapi.ESAPI.php');

$ESAPI = new ESAPI();

// Reset the users file for the tests.
$data = array();
$data[] = "# This is the user file associated with the ESAPI library from http://www.owasp.org";
$data[] = "# accountName | hashedPassword | roles | locked | enabled | rememberToken | csrfToken | oldPasswordHashes | lastPasswordChangeTime | lastLoginTime | lastFailedLoginTime | expirationTime | failedLoginCount";
$data[] = "";
file_put_contents($ESAPI->SecurityConfiguration()->getResourceDirectory() . 'users.txt', $data);

$test = &new GroupTest('All tests');

        $test->addTestFile('LoggerTest.php');
        $test->addTestFile('UserTest.php');
        $test->addTestFile('RandomizerTest.php');
        $test->addTestFile('AccessControllerTest.php');
        $test->addTestFile('HTTPUtilitiesTest.php');
        $test->addTestFile('ValidatorTest.php');
        $test->addTestFile('EncryptorTest.php');
        $test->addTestFile('IntrusionDetectorTest.php');
        $test->addTestFile('AccessReferenceMapTest.php');
        $test->addTestFile('ExecutorTest.php');
        $test->addTestFile('EncoderTest.php');
        $test->addTestFile('EncryptedPropertiesTest.php');
        $test->addTestFile('AuthenticatorTest.php');
        $test->addTestFile('EnterpriseSecurityExceptionTest.php');
        
$test->run(new HtmlReporter());

?>