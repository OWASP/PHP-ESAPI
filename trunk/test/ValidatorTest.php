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
 * @package org.owasp.esapi;
 * @since 2007
 */


require_once("../src/errors/org.owasp.esapi.IntrusionException.php");
require_once("../src/errors/org.owasp.esapi.ValidationException.php");
require_once("http/TestHttpServletRequest.php");
require_once("http/TestHttpServletResponse.php");
require_once("../src/interfaces/org.owasp.esapi.IValidator.php");

/**
 * The Class ValidatorTest.
 * 
 * @author Jeff Williams (jeff.williams@aspectsecurity.com)
 */
class ValidatorTest extends TestCase {

	/**
	 * Instantiates a new validator test.
	 * 
	 */
	function ValidatorTest() {
	
	}

	/*
	 * (non-Javadoc)
	 * 
	 * @see junit.framework.TestCase#setUp()
	 */
	function setUp() {
		// none
	}

	/*
	 * (non-Javadoc)
	 * 
	 * @see junit.framework.TestCase#tearDown()
	 */
	function tearDown() {
		// none
	}

	/**
	 * Test of isValidCreditCard method, of class org.owasp.esapi.Validator.
	 */
	public function testIsValidCreditCard() {
		echo("isValidCreditCard");
		$instance = $ESAPI->validator();
		$this->assertTrue($instance->isValidCreditCard("test", "1234 9876 0000 0008"));
		$this->assertTrue($instance->isValidCreditCard("test", "1234987600000008"));
		$this->assertFalse($instance->isValidCreditCard("test", "12349876000000081"));
		$this->assertFalse($instance->isValidCreditCard("test", "4417 1234 5678 9112"));
	}

	/**
	 * Test of isValidEmailAddress method, of class org.owasp.esapi.Validator.
	 */
	public function testIsValidDataFromBrowser() {
		echo("isValidDataFromBrowser");
		$instance = $ESAPI->validator();
		$this->assertTrue($instance->isValidDataFromBrowser("test", "Email", "jeff.williams@aspectsecurity.com"));
		$this->assertFalse($instance->isValidDataFromBrowser("test", "Email", "jeff.williams@@aspectsecurity.com"));
		$this->assertFalse($instance->isValidDataFromBrowser("test", "Email", "jeff.williams@aspectsecurity"));
		$this->assertTrue($instance->isValidDataFromBrowser("test", "IPAddress", "123.168.100.234"));
		$this->assertTrue($instance->isValidDataFromBrowser("test", "IPAddress", "192.168.1.234"));
		$this->assertFalse($instance->isValidDataFromBrowser("test", "IPAddress", "..168.1.234"));
		$this->assertFalse($instance->isValidDataFromBrowser("test", "IPAddress", "10.x.1.234"));
		$this->assertTrue($instance->isValidDataFromBrowser("test", "URL", "http://www.aspectsecurity.com"));
		$this->assertFalse($instance->isValidDataFromBrowser("test", "URL", "http:///www.aspectsecurity.com"));
		$this->assertFalse($instance->isValidDataFromBrowser("test", "URL", "http://www.aspect security.com"));
		$this->assertTrue($instance->isValidDataFromBrowser("test", "SSN", "078-05-1120"));
		$this->assertTrue($instance->isValidDataFromBrowser("test", "SSN", "078 05 1120"));
		$this->assertTrue($instance->isValidDataFromBrowser("test", "SSN", "078051120"));
		$this->assertFalse($instance->isValidDataFromBrowser("test", "SSN", "987-65-4320"));
		$this->assertFalse($instance->isValidDataFromBrowser("test", "SSN", "000-00-0000"));
		$this->assertFalse($instance->isValidDataFromBrowser("test", "SSN", "(555) 555-5555"));
		$this->assertFalse($instance->isValidDataFromBrowser("test", "SSN", "test"));
	}

