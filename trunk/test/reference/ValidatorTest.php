<?php
/**
 * OWASP Enterprise Security API (ESAPI)
 * 
 * This file is part of the Open Web Application Security Project (OWASP)
 * Enterprise Security API (ESAPI) project. For details, please see
 * <a href="http://www.owasp.org/index.php/ESAPI">http://www.owasp.org/index.php/ESAPI</a>.
 *
 * Copyright (c) 2007 - 2009 The OWASP Foundation
 * 
 * The ESAPI is published by OWASP under the BSD license. You should read and accept the
 * LICENSE before you use, modify, and/or redistribute this software.
 * 
 * @author Andrew van der Stock (vanderaj @ owasp.org)
 * @created 2009
 */

require_once dirname(__FILE__).'/../../src/ESAPI.php';
require_once dirname(__FILE__).'/../../src/reference/DefaultValidator.php';
// require_once dirname(__FILE__).'/HTTPUtilitiesTest.php';
class ValidatorTest extends UnitTestCase 
{
	function setUp() 
	{
		global $ESAPI;
		
		if ( !isset($ESAPI)) 
		{
			$ESAPI = new ESAPI();
		}
	}
	
	function tearDown()
	{
		
	}
	
function testIsValidCreditCard() {
		
		$val = ESAPI::getValidator();
		$this->assertTrue($val->isValidCreditCard("test", "1234 9876 0000 0008", false));
		$this->assertTrue($val->isValidCreditCard("test", "1234987600000008", false));
		$this->assertFalse($val->isValidCreditCard("test", "12349876000000081", false));
		$this->assertFalse($val->isValidCreditCard("test", "4417 1234 5678 9112", false));
	}

	/**
	 * Test of isValidEmailAddress method, of class org.owasp.esapi.Validator.
	 */
	function testisValidInput() {

		$instance = ESAPI::getValidator();
		$this->assertTrue($instance->isValidInput("test", "jeff.williams@aspectsecurity.com", "Email", 100, false));
		$this->assertFalse($instance->isValidInput("test", "jeff.williams@@aspectsecurity.com", "Email", 100, false));
		$this->assertFalse($instance->isValidInput("test", "jeff.williams@aspectsecurity", "Email", 100, false));
		$this->assertTrue($instance->isValidInput("test", "123.168.100.234", "IPAddress", 100, false));
		$this->assertTrue($instance->isValidInput("test", "192.168.1.234", "IPAddress", 100, false));
		$this->assertFalse($instance->isValidInput("test", "..168.1.234", "IPAddress", 100, false));
		$this->assertFalse($instance->isValidInput("test", "10.x.1.234", "IPAddress", 100, false));
		$this->assertTrue($instance->isValidInput("test", "http://www.aspectsecurity.com", "URL", 100, false));
		$this->assertFalse($instance->isValidInput("test", "http:///www.aspectsecurity.com", "URL", 100, false));
		$this->assertFalse($instance->isValidInput("test", "http://www.aspect security.com", "URL", 100, false));
		$this->assertTrue($instance->isValidInput("test", "078-05-1120", "SSN", 100, false));
		$this->assertTrue($instance->isValidInput("test", "078 05 1120", "SSN", 100, false));
		$this->assertTrue($instance->isValidInput("test", "078051120", "SSN", 100, false));
		$this->assertFalse($instance->isValidInput("test", "987-65-4320", "SSN", 100, false));
		$this->assertFalse($instance->isValidInput("test", "000-00-0000", "SSN", 100, false));
		$this->assertFalse($instance->isValidInput("test", "(555) 555-5555", "SSN", 100, false));
		$this->assertFalse($instance->isValidInput("test", "test", "SSN", 100, false));

		$this->assertTrue($instance->isValidInput("test", null, "Email", 100, true));
		$this->assertFalse($instance->isValidInput("test", null, "Email", 100, false));
	}

	/**
	 * Test of getValidInput method, of class org.owasp.esapi.Validator.
	 */
	function testGetValidInput(){
		$val = ESAPI::getValidator();
		$this->assertEqual("123abc", $val->getValidInput("test", "123abc", "SafeString", 6, false));		

	}
	
