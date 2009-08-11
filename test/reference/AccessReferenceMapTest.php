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
require_once dirname(__FILE__).'/../../src/reference/RandomAccessReferenceMap.php';
 
class AccessReferenceMapTest extends UnitTestCase 
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
	 * Test of iterator method, of class org.owasp.esapi.AccessReferenceMap.
	 */
    function testIterator() 
    {
//    	global $ESAPI;
    	
        $auth = ESAPI::getAuthenticator();
        
        $arm = new RandomAccessReferenceMap();
		$arm->update($auth->getUserNames());
		
		$i = $arm->iterator();
		while ( $i->valid() ) {
			$userName = $arm->getDirectReference($i->current());
			$u = $auth->getUserByName( $userName );
 	 	 	$this->assertNotNull($u, "Username = [".$userName."] not found, produced null user");
			$i->next();
		}
    }
    
	/**
     *
     * @throws org.owasp.esapi.errors.AccessControlException
     */
    function testRemoveDirectReference() 
    {
        
        $directReference = "234";
        
        $directArray = array();
        $directArray[] = "123";
        $directArray[] = $directReference;
        $directArray[] = "345";
        
        $instance = new RandomAccessReferenceMap( $directArray );
        
        $indirect = $instance->getIndirectReference($directReference);
        $this->assertNotNull($indirect);
        $deleted = $instance->removeDirectReference($directReference);
        $this->assertEqual($indirect,$deleted);
    	$deleted = $instance->removeDirectReference("ridiculous");
    	$this->assertNull($deleted);
    }
    
    /**
	 * Test of getIndirectReference method, of class
	 * org.owasp.esapi.AccessReferenceMap.
	 */
    function testGetIndirectReference()
	{	
        $directReference = "234";
        
        $directArray = array();
        $directArray[] = "123";
        $directArray[] = $directReference;
        $directArray[] = "345";
        
        $instance = new RandomAccessReferenceMap( $directArray );
        
        $expResult = $directReference;
        $result = $instance->getIndirectReference($directReference);
		$this->assertNotIdentical($expResult, $result);
    }

    /**
	 * Test of getDirectReference method, of class
	 * org.owasp.esapi.AccessReferenceMap.
	 * 
	 * @throws AccessControlException
	 *             the access control exception
	 */
    function testGetDirectReference()  
    {
        $directReference = "234";
        
        $directArray = array();
        $directArray[] = "123";
        $directArray[] = $directReference;
        $directArray[] = "345";
        
        $instance = new RandomAccessReferenceMap( $directArray );
        
        $ind = $instance->getIndirectReference($directReference);
        $dir = $instance->getDirectReference($ind);
        
        // echo "<p>ind = [$ind], dir = [$dir], directreference = [$directReference]";
        
        $this->assertEqual($directReference, $dir);
        try 
        {
        	$instance->getDirectReference("invalid");
        	$this->fail();
        }
		catch ( AccessControlException $e ) 
		{
        	// success
        }
    }
    
    /**
     *
     * @throws org.owasp.esapi.errors.AccessControlException
     */
    function testAddDirectReference() 
    {
        
        $directReference = "234";
        
        $directArray = array();
        $directArray[] = "123";
        $directArray[] = $directReference;
        $directArray[] = "345";
        
        $instance = new RandomAccessReferenceMap( $directArray );
        
        $newDirect = $instance->addDirectReference("newDirect"); 
        $this->assertNotNull( $newDirect ); 
        $ind = $instance->addDirectReference($directReference); 
        $dir = $instance->getDirectReference($ind); 
        $this->assertEqual($directReference, $dir); 
    	$newInd = $instance->addDirectReference($directReference); 
    	$this->assertEqual($ind, $newInd); 
    }
    
	/**
	 * Test of update method of class org.owasp.esapi.AccessReferenceMap
	 * 
	 * @throws AuthenticationException
     *             the authentication exception
     * @throws EncryptionException
	 */
    function testUpdate() 
	{
//		global $ESAPI;
    	
    	$auth = ESAPI::getAuthenticator();
    	$pass = $auth->generateStrongPassword();
    	$u = $auth->createUser( "armUpdate", $pass, $pass );

    	// test to make sure update returns something
    	
		$arm = new RandomAccessReferenceMap();
		$arm->update( $auth->getUserNames() );
		
		$indirect = $arm->getIndirectReference( $u->getAccountName() );
		$this->assertNotNull($indirect, "Account name [".$u->getAccountName()."] has no indirect mapping");
		
		// test to make sure update removes items that are no longer in the list
		$auth->removeUser( $u->getAccountName() );
		$arm->update($auth->getUserNames());
		$indirect = $arm->getIndirectReference( $u->getAccountName() );
		$this->assertNull($indirect, "Account name [".$u->getAccountName()."] has indirect mapping [".$indirect."]");
		
		// test to make sure old indirect reference is maintained after an update
		$arm->update($auth->getUserNames());
		$newIndirect = $arm->getIndirectReference( $u->getAccountName() );
		$this->assertEqual($indirect, $newIndirect);
    }

}
?>