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
class HttpUtilitiesTest extends UnitTestCase 
{
	function setUp() 
	{
		
	}
	
	function tearDown()
	{
		
	}
	
/**
     * Test of addCSRFToken method, of class org.owasp.esapi.HTTPUtilities.
     * @throws AuthenticationException 
     */
    function testAddCSRFToken() {
//		global $ESAPI;
//    	
//		$instance = $ESAPI->authenticator();
//		$username = $ESAPI->randomizer()->getRandomString(8, DefaultEncoder::CHAR_ALPHANUMERICS);
//		$user = $instance->createUser($username, "addCSRFToken", "addCSRFToken");
//		$instance->setCurrentUser($user);
//
//
//		$csrf1 = $ESAPI->httpUtilities()->addCSRFToken("/test1");
//
//		assertTrue(strpos($csrf1, '?') > 0);
//
//		$csrf2 = $ESAPI->httpUtilities()->addCSRFToken("/test1?one=two");
//
//		$this->assertTrue(strpos($csrf2, '&') > 0);
    }


    /**
     * Test of assertSecureRequest method, of class org.owasp.esapi.HTTPUtilities.
     */
    function testAssertSecureRequest() {
//		global $ESAPI;
//		
//        $request = new TestHttpServletRequest();
//        try {
//            $request->setRequestURL( "http://example.com" );
//            $ESAPI->httpUtilities()->assertSecureRequest( $request );
//            $this->fail();
//        } catch ( Exception $e ) {
//            // pass
//        }
//        try {
//            $request->setRequestURL( "ftp://example.com" );
//            $ESAPI->httpUtilities()->assertSecureRequest( $request );
//            $this->fail();
//        } catch( Exception $e ) {
//            // pass
//        }
//        try {
//            $request->setRequestURL( "" );
//            $ESAPI->httpUtilities()->assertSecureRequest( $request );
//            $this->fail();
//        } catch( Exception $e ) {
//            // pass
//        }
//        try {
//            $request->setRequestURL( null );
//            $ESAPI->httpUtilities()->assertSecureRequest( $request );
//            $this->fail();
//        } catch( Exception $e ) {
//            // pass
//        }
//        try {
//            $request->setRequestURL( "https://example.com" );
//            $ESAPI->httpUtilities()->assertSecureRequest( $request );
//            // pass
//        } catch( Exception $e ) {
//            $this->fail();
//        }
     }
        
    
    /**
     * Test of sendRedirect method, of class org.owasp.esapi.HTTPUtilities.
     * 
     * @throws EnterpriseSecurityException
     */
    function testChangeSessionIdentifier()  {

//        TestHttpServletRequest request = new TestHttpServletRequest();
//        TestHttpServletResponse response = new TestHttpServletResponse();
//        TestHttpSession session = (TestHttpSession) request.getSession();
//        ESAPI.httpUtilities().setCurrentHTTP(request, response);
//        session.setAttribute("one", "one");
//        session.setAttribute("two", "two");
//        session.setAttribute("three", "three");
//        String id1 = session.getId();
//        session = (TestHttpSession) ESAPI.httpUtilities().changeSessionIdentifier( request );
//        String id2 = session.getId();
//        assertTrue(!id1.equals(id2));
//        assertEquals("one", (String) session.getAttribute("one"));
    }