	/**
	 * Test of isValidSafeHTML method, of class org.owasp.esapi.Validator.
	 */
	function testIsValidSafeHTML() {

		$instance = ESAPI::getValidator();
/*
		$this->assertTrue($instance->isValidSafeHTML("test", "<b>Jeff</b>", 100, false));
		$this->assertTrue($instance->isValidSafeHTML("test", "<a href=\"http://www.aspectsecurity.com\">Aspect Security</a>", 100, false));
		$this->assertFalse($instance->isValidSafeHTML("test", "Test. <script>alert(document.cookie)</script>", 100, false));
*/
		// TODO: waiting for a way to validate text headed for an attribute for scripts		
		// This would be nice to catch, but just looks like text to AntiSamy
		// $this->assertFalse($instance->isValidSafeHTML("test", "\" onload=\"alert(document.cookie)\" "));
	}

	/**
	 * Test of getValidSafeHTML method, of class org.owasp.esapi.Validator.
     *
     * @throws Exception
     */
	function testGetValidSafeHTML() {
		$this->fail(); // DELETE ME ("getValidSafeHTML");
//		$val = ESAPI::getValidator();
//		String test1 = "<b>Jeff</b>";
//		String result1 = $val->getValidSafeHTML("test", test1, 100, false);
//		$this->assertEquals(test1, result1);
//		
//		String test2 = "<a href=\"http://www.aspectsecurity.com\">Aspect Security</a>";
//		String result2 = $val->getValidSafeHTML("test", test2, 100, false);
//		$this->assertEquals(test2, result2);
//		
//		String test3 = "Test. <script>alert(document.cookie)</script>";
//		String result3 = $instance->getValidSafeHTML("test", test3, 100, false);
//		$this->assertEquals("Test.", result3);
		
		// TODO: ENHANCE waiting for a way to validate text headed for an attribute for scripts		
		// This would be nice to catch, but just looks like text to AntiSamy
		// $this->assertFalse($instance->isValidSafeHTML("test", "\" onload=\"alert(document.cookie)\" "));
		// String result4 = $instance->getValidSafeHTML("test", test4);
		// $this->assertEquals("", result4);
	}

	/**
	 * Test of isValidListItem method, of class org.owasp.esapi.Validator.
	 */
	function testIsValidListItem() {
		$this->fail(); // DELETE ME ("isValidListItem");
//		Validator instance = ESAPI.validator();
//		List list = new ArrayList();
//		list.add("one");
//		list.add("two");
//		$this->assertTrue($instance->isValidListItem("test", "one", list));
//		$this->assertFalse($instance->isValidListItem("test", "three", list));
	}

	/**
	 * Test of isValidNumber method, of class org.owasp.esapi.Validator.
	 */
	function testIsValidNumber()  {
		$instance = ESAPI::getValidator();
//		testing negative range
		$this->assertFalse($instance->isValidNumber("test", "-4", 1, 10, false));
		$this->assertTrue($instance->isValidNumber("test", "-4", -10, 10, false));
//		//testing null value
		$this->assertTrue($instance->isValidNumber("test", null, -10, 10, true));
		$this->assertFalse($instance->isValidNumber("test", null, -10, 10, false));
//		//testing empty string
		$this->assertTrue($instance->isValidNumber("test", "", -10, 10, true));
		$this->assertFalse($instance->isValidNumber("test", "", -10, 10, false));
//		//testing improper range
		$this->assertFalse($instance->isValidNumber("test", "5", 10, -10, false));
//		//testing non-integers
		$this->assertTrue($instance->isValidNumber("test", "4.3214", -10, 10, true));
		$this->assertTrue($instance->isValidNumber("test", "-1.65", -10, 10, true));
//		//other testing
		$this->assertTrue($instance->isValidNumber("test", "4", 1, 10, false));
		$this->assertTrue($instance->isValidNumber("test", "400", 1, 10000, false));
		$this->assertTrue($instance->isValidNumber("test", "400000000", 1, 400000000, false));
		$this->assertFalse($instance->isValidNumber("test", "4000000000000", 1, 10000, false));
		$this->assertFalse($instance->isValidNumber("test", "alsdkf", 10, 10000, false));
		$this->assertFalse($instance->isValidNumber("test", "--10", 10, 10000, false));
		$this->assertFalse($instance->isValidNumber("test", "14.1414234x", 10, 10000, false));
		$this->assertFalse($instance->isValidNumber("test", "Infinity", 10, 10000, false));
		$this->assertFalse($instance->isValidNumber("test", "-Infinity", 10, 10000, false));
		$this->assertFalse($instance->isValidNumber("test", "NaN", 10, 10000, false));
		$this->assertFalse($instance->isValidNumber("test", "-NaN", 10, 10000, false));
		$this->assertFalse($instance->isValidNumber("test", "+NaN", 10, 10000, false));
		$this->assertTrue($instance->isValidNumber("test", "1e-6", -999999999, 999999999, false));
		$this->assertTrue($instance->isValidNumber("test", "-1e-6", -999999999, 999999999, false));
	}
	
