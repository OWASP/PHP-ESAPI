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
 * Notes - any changes to the testresources/ESAPI.xml file MUST be reflected in this file
 * or else most (if not all) of these tests will fail.  
 * 
 * @author Andrew van der Stock (vanderaj @ owasp.org)
 * @created 2009
 * @since 1.6
 */

require_once dirname(__FILE__).'/../../src/ESAPI.php';
require_once dirname(__FILE__).'/../../src/reference/DefaultSecurityConfiguration.php';
 
class SecurityConfigurationTest extends UnitTestCase 
{
	function setUp() 
	{
		global $ESAPI;
		
		if ( !isset($ESAPI)) 
		{
			$ESAPI = new ESAPI(dirname(__FILE__).'/../testresources/ESAPI.xml');
		}
	}
	
	function tearDown()
	{
		
	}
	
	function testConfigExists()
	{
		$this->assertTrue(file_exists(dirname(__FILE__).'/../testresources/ESAPI.xml'));
	}
	
	/**
	 * Gets the application name, used for logging
	 * 
	 * TODO: Change this when this is configurable in ESAPI.xml
	 * 
	 * @return the name of the current application
	 */
	function testApplicationName()
	{
		$config = ESAPI::getSecurityConfiguration();
		
		$this->assertEqual($config->getApplicationName(), 'ExampleApplication');
	}

	/**
	 * Gets the master password. This password can be used to encrypt/decrypt other files or types
	 * of data that need to be protected by your application.
	 * 
	 * @return the current master password
	 */
	function testMasterKey()
	{
		$config = ESAPI::getSecurityConfiguration();
		
		$this->assertEqual($config->getMasterKey(), base64_decode('pJhlri8JbuFYDgkqtHmm9s0Ziug2PE7ovZDyEPm4j14='));
	}

	/**
	 * Gets the master salt that is used to salt stored password hashes and any other location 
	 * where a salt is needed.
	 * 
	 * @return the current master salt
	 */
	function testMasterSalt()
	{
		$config = ESAPI::getSecurityConfiguration();
		
		$this->assertEqual($config->getMasterSalt(), base64_decode('SbftnvmEWD5ZHHP+pX3fqugNysc='));
	}

	/**
	 * Gets the allowed file extensions for files that are uploaded to this application.
	 * 
	 * @return a list of the current allowed file extensions
	 */
	function testAllowedFileExtensions()
	{
		$config = ESAPI::getSecurityConfiguration();
		
		$extensions = $config->getAllowedFileExtensions();
		
		$this->assertEqual(count($extensions), 24);
		
		$this->assertTrue(in_array('.zip', $extensions));  			// 1
		$this->assertTrue(in_array('.pdf', $extensions));  			// 2	
		$this->assertTrue(in_array('.doc', $extensions));  			// 3
		$this->assertTrue(in_array('.docx', $extensions));  		// 4
		$this->assertTrue(in_array('.ppt', $extensions));  			// 5
		$this->assertTrue(in_array('.pptx', $extensions));  		// 6
		$this->assertTrue(in_array('.tar', $extensions));  			// 7
		$this->assertTrue(in_array('.gz', $extensions));  			// 8
		$this->assertTrue(in_array('.tgz', $extensions));  			// 9
		$this->assertTrue(in_array('.rar', $extensions));  			// 10
		$this->assertTrue(in_array('.war', $extensions));  			// 11
		$this->assertTrue(in_array('.jar', $extensions));  			// 12
		$this->assertTrue(in_array('.ear', $extensions));  			// 13
		$this->assertTrue(in_array('.xls', $extensions));  			// 14
		$this->assertTrue(in_array('.rtf', $extensions));  			// 15
		$this->assertTrue(in_array('.properties', $extensions));  	// 16
		$this->assertTrue(in_array('.java', $extensions));  		// 17
		$this->assertTrue(in_array('.class', $extensions));  		// 18
		$this->assertTrue(in_array('.txt', $extensions));  			// 19
		$this->assertTrue(in_array('.xml', $extensions));  			// 20
		$this->assertTrue(in_array('.jsp', $extensions));  			// 21
		$this->assertTrue(in_array('.jsf', $extensions));  			// 22
		$this->assertTrue(in_array('.exe', $extensions));  			// 23
		$this->assertTrue(in_array('.dll', $extensions));  			// 24
		
		// These are more of a sanity check to ensure that no dodgy entries were found and stored
		
		$this->assertFalse(in_array('', $extensions));
		$this->assertFalse(in_array(null, $extensions));
		$this->assertFalse(in_array('ridiculous', $extensions));
	}

