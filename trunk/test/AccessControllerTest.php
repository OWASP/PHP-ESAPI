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

require_once('../src/org.owasp.esapi.AccessController.php');

/**
 * The Class AccessControllerTest.
 * 
 * @author Jeff Williams (jeff.williams@aspectsecurity.com)
 */
class AccessControllerTest extends UnitTestCase {

	private $authenticator;

	/**
	 * Instantiates a new access controller test.
	 * 
	 * @param testName
	 *            the test name
	 */
	function AccessControllerTest() {
	}

	/* (non-Javadoc)
	 * @see junit.framework.TestCase#setUp()
	 */
	function setUp()  {
		global $ESAPI;
				
		$this->authenticator = $ESAPI->authenticator();
		$password = $authenticator->generateStrongPassword();

		// create a user with the "user" role for this test
		$alice = $authenticator->getUser("testuser1");
		if ( $alice == null ) {
			$alice = $authenticator->createUser( "testuser1", $password, $password);
		}
		$alice.addRole("user");		

		// create a user with the "admin" role for this test
		$bob = $authenticator->getUser("testuser2");
		if ( $bob == null ) {
			$bob = $authenticator->createUser( "testuser2", $password, $password);
		}
		$bob->addRole("admin");
		
		// create a user with the "user" and "admin" roles for this test
		$mitch = $authenticator->getUser("testuser3");
		if ( $mitch == null ) {
			$mitch = $authenticator->createUser( "testuser3", $password, $password);
		}
		$mitch->addRole("admin");
		$mitch->addRole("user");
	}

	/* (non-Javadoc)
	 * @see junit.framework.TestCase#tearDown()
	 */
	function tearDown()  {
		// none
	}

	/**
	 * Test of isAuthorizedForURL method, of class
	 * org.owasp.esapi.AccessController.
	 */
	function testIsAuthorizedForURL()  {
		global $ESAPI;
		
		echo "<!-- isAuthorizedForURL --!>";
		$instance = $ESAPI->accessController();
		$auth = $ESAPI->authenticator();
		
		$auth->setCurrentUser( $auth->getUser("testuser1") );
		assertFalse($instance->isAuthorizedForURL("/nobody"));
		assertFalse($instance->isAuthorizedForURL("/test/admin"));
		assertTrue($instance->isAuthorizedForURL("/test/user"));
		assertTrue($instance->isAuthorizedForURL("/test/all"));
		assertFalse($instance->isAuthorizedForURL("/test/none"));
		assertTrue($instance->isAuthorizedForURL("/test/none/test.gif"));
		assertFalse($instance->isAuthorizedForURL("/test/none/test.exe"));

		$auth->setCurrentUser( $auth->getUser("testuser2") );
		assertFalse($instance->isAuthorizedForURL("/nobody"));
		assertTrue($instance->isAuthorizedForURL("/test/admin"));
		assertFalse($instance->isAuthorizedForURL("/test/user"));
		assertTrue($instance->isAuthorizedForURL("/test/all"));
		assertFalse($instance->isAuthorizedForURL("/test/none"));
		
		$auth->setCurrentUser( $auth->getUser("testuser3") );
		assertFalse($instance->isAuthorizedForURL("/nobody"));
		assertTrue($instance->isAuthorizedForURL("/test/admin"));
		assertTrue($instance->isAuthorizedForURL("/test/user"));
		assertTrue($instance->isAuthorizedForURL("/test/all"));
		assertFalse($instance->isAuthorizedForURL("/test/none"));
	}

	/**
	 * Test of isAuthorizedForFunction method, of class
	 * org.owasp.esapi.AccessController.
	 */
	function testIsAuthorizedForFunction() {
		echo("<!-- isAuthorizedForFunction --!>");
		$instance = $ESAPI->accessController();
		$auth = $ESAPI->authenticator();
		
		$auth->setCurrentUser( $auth->getUser("testuser1") );
		assertTrue($instance->isAuthorizedForFunction("/FunctionA"));
		assertFalse($instance->isAuthorizedForFunction("/FunctionAdeny"));
		assertFalse($instance->isAuthorizedForFunction("/FunctionB"));
		assertFalse($instance->isAuthorizedForFunction("/FunctionBdeny"));

		$auth->setCurrentUser( $auth->getUser("testuser2") );
		assertFalse($instance->isAuthorizedForFunction("/FunctionA"));
		assertFalse($instance->isAuthorizedForFunction("/FunctionAdeny"));
		assertTrue($instance->isAuthorizedForFunction("/FunctionB"));
		assertFalse($instance->isAuthorizedForFunction("/FunctionBdeny"));

		$auth->setCurrentUser( $auth->getUser("testuser3") );
		assertTrue($instance->isAuthorizedForFunction("/FunctionA"));
		assertFalse($instance->isAuthorizedForFunction("/FunctionAdeny"));
		assertTrue($instance->isAuthorizedForFunction("/FunctionB"));
		assertFalse($instance->isAuthorizedForFunction("/FunctionBdeny"));
	}

