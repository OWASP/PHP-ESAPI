<?php
/**
 * OWASP Enterprise Security API (ESAPI)
 *
 * This file is part of the Open Web Application Security Project (OWASP)
 * Enterprise Security API (ESAPI) project.
 *
 * PHP version 5.2
 *
 * LICENSE: This source file is subject to the New BSD license.  You should read
 * and accept the LICENSE before you use, modify, and/or redistribute this
 * software.
 *
 * @category  OWASP
 * @package   ESAPI_Test_Codec
 * @author    jah <jah@jahboite.co.uk>
 * @copyright 2009-2010 The OWASP Foundation
 * @license   http://www.opensource.org/licenses/bsd-license.php New BSD license
 * @version   SVN: $Id$
 * @link      http://www.owasp.org/index.php/ESAPI
 */


/**
 * Require ESAPI and XMLEntityCodec.
 */
require_once dirname(__FILE__).'/../../src/ESAPI.php';
require_once dirname(__FILE__).'/../../src/codecs/XMLEntityCodec.php';


/**
 * Test case for XMLEntityCodec.
 *
 * PHP version 5.2
 *
 * @category  OWASP
 * @package   ESAPI_Test_Codec
 * @author    jah <jah@jahboite.co.uk>
 * @copyright 2009-2010 The OWASP Foundation
 * @license   http://www.opensource.org/licenses/bsd-license.php New BSD license
 * @version   Release: @package_version@
 * @link      http://www.owasp.org/index.php/ESAPI
 */
class XMLEntityCodecTest extends PHPUnit_Framework_TestCase
{
    private $_xmlEntityCodec = null;

    // these immune characters are the ones defined in DefaultEncoder.
    private $_immune_xml     = array( ',', '.', '-', '_', ' ' );
    private $_immune_xmlattr = array( ',', '.', '-', '_' );
    
    
    /**
     * Use a new Codec for each test.
     * 
     * @return null
     */
    function setUp()
    {
        global $ESAPI;

        if (! isset($ESAPI)) {
            $ESAPI = new ESAPI();
        }

        $this->_xmlEntityCodec = new XMLEntityCodec();
    }


    /* ENCODING METHODS */


    /**
     *  testEncodeForXML - note that xml entities include &apos; which is not
     *  available in HTML.
     *  
     *  @return null
     */
    function testEncodeForXML()
    {
        $this->assertEquals(
            '&quot;&gt;&lt;script&gt;alert&#x28;&apos;XSS&apos;&#x29;&lt;&#x2f;script&gt;&lt;foo attr&#x3d;&quot;',
            $this->_xmlEntityCodec->encode(
                $this->_immune_xml,
                '"><script>alert(\'XSS\')</script><foo attr="'
            )
        );
    }

    /**
     * testEncodeImmuneCharsForXML test that characters normally immune from
     * encoding can be encoded.
     *  
     *  @return null
     */
    function testEncodeImmuneCharsForXML()
    {
        $immune = array('');
        $this->assertEquals(
            'testTEST0123&#x2c;&#x2e;&#x2d;&#x5f;&#x20;',
            $this->_xmlEntityCodec->encode(
                $immune,
                'testTEST0123,.-_ '
            )
        );
    }

    /**
     * testNoEncodeImmuneCharsForXML characters immune from encoding don't get
     * encoded.
     * 
     * @return null
     */
    function testNoEncodeImmuneCharsForXML()
    {
        $this->assertEquals(
            'testTEST0123,.-_ ',
            $this->_xmlEntityCodec->encode(
                $this->_immune_xml,
                'testTEST0123,.-_ '
            )
        );
    }

    /**
     * testEncodeNullForXML - null stays null
     * 
     * @return null
     */
    function testEncodeNullForXML()
    {
        $this->assertEquals(
            null,
            $this->_xmlEntityCodec->encode($this->_immune_xml, null)
        );
    }

    /**
     * testEncodeInvalidCharsReplacedBySpace chars that must not be encoded for xml
     * are replaced with spaces.
     * 
     * @return null
     */
    function testEncodeInvalidCharsReplacedBySpace()
    {
        $this->assertEquals(
            'a b c d e f&#x9;g',
            $this->_xmlEntityCodec->encode(
                $this->_immune_xml,
                'a' . (chr(0)) . 'b' . (chr(4)) . 'c' . (chr(128)) . 'd' .
                (chr(150)) . 'e' . (chr(159)) . 'f' . (chr(9)) . 'g'
            )
        );
    }

    /**
     * testEncodeInvalidCharsReplacedBySpacePlusISO
     * 
     * @return null
     */
    function testEncodeInvalidCharsReplacedBySpacePlusISO()
    {
        $this->assertEquals(
            'a b c d e f&#x9;g h i j&#xa0;k&#xa1;l&#xa2;m',
            $this->_xmlEntityCodec->encode(
                $this->_immune_xml,
                'a' . (chr(0))   . 'b' . (chr(4))   . 'c' . (chr(128)) .
                'd' . (chr(150)) . 'e' . (chr(159)) . 'f' . (chr(9))   .
                'g' . (chr(127)) . 'h' . (chr(129)) . 'i' . (chr(159)) .
                'j' . (chr(160)) . 'k' . (chr(161)) . 'l' . (chr(162)) .
                'm'
            )
        );
    }