	/**
	 * Gets the maximum allowed file upload size.
	 * 
	 * @return the current allowed file upload size
	 */
	function testAllowedFileUploadSize()
	{
		$config = ESAPI::getSecurityConfiguration();
		
		$this->assertEqual($config->getAllowedFileUploadSize(), 500000000);
	}

	/**
	 * Tests the allowed include feature unique to PHP ESAPI. 
	 * 
	 * This test should return three as there's three test includes
	 */
	function testAllowedIncludeSize()
	{
		$config = ESAPI::getSecurityConfiguration();
		
		$includes = $config->getAllowedIncludes();
		
		$this->assertEqual(count($includes), 3);
	}

	/**
	 * Tests the allowed include feature unique to PHP ESAPI. 
	 * 
	 * This test should return three as there's three test includes
	 */
	function testAllowedIncludeContents()
	{
		$config = ESAPI::getSecurityConfiguration();
		
		$includes = $config->getAllowedIncludes();
		
		$this->assertEqual($includes[0], 'test.php');
		$this->assertEqual($includes[1], 'foo.php');
		$this->assertEqual($includes[2], 'bar.php');
		$this->assertFalse(in_array('ridiculous.php', $includes, true));
	}
	
	
	/**
	 * Tests the allowed resource feature unique to PHP ESAPI. 
	 * 
	 * This test should return three as there's three test includes
	 */
	function testAllowedResourcesSize()
	{
		$config = ESAPI::getSecurityConfiguration();
		$includes = $config->getAllowedResources();
		
		$this->assertEqual(count($includes), 3);
	}

	/**
	 * Tests the allowed include feature unique to PHP ESAPI. 
	 * 
	 * This test should return three as there's three test includes
	 */
	function testAllowedResourcesContents()
	{
		$config = ESAPI::getSecurityConfiguration();
		$resources = $config->getAllowedResources();
		
		$this->assertEqual($resources[0], 'foo');
		$this->assertEqual($resources[1], 'admin');
		$this->assertEqual($resources[2], 'users.txt');
		$this->assertFalse(in_array('ridiculous', $resources, true));
	}
	
	/**
	 * Gets the name of the password parameter used during user authentication.
	 * 
	 * @return the name of the password parameter
	 */
	function testPasswordParameterName()
	{
		$config = ESAPI::getSecurityConfiguration();
		
		$this->assertEqual($config->getPasswordParameterName(), 'password');
	}

	/**
	 * Gets the name of the username parameter used during user authentication.
	 * 
	 * @return the name of the username parameter
	 */
	function testUsernameParameterName()
	{
		$config = ESAPI::getSecurityConfiguration();
		
		$this->assertEqual($config->getUsernameParameterName(), 'username');
	}

	/**
	 * Gets the encryption algorithm used by ESAPI to protect data.
	 * 
	 * @return the current encryption algorithm
	 */
	function testEncryptionAlgorithm()
	{
		$config = ESAPI::getSecurityConfiguration();
		
		$this->assertEqual($config->getEncryptionAlgorithm(), 'AES');
	}

	/**
	 * Gets the hashing algorithm used by ESAPI to hash data.
	 * 
	 * @return the current hashing algorithm
	 */
	function testHashAlgorithm()
	{
		$config = ESAPI::getSecurityConfiguration();
		
		$this->assertEqual($config->getHashAlgorithm(), 'SHA-512');
	}

	/**
	 * Gets the character encoding scheme supported by this application. This is used to set the
	 * character encoding scheme on requests and responses when setCharacterEncoding() is called
	 * on SafeRequests and SafeResponses. This scheme is also used for encoding/decoding URLs 
	 * and any other place where the current encoding scheme needs to be known.
	 * <br><br>
	 * Note: This does not get the configured response content type. That is accessed by calling 
	 * getResponseContentType().
	 * 
	 * @return the current character encoding scheme
	 */
	function testCharacterEncoding()
	{
		$config = ESAPI::getSecurityConfiguration();
		
		$this->assertEqual($config->getCharacterEncoding(), 'UTF-8');
	}

	/**
	 * Gets the digital signature algorithm used by ESAPI to generate and verify signatures.
	 * 
	 * @return the current digital signature algorithm
	 */
	function testDigitalSignatureAlgorithm()
	{
		$config = ESAPI::getSecurityConfiguration();
		
		$this->assertEqual($config->getDigitalSignatureAlgorithm(), 'DSA');
	}

	/**
	 * Gets the random number generation algorithm used to generate random numbers where needed.
	 * 
	 * @return the current random number generation algorithm
	 */
	function testRandomAlgorithm()
	{
		$config = ESAPI::getSecurityConfiguration();
		
		$this->assertEqual($config->getRandomAlgorithm(), 'SHA1PRNG');
	}