	/**
	 * Test of isAuthorizedForData method, of class
	 * org.owasp.esapi.AccessController.
	 */
	function testIsAuthorizedForData() {
		echo("<!-- isAuthorizedForData -->");
		$instance = $ESAPI->accessController();
		$auth = $ESAPI->authenticator();
		
		$auth->setCurrentUser( $auth->getUser("testuser1") );
		assertTrue($instance->isAuthorizedForData("/Data1"));
		assertFalse($instance->isAuthorizedForData("/Data2"));
		assertFalse($instance->isAuthorizedForData("/not_listed"));

		$auth->setCurrentUser( $auth->getUser("testuser2") );
		assertFalse($instance->isAuthorizedForData("/Data1"));
		assertTrue($instance->isAuthorizedForData("/Data2"));
		assertFalse($instance->isAuthorizedForData("/not_listed"));

		$auth->setCurrentUser( $auth->getUser("testuser3") );
		assertTrue($instance->isAuthorizedForData("/Data1"));
		assertTrue($instance->isAuthorizedForData("/Data2"));
		assertFalse($instance->isAuthorizedForData("/not_listed"));
	}

	/**
	 * Test of isAuthorizedForFile method, of class
	 * org.owasp.esapi.AccessController.
	 */
	function testIsAuthorizedForFile() {
		echo("<!-- isAuthorizedForFile -->");
		$instance = $ESAPI->accessController();
		$auth = $ESAPI->authenticator();
		
		$auth->setCurrentUser( $auth->getUser("testuser1") );
		assertTrue($instance->isAuthorizedForFile("/Dir/File1"));
		assertFalse($instance->isAuthorizedForFile("/Dir/File2"));
		assertFalse($instance->isAuthorizedForFile("/Dir/ridiculous"));

		$auth->setCurrentUser( $auth->getUser("testuser2") );
		assertFalse($instance->isAuthorizedForFile("/Dir/File1"));
		assertTrue($instance->isAuthorizedForFile("/Dir/File2"));
		assertFalse($instance->isAuthorizedForFile("/Dir/ridiculous"));

		$auth->setCurrentUser( $auth->getUser("testuser3") );
		assertTrue($instance->isAuthorizedForFile("/Dir/File1"));
		assertTrue($instance->isAuthorizedForFile("/Dir/File2"));
		assertFalse($instance->isAuthorizedForFile("/Dir/ridiculous"));
	}

	/**
	 * Test of isAuthorizedForBackendService method, of class
	 * org.owasp.esapi.AccessController.
	 */
	function testIsAuthorizedForBackendService() {
		echo("<!-- isAuthorizedForBackendService --!>");
		$instance = $ESAPI->accessController();
		$auth = $ESAPI->authenticator();
		
		$auth->setCurrentUser( $auth->getUser("testuser1") );
		assertTrue($instance->isAuthorizedForService("/services/ServiceA"));
		assertFalse($instance->isAuthorizedForService("/services/ServiceB"));
		assertFalse($instance->isAuthorizedForService("/test/ridiculous"));

		$auth->setCurrentUser( $auth->getUser("testuser2") );
		assertFalse($instance->isAuthorizedForService("/services/ServiceA"));
		assertTrue($instance->isAuthorizedForService("/services/ServiceB"));
		assertFalse($instance->isAuthorizedForService("/test/ridiculous"));

		$auth->setCurrentUser( $auth->getUser("testuser3") );
		assertTrue($instance->isAuthorizedForService("/services/ServiceA"));
		assertTrue($instance->isAuthorizedForService("/services/ServiceB"));
		assertFalse($instance->isAuthorizedForService("/test/ridiculous"));
	}
}
?>