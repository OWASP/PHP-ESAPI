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
 * @author jah (at jahboite.co.uk)
 * @created 2009
 */

require_once dirname(__FILE__) . '/../http/TestHttpServletRequest.php';
require_once dirname(__FILE__) . '/../http/TestHttpServletResponse.php';

class IntrusionDetectorTest extends UnitTestCase 
{
	// FIXME: remove the next 1 line when DefaultEncoder::CHAR_ALPHANUMERICS is avalable
	private $CHAR_ALPHANUMERICS = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ01234567890';	
	
	function setUp() 
	{
		global $ESAPI;
		if (!isset($ESAPI)) 
		{
			$ESAPI = new ESAPI(dirname(__FILE__).'/../testresources/ESAPI.xml');
		}
	}
	
	function tearDown() {}
	
	/**
	 * Test of addException method, of class org.owasp.esapi.IntrusionDetector.
	 * 
	 * @throws AuthenticationException
	 *             the authentication exception
	 */
	function testAddException()
	{
		ESAPI::getIntrusionDetector()->addException(new IntrusionException("user message", "log message"));
		$username = ESAPI::getRandomizer()->getRandomString(8, $this->CHAR_ALPHANUMERICS); // FIXME: DefaultEncoder::CHAR_ALPHANUMERICS
		$auth = ESAPI::getAuthenticator();
		$user = $auth->createUser($username, 'addException', 'addException');
		$user->enable();
		$request  = null; // FIXME: new TestHttpServletRequest();
		$response = null; // FIXME: new TestHttpServletResponse();
		ESAPI::getHttpUtilities()->setCurrentHTTP($request, $response);
		$user->loginWithPassword('addException');
			
		// Now generate some exceptions to disable account
		for ( $i = 0; $i < ESAPI::getSecurityConfiguration()->getQuota('IntegrityException')->count; $i++ )
		{
			// EnterpriseSecurityExceptions are added to IntrusionDetector automatically
			new IntegrityException("IntegrityException " + $i, "IntegrityException " + $i);
		}
		$this->assertFalse($user->isLoggedIn());
	}
	
	/**
	 * Test of addEvent method, of class org.owasp.esapi.IntrusionDetector.
	 *
	 * @throws AuthenticationException
	 *             the authentication exception
	 */
    function testAddEvent()
    {
        $username = ESAPI::getRandomizer()->getRandomString(8, $this->CHAR_ALPHANUMERICS); // FIXME: DefaultEncoder::CHAR_ALPHANUMERICS
        $auth = ESAPI::getAuthenticator();
		$user = $auth->createUser($username, 'addEvent', 'addEvent');
		$user->enable();
		$request  = null; // FIXME: new TestHttpServletRequest();
		$response = null; // FIXME: new TestHttpServletResponse();
		ESAPI::getHttpUtilities()->setCurrentHTTP($request, $response);
		$user->loginWithPassword('addEvent');
        
        // Now generate some events to disable user account
        for ( $i = 0; $i < ESAPI::getSecurityConfiguration()->getQuota('test')->count; $i++ )
        {
            ESAPI::getIntrusionDetector()->addEvent("test", "test message");
        }
        $this->assertFalse($user->isEnabled());
    }
}
?>