    /**
     * testEncodeScriptTag
     * 
     * @return null
     */
    function testEncodeScriptTag()
    {
        $this->assertEquals(
            '&lt;script&gt;',
            $this->_xmlEntityCodec->encode($this->_immune_xml, '<script>')
        );
    }

    /**
     * testEncodeEncodedScriptTag
     * 
     * @return null
     */
    function testEncodeEncodedScriptTag()
    {
        $this->assertEquals(
            '&amp;lt&#x3b;script&amp;gt&#x3b;',
            $this->_xmlEntityCodec->encode($this->_immune_xml, '&lt;script&gt;')
        );
    }

    /**
     * testEncodeSpecialsForXML
     * 
     * @return null
     */
    function testEncodeSpecialsForXML()
    {
        $this->assertEquals(
            '&#x21;&#x40;&#x24;&#x25;&#x28;&#x29;&#x3d;&#x2b;&#x7b;&#x7d;&#x5b;&#x5d;',
            $this->_xmlEntityCodec->encode($this->_immune_xml, '!@$%()=+{}[]')
        );
    }

    /**
     * testEncodeCanonicalisedEncodedSpecials
     * 
     * @return null
     */
    function testEncodeCanonicalisedEncodedSpecials()
    {
        $instance = ESAPI::getEncoder();
        $this->assertEquals(
            '&#x21;&#x40;&#x24;&#x25;&#x28;&#x29;&#x3d;&#x2b;&#x7b;&#x7d;&#x5b;&#x5d;',
            $this->_xmlEntityCodec->encode(
                $this->_immune_xml,
                $instance->canonicalize(
                    '&#33;&#64;&#36;&#37;&#40;&#41;&#61;&#43;&#123;&#125;&#91;&#93;'
                )
            )
        );
    }

    /**
     * testEncodeAmpForXMLEoS
     * 
     * @return null
     */
    function testEncodeAmpForXMLEoS()
    {
        $this->assertEquals(
            'dir&amp;',
            $this->_xmlEntityCodec->encode($this->_immune_xml, 'dir&')
        );
    }

    /**
     * testEncodeAmpForXMLMidS
     * 
     * @return null
     */
    function testEncodeAmpForXMLMidS()
    {
        $this->assertEquals(
            'one&amp;two',
            $this->_xmlEntityCodec->encode($this->_immune_xml, 'one&two')
        );
    }

    /**
     * testEncodeNullForXMLAttribute
     * 
     * @return null
     */
    function testEncodeNullForXMLAttribute()
    {
        $this->assertEquals(
            null,
            $this->_xmlEntityCodec->encode($this->_immune_xmlattr, null)
        );
    }

    /**
     * testEncodeImmuneCharsForXMLAttribute
     * 
     * @return null
     */
    function testEncodeImmuneCharsForXMLAttribute()
    {
        $immune = array('');
        $this->assertEquals(
            'testTEST0123&#x2c;&#x2e;&#x2d;&#x5f;',
            $this->_xmlEntityCodec->encode(
                $immune,
                'testTEST0123,.-_'
            )
        );
    }

    /**
     * testNoEncodeImmuneCharsForXMLAttribute
     * 
     * @return null
     */
    function testNoEncodeImmuneCharsForXMLAttribute()
    {
        $this->assertEquals(
            'testTEST0123,.-_',
            $this->_xmlEntityCodec->encode(
                $this->_immune_xmlattr,
                'testTEST0123,.-_'
            )
        );
    }

    /**
     * testEncodeSpecialsForXMLAttribute
     * 
     * @return null
     */
    function testEncodeSpecialsForXMLAttribute()
    {
        $this->assertEquals(
            '&#x20;&#x21;&#x40;&#x24;&#x25;&#x28;&#x29;&#x3d;&#x2b;&#x7b;&#x7d;&#x5b;&#x5d;',
            $this->_xmlEntityCodec->encode($this->_immune_xmlattr, ' !@$%()=+{}[]')
        );
    }


    /* DECODE METHODS */


    /**
     * testDecodeFromXML
     * 
     * @return null
     */
    function testDecodeFromXML()
    {
        $this->assertEquals(
            '"><script>alert(/XSS/)</script><foo attr="',
            $this->_xmlEntityCodec->decode(
                '&quot;&gt;&lt;script&gt;alert&#x28;&#x2f;XSS&#x2f;&#x29;&lt;&#x2f;script&gt;&lt;foo attr&#x3d;&quot;'
            )
        );
    }

    /**
     * testDecodeNullFromXML
     * 
     * @return null
     */
    function testDecodeNullFromXML()
    {
        $this->assertEquals(null, $this->_xmlEntityCodec->decode(null));
    }

