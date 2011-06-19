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
require_once dirname(__FILE__).'/../../src/codecs/VBScriptCodec.php';


class VBScriptCodecTest extends PHPUnit_Framework_TestCase
{
	private $vbScriptCodec = null;
	
	function setUp()
	{
		global $ESAPI;

		if ( !isset($ESAPI))
		{
			$ESAPI = new ESAPI();
		}
		
		$this->vbScriptCodec = new VBScriptCodec();
	}
		
	function testEncode()
	{
		$immune = array(" ");

		$this->assertEquals( " \"!\"@\"$\"%\"(\")\"=\"+\"{\"}\"[\"]\"\"\"<script\">", $this->vbScriptCodec->encode($immune, " !@$%()=+{}[]\"<script>") );
	}
	
	function testEncodeCharacter()
	{
		$immune = array(" ");
		
		$this->assertEquals( "\"<", $this->vbScriptCodec->encode($immune, "<") );
	}
	
	function testDecode()
	{
		$this->assertEquals( " !@$%()=+{}[]\"", $this->vbScriptCodec->decode(" \"!\"@\"$\"%\"(\")\"=\"+\"{\"}\"[\"]\"\"") );
	}
		
	function testDecodeCharacter()
	{
		$this->assertEquals( "<", $this->vbScriptCodec->decode("\"<") );
	}
}
?>