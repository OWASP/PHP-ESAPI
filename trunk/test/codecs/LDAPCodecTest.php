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
require_once dirname(__FILE__).'/../../src/codecs/LDAPCodec.php';


class LDAPCodecTest extends UnitTestCase
{
	private $ldapCodec = null;
	
	function setUp()
	{
		global $ESAPI;

		if ( !isset($ESAPI))
		{
			$ESAPI = new ESAPI();
		}
		
		$this->ldapCodec = new LDAPCodec();
	}
		
	function testEncode()
	{
		$immune = array("");
		
		$this->assertEqual( 'TODO', $this->ldapCodec->encode($immune, "esapi)(|(password=*)") );
	}
	
	function testEncodeCharacter()
	{
		$immune = array("");
		
		$this->assertEqual( "TODO", $this->ldapCodec->encode($immune, "<") );
	}	
	
	function testDecode()
	{
		$this->assertEqual( "esapi)(|(password=*)", $this->ldapCodec->decode('TODO') );
	}
		
	function testDecodeCharacter()
	{
		$this->assertEqual( "<", $this->ldapCodec->decode("TODO") );
	}
	
}
?>