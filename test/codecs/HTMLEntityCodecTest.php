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
require_once dirname(__FILE__).'/../../src/codecs/HTMLEntityCodec.php';


class HTMLEntityCodecTest extends UnitTestCase
{
	private $htmlEntityCodec = null;
	
	function setUp()
	{
		global $ESAPI;

		if ( !isset($ESAPI))
		{
			$ESAPI = new ESAPI();
		}
		
		$this->htmlEntityCodec = new HTMLEntityCodec();
	}
		
	function testEncode()
	{
		$immune = array("");
		
		$this->assertEqual( 'TODO', $this->htmlEntityCodec->encode($immune, '"><script>alert(/XSS/)</script><foo attr="') );
		
		// J2EE test case
		$this->assertEqual( "test", $this->htmlEntityCodec->encode( $immune, "test") );
	}
	
	function testEncodeCharacter()
	{
		$immune = array("");
		
		$this->assertEqual( "&lt;", $this->htmlEntityCodec->encode($immune, "<") );
	}	
	
	function testDecode()
	{
		$this->assertEqual( "><script>alert(/XSS/)</script><foo attr=", $this->htmlEntityCodec->decode('TODO') );
		
		// J2EE test cases
		$this->assertEqual( "test!", $this->htmlEntityCodec->decode('&#116;&#101;&#115;&#116;!') );
		$this->assertEqual( "test!", $this->htmlEntityCodec->decode('&#x74;&#x65;&#x73;&#x74;!') );
		$this->assertEqual( "&jeff;", $this->htmlEntityCodec->decode("&jeff;") );
	}
		
	function testDecodeCharacter()
	{
		$this->assertEqual( "<", $this->htmlEntityCodec->decode("&lt;") );
	}
	
}
?>