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
 * @since 1.6
 */
 
require_once dirname(__FILE__).'/../../src/ESAPI.php';
require_once dirname(__FILE__).'/../../src/reference/IntegerAccessReferenceMap.php';
 
class IntegerReferenceMapTest extends UnitTestCase 
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
		$users = array ("andrew", "bipin", "laura", "jah", "linden", "mike", "arnaud");        
        
        $arm = new IntegerAccessReferenceMap();
		$arm->update($users);
		
		$i = $arm->iterator();
		$j = 0;
		while ( $i->valid() ) {
			$userName = $arm->getDirectReference($i->current());
			$u = $users[$j];
 	 	 	$this->assertEqual($u, $userName);
			$i->next();
			$j++;
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
        
        $instance = new IntegerAccessReferenceMap( $directArray );
        
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
        
        $instance = new IntegerAccessReferenceMap( $directArray );
        
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
        
        $instance = new IntegerAccessReferenceMap( $directArray );
        
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
        
        $instance = new IntegerAccessReferenceMap( $directArray );
        
        $newDirect = $instance->addDirectReference("newDirect"); 
        $this->assertNotNull( $newDirect ); 
        $ind = $instance->addDirectReference($directReference); 
        $dir = $instance->getDirectReference($ind); 
        $this->assertEqual($directReference, $dir); 
    	$newInd = $instance->addDirectReference($directReference); 
    	$this->assertEqual($ind, $newInd); 
    }
    
	function testUpdatePass() 
	{
		$users = array ('alpha', 'juliet', 'victor');
        
        $arm = new IntegerAccessReferenceMap();
        $arm->update($users);

        $indirect = $arm->getIndirectReference('victor');
		$this->assertNotNull($indirect);
    }
    
    function testUpdateFail() 
	{
		$users = array ('alpha', 'juliet', 'victor');
        
        $arm = new IntegerAccessReferenceMap();
        $arm->update($users);

        $indirect = $arm->getIndirectReference('ridiculous');
		$this->assertNull($indirect);
	}
	
    function testUpdateRemoveItem() {    
		$users = array ('alpha', 'juliet', 'victor');
        
        $arm = new IntegerAccessReferenceMap();
        $arm->update($users);

        unset($users[1]);
        $arm->update($users);
        
        $indirect = $arm->getIndirectReference('juliet');
		$this->assertNull($indirect);
    }
    
    function testUpdateStableReference() {
		$users = array ('alpha', 'juliet', 'victor');
        
        $arm = new IntegerAccessReferenceMap();
        $arm->update($users);
		$indirect = $arm->getIndirectReference('juliet');

		$users[] = 'omega'; 
		
        $arm->update($users);
        
        $indirect2 = $arm->getIndirectReference('juliet');
		$this->assertEqual($indirect, $indirect2);
    }

}
?>