	/**
	 * Test of isValidSafeHTML method, of class org.owasp.esapi.Validator.
	 */
	public function testIsValidSafeHTML() {
		echo("isValidSafeHTML");
		$instance = $ESAPI->validator();
		$this->assertTrue($instance->isValidSafeHTML("test", "<b>Jeff</b>"));
		$this->assertTrue($instance->isValidSafeHTML("test", "<a href=\"http://www.aspectsecurity.com\">Aspect Security</a>"));
		$this->assertFalse($instance->isValidSafeHTML("test", "Test. <script>alert(document.cookie)</script>"));
		$this->assertFalse($instance->isValidSafeHTML("test", "\" onload=\"alert(document.cookie)\" "));
	}

	/**
	 * Test of getValidSafeHTML method, of class org.owasp.esapi.Validator.
	 */
	public function testGetValidSafeHTML() {
		echo("getValidSafeHTML");
		$instance = $ESAPI->validator();
		$test1 = "<b>Jeff</b>";
		$result1 = $instance->getValidSafeHTML("test", $test1);
		$this->assertEquals($test1, $result1);
		
		$test2 = "<a href=\"http://www.aspectsecurity.com\">Aspect Security</a>";
		$result2 = $instance->getValidSafeHTML("test", $test2);
		$this->assertEquals($test2, $result2);
		
		$test3 = "Test. <script>alert(document.cookie)</script>";
		$result3 = $instance->getValidSafeHTML("test", $test3);
		$this->assertEquals("Test.", $result3);
		
// FIXME: ENHANCE waiting for a way to validate text headed for an attribute for scripts		
//		$test4 = "\" onload=\"alert(document.cookie)\" ";
//		$result4 = $instance->getValidSafeHTML("test", $test4);
//		$this->assertEquals("", result4);
	}

	/**
	 * Test of isValidListItem method, of class org.owasp.esapi.Validator.
	 */
	public function testIsValidListItem() {
		echo("isValidListItem");
		$instance = $ESAPI->validator();
		$list = array();
		$list[] = "one";
		$list[] = "two";
		$this->assertTrue($instance->isValidListItem($list, "one"));
		$this->assertFalse($instance->isValidListItem($list, "three"));
	}

	/**
	 * Test of isValidNumber method, of class org.owasp.esapi.Validator.
	 */
	public function testIsValidNumber() {
		echo("isValidNumber");
		$instance = $ESAPI->validator();
		$this->assertTrue($instance->isValidNumber("4"));
		$this->assertTrue($instance->isValidNumber("400"));
		$this->assertTrue($instance->isValidNumber("4000000000000"));
		$this->assertFalse($instance->isValidNumber("alsdkf"));
		$this->assertFalse($instance->isValidNumber("--10"));
		$this->assertFalse($instance->isValidNumber("14.1414234x"));
		$this->assertFalse($instance->isValidNumber("Infinity"));
		$this->assertFalse($instance->isValidNumber("-Infinity"));
		$this->assertFalse($instance->isValidNumber("NaN"));
		$this->assertFalse($instance->isValidNumber("-NaN"));
		$this->assertFalse($instance->isValidNumber("+NaN"));
		$this->assertTrue($instance->isValidNumber("1e-6"));
		$this->assertTrue($instance->isValidNumber("-1e-6"));
	}

	/**
	 * Test of getValidDate method, of class org.owasp.esapi.Validator.
	 */
	public function testGetValidDate() {
		echo("getValidDate");
		$instance = $ESAPI->validator();
		$this->assertTrue($instance->getValidDate("test", "June 23, 1967", DateFormat.getDateInstance() ) != null);
		try {
			$instance->getValidDate("test", "freakshow", DateFormat.getDateInstance() );
		} catch( ValidationException $e ) {
			// expected
		}
		
		// FIXME: AAA This test case fails due to an apparent bug in SimpleDateFormat
		try {
			$instance->getValidDate( "test", "June 32, 2008", DateFormat.getDateInstance() );
		} catch( ValidationException $e ) {
			// expected
		}
	}

	/**
	 * Test of isValidFileName method, of class org.owasp.esapi.Validator.
	 */
	public function testIsValidFileName() {
		echo("isValidFileName");
		$instance = $ESAPI->validator();
		$this->assertTrue($instance->isValidFileName("test", "aspect.jar"));
		$this->assertFalse($instance->isValidFileName("test", ""));
        try {
            $instance->isValidFileName("test", "abc/def");
        } catch( IntrusionException $e ) {
            // expected
        }
	}

