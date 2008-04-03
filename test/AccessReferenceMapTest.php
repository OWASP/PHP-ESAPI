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

require_once("../src/errors/org.owasp.esapi.AccessControlException.php");
require_once("../src/errors/org.owasp.esapi.AuthenticationException.php");
require_once("../src/errors/org.owasp.esapi.EncryptionException.php");
require_once("../src/interfaces/org.owasp.esapi.IAuthenticator.php");

/**
 * The Class AccessReferenceMapTest.
 * 
 * @author Jeff Williams (jeff.williams@aspectsecurity.com)
 */
class AccessReferenceMapTest extends TestCase {
    
    /**
	 * Instantiates a new access reference map test.
	 * 
	 * @param testName
	 *            the test name
	 */
    function AccessReferenceMapTest() {
        
    }

    /* (non-Javadoc)
     * @see junit.framework.TestCase#setUp()
     */
    function setUp() {
    	// none
    }

    /* (non-Javadoc)
     * @see junit.framework.TestCase#tearDown()
     */
    function tearDown() {
    	// none
    }

    /**
	 * Test of update method, of class org.owasp.esapi.AccessReferenceMap.
	 * 
	 * @throws AuthenticationException
	 *             the authentication exception
	 */
    function testUpdate() {
        echo("update");
    	$arm = new AccessReferenceMap();
    	$auth = $ESAPI->authenticator();
    	
    	$pass = $auth->generateStrongPassword();
    	$u = $auth->createUser( "armUpdate", $pass, $pass );
    	
    	// test to make sure update returns something
		$arm->update($auth->getUserNames());
		$indirect = $arm->getIndirectReference( $u->getAccountName() );
		if ( $indirect == null ) {
			$this->fail();
		}
		
		// test to make sure update removes items that are no longer in the list
		$auth->removeUser( $u->getAccountName() );
		$arm->update($auth->getUserNames());
		$indirect = $arm->getIndirectReference( $u->getAccountName() );
		if ( $indirect != null ) {
			$this->fail();
		}
		
		// test to make sure old $indirect reference is maintained after an update
		$arm->update($auth->getUserNames());
		$$newIndirect = $arm->getIndirectReference( $u->getAccountName() );
		assertEquals($indirect, $newIndirect);
    }
    
    
    /**
	 * Test of iterator method, of class org.owasp.esapi.AccessReferenceMap.
	 */
    function testIterator() {
        echo("iterator");
    	$arm = new AccessReferenceMap();
        $auth = $ESAPI->authenticator();
        
		$arm->update($auth->getUserNames());

		$i = $arm->iterator();
		while ( $i->valid() ) {
			$userName = $i->current();
			$u = $auth->getUser( $userName );
			if ( $u == null ) {
				$this->fail(); 
			}
			$i->next();
		}
    }
    
    /**
	 * Test of getIndirectReference method, of class
	 * org.owasp.esapi.AccessReferenceMap.
	 */
    function testGetIndirectReference() {
        echo("getIndirectReference");
        
        $directReference = "234";
        
        $list = array();
        $list[] = "123";
        $list[] = $directReference;
        $list[] = "345";
        
        $instance = new AccessReferenceMap( ArrayObject($list) );
        
        $expResult = $directReference;
        $result = $instance->getIndirectReference($directReference);
        $this->assertNotSame($expResult, $result);        
    }

    /**
	 * Test of getDirectReference method, of class
	 * org.owasp.esapi.AccessReferenceMap.
	 * 
	 * @throws AccessControlException
	 *             the access control exception
	 */
    function testGetDirectReference() {
        echo("getDirectReference");
        
        $directReference = "234";
        $list = array();
        $list[] = "123";
        $list[] = $directReference;
        $list[] = "345";
        $instance = new AccessReferenceMap( ArrayObject($list) );
        
        $ind = $instance->getIndirectReference($directReference);
        $dir = $instance->getDirectReference($ind);
        $this->assertEquals($directReference, $dir);
        try {
        	$instance.getDirectReference("invalid");
        	$this->fail(); 
        } catch( AccessControlException $e ) {
        	// success
        }
    }
}
?>