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
 * @author Linden Darling <a href="http://www.jds.net.au">JDS Australia</a>
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
		
		$this->assertEqual( 'background\3a expression\28 window\2e x\3f 0\3a \28 alert\28 \2f XSS\2f \29 \2c window\2e x\3d 1\29 \29 \3b ', $this->cssCodec->encode($immune, 'background:expression(window.x?0:(alert(/XSS/),window.x=1));') );
	}
	
	function testEncodeCharacter()
	{
		$immune = array("");
		
		$this->assertEqual( "\\3c ", $this->cssCodec->encode($immune, "<") );
	}	
	
	function testDecode()
	{
		$this->assertEqual( "background:expression(window.x?0:(alert(/XSS/),window.x=1));", $this->cssCodec->decode('background\3a expression\28 window\2e x\3f 0\3a \28 alert\28 \2f XSS\2f \29 \2c window\2e x\3d 1\29 \29 \3b ') );
	}
		
	function testDecodeCharacter()
	{
		$this->assertEqual( "<", $this->cssCodec->decode("\\3c ") );
		$this->assertEqual( "<YEEHAA", $this->cssCodec->decode("\\3cYEEHAA") );
		$this->assertEqual( "<AHAHA", $this->cssCodec->decode("\\3c AHAHA") );
		$this->assertEqual( "?HAHA", $this->cssCodec->decode("\\3cAHAHA") );
		$this->assertEqual( "?g", $this->cssCodec->decode("\\abcdefg") );
		$this->assertEqual( "ESCAPED NEW LINE GETS IGNORED", $this->cssCodec->decode("\\\nESCAP\\\nED NEW\\\n LINE GETS IGNORED\\\n") );	//FIXME: consider adding logic to all ESAPI implementations to handle this situation properly (i.e. without throwing malformed entity exception)
		//$this->assertEqual( "CODEPOINT ZERO NOT RECOGNISED IN CSS", $this->cssCodec->decode("\\0 CODEP\\0 OINT ZER\\0O NOT\\0  RECOGNISED IN CSS\\0") );	//FIXME: this test yeilds an unexpected error when unpacking in Codec
	}
	
}
?>