	/**
	 * Gets the number of login attempts allowed before the user's account is locked. If this 
	 * many failures are detected within the alloted time period, the user's account will be locked.
	 * 
	 * @return the number of failed login attempts that cause an account to be locked
	 */
	function testAllowedLoginAttempts()
	{
		$config = ESAPI::getSecurityConfiguration();
		
		$this->assertEqual($config->getAllowedLoginAttempts(), 3);
	}

	/**
	 * Gets the maximum number of old password hashes that should be retained. These hashes can 
	 * be used to ensure that the user doesn't reuse the specified number of previous passwords
	 * when they change their password.
	 * 
	 * @return the number of old hashed passwords to retain
	 */
	function testMaxOldPasswordHashes()
	{
		$config = ESAPI::getSecurityConfiguration();
		
		$this->assertEqual($config->getMaxOldPasswordHashes(), 13);
	}

	/**
	 * Gets the intrusion detection quota for the specified event.
	 * 
	 * @param eventName the name of the event whose quota is desired
	 * 
	 * @return the Quota that has been configured for the specified type of event
	 */
	function testQuota()
	{
		$config = ESAPI::getSecurityConfiguration();
		
		// dummy call - should do nothing
		$config->getQuota(null);
		
		// Check the event tree has not been parsed
		$this->assertEqual(count($config->events), 0);
		
		$event = $config->getQuota('test');
	
		// Check the event tree has now been parsed
		$this->assertEqual(count($config->events), 4);
		
		// Check test event is okay
		
		$this->assertEqual($event->name, 'test');
		$this->assertEqual($event->count, 2);
		$this->assertEqual($event->interval, 10);
		$this->assertEqual(count($event->actions), 2);
		$this->assertEqual($event->actions[0], 'disable');
		$this->assertEqual($event->actions[1], 'log');

		// Test the integrity exception event
		$event = $config->getQuota('IntrusionException');
		$this->assertEqual($event->name, 'IntrusionException');
		$this->assertEqual($event->count, 1);
		$this->assertEqual($event->interval, 1);
		$this->assertEqual(count($event->actions), 3);
		$this->assertEqual($event->actions[0], 'disable');
		$this->assertEqual($event->actions[1], 'log');
		$this->assertEqual($event->actions[2], 'logout');

		// Test the integrity Exception event		
		$event = $config->getQuota('IntegrityException');
		$this->assertEqual($event->name, 'IntegrityException');
		$this->assertEqual($event->count, 10);
		$this->assertEqual($event->interval, 5);
		$this->assertEqual(count($event->actions), 3);
		$this->assertEqual($event->actions[0], 'disable');
		$this->assertEqual($event->actions[1], 'log');
		$this->assertEqual($event->actions[2], 'logout');

		// Test the integrity Exception event		
		$event = $config->getQuota('AuthenticationHostException');
		$this->assertEqual($event->name, 'AuthenticationHostException');
		$this->assertEqual($event->count, 2);
		$this->assertEqual($event->interval, 10);
		$this->assertEqual(count($event->actions), 2);
		$this->assertEqual($event->actions[0], 'log');
		$this->assertEqual($event->actions[1], 'logout');

		// Check that asking for a bad event doesn't work
		$event = $config->getQuota('ridiculous');
		$this->assertNull($event);
	}

	/**
	 * Gets the name of the ESAPI resource directory as a String.
	 * 
	 * @return The ESAPI resource directory.
	 */
	function testGetResourceDirectory()
	{
		$config = ESAPI::getSecurityConfiguration();
		
		$this->assertEqual($config->getResourceDirectory(), realpath(dirname(__FILE__).'/../testresources/'));
	}

	function testSetResourceDirectoryRealPath() {
		$config = ESAPI::getSecurityConfiguration();
		
		$config->setResourceDirectory(realpath(dirname(__FILE__).'/../testresources/'));
		$this->assertEqual($config->getResourceDirectory(), realpath(dirname(__FILE__).'/../testresources/'));
	}

	function testSetResourceDirectoryNullPath() {
		$config = ESAPI::getSecurityConfiguration();
		
		$config->setResourceDirectory(null);
		$this->assertEqual($config->getResourceDirectory(), null);
	}
	
	/**
	 * Gets the content type for responses used when setSafeContentType() is called.
	 * <br><br>
	 * Note: This does not get the configured character encoding scheme. That is accessed by calling 
	 * getCharacterEncoding().
	 * 
	 * @return The current content-type set for responses.
	 */
	function testResponseContentType()
	{
		$config = ESAPI::getSecurityConfiguration();
		
		$this->assertEqual($config->getResponseContentType(), 'text/html; charset=UTF-8');
	}