    /**
     *
     */
    function testIsValidInteger() {
		$this->fail(); // DELETE ME ("isValidInteger");
//		Validator instance = ESAPI.validator();
//		//testing negative range
//		$this->assertFalse($instance->isValidInteger("test", "-4", 1, 10, false));
//		$this->assertTrue($instance->isValidInteger("test", "-4", -10, 10, false));
//		//testing null value
//		$this->assertTrue($instance->isValidInteger("test", null, -10, 10, true));
//		$this->assertFalse($instance->isValidInteger("test", null, -10, 10, false));
//		//testing empty string
//		$this->assertTrue($instance->isValidInteger("test", "", -10, 10, true));
//		$this->assertFalse($instance->isValidInteger("test", "", -10, 10, false));
//		//testing improper range
//		$this->assertFalse($instance->isValidInteger("test", "5", 10, -10, false));
//		//testing non-integers
//		$this->assertFalse($instance->isValidInteger("test", "4.3214", -10, 10, true));
//		$this->assertFalse($instance->isValidInteger("test", "-1.65", -10, 10, true));
//		//other testing
//		$this->assertTrue($instance->isValidInteger("test", "4", 1, 10, false));
//		$this->assertTrue($instance->isValidInteger("test", "400", 1, 10000, false));
//		$this->assertTrue($instance->isValidInteger("test", "400000000", 1, 400000000, false));
//		$this->assertFalse($instance->isValidInteger("test", "4000000000000", 1, 10000, false));
//		$this->assertFalse($instance->isValidInteger("test", "alsdkf", 10, 10000, false));
//		$this->assertFalse($instance->isValidInteger("test", "--10", 10, 10000, false));
//		$this->assertFalse($instance->isValidInteger("test", "14.1414234x", 10, 10000, false));
//		$this->assertFalse($instance->isValidInteger("test", "Infinity", 10, 10000, false));
//		$this->assertFalse($instance->isValidInteger("test", "-Infinity", 10, 10000, false));
//		$this->assertFalse($instance->isValidInteger("test", "NaN", 10, 10000, false));
//		$this->assertFalse($instance->isValidInteger("test", "-NaN", 10, 10000, false));
//		$this->assertFalse($instance->isValidInteger("test", "+NaN", 10, 10000, false));
//		$this->assertFalse($instance->isValidInteger("test", "1e-6", -999999999, 999999999, false));
//		$this->assertFalse($instance->isValidInteger("test", "-1e-6", -999999999, 999999999, false));

	}

	/**
	 * Test of getValidDate method, of class org.owasp.esapi.Validator.
     *
     * @throws Exception
     */
	function testGetValidDate() {
		$this->fail(); // DELETE ME ("getValidDate");
//		Validator instance = ESAPI.validator();
//		$this->assertTrue($instance->getValidDate("test", "June 23, 1967", DateFormat.getDateInstance(DateFormat.MEDIUM, Locale.US), false ) != null);
//		try {
//			$instance->getValidDate("test", "freakshow", DateFormat.getDateInstance(), false );
//		} catch( ValidationException e ) {
//			// expected
//		}
//		
//		// This test case fails due to an apparent bug in SimpleDateFormat
//		try {
//			$instance->getValidDate( "test", "June 32, 2008", DateFormat.getDateInstance(), false );
//			// fail();
//		} catch( ValidationException e ) {
//			// expected
//		}
	}

