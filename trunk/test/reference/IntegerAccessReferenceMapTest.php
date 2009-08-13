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
class IntegerAccessReferenceMapTest extends UnitTestCase 
{
	function setUp() 
	{
		
	}
	
	function tearDown()
	{
		
	}
	
	/**
	 * Test of update method, of class org.owasp.esapi.AccessReferenceMap.
	 * 
	 * @throws AuthenticationException
     *             the authentication exception
     * @throws EncryptionException
	 */
    function testUpdate() {
    	$this->fail();
//    	IntegerAccessReferenceMap arm = new IntegerAccessReferenceMap();
//    	Authenticator auth = ESAPI.authenticator();
//    	
//    	String pass = auth.generateStrongPassword();
//    	User u = auth.createUser( "armUpdate", pass, pass );
//    	
//    	// test to make sure update returns something
//		arm.update(auth.getUserNames());
//		String indirect = arm.getIndirectReference( u.getAccountName() );
//		if ( indirect == null ) fail();
//		
//		// test to make sure update removes items that are no longer in the list
//		auth.removeUser( u.getAccountName() );
//		arm.update(auth.getUserNames());
//		indirect = arm.getIndirectReference( u.getAccountName() );
//		if ( indirect != null ) fail();
//		
//		// test to make sure old indirect reference is maintained after an update
//		arm.update(auth.getUserNames());
//		String newIndirect = arm.getIndirectReference( u.getAccountName() );
//		assertEquals(indirect, newIndirect);
    }
    
    
    /**
	 * Test of iterator method, of class org.owasp.esapi.AccessReferenceMap.
	 */
    function testIterator() {
$this->fail(); // DELETE ME ("iterator");
//    	IntegerAccessReferenceMap arm = new IntegerAccessReferenceMap();
//        Authenticator auth = ESAPI.authenticator();
//        
//		arm.update(auth.getUserNames());
//
//		Iterator i = arm.iterator();
//		while ( i.hasNext() ) {
//			String userName = (String)i.next();
//			User u = auth.getUser( userName );
//			if ( u == null ) fail();
//		}
    }
    
    /**
	 * Test of getIndirectReference method, of class
	 * org.owasp.esapi.AccessReferenceMap.
	 */
    function testGetIndirectReference() {
$this->fail(); // DELETE ME ("getIndirectReference");
//        
//        String directReference = "234";
//        Set list = new HashSet();
//        list.add( "123" );
//        list.add( directReference );
//        list.add( "345" );
//        IntegerAccessReferenceMap instance = new IntegerAccessReferenceMap( list );
//        
//        String expResult = directReference;
//        String result = instance.getIndirectReference(directReference);
//        assertNotSame(expResult, result);        
    }

    /**
	 * Test of getDirectReference method, of class
	 * org.owasp.esapi.AccessReferenceMap.
	 * 
	 * @throws AccessControlException
	 *             the access control exception
	 */
    function testGetDirectReference() {
$this->fail(); // DELETE ME ("getDirectReference");
//        
//        String directReference = "234";
//        Set list = new HashSet();
//        list.add( "123" );
//        list.add( directReference );
//        list.add( "345" );
//        IntegerAccessReferenceMap instance = new IntegerAccessReferenceMap( list );
//        
//        String ind = instance.getIndirectReference(directReference);
//        String dir = (String)instance.getDirectReference(ind);
//        assertEquals(directReference, dir);
//        try {
//        	instance.getDirectReference("invalid");
//        	fail();
//        } catch( AccessControlException e ) {
//        	// success
//        }
    }
    
    /**
     *
     * @throws org.owasp.esapi.errors.AccessControlException
     */
    function testAddDirectReference() {
$this->fail(); // DELETE ME ("addDirectReference");
//        
//        String directReference = "234";
//        Set list = new HashSet();
//        list.add( "123" );
//        list.add( directReference );
//        list.add( "345" );
//        IntegerAccessReferenceMap instance = new IntegerAccessReferenceMap( list );
//        
//        String newDirect = instance.addDirectReference("newDirect");
//        assertNotNull( newDirect );
//        String ind = instance.addDirectReference(directReference);
//        String dir = (String)instance.getDirectReference(ind);
//        assertEquals(directReference, dir);
//    	String newInd = instance.addDirectReference(directReference);
//    	assertEquals(ind, newInd);
    }

    /**
     *
     * @throws org.owasp.esapi.errors.AccessControlException
     */
    function testRemoveDirectReference() {
$this->fail(); // DELETE ME ("removeDirectReference");
//        
//        String directReference = "234";
//        Set list = new HashSet();
//        list.add( "123" );
//        list.add( directReference );
//        list.add( "345" );
//        IntegerAccessReferenceMap instance = new IntegerAccessReferenceMap( list );
//        
//        String indirect = instance.getIndirectReference(directReference);
//        assertNotNull(indirect);
//        String deleted = instance.removeDirectReference(directReference);
//        assertEquals(indirect,deleted);
//    	deleted = instance.removeDirectReference("ridiculous");
//    	assertNull(deleted);
    }
}
?>