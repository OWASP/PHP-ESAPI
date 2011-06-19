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
require_once dirname(__FILE__).'/../../src/codecs/UnixCodec.php';

class UnixCodecTest extends PHPUnit_Framework_TestCase
{
	private $unixCodec = null;
	
	function setUp()
	{
		global $ESAPI;

		if ( !isset($ESAPI))
		{
			$ESAPI = new ESAPI();
		}

		$this->unixCodec = new UnixCodec();
	}

	function tearDown()
	{

	}
	
	function testEncode()
	{
		$immune = array("");
		
		$this->assertEquals( '\\"\\;\\ ls\\ \\/\\ \\>\\ \\/tmp\\/foo\\;\\ \\#\\ ', $this->unixCodec->encode($immune, '"; ls / > /tmp/foo; # ') );
	}
	
	function testEncodeCharacter()
	{
		$immune = array("");
		
		$this->assertEquals( "\\<", $this->unixCodec->encode($immune, "<") );
	}	
	
	function testDecode()
	{
		$this->assertEquals( '"; ls / > /tmp/foo; # ', $this->unixCodec->decode('\\"\\;\\ ls\\ \\/\\ \\>\\ \\/tmp\\/foo\\;\\ \\#\\ ') );
	}
		
	function testDecodeCharacter()
	{
		$this->assertEquals( "<", $this->unixCodec->decode("\\<") );
	}
	
}
?>