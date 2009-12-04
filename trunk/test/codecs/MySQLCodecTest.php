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
require_once dirname(__FILE__).'/../../src/codecs/MySQLCodec.php';


class MySQLCodecTest extends UnitTestCase
{
	private $mysqlAnsiCodec = null;
	private $mysqlStdCodec = null;
	
	function setUp()
	{
		global $ESAPI;

		if ( !isset($ESAPI))
		{
			$ESAPI = new ESAPI();
		}
		
		$this->mysqlAnsiCodec = new MySQLCodec(MySQLCodec::MYSQL_ANSI);
		$this->mysqlStdCodec = new MySQLCodec(MySQLCodec::MYSQL_STD);
	}
		
	function testANSIEncode()
	{
		$immune = array("");
		
		$this->assertEqual( "'') or (''1''=''1--", $this->mysqlAnsiCodec->encode($immune, "') or ('1'='1--") );
	}
	
	function testANSIEncodeCharacter()
	{
		$immune = array("");
		
		$this->assertEqual( "''", $this->mysqlAnsiCodec->encode($immune, "'") );
	}	
	
	function testANSIDecode()
	{
		$this->assertEqual( "') or ('1'='1--", $this->mysqlAnsiCodec->decode("'') or (''1''=''1--") );
	}
		
	function testANSIDecodeCharacter()
	{
		$this->assertEqual( "'", $this->mysqlAnsiCodec->decode("''") );
	}
	
	function testStdDecode()
	{
		$this->assertEqual( "') \or ('1'='1--\0\x25", $this->mysqlStdCodec->decode("\\'\\) \\\\or \\(\\'1\\'\\=\\'1\\-\\-\\0\\%") );
	}

	function testStdEncode()
	{
		$immune = array(" ");
		
		$this->assertEqual( "\\'\\) \\\\or \\(\\'1\\'\\=\\'1\\-\\-\\0\\%", $this->mysqlStdCodec->encode($immune, "') \or ('1'='1--\0\x25") );
	}
	
	function testStdDecodeCharacter()
	{
		$this->assertEqual( "'", $this->mysqlStdCodec->decode("\\'") );
	}
	
	function testStdEncodeCharacter()
	{
		$immune = array(" ");
		
		$this->assertEqual( "\\'", $this->mysqlStdCodec->encode($immune, "'") );
	}
	
	function testStdDecodeExtra()
	{
		$this->assertEqual( "\x08 \x0a \x0d \x09 \x1a _ \" ' \\ \x00 \x25", $this->mysqlStdCodec->decode("\\b \\n \\r \\t \\z \\_ \\\" \\' \\\\ \\0 \\%") );
	}

	function testStdEncodeExtra()
	{
		$immune = array(" ");
		
		$this->assertEqual( "\\b \\n \\r \\t \\z \\_ \\\" \\' \\\\ \\0 \\%", $this->mysqlStdCodec->encode($immune, "\x08 \x0a \x0d \x09 \x1a _ \" ' \\ \x00 \x25") );
	}
}
?>