    /**
     * Test of formatHttpRequestForLog method, of class org.owasp.esapi.HTTPUtilities.
     * @throws IOException 
     */
    function testGetFileUploads() {
$this->fail(); // DELETE ME ("getFileUploads");
//        File home = new File( System.getProperty("user.home" ) + "/.esapi", "uploads" );
//        String content = "--ridiculous\r\nContent-Disposition: form-data; name=\"upload\"; filename=\"testupload.txt\"\r\nContent-Type: application/octet-stream\r\n\r\nThis is a test of the multipart broadcast system.\r\nThis is only a test.\r\nStop.\r\n\r\n--ridiculous\r\nContent-Disposition: form-data; name=\"submit\"\r\n\r\nSubmit Query\r\n--ridiculous--\r\nEpilogue";
//        
//        TestHttpServletRequest request1 = new TestHttpServletRequest("/test", content.getBytes());
//        TestHttpServletResponse response = new TestHttpServletResponse();
//        ESAPI.httpUtilities().setCurrentHTTP(request1, response);
//        try {
//            ESAPI.httpUtilities().getSafeFileUploads(request1, home, home);
//            fail();
//        } catch( ValidationException e ) {
//        	// expected
//        }
//        
//        TestHttpServletRequest request2 = new TestHttpServletRequest("/test", content.getBytes());
//        request2.setContentType( "multipart/form-data; boundary=ridiculous");
//        ESAPI.httpUtilities().setCurrentHTTP(request2, response);
//        try {
//            List list = ESAPI.httpUtilities().getSafeFileUploads(request2, home, home);
//            Iterator i = list.iterator();
//            while ( i.hasNext() ) {
//            	File f = (File)i.next();
//            	System.out.println( "  " + f.getAbsolutePath() );
//            }
//            assertTrue( list.size() > 0 );
//        } catch (ValidationException e) {
//            fail();
//        }
//        
//        TestHttpServletRequest request3 = new TestHttpServletRequest("/test", content.replaceAll("txt", "ridiculous").getBytes());
//        request3.setContentType( "multipart/form-data; boundary=ridiculous");
//        ESAPI.httpUtilities().setCurrentHTTP(request3, response);
//        try {
//            ESAPI.httpUtilities().getSafeFileUploads(request3, home, home);
//            fail();
//        } catch (ValidationException e) {
//        	// expected
//        }
    }

    /**
     * Test of isValidHTTPRequest method, of class org.owasp.esapi.HTTPUtilities.
     */
    function testIsValidHTTPRequest() {
$this->fail(); // DELETE ME ("isValidHTTPRequest");
//        TestHttpServletRequest request = new TestHttpServletRequest();
//        request.addParameter("p1", "v1");
//        request.addParameter("p2", "v3");
//        request.addParameter("p3", "v2");
//        request.addHeader("h1","v1");
//        request.addHeader("h2","v1");
//        request.addHeader("h3","v1");
//        ArrayList list = new ArrayList();
//        list.add(new Cookie("c1", "v1"));
//        list.add(new Cookie("c2", "v2"));
//        list.add(new Cookie("c3", "v3"));
//        request.setCookies(list);
//        ESAPI.httpUtilities().setCurrentHTTP(request, new TestHttpServletResponse() );
//        
//        // should throw IntrusionException which will be caught in isValidHTTPRequest and return false
//        request.setMethod("JEFF");
//        assertFalse( ESAPI.validator().isValidHTTPRequest() );
//         
//        request.setMethod("POST");
//        assertTrue( ESAPI.validator().isValidHTTPRequest() );
//        request.setMethod("GET");
//        assertTrue( ESAPI.validator().isValidHTTPRequest() );
//        request.addParameter("bad_name", "bad*value");
//        request.addHeader("bad_name", "bad*value");
//        list.add(new Cookie("bad_name", "bad*value"));
//        
//        // call the validator directly, since the safe request will shield this from failing
//        assertFalse( ((DefaultValidator)ESAPI.validator()).isValidHTTPRequest( request ) );
     }
    
    
    /**
     * Test of killAllCookies method, of class org.owasp.esapi.HTTPUtilities.
     */
    function testKillAllCookies() {
$this->fail(); // DELETE ME ("killAllCookies");
//        TestHttpServletRequest request = new TestHttpServletRequest();
//        TestHttpServletResponse response = new TestHttpServletResponse();
//        SafeResponse safeResponse = new SafeResponse( response );
//        assertTrue(response.getCookies().isEmpty());
//        ArrayList list = new ArrayList();
//        list.add(new Cookie("test1", "1"));
//        list.add(new Cookie("test2", "2"));
//        list.add(new Cookie("test3", "3"));
//        request.setCookies(list);
//        ESAPI.httpUtilities().killAllCookies(request, safeResponse);
//        // this tests getHeaders because we're using addHeader in our setCookie method
//        assertTrue(response.getHeaderNames().size() == 3);
    }

