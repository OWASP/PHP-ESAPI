<?php
/**
 * OWASP Enterprise Security API (ESAPI)
 *
 * This file is part of the Open Web Application Security Project (OWASP)
 * Enterprise Security API (ESAPI) project. For details, please see
 * <a href="http://www.owasp.org/index.php/ESAPI">http://www.owasp.org/index.php/ESAPI</a>.
 *
 * Copyright (c) 2007 - The OWASP Foundation
 *
 * The ESAPI is published by OWASP under the BSD license. You should read and accept the
 * LICENSE before you use, modify, and/or redistribute this software.
 *
 * @author Jeff Williams <a href="http://www.aspectsecurity.com">Aspect Security</a>
 * @created 2007
 */

require_once dirname(__FILE__).'/../../src/ESAPI.php';
require_once dirname(__FILE__).'/../../src/codecs/Base64Codec.php';
require_once dirname(__FILE__).'/../../src/codecs/Codec.php';
require_once dirname(__FILE__).'/../../src/codecs/CSSCodec.php';
require_once dirname(__FILE__).'/../../src/codecs/HTMLEntityCodec.php';
require_once dirname(__FILE__).'/../../src/codecs/JavaScriptCodec.php';
require_once dirname(__FILE__).'/../../src/codecs/LDAPCodec.php';
require_once dirname(__FILE__).'/../../src/codecs/MySQLCodec.php';
require_once dirname(__FILE__).'/../../src/codecs/OracleCodec.php';
require_once dirname(__FILE__).'/../../src/codecs/PercentCodec.php';
require_once dirname(__FILE__).'/../../src/codecs/UnixCodec.php';
require_once dirname(__FILE__).'/../../src/codecs/VBScriptCodec.php';
require_once dirname(__FILE__).'/../../src/codecs/WindowsCodec.php';


class CodecTest extends UnitTestCase
{
	private static $htmlCodec = null;
    private static $percentCodec = null;
    private static $javaScriptCodec = null;
    private static $vbScriptCodec = null;
    private static $cssCodec = null;
    private static $mySQLCodecANSI = null;
    private static $mySQLCodecStandard = null;
    private static $oracleCodec = null;
    private static $unixCodec = null;
    private static $windowsCodec = null;
	
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
	
	function testEncode()
	{
		$immune = array("");
		
		// unixCodec
		$unixCodec = new UnixCodec();
		$this->assertEqual("\\<", $unixCodec->encode($immune, "<"));
		
		// windowsCodec
		$windowsCodec = new WindowsCodec();
		$this->assertEqual("^<", $windowsCodec->encode($immune, "<"));
		
/*		
	    System.out.println("encode");

        char[] immune = new char[0];
        
        // htmlCodec
        assertEquals( "test", htmlCodec.encode( immune, "test") );
        
        // percentCodec
        assertEquals( "%3c", percentCodec.encode(immune, "<") );

        // javaScriptCodec
        assertEquals( "\\x3C", javaScriptCodec.encode(immune, "<") );
        
        // vbScriptCodec
        assertEquals( "chrw(60)", vbScriptCodec.encode(immune, "<") );

        // cssCodec
        assertEquals( "\\3c ", cssCodec.encode(immune, "<") );

        // mySQLCodecANSI
        assertEquals( "\'\'", mySQLCodecANSI.encode(immune, "\'") );

        // mySQLCodecStandard
        assertEquals( "\\<", mySQLCodecStandard.encode(immune, "<") );

        // oracleCodec
        assertEquals( "\\<", oracleCodec.encode(immune, "<") );

        // unixCodec
        assertEquals( "\\<", unixCodec.encode(immune, "<") );

        // windowsCodec
        assertEquals( "^<", windowsCodec.encode(immune, "<") );
	
*/		
	}
	
	function testEncodeCharacter()
	{
		$this->fail();
	}	
	
	function testDecode()
	{
		// unixCodec
		$unixCodec = new UnixCodec();
		$this->assertEqual("<", $unixCodec->decode("\\<"));

        // windowsCodec
        $windowsCodec = new WindowsCodec();
		$this->assertEqual("<", $windowsCodec->decode("^<"));
		
/*
        // htmlCodec
        assertEquals( "test!", htmlCodec.decode("&#116;&#101;&#115;&#116;!") );
        assertEquals( "test!", htmlCodec.decode("&#x74;&#x65;&#x73;&#x74;!") );
        assertEquals( "&jeff;", htmlCodec.decode("&jeff;") );

        // percentCodec
        assertEquals( "<", percentCodec.decode("%3c") );

        // javaScriptCodec
        assertEquals( "<", javaScriptCodec.decode("\\x3c") );
        
        // vbScriptCodec
        assertEquals( "<", vbScriptCodec.decode("\"<") );

        // cssCodec
        assertEquals( "<", cssCodec.decode("\\<") );

        // mySQLCodecANSI
        assertEquals( "\'", mySQLCodecANSI.decode("\'\'") );

        // mySQLCodecStandard
        assertEquals( "<", mySQLCodecStandard.decode("\\<") );

        // oracleCodec
        assertEquals( "<", oracleCodec.decode("\\<") );

        // unixCodec
        assertEquals( "<", unixCodec.decode("\\<") );

        // windowsCodec
        assertEquals( "<", windowsCodec.decode("^<") );
 */
	}
		
	function testDecodeCharacter()
	{
		$this->fail();
	}
	
}
?>