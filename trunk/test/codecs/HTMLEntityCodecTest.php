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
	private $immune_html = array( ',', '.', '-', '_', ' ' );	//copied from DefaultEncoder
	
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
		
		//copied & modified from old EncoderTest::testEncodeForHtml(), still uses $immune_html as per DefaultEncoder
		$this->assertEqual(null, $this->htmlEntityCodec->encode(null,null));		
        // test invalid characters are replaced with spaces
        //$this->assertEqual("a b c d e f&#x9;g", $this->htmlEntityCodec->encode($this->immune_html,"a".(chr(0))."b".(chr(4))."c".(chr(128))."d".(chr(150))."e".(chr(159))."f".(chr(9))."g"));
			$this->assertEqual("a b c d e f&#x9;g h i j&#xa0;k&#xa1;l&#xa2;m", $this->htmlEntityCodec->encode($this->immune_html,"a".(chr(0))."b".(chr(4))."c".(chr(128))."d".(chr(150))."e".(chr(159))."f".(chr(9))."g".(chr(127))."h".(chr(129))."i".(chr(159))."j".(chr(160))."k".(chr(161))."l".(chr(162))."m"));
        $this->assertEqual("&lt;script&gt;", $this->htmlEntityCodec->encode($this->immune_html,"<script>"));
        $this->assertEqual("&amp;lt&#x3b;script&amp;gt&#x3b;", $this->htmlEntityCodec->encode($this->immune_html,"&lt;script&gt;"));
        $this->assertEqual("&#x21;&#x40;&#x24;&#x25;&#x28;&#x29;&#x3d;&#x2b;&#x7b;&#x7d;&#x5b;&#x5d;", $this->htmlEntityCodec->encode($this->immune_html,"!@$%()=+{}[]"));
//        $this->assertEqual("&#x21;&#x40;&#x24;&#x25;&#x28;&#x29;&#x3d;&#x2b;&#x7b;&#x7d;&#x5b;&#x5d;", $this->htmlEntityCodec->encode($this->immune_html,$instance->canonicalize("&#33;&#64;&#36;&#37;&#40;&#41;&#61;&#43;&#123;&#125;&#91;&#93;") ) ); //TODO: open this test up when canonicalize function is done
        $this->assertEqual(",.-_ ", $this->htmlEntityCodec->encode($this->immune_html,",.-_ "));
        $this->assertEqual("dir&amp;", $this->htmlEntityCodec->encode($this->immune_html,"dir&"));
        $this->assertEqual("one&amp;two", $this->htmlEntityCodec->encode($this->immune_html,"one&two"));
        $this->assertEqual("".(chr(12345)).(chr(65533)).(chr(1244)), "".(chr(12345)).(chr(65533)).(chr(1244)) );
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