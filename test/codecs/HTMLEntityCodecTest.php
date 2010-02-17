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
 * The ESAPI is published by OWASP under the BSD license. You should read and
 * accept the LICENSE before you use, modify, and/or redistribute this software.
 *
 * @author Linden Darling <a href="http://www.jds.net.au">JDS Australia</a>
 * @since  2009
 * @since  1.6
 */


/**
 *
 */
require_once dirname(__FILE__).'/../../src/ESAPI.php';
require_once dirname(__FILE__).'/../../src/codecs/HTMLEntityCodec.php';


/**
 * Test case for HTMLEntityCodec.
 */
class HTMLEntityCodecTest extends UnitTestCase
{
    private $htmlEntityCodec = null;

    // these immune characters are the ones defined in DefaultEncoder.
    private $immune_html     = array( ',', '.', '-', '_', ' ' );
    private $immune_htmlattr = array( ',', '.', '-', '_' );

    function setUp()
    {
        global $ESAPI;

        if ( !isset($ESAPI))
        {
            $ESAPI = new ESAPI();
        }

        $this->htmlEntityCodec = new HTMLEntityCodec();
    }


    /* ENCODING METHODS */


    // nice example of encoding for HTML.
    function testEncodeForHTML()
    {
        $this->assertEqual(
            '&quot;&gt;&lt;script&gt;alert&#x28;&#x2f;XSS&#x2f;&#x29;&lt;&#x2f;script&gt;&lt;foo attr&#x3d;&quot;',
            $this->htmlEntityCodec->encode(
                $this->immune_html,
                '"><script>alert(/XSS/)</script><foo attr="'
            )
        );
    }

    // test that characters normally immune from encoding, can be encoded.
    function testEncodeImmuneCharsForHTML()
    {
        $immune = array('');
        $this->assertEqual(
            'testTEST0123&#x2c;&#x2e;&#x2d;&#x5f;&#x20;',
            $this->htmlEntityCodec->encode(
                $immune,
                'testTEST0123,.-_ '
            )
        );
    }

    // characters immune from encoding don't get encoded.
    function testNoEncodeImmuneCharsForHTML()
    {
        $this->assertEqual(
            'testTEST0123,.-_ ',
            $this->htmlEntityCodec->encode(
                $this->immune_html,
                'testTEST0123,.-_ '
            )
        );
    }

    // null stays null
    function testEncodeNullForHTML()
    {
        $this->assertEqual(
            null,
            $this->htmlEntityCodec->encode($this->immune_html, null)
        );
    }

    // chars that must not be encoded for html are replaced with spaces. These
    // two tests expect the same as ESAPI 2.0 for Java.
    function testEncodeInvalidCharsReplacedBySpace_01()
    {
        $this->assertEqual(
            'a b c d e f&#x9;g',
            $this->htmlEntityCodec->encode(
                $this->immune_html,
                'a' . (chr(0)) . 'b' . (chr(4)) . 'c' . (chr(128)) . 'd' .
                (chr(150)) . 'e' . (chr(159)) . 'f' . (chr(9)) . 'g'
            )
        );
    }

    function testEncodeInvalidCharsReplacedBySpace_02()
    {
        $this->assertEqual(
            'a b c d e f&#x9;g h i j&nbsp;k&iexcl;l&cent;m',
            $this->htmlEntityCodec->encode(
                $this->immune_html,
                'a' . (chr(0))   . 'b' . (chr(4))   . 'c' . (chr(128)) .
                'd' . (chr(150)) . 'e' . (chr(159)) . 'f' . (chr(9))   .
                'g' . (chr(127)) . 'h' . (chr(129)) . 'i' . (chr(159)) .
                'j' . (chr(160)) . 'k' . (chr(161)) . 'l' . (chr(162)) .
                'm'
            )
        );
    }

    function testEncodeScriptTag()
    {
        $this->assertEqual(
            '&lt;script&gt;',
            $this->htmlEntityCodec->encode($this->immune_html, '<script>')
        );
    }

    function testEncodeEncodedScriptTag()
    {
        $this->assertEqual(
            '&amp;lt&#x3b;script&amp;gt&#x3b;',
            $this->htmlEntityCodec->encode($this->immune_html, '&lt;script&gt;')
        );
    }

    function testEncodeSpecialsForHTML()
    {
        $this->assertEqual(
            '&#x21;&#x40;&#x24;&#x25;&#x28;&#x29;&#x3d;&#x2b;&#x7b;&#x7d;&#x5b;&#x5d;',
            $this->htmlEntityCodec->encode($this->immune_html, '!@$%()=+{}[]')
        );
    }

    function testEncodeCanonicalisedEncodedSpecials()
    {
        $instance = ESAPI::getEncoder();
        $this->assertEqual(
            '&#x21;&#x40;&#x24;&#x25;&#x28;&#x29;&#x3d;&#x2b;&#x7b;&#x7d;&#x5b;&#x5d;',
            $this->htmlEntityCodec->encode(
                $this->immune_html,
                $instance->canonicalize(
                    '&#33;&#64;&#36;&#37;&#40;&#41;&#61;&#43;&#123;&#125;&#91;&#93;'
                )
            )
        );
    }

