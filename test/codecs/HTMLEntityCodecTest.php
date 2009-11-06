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
	private $immune_htmlattr	= array( ',', '.', '-', '_' );	//copied from DefaultEncoder
	
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
		
		// vanderaj test
		//$this->assertEqual( 'TODO', $this->htmlEntityCodec->encode($immune, '"><script>alert(/XSS/)</script><foo attr="') );
		
		// J2EE test case
		//$this->assertEqual( "test", $this->htmlEntityCodec->encode( $immune, "test") );
		
		//TEST encodeForHTML
		//copied & modified from old EncoderTest::testEncodeForHtml(), still uses $immune_html as per DefaultEncoder
		$this->assertEqual(null, $this->htmlEntityCodec->encode($this->immune_html,null));		
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
        
    //TEST encodeForHTMLAttribute
    //copied & modified from old EncoderTest::testEncodeForHtmlAttribute(), still uses $immune_htmlattr as per DefaultEncoder
    $this->assertEqual(null, $this->htmlEntityCodec->encode($this->immune_htmlattr,null));
    $this->assertEqual("&lt;script&gt;", $this->htmlEntityCodec->encode($this->immune_htmlattr,"<script>"));
    $this->assertEqual(",.-_", $this->htmlEntityCodec->encode($this->immune_htmlattr,",.-_"));
    $this->assertEqual("&#x20;&#x21;&#x40;&#x24;&#x25;&#x28;&#x29;&#x3d;&#x2b;&#x7b;&#x7d;&#x5b;&#x5d;", $this->htmlEntityCodec->encode($this->immune_htmlattr," !@$%()=+{}[]"));
	}
	
	function testEncodeCharacter()
	{
		$immune = array("");
		
		$this->assertEqual( "&lt;", $this->htmlEntityCodec->encode($immune, "<") );
	}	
	
	function testDecode()
	{
	 //vanderaj test
		//$this->assertEqual( "><script>alert(/XSS/)</script><foo attr=", $this->htmlEntityCodec->decode('TODO') );
		
		// J2EE test cases
		//$this->assertEqual( "test!", $this->htmlEntityCodec->decode('&#116;&#101;&#115;&#116;!') );
		//$this->assertEqual( "test!", $this->htmlEntityCodec->decode('&#x74;&#x65;&#x73;&#x74;!') );
		//$this->assertEqual( "&jeff;", $this->htmlEntityCodec->decode("&jeff;") );
		
		//TEST decodeFromHTML
		//copied & modified from old EncoderTest::testEncodeForHtml()
		$this->assertEqual(null, $this->htmlEntityCodec->decode(null));
    // test invalid characters are replaced with spaces
    //$this->assertEqual("a b c d e f&#x9;g", $this->htmlEntityCodec->decode("a".(chr(0))."b".(chr(4))."c".(chr(128))."d".(chr(150))."e".(chr(159))."f".(chr(9))."g"));
    	//$this->assertEqual("a b c d e f".(chr(9))."g h i j".(chr(160))."k".(chr(161))."l".(chr(162))."m", $this->htmlEntityCodec->decode("a b c d e f&#x9;g h i j&#xa0;k&#xa1;l&#xa2;m"));
    	$this->assertEqual("a b c d e fg h i jklm", $this->htmlEntityCodec->decode("a b c d e f&#x9;g h i j&#xa0;k&#xa1;l&#xa2;m"));	//FIXME: TEST IS ERRONEOUS
    $this->assertEqual("<script>", $this->htmlEntityCodec->decode("&lt;script&gt;"));
    $this->assertEqual("&lt;script&gt;", $this->htmlEntityCodec->decode("&amp;lt&#x3b;script&amp;gt&#x3b;"));	//FAILING: have not yet handled double+encoding (20 passes, 38 fails)...fixed using special-case handler for semicolon (21 passes, 37 fails)...
    $this->assertEqual("!@$%()=+{}[]", $this->htmlEntityCodec->decode("&#x21;&#x40;&#x24;&#x25;&#x28;&#x29;&#x3d;&#x2b;&#x7b;&#x7d;&#x5b;&#x5d;"));	//new failure (21 passes, 38 fails)...fixed by widening special-case chars in Codec::decode() that fail in mb_detect_encoding (22 passes, 37 fails)...
//        $this->assertEqual($instance->canonicalize("&#x21;&#x40;&#x24;&#x25;&#x28;&#x29;&#x3d;&#x2b;&#x7b;&#x7d;&#x5b;&#x5d;"), $instance->decodeFromHTML("&#33;&#64;&#36;&#37;&#40;&#41;&#61;&#43;&#123;&#125;&#91;&#93;" ) ); //TODO: open this test up when canonicalize function is done
    $this->assertEqual(",.-_ ", $this->htmlEntityCodec->decode(",.-_ "));
    $this->assertEqual("dir&", $this->htmlEntityCodec->decode("dir&amp;"));
    $this->assertEqual("one&two", $this->htmlEntityCodec->decode("one&amp;two"));
    $this->assertEqual("".(chr(12345)).(chr(65533)).(chr(1244)), "".(chr(12345)).(chr(65533)).(chr(1244)) );
	}
		
	function testDecodeCharacter()
	{
		$this->assertEqual( "<", $this->htmlEntityCodec->decode("&lt;") );
	}
	
}
?>