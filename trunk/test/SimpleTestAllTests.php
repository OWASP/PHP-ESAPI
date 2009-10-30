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
        $this->addTestFile(dirname(__FILE__).'/errors/EnterpriseSecurityExceptionTest.php');
        $this->addTestFile(dirname(__FILE__).'/reference/RandomAccessReferenceMapTest.php');
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
        $this->addTestFile(dirname(__FILE__).'/reference/LoggerTest.php');

        // Codecs
        
		$test->addTestFile(dirname(__FILE__).'/codecs/Base64CodecTest.php');
		$test->addTestFile(dirname(__FILE__).'/codecs/CSSCodecTest.php');
		$test->addTestFile(dirname(__FILE__).'/codecs/HTMLEntityCodecTest.php');
		$test->addTestFile(dirname(__FILE__).'/codecs/JavaScriptCodecTest.php');
		$test->addTestFile(dirname(__FILE__).'/codecs/LDAPCodecTest.php');
		$test->addTestFile(dirname(__FILE__).'/codecs/MySQLCodecTest.php');
		$test->addTestFile(dirname(__FILE__).'/codecs/OracleCodecTest.php');
		$test->addTestFile(dirname(__FILE__).'/codecs/PercentCodecTest.php');
		$test->addTestFile(dirname(__FILE__).'/codecs/UnixCodecTest.php');
		$test->addTestFile(dirname(__FILE__).'/codecs/VBScriptCodecTest.php');
		$test->addTestFile(dirname(__FILE__).'/codecs/WindowsCodecTest.php');

        // Unallocated

        $this->addTestFile(dirname(__FILE__).'/reference/ExecutorTest.php');
        $this->addTestFile(dirname(__FILE__).'/reference/HTTPUtilitiesTest.php');
        $this->addTestFile(dirname(__FILE__).'/reference/IntrusionDetectorTest.php');
        $this->addTestFile(dirname(__FILE__).'/reference/RandomizerTest.php');
        $this->addTestFile(dirname(__FILE__).'/reference/SafeFileTest.php');
        $this->addTestFile(dirname(__FILE__).'/reference/StringUtilitiesTest.php');
    }
}        

?>