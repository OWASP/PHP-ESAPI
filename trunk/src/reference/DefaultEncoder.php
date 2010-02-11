<?php
/**
 * OWASP Enterprise Security API (ESAPI)
 *
 * This file is part of the Open Web Application Security Project (OWASP)
 * Enterprise Security API (ESAPI) project. For details, please see
 * <a href="http://www.owasp.org/index.php/ESAPI">http://www.owasp.org/index.php/ESAPI</a>.
 *
 * Copyright (c) 2007 - 2009 The OWASP Foundation
 *
 * The ESAPI is published by OWASP under the BSD license. You should read and
 * accept the LICENSE before you use, modify, and/or redistribute this software.
 *
 * @author Jeff Williams <a href="http://www.aspectsecurity.com">Aspect Security</a>
 * @created 2009
 * @since 1.6
 */


/**
 * DefaultEncoder requires the interface it implements and any Codecs it uses.
 */
require_once dirname(__FILE__).'/../Encoder.php';
require_once dirname(__FILE__).'/../codecs/Base64Codec.php';
require_once dirname(__FILE__).'/../codecs/CSSCodec.php';
require_once dirname(__FILE__).'/../codecs/HTMLEntityCodec.php';
require_once dirname(__FILE__).'/../codecs/JavaScriptCodec.php';
//require_once dirname(__FILE__).'/../codecs/LDAPCodec.php';
require_once dirname(__FILE__).'/../codecs/PercentCodec.php';
require_once dirname(__FILE__).'/../codecs/VBScriptCodec.php';


/**
 * Reference implementation of the Encoder interface.
 *
 * This implementation takes a whitelist approach to encoding, meaning that
 * everything not specifically identified in a list of "immune" characters is
 * encoded.
 *
 * Users of encoding methods should ensure that data is first canonicalized
 * before invoking them to prevent double encoding.  In future versions of the
 * reference implementation, encoding methods will perhaps perform
 * canonicalization prior to encoding and callers will not have to concern
 * themselves with ensuring canonicalization is done.
 *
 * @see Encoder
 *
 * @author Linden Darling <a href="http://www.jds.net.au">JDS Australia</a>
 * @author jah (at jahboite.co.uk)
 * @since  1.6
 * @since  2009
 */
class DefaultEncoder implements Encoder {

    private $base64Codec     = null;
    private $cssCodec        = null;
    private $htmlCodec       = null;
    private $javascriptCodec = null;
//  private $ldapCodec       = null;
    private $percentCodec    = null;
    private $vbscriptCodec   = null;

    /*
     *  Character sets that define characters (in addition to alphanumerics) that are
     * immune from encoding in various formats
     */
    private $immune_base64     = array();
    private $immune_css        = array( ' ' );    // Note: zero immune chars in Java ESAPI 2
    private $immune_html       = array( ',', '.', '-', '_', ' ' );
    private $immune_htmlattr   = array( ',', '.', '-', '_' );
    private $immune_javascript = array( ',', '.', '_' );    // Note: JavaScriptCodec is closer to Java ESAPI 2 - these imuune chars reflect that.
    private $immune_os         = array( '-' );
    private $immune_sql        = array( ' ' );
    private $immune_vbscript   = array( ' ' );    // Note: Java ESAPI 2 = { ',', '.', '_' }
    private $immune_xml        = array( ',', '.', '-', '_', ' ' );
    private $immune_xmlattr    = array( ',', '.', '-', '_' );
    private $immune_xpath      = array( ',', '.', '-', '_', ' ' );
    private $immune_url        = array( '.', '-', '*', '_');

    private $codecs = array();
    private $logger = null;


    /**
     *
     * @param $codecs An array of Codec instances which will be used for
     *        canonicalization.
     */
    function __construct($codecs = null)
    {
        $this->logger = ESAPI::getLogger("Encoder");

        // initialise codecs
        $this->base64Codec     = new Base64Codec();
        $this->cssCodec        = new CSSCodec();
        $this->htmlCodec       = new HTMLEntityCodec();
        $this->javascriptCodec = new JavaScriptCodec();
//      $this->ldapCodec       = new LDAPCodec();
        $this->percentCodec    = new PercentCodec();
        $this->vbscriptCodec   = new VBScriptCodec();

        // initialise array of codecs for use by canonicalize
        if ($codecs === null)
        {
            array_push($this->codecs, $this->htmlCodec);
            array_push($this->codecs, $this->javascriptCodec);
            array_push($this->codecs, $this->percentCodec);
            // leaving css and vbs codecs out - they eat / and " chars respectively
            // array_push($this->codecs,$this->cssCodec);
            // array_push($this->codecs,$this->vbscriptCodec);
        }
        else if (! is_array($codecs))
        {
            throw new Exception('Invalid Argument. Codec list must be of type Array.');
        }
        else
        {
            // check array contains only codec instances
            foreach ($codecs as $codec) {
                if (! is_a($codec, 'Codec')) {
                    throw new Exception('Invalid Argument. Codec list must contain only Codec instances.');
                }
            }
            $this->codecs = array_merge($this->codecs, $codecs);
        }

    }


