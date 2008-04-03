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
 * @author Andrew van der Stock <a href="http://www.aspectsecurity.com">Aspect Security</a>
 * @package org.owasp.esapi;
 * @since 2008
 */

require_once("../src/errors/org.owasp.esapi.EncryptionException.php");

/**
 * The Class EncryptedPropertiesTest.
 *
 * @author Jeff Williams (jeff.williams@aspectsecurity.com)
 */
class EncryptedPropertiesTest extends TestCase {

	/**
	 * Instantiates a new encrypted properties test.
	 *
	 * @param testName
	 *            the test name
	 */
	public function EncryptedPropertiesTest() {

	}

	/* (non-Javadoc)
	 * @see junit.framework.TestCase#setUp()
	 */
	protected function setUp() {
		// none
	}

	/* (non-Javadoc)
	 * @see junit.framework.TestCase#tearDown()
	 */
	protected function tearDown() {
		// none
	}

	/**
	 * Test of getProperty method, of class org.owasp.esapi.EncryptedProperties.
	 *
	 * @throws EncryptionException
	 *             the encryption exception
	 */
	public function testGetProperty() {
		echo("getProperty");
		$instance = new EncryptedProperties();
		$name = "name";
		$value = "value";
		$instance->setProperty($name, $value);
		$result = $instance->getProperty($name);
		$this->assertEquals($value, $result);
		try {
			$instance->getProperty("ridiculous");
			$this->fail();
		} catch( Exception $e ) {
			// expected
		}
	}

	/**
	 * Test of setProperty method, of class org.owasp.esapi.EncryptedProperties.
	 *
	 * @throws EncryptionException
	 *             the encryption exception
	 */
	public function testSetProperty() {
		echo("setProperty");
		$instance = new EncryptedProperties();
		$name = "name";
		$value = "value";
		$instance->setProperty($name, $value);
		$result = $instance->getProperty($name);
		$this->assertEquals($value, $result);
		try {
			$instance->setProperty(null, null);
			fail();
		} catch( Exception $e ) {
			// expected
		}
	}


	/**
	 * Test of keySet method, of class org.owasp.esapi.EncryptedProperties.
	 */
	public function testKeySet() {
		echo("keySet");
		$instance = new EncryptedProperties();
		$instance->setProperty("one", "two");
		$instance->setProperty("two", "three");
		$i = $instance->keySet().iterator();
		$this->assertEquals( "two", $i.next() );
		$this->assertEquals( "one", $i.next() );
		try {
			$i.next();
			fail();
		} catch( Exception $e ) {
			// expected
		}
	}

	/**
	 * Test of load method, of class org.owasp.esapi.EncryptedProperties.
	 */
	public function testLoad() {
		echo("load");
		$instance = new EncryptedProperties();
		$f = file_get_contents($ESAPI->securityConfiguration()->getResourceDirectory() . "test.properties");
		$instance->load( $f );
		$this->assertEquals( "two", $instance->getProperty("one" ) );
		$this->assertEquals( "three",  $instance->getProperty("two" ) );
	}

	/**
	 * Test of store method, of class org.owasp.esapi.EncryptedProperties.
	 */
	public function testStore() {
		echo("store");
		$instance = new EncryptedProperties();
		$instance->setProperty("one", "two");
		$instance->setProperty("two", "three");
		$f = $ESAPI->securityConfiguration()->getResourceDirectory() . "test.properties";
		$instance->store($f, "testStore");
	}

	/**
	 * Test of store method, of class org.owasp.esapi.EncryptedProperties.
	 */
	public function testMain() {
		echo("main");
		File f = new File( ((SecurityConfiguration)ESAPI.securityConfiguration()).getResourceDirectory(), "test.properties" );
		String[] args1 = { f.getAbsolutePath() };
		InputStream orig = System.in;
		$input = "key\r\nvalue\r\n";
		System.setIn( new StringBufferInputStream( input ) );
		EncryptedProperties.main(args1);
		System.setIn( orig );
		String[] args2 = { "ridiculous.properties" };
		try {
			EncryptedProperties.main(args2);
			fail();
		} catch( Exception e ) {
			// expected
		}
	}

}
?>