    /**
     * testDecodeDecimalNumericEntitiesFromXML
     * 
     * @return null
     */
    function testDecodeDecimalNumericEntitiesFromXML()
    {
        $this->assertEquals(
            'test!',
            $this->_xmlEntityCodec->decode('&#116;&#101;&#115;&#116;!')
        );
    }

    /**
     * testDecodeHexNumericEntitiesFromXML
     * 
     * @return null
     */
    function testDecodeHexNumericEntitiesFromXML()
    {
        $this->assertEquals(
            'test!',
            $this->_xmlEntityCodec->decode('&#x74;&#x65;&#x73;&#x74;!')
        );
    }

    /**
     * testDecodeInvalidNamedEntityFromXML
     * 
     * @return null
     */
    function testDecodeInvalidNamedEntityFromXML()
    {
        $this->assertEquals('&jeff;', $this->_xmlEntityCodec->decode('&jeff;'));
    }

    /**
     * testDecodeDoesNotProduceMixedCharacterEncoding -  Mixed character encoding
     * should not be returned from decode.
     * In this case, ASCII and Latin Supplement characters will exist in the
     * decoded string and should be presented as UTF-8
     * 
     * @return null
     */
    function testDecodeDoesNotProduceMixedCharacterEncoding()
    {
        $codec = new XMLEntityCodec();
        // expecting a UTF-8 encoded string
        $expected = mb_convert_encoding("a b c d e f\x09g h i j\xa0k\xa1l\xa2m", 'UTF-8', 'ISO-8859-1');
        // check that the encoding conversion went well and the expected string is correct
        $expected_unpacked = array(
            1 => 0x61,  2 => 0x20,  3 => 0x62,  4 => 0x20,
            5 => 0x63,  6 => 0x20,  7 => 0x64,  8 => 0x20,
            9 => 0x65, 10 => 0x20, 11 => 0x66, 12 => 0x09,
           13 => 0x67, 14 => 0x20, 15 => 0x68, 16 => 0x20,
           17 => 0x69, 18 => 0x20, 19 => 0x6a, 20 => 0xc2,
           21 => 0xa0, 22 => 0x6b, 23 => 0xc2, 24 => 0xa1,
           25 => 0x6c, 26 => 0xc2, 27 => 0xa2, 28 => 0x6d,
        );
        $unpacked = unpack('C*', $expected);
        $this->assertSame(
            $expected_unpacked,
            $unpacked,
            'Ensuring expected value was correctly encoded to UTF-8 - %s'
        );
        // decode and hope we get $expected!
        $this->assertEquals(
            $expected,
            $codec->decode(
                'a b c d e f&#x9;g h i j&#xa0;k&#xa1;l&#xa2;m'
            )
        );
    }

    /**
     * testDecodeScriptTag
     * 
     * @return null
     */
    function testDecodeScriptTag()
    {
        $this->assertEquals(
            '<script>',
            $this->_xmlEntityCodec->decode('&lt;script&gt;')
        );
    }

    /**
     * testDecodeOnceDoubleEncodedScriptTag
     * 
     * @return null
     */
    function testDecodeOnceDoubleEncodedScriptTag()
    {
        $this->assertEquals(
            '&lt;script&gt;',
            $this->_xmlEntityCodec->decode('&amp;lt&#x3b;script&amp;gt&#x3b;')
        );
    }

    /**
     * testDecodeSpecials
     * 
     * @return null
     */
    function testDecodeSpecials()
    {
        $this->assertEquals(
            '!@$%()=+{}[]',
            $this->_xmlEntityCodec->decode(
                '&#x21;&#x40;&#x24;&#x25;&#x28;&#x29;&#x3d;&#x2b;&#x7b;&#x7d;&#x5b;&#x5d;'
            )
        );
    }

    /**
     * testDecodeSpecialsEqualsCanonicalisedEncodedSpecials
     * 
     * @return null
     */
    function testDecodeSpecialsEqualsCanonicalisedEncodedSpecials()
    {
        $instance = ESAPI::getEncoder();
        $this->assertEquals(
            $instance->canonicalize(
                '&#x21;&#x40;&#x24;&#x25;&#x28;&#x29;&#x3d;&#x2b;&#x7b;&#x7d;&#x5b;&#x5d;'
            ),
            $this->_xmlEntityCodec->decode(
                '&#33;&#64;&#36;&#37;&#40;&#41;&#61;&#43;&#123;&#125;&#91;&#93;'
            )
        );
    }

    /**
     * testDecodeAmpFromXMLEoS
     * 
     * @return null
     */
    function testDecodeAmpFromXMLEoS()
    {
        $this->assertEquals('dir&', $this->_xmlEntityCodec->decode('dir&amp;'));
    }

    /**
     * testDecodeAmpFromXMLMidS
     * 
     * @return null
     */
    function testDecodeAmpFromXMLMidS()
    {
        $this->assertEquals(
            'one&two',
            $this->_xmlEntityCodec->decode('one&amp;two')
        );
    }

    /**
     * testDecodeCharacter
     * 
     * @return null
     */
    function testDecodeCharacter()
    {
        $this->assertEquals('<', $this->_xmlEntityCodec->decode('&lt;'));
    }

}