	/**
	 * Test of isValidDirectoryPath method, of class org.owasp.esapi.Validator.
	 */
	public function testIsValidDirectoryPath() {
		echo("isValidDirectoryPath");
		$instance = $ESAPI->validator();
		$this->assertTrue($instance->isValidDirectoryPath("test", "/"));
		$this->assertTrue($instance->isValidDirectoryPath("test", "c:\\temp"));
		$this->assertTrue($instance->isValidDirectoryPath("test", "/etc/config"));
		// FIXME: ENHANCE doesn't accept filenames, just directories - should it?
		// $this->assertTrue( $instance->isValidDirectoryPath(
		// "c:\\Windows\\System32\\cmd.exe" ) );
		$this->assertFalse($instance->isValidDirectoryPath("test", "c:\\temp\\..\\etc"));
	}

	public function testIsValidPrintable() {
		echo("isValidPrintable");
		$instance = $ESAPI->validator();
		$this->assertTrue($instance->isValidPrintable("abcDEF"));
		$this->assertTrue($instance->isValidPrintable("!@#R()*$;><()"));
        $bytes = chr(0x60) . chr(0xFF) . chr(0x10) . chr(0x25);
        $this->assertFalse( $instance->isValidPrintable( $bytes ) );
		$this->assertFalse($instance->isValidPrintable("%08"));
    }

	/**
	 * Test of isValidFileContent method, of class org.owasp.esapi.Validator.
	 */
	public function testIsValidFileContent() {
		echo("isValidFileContent");
		$content = "This is some file content".getBytes();
		$instance = $ESAPI->validator();
		$this->assertTrue($instance->isValidFileContent("test", $content));
	}

	/**
	 * Test of isValidFileUpload method, of class org.owasp.esapi.Validator.
	 */
	public function testIsValidFileUpload() {
		echo("isValidFileUpload");

		$filepath = "/etc";
		$filename = "aspect.jar";
		$content = "Thisi is some file content";
		$instance = $ESAPI->validator();
		$this->assertTrue($instance->isValidFileUpload("test", $filepath, $filename, $content));
	}

	/**
	 * Test of isValidParameterSet method, of class org.owasp.esapi.Validator.
	 */
	public function testIsValidParameterSet() {
		echo("isValidParameterSet");

		$requiredNames = array();
		$requiredNames[] = "p1";
		$requiredNames[] = "p2";
		$requiredNames[] = "p3";
		$optionalNames = array();
		$optionalNames[] = "p4";
		$optionalNames[] = "p5";
		$optionalNames[] = "p6";
        $request = new TestHttpServletRequest();
        $response = new TestHttpServletResponse();
		$request.addParameter("p1","value");
		$request.addParameter("p2","value");
		$request.addParameter("p3","value");
        $ESAPI->authenticator()->setCurrentHTTP($request, $response);
		$instance = $ESAPI->validator();
		$this->assertTrue($instance->isValidParameterSet($requiredNames, $optionalNames));
		$request.addParameter("p4","value");
		$request.addParameter("p5","value");
		$request.addParameter("p6","value");
		$this->assertTrue($instance->isValidParameterSet($requiredNames, $optionalNames));
		$request.removeParameter("p1");
		$this->assertFalse($instance->isValidParameterSet($requiredNames, $optionalNames));
	}

	/**
	 * Test safe read line.
	 */
	public function testSafeReadLine() {
		$s = "testString";
		$instance = $ESAPI->validator();
		try {
			$instance->safeReadLine($s, -1);
			$this->fail();
		} catch (ValidationException $e) {
			// Expected
		}
		
		try {
			$instance->safeReadLine($s, 4);
			$this->fail();
		} catch (ValidationException $e) {
			// Expected
		}
		
		try {
			$u = $instance->safeReadLine($s, 20);
			$this->assertEquals("testString", $u);
		} catch (ValidationException $e) {
			$this->fail();
		}
	}
}
?>