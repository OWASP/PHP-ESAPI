<?php
/**
 * This class implements the Booz Allen ESAPI Adapter tests.
 * 
 * @author Mike Boberski
 * @created 2009
 * @package org.owasp.esapi
 */

require_once dirname(__FILE__).'/../../src/ESAPI.php';
require_once dirname(__FILE__).'/../../src/adapters/MyCompanyAdapter.php';
class MyCompanyAdapterTest extends UnitTestCase 
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
	
	/**
	 * Required test setup: Update ESAPI.xml to include EmployeeID validation 
	 * string of [0-9][0-9][0-9][0-9][0-9][0-9]
	 */
	function testIsValidEmployeeID() {
		
		$_SECURITY = ESAPI::getAdapter();
		$this->assertTrue($_SECURITY->isValidEmployeeID("123456")); //isValid calls getValid, so have coverage
		$this->assertFalse($_SECURITY->isValidEmployeeID("1234567"));
		$this->assertFalse($_SECURITY->isValidEmployeeID("123"));
		$this->assertFalse($_SECURITY->isValidEmployeeID("abcdef"));
		$this->assertFalse($_SECURITY->isValidEmployeeID("abc"));
		$this->assertFalse($_SECURITY->isValidEmployeeID("123abc"));

	}

}
?>