    /**
     * Data canonicalization.
     *
     * This method performs canonicalization on data received to ensure that it
     * has been reduced to its most basic form before validation, for example
     * URL-encoded data received from ordinary "application/x-www-url-encoded"
     * forms, so that it may be validated properly.
     *
     * Canonicalization is simply the operation of reducing a possibly encoded
     * string down to its simplest form. This is important, because attackers
     * frequently use encoding to change their input in a way that will bypass
     * validation filters, but still be interpreted properly by the target of
     * the attack. Note that data encoded more than once is not something that a
     * normal user would generate and should be regarded as an attack.
     *
     * For input that comes from an HTTP request, there are generally two types
     * of encoding to be concerned with. The first is
     * "applicaton/x-www-url-encoded" which is what is typically used in most
     * forms and URI's where characters are encoded in a %xy format. The other
     * type of common character encoding is HTML entity encoding, which uses
     * several formats:
     *
     * <pre>&amp;lt;</pre>,
     * <pre>&amp;#117;</pre>, and
     * <pre>&amp;#x3a;</pre>.
     *
     * Note that all of these formats may possibly render properly in a browser
     * without the trailing semicolon.
     *
     * Double-encoding is a particularly thorny problem, as applying ordinary
     * decoders may introduce encoded characters, even characters encoded with a
     * different encoding scheme. For example %26lt; is a < character which has
     * been entity encoded and then the first character has been url-encoded.
     *
     * Note that there is also "multipart/form" encoding, which allows files and
     * other binary data to be transmitted. Each part of a multipart form can
     * itself be encoded according to a "Content-Transfer-Encoding" header. See
     * the HTTPUtilties.getSafeFileUploads() method.
     *
     * For more information on form encoding, please refer to the
     * <a href="http://www.w3.org/TR/html4/interact/forms.html#h-17.13.4">W3C specifications</a>.
     *
     * This Implementation will throw an IntrusionException when mixed or
     * multiple encoding is detected and 'strict' mode canonicalization was
     * requested.  It detects mixed and multiple encoding by making as many
     * passes as are required to reduce the input to its simplest form and keeps
     * track of which decoders affected a change in the input and the number of
     * passes in which each decoder performed any decoding.  When strict mode
     * canonicalization was not requested, the canonicalized input will be
     * returned, but a warning will be logged.
     *
     * @see <a href="http://www.w3.org/TR/html4/interact/forms.html#h-17.13.4">W3C specifications</a>
     *
     * @param  $input string to canonicalize.
     * @param  $strict true if checking for multiple and/or mixed encoding is
     *         desired, false otherwise.
     *
     * @return the canonicalized input string.
     *
     * @throws IntrusionException if, in strict mode, canonicalization detects
     *         multiple or mixed encoding.
     */
    function canonicalize($input, $strict = true)
    {
        if ($input === null) {
            return null;
        }
        $working = $input;
        $codecFound = null;
        $mixedCount = 1;
        $foundCount = 0;
        $clean = false;
        while (! $clean)
        {
            $clean = true;

            foreach ($this->codecs as $codec)
            {
                $old = $working;
                $working = $codec->decode($working);
                if ($old != $working) {
                    if ($codecFound != null && $codecFound != $codec) {
                        $mixedCount++;
                    }
                    $codecFound = $codec;
                    if ($clean) {
                        $foundCount++;
                    }
                    $clean = false;
                }
            }
        }
        if ( $foundCount >= 2 && $mixedCount > 1 )
        {
            if ( $strict == true ) {
                throw new IntrusionException('Input validation failure',
                    'Multiple (' . $foundCount . 'x) and mixed ('
                    . $mixedCount . 'x) encoding detected in ' . $input
                );
            } else {
                $this->logger->warning(ESAPILogger::SECURITY, false,
                    'Multiple (' . $foundCount . 'x) and mixed ('
                    . $mixedCount . 'x) encoding detected in ' . $input
                );
            }
        }
        else if ( $foundCount >= 2 )
        {
            if ( $strict == true ) {
                throw new IntrusionException('Input validation failure',
                    "Multiple encoding ({$foundCount}x) detected in {$input}"
                );
            } else {
                $this->logger->warning(ESAPILogger::SECURITY, false,
                    "Multiple encoding ({$foundCount}x) detected in {$input}"
                );
            }
        }
        else if ( $mixedCount > 1 )
        {
            if ( $strict == true ) {
                throw new IntrusionException('Input validation failure',
                    "Mixed encoding ({$mixedCount}x) detected in {$input}"
                );
            } else {
                $this->logger->warning(ESAPILogger::SECURITY, false,
                    "Mixed encoding ({$mixedCount}x) detected in {$input}"
                );
            }
        }
        return $working;
    }


