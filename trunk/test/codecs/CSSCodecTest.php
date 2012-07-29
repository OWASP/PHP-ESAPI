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


class CSSCodecTest extends PHPUnit_Framework_TestCase
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
		
		$this->assertEquals( 'background\3a expression\28 window\2e x\3f 0\3a \28 alert\28 \2f XSS\2f \29 \2c window\2e x\3d 1\29 \29 \3b ', $this->cssCodec->encode($immune, 'background:expression(window.x?0:(alert(/XSS/),window.x=1));') );
	}
	
	function testEncodeCharacter()
	{
		$immune = array("");
		
		$this->assertEquals( "\\3c ", $this->cssCodec->encode($immune, "<") );
	}	
	
	function testDecode()
	{
		$this->assertEquals( "background:expression(window.x?0:(alert(/XSS/),window.x=1));", $this->cssCodec->decode('background\3a expression\28 window\2e x\3f 0\3a \28 alert\28 \2f XSS\2f \29 \2c window\2e x\3d 1\29 \29 \3b ') );
	}
		
	function testDecodeLessThan()
	{
		$this->assertEquals( "<", $this->cssCodec->decode("\\3c ") );
	}
		
	function testDecodeLTNonHexTerminated()
	{
		$this->assertEquals( "<YEEHAA", $this->cssCodec->decode("\\3cYEEHAA") );
	}
		
	function testDecodeLTSpaceTerminated()
	{
		$this->assertEquals( "<AHAHA", $this->cssCodec->decode("\\3c AHAHA") );
	}
		
	function testDecodeUpToFirstNonHex()
	{
		$expected = mb_convert_encoding('&#' . 0x03CA . ';', 'UTF-8', 'HTML-ENTITIES') . 'HAHA';
		$this->assertEquals( $expected, $this->cssCodec->decode("\\3cAHAHA") );
	}
		
	function testDecodeMaxHexChars()
	{
		$this->assertEquals( ' 0', $this->cssCodec->decode('\\0000200') );
	}
		
	function testDoNotDecodeInvalidCodePoint()
	{
		// 0xABCDEF is not a valid code point so the escape seqence is not a
		// valid one.
		$this->assertEquals( '\\abcdefg', $this->cssCodec->decode('\\abcdefg') );
	}
		
	function testDecodeIgnoreEscapedNewline()
	{
		$this->assertEquals( "ESCAPED NEW LINE GETS IGNORED", $this->cssCodec->decode("\\\nESCAP\\\nED NEW\\\n LINE GETS IGNORED\\\n") );	//FIXME: consider adding logic to all ESAPI implementations to handle this situation properly (i.e. without throwing malformed entity exception)
	}

	/*
	 * This test is bogus. The string to be encoded, in its present form,
	 * doesn't contain nulls and the literal '\0' (contained in the string to be
	 * encoded) should not be interpreted (zero is not a valid codepoint
	 * in CSS) and certainly shouldn't be eaten.
	 */
#	function testDecodeEatNullChar()
#	{
#		$this->assertEquals( "CODEPOINT ZERO NOT RECOGNISED IN CSS", $this->cssCodec->decode("\\0 CODEP\\0 OINT ZER\\0O NOT\\0  RECOGNISED IN CSS\\0") );	//FIXME: this test yeilds an unexpected error when unpacking in Codec
#	}

	/*
	 * This test replaces testDecodeEatNullChar with the proper expectation for
	 * that same string.
	 */
	function testDoNotDecodeInvalidZeroCodePoint()
	{
		$this->assertEquals( '\0 CODEP\0 OINT ZER\0O NOT\0  RECOGNISED IN CSS\0', $this->cssCodec->decode('\0 CODEP\\0 OINT ZER\0O NOT\0  RECOGNISED IN CSS\0') );
	}

	function testEncodeZero()
	{
		$this->setExpectedException('InvalidArgumentException');

		$immune = array("");
		$this->cssCodec->encodeCharacter($immune, chr(0x00));
	}
	
}
?>