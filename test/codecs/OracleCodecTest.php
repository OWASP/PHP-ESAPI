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
require_once dirname(__FILE__).'/../../src/codecs/OracleCodec.php';


class OracleCodecTest extends UnitTestCase
{
	private $oracleCodec = null;
	
	function setUp()
	{
		global $ESAPI;

		if ( !isset($ESAPI))
		{
			$ESAPI = new ESAPI();
		}
		
		$this->oracleCodec = new OracleCodec();
	}
		
	function testEncode()
	{
		$immune = array("");
		
		$this->assertEqual( 'TODO', $this->oracleCodec->encode($immune, " || 'x' FROM DUAL;--") );
	}
	
	function testEncodeCharacter()
	{
		$immune = array("");
		
		$this->assertEqual( "''", $this->oracleCodec->encode($immune, "'") );
	}	
	
	function testDecode()
	{
		$this->assertEqual( " || 'x' FROM DUAL;--", $this->oracleCodec->decode('TODO') );
	}
		
	function testDecodeCharacter()
	{
		$this->assertEqual( "'", $this->oracleCodec->decode("''") );
	}
	
}
?>