	/**
	 * Test of isValidFileName method, of class org.owasp.esapi.Validator.
	 */
	function testIsValidFileName() {
		$this->fail(); // DELETE ME ("isValidFileName");
//		Validator instance = ESAPI.validator();
//		$this->assertTrue($instance->isValidFileName("test", "aspect.jar", false));
//		$this->assertFalse($instance->isValidFileName("test", "", false));
//        try {
//            $instance->isValidFileName("test", "abc/def", false);
//        } catch( IntrusionException e ) {
//            // expected
//        }
	}

	/**
	 * Test of isValidDirectoryPath method, of class org.owasp.esapi.Validator.
	 */
	function testIsValidDirectoryPath() {
		$this->fail(); // DELETE ME ("isValidDirectoryPath");
//
//		// get an encoder with a special list of codecs and make a validator out of it
//		List list = new ArrayList();
//		list.add( new HTMLEntityCodec() );
//		Encoder encoder = new DefaultEncoder( list );
//		Validator instance = new DefaultValidator( encoder );
//		
//		boolean isWindows = (System.getProperty("os.name").indexOf("Windows") != -1 ) ? true : false;
//		
//		if ( isWindows ) {
//			// Windows paths that don't exist and thus should fail
//			$this->assertFalse($instance->isValidDirectoryPath("test", "c:\\ridiculous", false));
//			$this->assertFalse($instance->isValidDirectoryPath("test", "c:\\jeff", false));
//			$this->assertFalse($instance->isValidDirectoryPath("test", "c:\\temp\\..\\etc", false));
//
//			// Windows paths that should pass
//			$this->assertTrue($instance->isValidDirectoryPath("test", "C:\\", false));								// Windows root directory
//			$this->assertTrue($instance->isValidDirectoryPath("test", "C:\\Windows", false));						// Windows always exist directory
//			$this->assertTrue($instance->isValidDirectoryPath("test", "C:\\Windows\\System32\\cmd.exe", false));		// Windows command shell	
//			
//			// Unix specific paths should not pass
//			$this->assertFalse($instance->isValidDirectoryPath("test", "/tmp", false));		// Unix Temporary directory
//			$this->assertFalse($instance->isValidDirectoryPath("test", "/bin/sh", false));	// Unix Standard shell	
//			$this->assertFalse($instance->isValidDirectoryPath("test", "/etc/config", false));
//			
//			// Unix specific paths that should not exist or work
//			$this->assertFalse($instance->isValidDirectoryPath("test", "/etc/ridiculous", false));
//			$this->assertFalse($instance->isValidDirectoryPath("test", "/tmp/../etc", false));
//		} else {
//			// Windows paths should fail
//			$this->assertFalse($instance->isValidDirectoryPath("test", "c:\\ridiculous", false));
//			$this->assertFalse($instance->isValidDirectoryPath("test", "c:\\temp\\..\\etc", false));
//
//			// Standard Windows locations should fail
//			$this->assertFalse($instance->isValidDirectoryPath("test", "c:\\", false));								// Windows root directory
//			$this->assertFalse($instance->isValidDirectoryPath("test", "c:\\Windows\\temp", false));					// Windows temporary directory
//			$this->assertFalse($instance->isValidDirectoryPath("test", "c:\\Windows\\System32\\cmd.exe", false));	// Windows command shell	
//			
//			// Unix specific paths should pass
//			$this->assertTrue($instance->isValidDirectoryPath("test", "/", false));			// Root directory
//			$this->assertTrue($instance->isValidDirectoryPath("test", "/bin", false));		// Always exist directory
//			$this->assertTrue($instance->isValidDirectoryPath("test", "/bin/sh", false));	// Standard shell	
//			
//			// Unix specific paths that should not exist or work
//			$this->assertFalse($instance->isValidDirectoryPath("test", "/etc/ridiculous", false));
//			$this->assertFalse($instance->isValidDirectoryPath("test", "/tmp/../etc", false));
//		}
	}

    /**
     *
     */
    function testIsValidPrintable() {
		$val = ESAPI::getValidator();
		$this->assertTrue($val->isValidPrintable("name", "abcDEF", 100, false));
		$this->assertTrue($val->isValidPrintable("name", "!@#R()*$;><()", 100, false));
		$bytes = array(0x60,0xFF, 0x10, 0x25);
		$this->assertFalse($val->isValidPrintable("name", $bytes, 100, false ) );
		$this->assertFalse($val->isValidPrintable("name", "%08", 100, false));
    }

