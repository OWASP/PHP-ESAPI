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
require_once dirname(__FILE__).'/../../src/codecs/CSSCodec.php';


class CSSCodecTest extends UnitTestCase
{
	private $cssCodec = null;
	
	function setUp()
	{
		global $ESAPI;

		if ( !isset($ESAPI))
		{
			$ESAPI = new ESAPI();
		}
		
		$this->cssCodec = new CSSCodec();
	}
		
	function testEncode()
	{
		$immune = array("");
		
		$this->assertEqual( 'TODO', $this->cssCodec->encode($immune, 'background:expression(window.x?0:(alert(/XSS/),window.x=1));') );
	}
	
	function testEncodeCharacter()
	{
		$immune = array("");
		
		$this->assertEqual( "\\3c", $this->cssCodec->encode($immune, "<") );
	}	
	
	function testDecode()
	{
		$this->assertEqual( "background:expression(window.x?0:(alert(/XSS/),window.x=1));", $this->cssCodec->decode('TODO') );
	}
		
	function testDecodeCharacter()
	{
		$this->assertEqual( "<", $this->cssCodec->decode("\\3c") );
	}
	
}
?>