    function testEncodeAmpForHTMLEoS()
    {
        $this->assertEqual(
            'dir&amp;',
            $this->htmlEntityCodec->encode($this->immune_html, 'dir&')
        );
    }

    function testEncodeAmpForHTMLMidS()
    {
        $this->assertEqual(
            'one&amp;two',
            $this->htmlEntityCodec->encode($this->immune_html, 'one&two')
        );
    }

    function testSomeChars ()
    {
        $this->assertEqual(
            ''.(chr(12345)).(chr(65533)).(chr(1244)),
            ''.(chr(12345)).(chr(65533)).(chr(1244))
        );
    }

    function testEncodeNullForHTMLAttribute()
    {
        $this->assertEqual(
            null,
            $this->htmlEntityCodec->encode($this->immune_htmlattr, null)
        );
    }

    function testEncodeImmuneCharsForHTMLAttribute()
    {
        $immune = array('');
        $this->assertEqual(
            'testTEST0123&#x2c;&#x2e;&#x2d;&#x5f;',
            $this->htmlEntityCodec->encode(
                $immune,
                'testTEST0123,.-_'
            )
        );
    }

    function testNoEncodeImmuneCharsForHTMLAttribute()
    {
        $this->assertEqual(
            'testTEST0123,.-_',
            $this->htmlEntityCodec->encode(
                $this->immune_htmlattr,
                'testTEST0123,.-_'
            )
        );
    }

    function testEncodeSpecialsForHTMLAttribute()
    {
        $this->assertEqual(
            '&#x20;&#x21;&#x40;&#x24;&#x25;&#x28;&#x29;&#x3d;&#x2b;&#x7b;&#x7d;&#x5b;&#x5d;',
            $this->htmlEntityCodec->encode($this->immune_htmlattr, ' !@$%()=+{}[]')
        );
    }


    /* DECODE METHODS */


    function testDecodeFromHTML()
    {
        $this->assertEqual(
           '"><script>alert(/XSS/)</script><foo attr="',
            $this->htmlEntityCodec->decode(
                '&quot;&gt;&lt;script&gt;alert&#x28;&#x2f;XSS&#x2f;&#x29;&lt;&#x2f;script&gt;&lt;foo attr&#x3d;&quot;'
            ));
    }

    function testDecodeNullFromHTML()
    {
        $this->assertEqual(null, $this->htmlEntityCodec->decode(null));
    }

    function testDecodeDecimalNumericEntitiesFromHTML()
    {
        $this->assertEqual(
            'test!',
            $this->htmlEntityCodec->decode('&#116;&#101;&#115;&#116;!')
        );
    }

    function testDecodeHexNumericEntitiesFromHTML()
    {
        $this->assertEqual(
            'test!',
            $this->htmlEntityCodec->decode('&#x74;&#x65;&#x73;&#x74;!')
        );
    }

    function testDecodeInvalidNamedEntityFromHTML()
    {
        $this->assertEqual('&jeff;', $this->htmlEntityCodec->decode('&jeff;'));
    }

    function testDecodeValidCharsFromHTML()
    {
        $this->assertEqual(
            'a b c d e f' . (chr(9)) . 'g h i j' . (chr(160)) .
            'k' . (chr(161)) . 'l' . (chr(162)) . 'm',
            $this->htmlEntityCodec->decode(
                'a b c d e f&#x9;g h i j&#xa0;k&#xa1;l&#xa2;m'
            )
        );
    }

    function testDecodeScriptTag()
    {
        $this->assertEqual(
            '<script>',
            $this->htmlEntityCodec->decode('&lt;script&gt;')
        );
    }

    function testDecodeOnceDoubleEncodedScriptTag()
    {
        $this->assertEqual(
            '&lt;script&gt;',
            $this->htmlEntityCodec->decode('&amp;lt&#x3b;script&amp;gt&#x3b;')
        );
    }

    function testDecodeSpecials()
    {
        $this->assertEqual(
            '!@$%()=+{}[]',
            $this->htmlEntityCodec->decode(
                '&#x21;&#x40;&#x24;&#x25;&#x28;&#x29;&#x3d;&#x2b;&#x7b;&#x7d;&#x5b;&#x5d;'
            )
        );
    }

    function testDecodeSpecialsEqualsCanonicalisedEncodedSpecials()
    {
        $instance = ESAPI::getEncoder();
        $this->assertEqual(
            $instance->canonicalize(
                '&#x21;&#x40;&#x24;&#x25;&#x28;&#x29;&#x3d;&#x2b;&#x7b;&#x7d;&#x5b;&#x5d;'
            ),
            $this->htmlEntityCodec->decode(
                '&#33;&#64;&#36;&#37;&#40;&#41;&#61;&#43;&#123;&#125;&#91;&#93;'
            )
        );
    }

    function testDecodeAmpFromHTMLEoS()
    {
        $this->assertEqual('dir&', $this->htmlEntityCodec->decode('dir&amp;'));
    }

    function testDecodeAmpFromHTMLMidS()
    {
        $this->assertEqual(
            'one&two',
            $this->htmlEntityCodec->decode('one&amp;two')
        );
    }

    function testDecodeCharacter()
    {
        $this->assertEqual( '<', $this->htmlEntityCodec->decode('&lt;') );
    }

}