    /**
     * Encode data for use in Cascading Style Sheets (CSS) content.
     *
     * @see <a href="http://www.w3.org/TR/CSS21/syndata.html#escaped-characters">CSS Syntax [w3.org]</a>
     *
     * @param  $input string to encode for CSS.
     *
     * @return the input string encoded for CSS.
     */
    function encodeForCSS($input)
    {
        if ($input === null)
        {
            return null;
        }
        return $this->cssCodec->encode($this->immune_css, $input);
    }


    /**
     * Encode data for use in HTML using HTML entity encoding
     *
     * Note that the following characters: 00-08, 0B-0C, 0E-1F and 7F-9F cannot
     * be used in HTML.
     *
     * @see <a href="http://en.wikipedia.org/wiki/Character_encodings_in_HTML">HTML Encodings [wikipedia.org]</a>
     * @see <a href="http://www.w3.org/TR/html4/sgml/sgmldecl.html">SGML Specification [w3.org]</a>
     * @see <a href="http://www.w3.org/TR/REC-xml/#charsets">XML Specification [w3.org]</a>
     *
     * @param  $input string to encode for HTML.
     *
     * @return the input string encoded for HTML.
     */
    function encodeForHTML($input)
    {
        if ($input === null)
        {
            return null;
        }
        return $this->htmlCodec->encode($this->immune_html, $input);
    }


    /**
     * Encode data for use in HTML attributes.
     *
     * @param  $input string to encode for an HTML attribute.
     *
     * @return the input string encoded for use as an HTML attribute.
     */
    function encodeForHTMLAttribute($input)
    {
        if ($input === null)
        {
            return null;
        }
        return $this->htmlCodec->encode($this->immune_htmlattr, $input);
    }


    /**
     * Encode data for insertion inside a data value in JavaScript. Putting user
     * data directly inside a script is quite dangerous. Great care must be
     * taken to prevent putting user data directly into script code itself, as
     * no amount of encoding will prevent attacks there.
     *
     * @param  $input string to encode for use in JavaScript.
     *
     * @return the input string encoded for use in JavaScript.
     */
    function encodeForJavaScript($input)
    {
        if ($input === null)
        {
            return null;
        }
        return $this->javascriptCodec->encode($this->immune_javascript, $input);
    }


    /**
     * Encode data for insertion inside a data value in a Visual Basic script.
     * Putting user data directly inside a script is quite dangerous. Great care
     * must be taken to prevent putting user data directly into script code
     * itself, as no amount of encoding will prevent attacks there.
     *
     * This method is not recommended as VBScript is only supported by Internet
     * Explorer.
     *
     * @param  $input string to encode for use in VBScript.
     *
     * @return the input string encoded for use in VBScript.
     */
    function encodeForVBScript($input)
    {
        if ($input === null)
        {
            return null;
        }
        return $this->vbscriptCodec->encode($this->immune_vbscript, $input);
    }


    /**
     * Encode input for use in a SQL query, according to the selected codec
     * (appropriate codecs include the MySQLCodec and OracleCodec).
     *
     * This method is not recommended. The use of the PreparedStatement
     * interface is the preferred approach. However, if for some reason this is
     * impossible, then this method is provided as a weaker alternative.
     *
     * @param  $codec an instance of a Codec which will encode the input string
     *         for the desired SQL database (e.g. MySQL, Oracle, etc.).  Note
     *         that MySQLCodec should be instantiated with the 'mode' to allow
     *         correct encoding. {@see MySQLCodec}
     * @param  $input string to encode for use in a SQL query.
     *
     * @return the input string encoded for use in a SQL query.
     */
    function encodeForSQL($codec, $input)
    {
        if ($input === null)
        {
            return null;
        }
        return $codec->encode($this->immune_sql, $input);
    }


