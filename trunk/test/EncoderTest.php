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

require_once("../src/errors/org.owasp.esapi.EncodingException.php");
require_once("../src/errors/org.owasp.esapi.IntrusionException.php");
require_once("../src/errors/org.owasp.esapi.ValidationException.php");
require_once("../src/interfaces/org.owasp.esapi.IEncoder.php");

/**
 * The Class EncoderTest.
 *
 * @author Jeff Williams (jeff.williams@aspectsecurity.com)
 */
class EncoderTest extends TestCase {

    /**
	 * Instantiates a new encoder test.
	 *
	 * @param testName
	 *            the test name
	 */
    function EncoderTest() {

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
	 * Test of canonicalize method, of class org.owasp.esapi.Validator.
	 *
	 * @throws ValidationException
	 */
	public function testCanonicalize() {
		echo("canonicalize");
		$instance = $ESAPI->encoder();
        assertEquals( "<script>alert(\"hello\");</script>", $instance->canonicalize("%3Cscript%3Ealert%28%22hello%22%29%3B%3C%2Fscript%3E") );
        try {
        	assertEquals( "<script", $instance->canonicalize("%253Cscript" ) );
        } catch( IntrusionException $e ) {
        	// expected
        }
        try {
        	assertEquals( "<script", $instance->canonicalize("&#37;3Cscript" ) );
        } catch( IntrusionException $e ) {
        	// expected
        }
	}

	/**
	 * Test of normalize method, of class org.owasp.esapi.Validator.
	 *
	 * @throws ValidationException
	 *             the validation exception
	 */
	public function testNormalize()  {
		echo("normalize");
		assertEquals($ESAPI->encoder().normalize("é à î _ @ \" < > \u20A0"), "e a i _ @ \" < > ");
	}

    public function testEntityEncode() {
    	echo( "entityEncode" );
    	$instance = $ESAPI->encoder();
        assertEquals("&lt;script&gt;", $instance->encodeForHTML("&lt;script&gt;"));
        assertEquals("&#33;&#64;&#36;&#37;&#40;&#41;&#61;&#43;&#123;&#125;&#91;&#93;", $instance->encodeForHTML("&#33;&#64;&#36;&#37;&#40;&#41;&#61;&#43;&#123;&#125;&#91;&#93;") );
    }

    /**
	 * Test of encodeForHTML method, of class org.owasp.esapi.Encoder.
	 */
    public function testEncodeForHTML() {
        echo("encodeForHTML");
        $instance = $ESAPI->encoder();
        assertEquals("", $instance->encodeForHTML(null));
        assertEquals("&lt;script&gt;", $instance->encodeForHTML("<script>"));
        assertEquals(",.-_ ", $instance->encodeForHTML(",.-_ "));
        assertEquals("&#33;&#64;&#36;&#37;&#40;&#41;&#61;&#43;&#123;&#125;&#91;&#93;", $instance->encodeForHTML("!@$%()=+{}[]"));
        assertEquals("dir&amp;", $instance->encodeForHTML("dir&"));
        assertEquals("one&amp;two", $instance->encodeForHTML("one&two"));
    }

    /**
	 * Test of encodeForHTMLAttribute method, of class org.owasp.esapi.Encoder.
	 */
    public function testEncodeForHTMLAttribute() {
        echo("encodeForHTMLAttribute");
        $instance = $ESAPI->encoder();
        assertEquals("&lt;script&gt;", $instance->encodeForHTMLAttribute("<script>"));
        assertEquals(",.-_", $instance->encodeForHTMLAttribute(",.-_"));
        assertEquals("&#32;&#33;&#64;&#36;&#37;&#40;&#41;&#61;&#43;&#123;&#125;&#91;&#93;", $instance->encodeForHTMLAttribute(" !@$%()=+{}[]"));
    }

    /**
	 * Test of encodeForJavaScript method, of class org.owasp.esapi.Encoder.
	 */
    public function testEncodeForJavascript() {
        echo("encodeForJavascript");
        $instance = $ESAPI->encoder();
        assertEquals("&lt;script&gt;", $instance->encodeForJavascript("<script>"));
        assertEquals(",.-_ ", $instance->encodeForJavascript(",.-_ "));
        assertEquals("&#33;&#64;&#36;&#37;&#40;&#41;&#61;&#43;&#123;&#125;&#91;&#93;", $instance->encodeForJavascript("!@$%()=+{}[]"));
    }

    /**
	 * Test of encodeForVisualBasicScript method, of class
	 * org.owasp.esapi.Encoder.
	 */
    public function testEncodeForVBScript() {
        echo("encodeForVBScript");
        $instance = $ESAPI->encoder();
        assertEquals("&lt;script&gt;", $instance->encodeForVBScript("<script>"));
        assertEquals(",.-_ ", $instance->encodeForVBScript(",.-_ "));
        assertEquals("&#33;&#64;&#36;&#37;&#40;&#41;&#61;&#43;&#123;&#125;&#91;&#93;", $instance->encodeForVBScript("!@$%()=+{}[]"));
    }

    /**
	 * Test of encodeForXPath method, of class org.owasp.esapi.Encoder.
	 */
    public function testEncodeForXPath() {
        echo("encodeForXPath");
        $instance = $ESAPI->encoder();
        assertEquals("&#39;or 1&#61;1", $instance->encodeForXPath("'or 1=1"));
    }



    /**
	 * Test of encodeForSQL method, of class org.owasp.esapi.Encoder.
	 */
    public function testEncodeForSQL() {
        echo("encodeForSQL");
        $instance = $ESAPI->encoder();
        assertEquals("Single quote", "Jeff'' or ''1''=''1", $instance->encodeForSQL("Jeff' or '1'='1"));
    }


    /**
	 * Test of encodeForLDAP method, of class org.owasp.esapi.Encoder.
	 */
    public function testEncodeForLDAP() {
        echo("encodeForLDAP");
        $instance = $ESAPI->encoder();
        assertEquals("No special characters to escape", "Hi This is a test #çà", $instance->encodeForLDAP("Hi This is a test #çà"));
        assertEquals("Zeros", "Hi \\00", $instance->encodeForLDAP("Hi \u0000"));
        assertEquals("LDAP Christams Tree", "Hi \\28This\\29 = is \\2a a \\5c test # ç à ô", $instance->encodeForLDAP("Hi (This) = is * a \\ test # ç à ô"));
    }

    /**
	 * Test of encodeForLDAP method, of class org.owasp.esapi.Encoder.
	 */
    public function testEncodeForDN() {
        echo("encodeForDN");
        $instance = $ESAPI->encoder();
        assertEquals("No special characters to escape", "Helloé", $instance->encodeForDN("Helloé"));
        assertEquals("leading #", "\\# Helloé", $instance->encodeForDN("# Helloé"));
        assertEquals("leading space", "\\ Helloé", $instance->encodeForDN(" Helloé"));
        assertEquals("trailing space", "Helloé\\ ", $instance->encodeForDN("Helloé "));
        assertEquals("less than greater than", "Hello\\<\\>", $instance->encodeForDN("Hello<>"));
        assertEquals("only 3 spaces", "\\  \\ ", $instance->encodeForDN("   "));
        assertEquals("Christmas Tree DN", "\\ Hello\\\\ \\+ \\, \\\"World\\\" \\;\\ ", $instance->encodeForDN(" Hello\\ + , \"World\" ; "));
    }


    /**
	 * Test of encodeForXML method, of class org.owasp.esapi.Encoder.
	 */
    public function testEncodeForXML() {
        echo("encodeForXML");
        $instance = $ESAPI->encoder();
        assertEquals(" ", $instance->encodeForXML(" "));
        assertEquals("&lt;script&gt;", $instance->encodeForXML("<script>"));
        assertEquals(",.-_", $instance->encodeForXML(",.-_"));
        assertEquals("&#33;&#64;&#36;&#37;&#40;&#41;&#61;&#43;&#123;&#125;&#91;&#93;", $instance->encodeForXML("!@$%()=+{}[]"));
    }



    /**
	 * Test of encodeForXMLAttribute method, of class org.owasp.esapi.Encoder.
	 */
    public function testEncodeForXMLAttribute() {
        echo("encodeForXMLAttribute");
        $instance = $ESAPI->encoder();
        assertEquals("&#32;", $instance->encodeForXMLAttribute(" "));
        assertEquals("&lt;script&gt;", $instance->encodeForXMLAttribute("<script>"));
        assertEquals(",.-_", $instance->encodeForXMLAttribute(",.-_"));
        assertEquals("&#32;&#33;&#64;&#36;&#37;&#40;&#41;&#61;&#43;&#123;&#125;&#91;&#93;", $instance->encodeForXMLAttribute(" !@$%()=+{}[]"));
    }

    /**
	 * Test of encodeForURL method, of class org.owasp.esapi.Encoder.
	 */
    public function testEncodeForURL()  {
        echo("encodeForURL");
        $instance = $ESAPI->encoder();
        assertEquals("%3Cscript%3E", $instance->encodeForURL("<script>"));
    }

    /**
	 * Test of decodeFromURL method, of class org.owasp.esapi.Encoder.
	 */
    public function testDecodeFromURL()  {
        echo("decodeFromURL");
        $instance = $ESAPI->encoder();
        try {
            assertEquals("<script>", $instance->decodeFromURL("%3Cscript%3E"));
            assertEquals("     ", $instance->decodeFromURL("+++++") );
        } catch ( Exception $e ) {
            fail();
        }
    }

    /**
	 * Test of encodeForBase64 method, of class org.owasp.esapi.Encoder.
	 */
    public function testEncodeForBase64() {
        echo("encodeForBase64");
        $instance = $ESAPI->encoder();
        try {
            for ( $i=0; $i < 100; $i++ ) {
                $r = $ESAPI->randomizer().getRandomString( 20, Encoder::CHAR_SPECIALS );
                $encoded = $instance->encodeForBase64( $r, $ESAPI->randomizer().getRandomBoolean() );
                $decoded = $instance->decodeFromBase64( $encoded );
                assertTrue( $r == $decoded );
            }
        } catch ( IOException $e ) {
            fail();
        }
    }

    /**
	 * Test of decodeFromBase64 method, of class org.owasp.esapi.Encoder.
	 */
    public function testDecodeFromBase64() {
        echo("decodeFromBase64");
        $instance = $ESAPI->encoder();
        for ( $i=0; $i < 100; $i++ ) {
            try {
                $r = $ESAPI->randomizer().getRandomString( 20, Encoder::CHAR_SPECIALS );
                $encoded = $instance->encodeForBase64( $r, $ESAPI->randomizer().getRandomBoolean() );
                $decoded = $instance->decodeFromBase64( $encoded );
                assertTrue( $r == $decoded );
            } catch ( IOException $e ) {
                fail();
	        }
        }
        for ( $i=0; $i < 100; $i++ ) {
            try {
                $r = $ESAPI->randomizer().getRandomString( 20, Encoder::CHAR_SPECIALS );
                $encoded = $ESAPI->randomizer().getRandomString(1, Encoder::CHAR_ALPHANUMERICS) + $instance->encodeForBase64( $r, $ESAPI->randomizer().getRandomBoolean() );
	            $decoded = $instance->decodeFromBase64( $encoded );
	            assertFalse( $r, $decoded );
            } catch ( IOException $e ) {
            	// expected
            }
        }
    }

}
?>