	/**
	 * Test of isValidFileContent method, of class org.owasp.esapi.Validator.
	 */
	function testIsValidFileContent() {
		$this->fail(); // DELETE ME ("isValidFileContent");
//		byte[] content = "This is some file content".getBytes();
//		Validator instance = ESAPI.validator();
//		$this->assertTrue($instance->isValidFileContent("test", content, 100, false));
	}

	/**
	 * Test of isValidFileUpload method, of class org.owasp.esapi.Validator.
	 */
	function testIsValidFileUpload() {
		$this->fail(); // DELETE ME ("isValidFileUpload");
//		global $ESAPI;
//
//		String filepath = System.getProperty( "user.dir" );
//		String filename = "aspect.jar";
//		byte[] content = "This is some file content".getBytes();
//		Validator instance = ESAPI.validator();
//		$this->assertTrue($instance->isValidFileUpload("test", filepath, filename, content, 100, false));
//		
//		filepath = "/ridiculous";
//		filename = "aspect.jar";
//		content = "This is some file content".getBytes();
//		$this->assertFalse($instance->isValidFileUpload("test", filepath, filename, content, 100, false));
	}

//	/**
//	 * Test of isValidParameterSet method, of class org.owasp.esapi.Validator.
//	 */
	function testIsValidParameterSet() {
		 $this->fail(); // DELETE ME ("isValidParameterSet")
//		global $ESAPI;
//		$requiredNames = array();
//		array_push($requiredNames,"p1");
//		array_push($requiredNames,"p2");
//		array_push($requiredNames,"p3");
//		$optionalNames = array();
//		array_push($optionalNames,"p4");
//		array_push($optionalNames,"p5");
//		array_push($optionalNames,"p6");
//		$request = new TestHttpServletRequest();
//		$response = new TestHttpServletResponse();
//		$request.addParameter("p1","value");
//		$request.addParameter("p2","value");
//		$request.addParameter("p3","value");
//		$ESAPI.httpUtilities().setCurrentHTTP(request, response);
//		$instance = ESAPI.validator();		
//		$this->assertTrue($instance->isValidHTTPRequestParameterSet("HTTPParameters", $requiredNames, optionalNames));
//		$request.addParameter("p4","value");
//		$request.addParameter("p5","value");
//		$request.addParameter("p6","value");
//		$this->assertTrue($instance->isValidHTTPRequestParameterSet("HTTPParameters", $requiredNames, optionalNames));
//		$request.removeParameter("p1");
//		$this->assertFalse($instance->isValidHTTPRequestParameterSet("HTTPParameters", $requiredNames, optionalNames));
	}

	/**
	 * Test safe read line.
	 *
	 *   This does not appear to be necessary for the PHP version (jullrich@sans.edu)
	 *
	 */
//	function testSafeReadLine() {
//		$this->fail(); // DELETE ME ("safeReadLine");
//		
//		ByteArrayInputStream s = new ByteArrayInputStream("testString".getBytes());
//		Validator instance = ESAPI.validator();
//		try {
//			$instance->safeReadLine(s, -1);
//			fail();
//		} catch (ValidationException e) {
//			// Expected
//		}
//		s.reset();
//		try {
//			$instance->safeReadLine(s, 4);
//			fail();
//		} catch (ValidationException e) {
//			// Expected
//		}
//		s.reset();
//		try {
//			String u = $instance->safeReadLine(s, 20);
//			$this->assertEquals("testString", u);
//		} catch (ValidationException e) {
//			fail();
//		}
//		
//		// This sub-test attempts to validate that BufferedReader.readLine() and safeReadLine() are similar in operation 
//		// for the nominal case 
//		try {
//			s.reset();
//			InputStreamReader isr = new InputStreamReader(s);
//			BufferedReader br = new BufferedReader(isr);
//			String u = br.readLine();
//			s.reset();
//			String v = $instance->safeReadLine(s, 20);
//			$this->$this->assertEquals(u, v);
//		} catch (IOException e) {
//			fail();
//		} catch (ValidationException e) {
//			fail();
//		}
//	}
}
?>