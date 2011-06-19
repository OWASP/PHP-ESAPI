<?php
/**
 * OWASP Enterprise Security API (ESAPI)
 *
 * This file is part of the Open Web Application Security Project (OWASP)
 * Enterprise Security API (ESAPI) project. For details, please see
 * <a href="http://www.owasp.org/index.php/ESAPI">http://www.owasp.org/index.php/ESAPI</a>.
 *
 * Copyright (c) 2009 The OWASP Foundation
 *
 * The ESAPI is published by OWASP under the BSD license. You should read and accept the
 * LICENSE before you use, modify, and/or redistribute this software.
 *
 * @author Andrew van der Stock < van der aj ( at ) owasp. org >
 * @created 2009
 */

require_once dirname(__FILE__).'/../../src/ESAPI.php';
require_once dirname(__FILE__).'/../../src/codecs/JavaScriptCodec.php';


class JavaScriptCodecTest extends PHPUnit_Framework_TestCase
{
	private $javascriptCodec = null;
	
	function setUp()
	{
		global $ESAPI;

		if ( !isset($ESAPI))
		{
			$ESAPI = new ESAPI();
		}
		
		$this->javascriptCodec = new JavaScriptCodec();
	}
		
/*	function testEncode()
	{
		$immune = array("");
		
		$this->assertEquals( 'TODO', $this->javascriptCodec->encode($immune, '"; eval(alert(/XSS/));') );
	}
*/	
	function testEncodeCharacter()
	{
		$immune = array("");
		
		$this->assertEquals( "\\x3C", $this->javascriptCodec->encode($immune, "<") );
	}	
/*	
	function testDecode()
	{
		$this->assertEquals( '"; eval(alert(/XSS/));', $this->javascriptCodec->decode('TODO') );
	}
*/		
	function testDecodeCharacter()
	{
		$this->assertEquals( "<", $this->javascriptCodec->decode("\\x3C") );
	}
	
}
?>