    /**
     * Encode for an operating system command shell according to the selected
     * codec (appropriate codecs include the WindowsCodec and UnixCodec).
     *
     * @param  $codec an instance of a Codec which will encode the input string
     *         for the desired operating system (e.g. Windows, Unix, etc.).
     * @param  $input string to encode for use in a command shell.
     *
     * @return the input string encoded for use in a command shell.
     */
    function encodeForOS($codec, $input)
    {
        if ($input === null)
        {
            return null;
        }
        if (! is_a($codec, 'Codec'))
        {
            ESAPI::getLogger('Encoder')->error(
                ESAPILogger::SECURITY, false,
                'Invalid Argument, expected an instance of an OS Codec.'
            );
            return null;
        }
        return $codec->encode($this->immune_os, $input);
    }


    /*
     * Encode data for use in LDAP queries.
     *
     * @param  $input string to be encoded for use in LDAP queries.
     *
     * @return the input string encoded for use in LDAP queries.
     */
    function encodeForLDAP($input)
    {
        throw new EnterpriseSecurityException('Method Not implemented');
    }


    /*
     * Encode data for use in an LDAP distinguished name.
     *
     * @param  $input string to be encoded for an LDAP distinguished name.
     *
     * @return the input string encoded for use in an LDAP distinguished name.
     */
    function encodeForDN($input)
    {
        throw new EnterpriseSecurityException('Method Not implemented');
    }


    /**
     * Encode data for use in an XPath query.
     *
     * NB: This reference implementation encodes almost everything and may
     * over-encode.
     *
     * The difficulty with XPath encoding is that XPath has no built in
     * mechanism for escaping characters. It is possible to use XQuery in a
     * parameterized way to prevent injection.
     *
     * For more information, refer to <a
     * href="http://www.ibm.com/developerworks/xml/library/x-xpathinjection.html">
     * this article</a> which specifies the following list of characters as the
     * most dangerous: ^&"*';<>(). <a href=
     * "http://www.packetstormsecurity.org/papers/bypass/Blind_XPath_Injection_20040518.pdf">
     * This paper</a> suggests disallowing ' and " in queries.
     *
     * @param  $input string to be encoded for use in an XPath query.
     *
     * @return the input string encoded for use in an XPath query.
     */
    function encodeForXPath($input)
    {
        if ($input === null)
        {
            return null;
        }
        return $this->htmlCodec->encode($this->immune_xpath, $input);
    }


    /**
     * Encode data for use in an XML element. The implementation should follow
     * the <a href="http://www.w3schools.com/xml/xml_encoding.asp">XML Encoding
     * Standard</a> from the W3C.
     *
     * The use of a real XML parser is strongly encouraged. However, in the
     * hopefully rare case that you need to make sure that data is safe for
     * inclusion in an XML document and cannot use a parse, this method provides
     * a safe mechanism to do so.
     *
     * @param  $input string to be encoded for use in an XML element.
     *
     * @return the input string encoded for use in an XML element.
     */
    function encodeForXML($input)
    {
        // TODO http://code.google.com/p/owasp-esapi-java/issues/detail?id=62
        if ($input === null)
        {
            return null;
        }
        return $this->htmlCodec->encode($this->immune_xml, $input);
    }


    /**
     * Encode data for use in an XML attribute. The implementation should follow
     * the <a href="http://www.w3schools.com/xml/xml_encoding.asp">XML Encoding
     * Standard</a> from the W3C.
     *
     * The use of a real XML parser is highly encouraged. However, in the
     * hopefully rare case that you need to make sure that data is safe for
     * inclusion in an XML document and cannot use a parse, this method provides
     * a safe mechanism to do so.
     *
     * @param  $input string to be encoded for use as an XML attribute.
     *
     * @return the input string encoded for use in an XML attribute.
     */
    function encodeForXMLAttribute($input)
    {
        // TODO http://code.google.com/p/owasp-esapi-java/issues/detail?id=62
        if ($input === null)
        {
            return null;
        }
        return $this->htmlCodec->encode($this->immune_xmlattr, $input);
    }