    /**
     * Test of killCookie method, of class org.owasp.esapi.HTTPUtilities.
     */
    function testKillCookie() {
$this->fail(); // DELETE ME ("killCookie");
//        TestHttpServletRequest request = new TestHttpServletRequest();
//        TestHttpServletResponse response = new TestHttpServletResponse();
//        SafeResponse safeResponse = new SafeResponse( response );
//        ESAPI.httpUtilities().setCurrentHTTP(request, response);
//        assertTrue(response.getCookies().isEmpty());
//        ArrayList list = new ArrayList();
//        list.add(new Cookie("test1", "1"));
//        list.add(new Cookie("test2", "2"));
//        list.add(new Cookie("test3", "3"));
//        request.setCookies(list);
//        ESAPI.httpUtilities().killCookie( request, safeResponse, "test1" );
//        // this tests getHeaders because we're using addHeader in our setCookie method
//        assertTrue(response.getHeaderNames().size() == 1);
    }

    /**
     * Test of sendRedirect method, of class org.owasp.esapi.HTTPUtilities.
     * 
     * @throws ValidationException the validation exception
     * @throws IOException Signals that an I/O exception has occurred.
     */
    function testSendSafeRedirect() {
$this->fail(); // DELETE ME ("sendSafeRedirect");
//        TestHttpServletResponse response = new TestHttpServletResponse();
//        SafeResponse safeResponse = new SafeResponse( response );
//        try {
//        	safeResponse.sendRedirect("/test1/abcdefg");
//            safeResponse.sendRedirect("/test2/1234567");
//        } catch (IOException e) {
//            fail();
//        }
//        try {
//        	safeResponse.sendRedirect("http://www.aspectsecurity.com");
//            fail();
//        } catch (IOException e) {
//            // expected
//        }
//        try {
//            safeResponse.sendRedirect("/ridiculous");
//            fail();
//        } catch (IOException e) {
//            // expected
//        }
    }

    /**
     * Test of setCookie method, of class org.owasp.esapi.HTTPUtilities.
     */
    function testSetCookie() {
$this->fail(); // DELETE ME ("setCookie");
//        TestHttpServletResponse response = new TestHttpServletResponse();
//        SafeResponse safeResponse = new SafeResponse( response );
//        assertTrue(response.getCookies().isEmpty());
//        
//		safeResponse.addCookie( new Cookie( "test1", "test1" ) );
//	    assertTrue(response.getHeaderNames().size() == 1);
//	    
//	    safeResponse.addCookie( new Cookie( "test2", "test2" ) );
//	    assertTrue(response.getHeaderNames().size() == 2);
//
//	    // test illegal name
//	    safeResponse.addCookie( new Cookie( "tes<t3", "test3" ) );
//	    assertTrue(response.getHeaderNames().size() == 2);
//
//	    // test illegal value
//	    safeResponse.addCookie( new Cookie( "test3", "tes<t3" ) );
//	    assertTrue(response.getHeaderNames().size() == 2);
	}

    /**
     *
     * @throws java.lang.Exception
     */
    function testGetStateFromEncryptedCookie() {
    	$this->fail();
//        TestHttpServletRequest request = new TestHttpServletRequest();
//        TestHttpServletResponse response = new TestHttpServletResponse();
//        SafeResponse safeResponse = new SafeResponse( response );
//        
//        // test null cookie array
//        Map empty = ESAPI.httpUtilities().decryptStateFromCookie(request);
//        assertTrue( empty.isEmpty() );
//        
//        HashMap map = new HashMap();
//        map.put( "one", "aspect" );
//        map.put( "two", "ridiculous" );
//        map.put( "test_hard", "&(@#*!^|;,." );
//        try {
//	        ESAPI.httpUtilities().encryptStateInCookie(safeResponse, map);
//	        String value = response.getHeader( "Set-Cookie" );
//	        String encrypted = value.substring(value.indexOf("=")+1, value.indexOf(";"));
//	        request.setCookie( "state", encrypted );
//	        Map state = ESAPI.httpUtilities().decryptStateFromCookie(request);
//	        Iterator i = map.entrySet().iterator();
//	        while ( i.hasNext() ) {
//	        	Map.Entry entry = (Map.Entry)i.next();
//	        	String origname = (String)entry.getKey();
//	        	String origvalue = (String)entry.getValue();
//	        	if( !state.get( origname ).equals( origvalue ) ) {
//	        		fail();
//	        	}
//	        }
//        } catch( EncryptionException e ) {
//        	fail();
//        }
    }
    