	/**
	 * Gets the length of the time to live window for remember me tokens (in milliseconds).
	 * 
	 * @return The time to live length for generated remember me tokens.
	 */
	function testRememberTokenDuration()
	{
		$config = ESAPI::getSecurityConfiguration();
		
		$this->assertEqual($config->getRememberTokenDuration(), 14 * 1000 * 60 * 60 * 24);
	}

	/**
	 * Gets the idle timeout length for sessions (in milliseconds). This is the amount of time that a session
	 * can live before it expires due to lack of activity. Applications or frameworks could provide a reauthenticate
	 * function that enables a session to continue after reauthentication.
	 * 
	 * @return The session idle timeout length.
	 */
	function testSessionIdleTimeoutLength()
	{
		$config = ESAPI::getSecurityConfiguration();
		
		$this->assertEqual($config->getSessionIdleTimeoutLength(), 20 * 60 * 1000);
	}

	/**
	 * Gets the absolute timeout length for sessions (in milliseconds). This is the amount of time that a session
	 * can live before it expires regardless of the amount of user activity. Applications or frameworks could 
	 * provide a reauthenticate function that enables a session to continue after reauthentication.
	 * 
	 * @return The session absolute timeout length.
	 */
	function testSessionAbsoluteTimeoutLength()
	{
		$config = ESAPI::getSecurityConfiguration();
		$this->assertEqual($config->getSessionAbsoluteTimeoutLength(), 120 * 60 * 1000);
	}

	/**
	 * Returns whether HTML entity encoding should be applied to log entries.
	 * 
	 * @return True if log entries are to be HTML Entity encoded. False otherwise.
	 */
	function testLogEncodingRequired()
	{
		$config = ESAPI::getSecurityConfiguration();
		$this->assertEqual($config->getLogEncodingRequired(), false);
	}

	/**
	 * Get the log level specified in the ESAPI configuration properties file. Return a default 
	 * value if it is not specified in the properties file.
	 * 
	 * @return the logging level defined in the properties file. If none is specified, the default 
	 * of Logger.WARNING is returned.
	 */
	function testLogLevel()
	{
		$config = ESAPI::getSecurityConfiguration();
		$this->assertEqual($config->getLogLevel(), 'ALL');		// TODO Replace with Logger:ALL when Logger is complete
	}

	/**
	 * Get the name of the log file specified in the ESAPI configuration properties file. Return a default value 
	 * if it is not specified.
	 * 
	 * @return the log file name defined in the properties file.
	 */
	function testLogFileName()
	{
		$config = ESAPI::getSecurityConfiguration();
		$this->assertEqual($config->getLogFileName(),'ESAPI_logging_file');
	}

	/**
	 * Get the maximum size of a single log file from the ESAPI configuration properties file. Return a default value 
	 * if it is not specified. Once the log hits this file size, it will roll over into a new log.
	 * 
	 * @return the maximum size of a single log file (in bytes).
	 */
	function testMaxLogFileSize()
	{
		$config = ESAPI::getSecurityConfiguration();
		$this->assertEqual($config->getMaxLogFileSize(), 10000000);
	}
	
	function testValidationPattern()
	{
		$config = ESAPI::getSecurityConfiguration();
		$this->assertEqual($config->getValidationPattern("SafeString"), '^[\\p{L}\\p{N}.]{0,1024}$');
	}
		
	function testWorkingDirectory()
	{
		$config = ESAPI::getSecurityConfiguration();
		
		$directory = $config->getWorkingDirectory();
		
		if ( substr(PHP_OS, 0, 3) == 'WIN' ) {
			$this->assertEqual($directory, '%SYSTEMROOT%\\Temp');	
		} else {
			$this->assertEqual($directory, '/tmp');
		}
	}
	
	function testAllowedExecutables()
	{
		$config = ESAPI::getSecurityConfiguration();
	
		$exes = $config->getAllowedExecutables();
		$this->assertEqual(count($exes), 2);
				
		if ( substr(PHP_OS, 0, 3) == 'WIN' ) {
			$this->assertTrue(in_array('%SYSTEMROOT%\\System32\\cmd.exe', $exes));  			// 1
			$this->assertTrue(in_array('%SYSTEMROOT%\\System32\\runas.exe', $exes));  			// 1	
		} else {
			$this->assertTrue(in_array('/bin/sh', $exes));  										// 1
			$this->assertTrue(in_array('/usr/bin/sudo', $exes));  									// 1
		}
	}
}
?>