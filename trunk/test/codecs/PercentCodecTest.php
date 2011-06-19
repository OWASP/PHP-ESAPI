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
require_once dirname(__FILE__).'/../../src/codecs/PercentCodec.php';

class PercentCodecTest extends PHPUnit_Framework_TestCase
{
	private $percentCodec = null;
	
	function setUp()
	{
		global $ESAPI;

		if ( !isset($ESAPI))
		{
			$ESAPI = new ESAPI();
		}
		
		$this->percentCodec = new PercentCodec();
	}

	function tearDown()
	{

	}
	
	function testEncode()
	{
		$immune = array("/");
		
		$this->assertEquals( '%22%3B%20ls%20/%20%3E%20/tmp/foo%3B%20%23%20', $this->percentCodec->encode($immune, '"; ls / > /tmp/foo; # ') );
	}
	
	function testEncodeCharacter()
	{
		$immune = array("");
		
		$this->assertEquals( "%3C", $this->percentCodec->encode($immune, "<") );
	}	
	
	function testDecode()
	{
		$this->assertEquals( '"; ls / > /tmp/foo; # ', $this->percentCodec->decode('%22%3B%20ls%20/%20%3E%20/tmp/foo%3B%20%23%20') );
	}
		
	function testDecodeCharacter()
	{
		$this->assertEquals( "<", $this->percentCodec->decode("%3C") );
	}
	
}
?>