    /**
     *
     */
    function testSaveStateInEncryptedCookie() {
$this->fail(); // DELETE ME ("saveStateInEncryptedCookie");
//        TestHttpServletRequest request = new TestHttpServletRequest();
//        TestHttpServletResponse response = new TestHttpServletResponse();
//        SafeResponse safeResponse = new SafeResponse( response );
//        ESAPI.httpUtilities().setCurrentHTTP(request, response);
//        HashMap map = new HashMap();
//        map.put( "one", "aspect" );
//        map.put( "two", "ridiculous" );
//        map.put( "test_hard", "&(@#*!^|;,." );
//        try {
//	        ESAPI.httpUtilities().encryptStateInCookie(safeResponse,map);
//	        String value = response.getHeader( "Set-Cookie" );
//	        String encrypted = value.substring(value.indexOf("=")+1, value.indexOf(";"));
//        	ESAPI.encryptor().decrypt( encrypted );
//        } catch( EncryptionException e ) {
//        	fail();
//        }
    }
    
    
    /**
     *
     */
    function testSaveTooLongStateInEncryptedCookieException() {
$this->fail();
//        TestHttpServletRequest request = new TestHttpServletRequest();
//        TestHttpServletResponse response = new TestHttpServletResponse();
//        SafeResponse safeResponse = new SafeResponse( response );
//        ESAPI.httpUtilities().setCurrentHTTP(request, response);
//
//        String foo = ESAPI.randomizer().getRandomString(4096, DefaultEncoder.CHAR_ALPHANUMERICS);
//
//        HashMap map = new HashMap();
//        map.put("long", foo);
//        try {
//	        ESAPI.httpUtilities().encryptStateInCookie(safeResponse, map);
//	        fail("Should have thrown an exception");
//        }
//        catch (EncryptionException expected) {
//        	//expected
//        }    	
    }
    
    /**
     * Test set no cache headers.
     */
    function testSetNoCacheHeaders() {
$this->fail();
//        TestHttpServletRequest request = new TestHttpServletRequest();
//        TestHttpServletResponse response = new TestHttpServletResponse();
//        ESAPI.httpUtilities().setCurrentHTTP(request, response);
//        assertTrue(response.getHeaderNames().isEmpty());
//        response.addHeader("test1", "1");
//        response.addHeader("test2", "2");
//        response.addHeader("test3", "3");
//        assertFalse(response.getHeaderNames().isEmpty());
//        ESAPI.httpUtilities().setNoCacheHeaders( response );
//        assertTrue(response.containsHeader("Cache-Control"));
//        assertTrue(response.containsHeader("Expires"));
    }

    /**
     *
     * @throws org.owasp.esapi.errors.AuthenticationException
     */
    function testSetRememberToken() {
		$this->fail();
//        Authenticator instance = (Authenticator)ESAPI.authenticator();
//		String accountName=ESAPI.randomizer().getRandomString(8, DefaultEncoder.CHAR_ALPHANUMERICS);
//		String password = instance.generateStrongPassword();
//		User user = instance.createUser(accountName, password, password);
//		user.enable();
//		TestHttpServletRequest request = new TestHttpServletRequest();
//		request.addParameter("username", accountName);
//		request.addParameter("password", password);
//		TestHttpServletResponse response = new TestHttpServletResponse();
//		instance.login( request, response);
//
//		int maxAge = ( 60 * 60 * 24 * 14 );
//		ESAPI.httpUtilities().setRememberToken( request, response, password, maxAge, "domain", "/" );
		// Can't test this because we're using safeSetCookie, which sets a header, not a real cookie!
		// String value = response.getCookie( Authenticator.REMEMBER_TOKEN_COOKIE_NAME ).getValue();
	    // assertEquals( user.getRememberToken(), value );
	}
}
?>