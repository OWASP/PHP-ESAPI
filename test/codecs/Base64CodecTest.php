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
require_once dirname(__FILE__).'/../../src/codecs/Base64Codec.php';

class Base64CodecTest extends UnitTestCase
{
	private $base64Codec = null;
	
	function setUp()
	{
		global $ESAPI;

		if ( !isset($ESAPI))
		{
			$ESAPI = new ESAPI();
		}
		
		$this->base64Codec = new Base64Codec();
	}
		
	function testEncode()
	{
		$this->assertEqual('Ij48c2NyaXB0PmFsZXJ0KC9YU1MvKTwvc2NyaXB0Pjxmb28gYXR0cj0i', $this->base64Codec->encode('"><script>alert(/XSS/)</script><foo attr="') );
	}
	
	function testEncodeCharacter()
	{
		$this->assertEqual( "PA==", $this->base64Codec->encode("<") );
	}	
	
	function testDecode()
	{
		$this->assertEqual('"><script>alert(/XSS/)</script><foo attr="', $this->base64Codec->decode('Ij48c2NyaXB0PmFsZXJ0KC9YU1MvKTwvc2NyaXB0Pjxmb28gYXR0cj0i') );
	}
		
	function testDecodeCharacter()
	{
		$this->assertEqual( "<", $this->base64Codec->decode("PA==") );
	}
}
?>