    /**
     * Encode for use in a URL. This method performs <a
     * href="http://en.wikipedia.org/wiki/Percent-encoding">URL encoding</a>
     * on the entire string.  First the input string is passed to PercentCodec
     * and the result is then searched for occurrances of '%20' which are
     * replaced with the '+' character.
     *
     * @param  $input string to be encoded for use in a URL.
     *
     * @return the input string encoded for use in a URL.
     */
    function encodeForURL($input)
    {
        if ($input === null)
        {
            return null;
        }
        $encoded = $this->percentCodec->encode($this->immune_url, $input);

        $initialEncoding = $this->percentCodec->detectEncoding($encoded);
        $decodedString = mb_convert_encoding('', $initialEncoding);

        $pcnt = $this->percentCodec->normalizeEncoding('%');
        $two  = $this->percentCodec->normalizeEncoding('2');
        $zero = $this->percentCodec->normalizeEncoding('0');
        $char_plus = mb_convert_encoding('+', $initialEncoding);

        $index = 0;
        $limit = mb_strlen($encoded, $initialEncoding);
        for ($i = 0; $i < $limit; $i++)
        {
            if ($index > $i) {
                continue; // already dealt with this character
            }
            $c = mb_substr($encoded, $i,   1, $initialEncoding);
            $d = mb_substr($encoded, $i+1, 1, $initialEncoding);
            $e = mb_substr($encoded, $i+2, 1, $initialEncoding);
            if (   $this->percentCodec->normalizeEncoding($c) == $pcnt
                && $this->percentCodec->normalizeEncoding($d) == $two
                && $this->percentCodec->normalizeEncoding($e) == $zero
            ) {
                $decodedString .= $char_plus;
                $index += 3;
            } else {
                $decodedString .= $c;
                $index++;
            }
        }

        return $decodedString;
    }


    /**
     * Decode from URL. This implementation first performs canonicalization to
     * detect any multiple or mixed encoding which, if detected, will cause an
     * IntrusionException to be thrown.
     * The canonicalized string is passed to the URL decoder after any '+'
     * characters are each replaced with a single space.
     *
     * @param  $input string to be decoded.
     *
     * @return the input string decoded from URL
     */
    function decodeFromURL($input)
    {
        if ($input === null)
        {
            return null;
        }
        $canonical = $this->canonicalize($input, true);

        // Replace '+' with ' '
        $initialEncoding = $this->percentCodec->detectEncoding($canonical);
        $decodedString = mb_convert_encoding('', $initialEncoding);

        $find = $this->percentCodec->normalizeEncoding('+');
        $char_space = mb_convert_encoding(' ', $initialEncoding);

        $limit = mb_strlen($canonical, $initialEncoding);
        for ($i = 0; $i < $limit; $i++)
        {
            $c = mb_substr($canonical, $i, 1, $initialEncoding);
            if ($this->percentCodec->normalizeEncoding($c) == $find) {
                $decodedString .= $char_space;
            } else {
                $decodedString .= $c;
            }
        }

        return $this->percentCodec->decode($decodedString);
    }


    /**
     * Encode data with Base64 encoding.
     *
     * @param  $input string to encode for Base64
     * @param  $wrap boolean the encoder will wrap lines every 64 characters of
     *               output if true
     *
     * @return the input string encoded for Base64
     */
    function encodeForBase64($input, $wrap = false)
    {
        if ($input === null)
        {
            return null;
        }
        if ($wrap == false)
        {
            return $this->base64Codec->encode($this->immune_base64, $input);
        }

        // wrap encoded string into lines of not more than 64 characters
        $encoded = $this->base64Codec->encode($this->immune_base64, $input);
        $initialEncoding = $this->base64Codec->detectEncoding($encoded);
        $wrapped = '';
        $limit = mb_strlen($encoded, $initialEncoding);
        $index = 0;

        while ($index < $limit) {
            if ($wrapped != '') {
                 $wrapped .= "\n";
            }
            $wrapped .= mb_substr($encoded, $index, 64);
            $index += 64;
        }
        return $wrapped;
    }


    /**
     * Decode data encoded with Base64 encoding.
     *
     * @param  $input string to be decoded.
     *
     * @return the input string decoded from Base64.
     */
    function decodeFromBase64($input)
    {
        if ($input === null)
        {
            return null;
        }
        return $this->base64Codec->decode($input);
    }
}
