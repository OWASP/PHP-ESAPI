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
		$this->fail(); // DELETE ME ("isValidCreditCard");
//		Validator instance = ESAPI.validator();
//		assertTrue(instance.isValidCreditCard("test", "1234 9876 0000 0008", false));
//		assertTrue(instance.isValidCreditCard("test", "1234987600000008", false));
//		assertFalse(instance.isValidCreditCard("test", "12349876000000081", false));
//		assertFalse(instance.isValidCreditCard("test", "4417 1234 5678 9112", false));
	}

	/**
	 * Test of isValidEmailAddress method, of class org.owasp.esapi.Validator.
	 */
	function testisValidInput() {
		$this->fail(); // DELETE ME ("isValidInput");
//		Validator instance = ESAPI.validator();
//		assertTrue(instance.isValidInput("test", "jeff.williams@aspectsecurity.com", "Email", 100, false));
//		assertFalse(instance.isValidInput("test", "jeff.williams@@aspectsecurity.com", "Email", 100, false));
//		assertFalse(instance.isValidInput("test", "jeff.williams@aspectsecurity", "Email", 100, false));
//		assertTrue(instance.isValidInput("test", "123.168.100.234", "IPAddress", 100, false));
//		assertTrue(instance.isValidInput("test", "192.168.1.234", "IPAddress", 100, false));
//		assertFalse(instance.isValidInput("test", "..168.1.234", "IPAddress", 100, false));
//		assertFalse(instance.isValidInput("test", "10.x.1.234", "IPAddress", 100, false));
//		assertTrue(instance.isValidInput("test", "http://www.aspectsecurity.com", "URL", 100, false));
//		assertFalse(instance.isValidInput("test", "http:///www.aspectsecurity.com", "URL", 100, false));
//		assertFalse(instance.isValidInput("test", "http://www.aspect security.com", "URL", 100, false));
//		assertTrue(instance.isValidInput("test", "078-05-1120", "SSN", 100, false));
//		assertTrue(instance.isValidInput("test", "078 05 1120", "SSN", 100, false));
//		assertTrue(instance.isValidInput("test", "078051120", "SSN", 100, false));
//		assertFalse(instance.isValidInput("test", "987-65-4320", "SSN", 100, false));
//		assertFalse(instance.isValidInput("test", "000-00-0000", "SSN", 100, false));
//		assertFalse(instance.isValidInput("test", "(555) 555-5555", "SSN", 100, false));
//		assertFalse(instance.isValidInput("test", "test", "SSN", 100, false));
//
//		assertTrue(instance.isValidInput("test", null, "Email", 100, true));
//		assertFalse(instance.isValidInput("test", null, "Email", 100, false));
	}

	/**
	 * Test of getValidInput method, of class org.owasp.esapi.Validator.
	 */
	function testGetValidInput(){
		$val = ESAPI::getValidator();
		
		// TODO: Security -- add test cases
		
		$this->assertEqual("123abc", $val->getValidInput("test", "123abc", "SafeString", 6, false));		

	}
	
	/**
	 * Test of isValidSafeHTML method, of class org.owasp.esapi.Validator.
	 */
	function testIsValidSafeHTML() {
		$this->fail(); // DELETE ME ("isValidSafeHTML");
		// Validator instance = ESAPI.validator();

//		assertTrue(instance.isValidSafeHTML("test", "<b>Jeff</b>", 100, false));
//		assertTrue(instance.isValidSafeHTML("test", "<a href=\"http://www.aspectsecurity.com\">Aspect Security</a>", 100, false));
//		assertFalse(instance.isValidSafeHTML("test", "Test. <script>alert(document.cookie)</script>", 100, false));

		// TODO: waiting for a way to validate text headed for an attribute for scripts		
		// This would be nice to catch, but just looks like text to AntiSamy
		// assertFalse(instance.isValidSafeHTML("test", "\" onload=\"alert(document.cookie)\" "));
	}

	/**
	 * Test of getValidSafeHTML method, of class org.owasp.esapi.Validator.
     *
     * @throws Exception
     */
	function testGetValidSafeHTML() {
		$this->fail(); // DELETE ME ("getValidSafeHTML");
//		Validator instance = ESAPI.validator();
//		String test1 = "<b>Jeff</b>";
//		String result1 = instance.getValidSafeHTML("test", test1, 100, false);
//		assertEquals(test1, result1);
//		
//		String test2 = "<a href=\"http://www.aspectsecurity.com\">Aspect Security</a>";
//		String result2 = instance.getValidSafeHTML("test", test2, 100, false);
//		assertEquals(test2, result2);
//		
//		String test3 = "Test. <script>alert(document.cookie)</script>";
//		String result3 = instance.getValidSafeHTML("test", test3, 100, false);
//		assertEquals("Test.", result3);
		
		// TODO: ENHANCE waiting for a way to validate text headed for an attribute for scripts		
		// This would be nice to catch, but just looks like text to AntiSamy
		// assertFalse(instance.isValidSafeHTML("test", "\" onload=\"alert(document.cookie)\" "));
		// String result4 = instance.getValidSafeHTML("test", test4);
		// assertEquals("", result4);
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
//		assertTrue(instance.isValidListItem("test", "one", list));
//		assertFalse(instance.isValidListItem("test", "three", list));
	}

	/**
	 * Test of isValidNumber method, of class org.owasp.esapi.Validator.
	 */
	function testIsValidNumber() {
		$this->fail(); // DELETE ME ("isValidNumber");
//		Validator instance = ESAPI.validator();
//		//testing negative range
//		assertFalse(instance.isValidNumber("test", "-4", 1, 10, false));
//		assertTrue(instance.isValidNumber("test", "-4", -10, 10, false));
//		//testing null value
//		assertTrue(instance.isValidNumber("test", null, -10, 10, true));
//		assertFalse(instance.isValidNumber("test", null, -10, 10, false));
//		//testing empty string
//		assertTrue(instance.isValidNumber("test", "", -10, 10, true));
//		assertFalse(instance.isValidNumber("test", "", -10, 10, false));
//		//testing improper range
//		assertFalse(instance.isValidNumber("test", "5", 10, -10, false));
//		//testing non-integers
//		assertTrue(instance.isValidNumber("test", "4.3214", -10, 10, true));
//		assertTrue(instance.isValidNumber("test", "-1.65", -10, 10, true));
//		//other testing
//		assertTrue(instance.isValidNumber("test", "4", 1, 10, false));
//		assertTrue(instance.isValidNumber("test", "400", 1, 10000, false));
//		assertTrue(instance.isValidNumber("test", "400000000", 1, 400000000, false));
//		assertFalse(instance.isValidNumber("test", "4000000000000", 1, 10000, false));
//		assertFalse(instance.isValidNumber("test", "alsdkf", 10, 10000, false));
//		assertFalse(instance.isValidNumber("test", "--10", 10, 10000, false));
//		assertFalse(instance.isValidNumber("test", "14.1414234x", 10, 10000, false));
//		assertFalse(instance.isValidNumber("test", "Infinity", 10, 10000, false));
//		assertFalse(instance.isValidNumber("test", "-Infinity", 10, 10000, false));
//		assertFalse(instance.isValidNumber("test", "NaN", 10, 10000, false));
//		assertFalse(instance.isValidNumber("test", "-NaN", 10, 10000, false));
//		assertFalse(instance.isValidNumber("test", "+NaN", 10, 10000, false));
//		assertTrue(instance.isValidNumber("test", "1e-6", -999999999, 999999999, false));
//		assertTrue(instance.isValidNumber("test", "-1e-6", -999999999, 999999999, false));
	}
	
    /**
     *
     */
    function testIsValidInteger() {
		$this->fail(); // DELETE ME ("isValidInteger");
//		Validator instance = ESAPI.validator();
//		//testing negative range
//		assertFalse(instance.isValidInteger("test", "-4", 1, 10, false));
//		assertTrue(instance.isValidInteger("test", "-4", -10, 10, false));
//		//testing null value
//		assertTrue(instance.isValidInteger("test", null, -10, 10, true));
//		assertFalse(instance.isValidInteger("test", null, -10, 10, false));
//		//testing empty string
//		assertTrue(instance.isValidInteger("test", "", -10, 10, true));
//		assertFalse(instance.isValidInteger("test", "", -10, 10, false));
//		//testing improper range
//		assertFalse(instance.isValidInteger("test", "5", 10, -10, false));
//		//testing non-integers
//		assertFalse(instance.isValidInteger("test", "4.3214", -10, 10, true));
//		assertFalse(instance.isValidInteger("test", "-1.65", -10, 10, true));
//		//other testing
//		assertTrue(instance.isValidInteger("test", "4", 1, 10, false));
//		assertTrue(instance.isValidInteger("test", "400", 1, 10000, false));
//		assertTrue(instance.isValidInteger("test", "400000000", 1, 400000000, false));
//		assertFalse(instance.isValidInteger("test", "4000000000000", 1, 10000, false));
//		assertFalse(instance.isValidInteger("test", "alsdkf", 10, 10000, false));
//		assertFalse(instance.isValidInteger("test", "--10", 10, 10000, false));
//		assertFalse(instance.isValidInteger("test", "14.1414234x", 10, 10000, false));
//		assertFalse(instance.isValidInteger("test", "Infinity", 10, 10000, false));
//		assertFalse(instance.isValidInteger("test", "-Infinity", 10, 10000, false));
//		assertFalse(instance.isValidInteger("test", "NaN", 10, 10000, false));
//		assertFalse(instance.isValidInteger("test", "-NaN", 10, 10000, false));
//		assertFalse(instance.isValidInteger("test", "+NaN", 10, 10000, false));
//		assertFalse(instance.isValidInteger("test", "1e-6", -999999999, 999999999, false));
//		assertFalse(instance.isValidInteger("test", "-1e-6", -999999999, 999999999, false));

	}

	/**
	 * Test of getValidDate method, of class org.owasp.esapi.Validator.
     *
     * @throws Exception
     */
	function testGetValidDate() {
		$this->fail(); // DELETE ME ("getValidDate");
//		Validator instance = ESAPI.validator();
//		assertTrue(instance.getValidDate("test", "June 23, 1967", DateFormat.getDateInstance(DateFormat.MEDIUM, Locale.US), false ) != null);
//		try {
//			instance.getValidDate("test", "freakshow", DateFormat.getDateInstance(), false );
//		} catch( ValidationException e ) {
//			// expected
//		}
//		
//		// This test case fails due to an apparent bug in SimpleDateFormat
//		try {
//			instance.getValidDate( "test", "June 32, 2008", DateFormat.getDateInstance(), false );
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
//		assertTrue(instance.isValidFileName("test", "aspect.jar", false));
//		assertFalse(instance.isValidFileName("test", "", false));
//        try {
//            instance.isValidFileName("test", "abc/def", false);
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
//			assertFalse(instance.isValidDirectoryPath("test", "c:\\ridiculous", false));
//			assertFalse(instance.isValidDirectoryPath("test", "c:\\jeff", false));
//			assertFalse(instance.isValidDirectoryPath("test", "c:\\temp\\..\\etc", false));
//
//			// Windows paths that should pass
//			assertTrue(instance.isValidDirectoryPath("test", "C:\\", false));								// Windows root directory
//			assertTrue(instance.isValidDirectoryPath("test", "C:\\Windows", false));						// Windows always exist directory
//			assertTrue(instance.isValidDirectoryPath("test", "C:\\Windows\\System32\\cmd.exe", false));		// Windows command shell	
//			
//			// Unix specific paths should not pass
//			assertFalse(instance.isValidDirectoryPath("test", "/tmp", false));		// Unix Temporary directory
//			assertFalse(instance.isValidDirectoryPath("test", "/bin/sh", false));	// Unix Standard shell	
//			assertFalse(instance.isValidDirectoryPath("test", "/etc/config", false));
//			
//			// Unix specific paths that should not exist or work
//			assertFalse(instance.isValidDirectoryPath("test", "/etc/ridiculous", false));
//			assertFalse(instance.isValidDirectoryPath("test", "/tmp/../etc", false));
//		} else {
//			// Windows paths should fail
//			assertFalse(instance.isValidDirectoryPath("test", "c:\\ridiculous", false));
//			assertFalse(instance.isValidDirectoryPath("test", "c:\\temp\\..\\etc", false));
//
//			// Standard Windows locations should fail
//			assertFalse(instance.isValidDirectoryPath("test", "c:\\", false));								// Windows root directory
//			assertFalse(instance.isValidDirectoryPath("test", "c:\\Windows\\temp", false));					// Windows temporary directory
//			assertFalse(instance.isValidDirectoryPath("test", "c:\\Windows\\System32\\cmd.exe", false));	// Windows command shell	
//			
//			// Unix specific paths should pass
//			assertTrue(instance.isValidDirectoryPath("test", "/", false));			// Root directory
//			assertTrue(instance.isValidDirectoryPath("test", "/bin", false));		// Always exist directory
//			assertTrue(instance.isValidDirectoryPath("test", "/bin/sh", false));	// Standard shell	
//			
//			// Unix specific paths that should not exist or work
//			assertFalse(instance.isValidDirectoryPath("test", "/etc/ridiculous", false));
//			assertFalse(instance.isValidDirectoryPath("test", "/tmp/../etc", false));
//		}
	}

    /**
     *
     */
    function testIsValidPrintable() {
		$this->fail(); // DELETE ME ("isValidPrintable");
//		Validator instance = ESAPI.validator();
//		assertTrue(instance.isValidPrintable("name", "abcDEF", 100, false));
//		assertTrue(instance.isValidPrintable("name", "!@#R()*$;><()", 100, false));
//        byte[] bytes = { 0x60, (byte) 0xFF, 0x10, 0x25 };
//        assertFalse( instance.isValidPrintable("name", bytes, 100, false ) );
//		assertFalse(instance.isValidPrintable("name", "%08", 100, false));
    }

	/**
	 * Test of isValidFileContent method, of class org.owasp.esapi.Validator.
	 */
	function testIsValidFileContent() {
		$this->fail(); // DELETE ME ("isValidFileContent");
//		byte[] content = "This is some file content".getBytes();
//		Validator instance = ESAPI.validator();
//		assertTrue(instance.isValidFileContent("test", content, 100, false));
	}

	/**
	 * Test of isValidFileUpload method, of class org.owasp.esapi.Validator.
	 */
	function testIsValidFileUpload() {
		$this->fail(); // DELETE ME ("isValidFileUpload");
//
//		String filepath = System.getProperty( "user.dir" );
//		String filename = "aspect.jar";
//		byte[] content = "This is some file content".getBytes();
//		Validator instance = ESAPI.validator();
//		assertTrue(instance.isValidFileUpload("test", filepath, filename, content, 100, false));
//		
//		filepath = "/ridiculous";
//		filename = "aspect.jar";
//		content = "This is some file content".getBytes();
//		assertFalse(instance.isValidFileUpload("test", filepath, filename, content, 100, false));
	}

	/**
	 * Test of isValidParameterSet method, of class org.owasp.esapi.Validator.
	 */
	function testIsValidParameterSet() {
		$this->fail(); // DELETE ME ("isValidParameterSet");
//
//		Set requiredNames = new HashSet();
//		requiredNames.add("p1");
//		requiredNames.add("p2");
//		requiredNames.add("p3");
//		Set optionalNames = new HashSet();
//		optionalNames.add("p4");
//		optionalNames.add("p5");
//		optionalNames.add("p6");
//        TestHttpServletRequest request = new TestHttpServletRequest();
//        TestHttpServletResponse response = new TestHttpServletResponse();
//		request.addParameter("p1","value");
//		request.addParameter("p2","value");
//		request.addParameter("p3","value");
//		ESAPI.httpUtilities().setCurrentHTTP(request, response);
//		Validator instance = ESAPI.validator();		
//		assertTrue(instance.isValidHTTPRequestParameterSet("HTTPParameters", requiredNames, optionalNames));
//		request.addParameter("p4","value");
//		request.addParameter("p5","value");
//		request.addParameter("p6","value");
//		assertTrue(instance.isValidHTTPRequestParameterSet("HTTPParameters", requiredNames, optionalNames));
//		request.removeParameter("p1");
//		assertFalse(instance.isValidHTTPRequestParameterSet("HTTPParameters", requiredNames, optionalNames));
	}

	/**
	 * Test safe read line.
	 */
	function testSafeReadLine() {
		$this->fail(); // DELETE ME ("safeReadLine");
//		
//		ByteArrayInputStream s = new ByteArrayInputStream("testString".getBytes());
//		Validator instance = ESAPI.validator();
//		try {
//			instance.safeReadLine(s, -1);
//			fail();
//		} catch (ValidationException e) {
//			// Expected
//		}
//		s.reset();
//		try {
//			instance.safeReadLine(s, 4);
//			fail();
//		} catch (ValidationException e) {
//			// Expected
//		}
//		s.reset();
//		try {
//			String u = instance.safeReadLine(s, 20);
//			assertEquals("testString", u);
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
//			String v = instance.safeReadLine(s, 20);
//			assertEquals(u, v);
//		} catch (IOException e) {
//			fail();
//		} catch (ValidationException e) {
//			fail();
//		}
	}
}
?>