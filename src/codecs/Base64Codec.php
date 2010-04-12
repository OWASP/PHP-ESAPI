<?php
/**
 * OWASP Enterprise Security API (ESAPI)
 *
 * This file is part of the Open Web Application Security Project (OWASP)
 * Enterprise Security API (ESAPI) project. For details, please see
 * <a href="http://www.owasp.org/index.php/ESAPI">http://www.owasp.org/index.php/ESAPI</a>.
 *
 * Copyright (c) 2007 - 2010 The OWASP Foundation
 *
 * The ESAPI is published by OWASP under the BSD license. You should read and
 * accept the LICENSE before you use, modify, and/or redistribute this software.
 *
 * @author martin.reiche
 * @author jah (at jahboite.co.uk)
 * @created 2009
 * @since 1.6
 * @package org.owasp.esapi.codecs
 */


/**
 *
 */
require_once dirname ( __FILE__ ) . '/Codec.php';
require_once dirname ( __FILE__ ) . '/../ESAPI.php';


/**
 * Implementation of the Codec Interface for Base64 encoding and decoding.
 *
 * This implementation makes use of the standard PHP base64_encode function and
 * implements Section 6.8 of RFC 2045.
 *
 * For more information see:
 * {@link http://tools.ietf.org/html/rfc2045#section-6.8}
 * {@link http://en.wikipedia.org/wiki/Base64#MIME}
 *
 */
class Base64Codec
{

    /**
     * Public Constructor
     */
    function __construct()
    {
        $logger = ESAPI::getAuditor("Base64");
    }

    /**
     * Encodes the input string to Base64.
     *
     * The output is wrapped at 76 characters by default, but this behaviour may
     * be overridden by supplying a value of boolean false for the $wrap
     * parameter.
     *
     * @param input the input string to be encoded
     * @return the encoded string
     */
    public function encode($input, $wrap = true)
    {
        $encoded = base64_encode($input);

        if ($wrap === false)
        {
            return $encoded;
        }

        // wrap encoded string into lines of not more than 76 characters
        $detectedCharacterEncoding = Codec::detectEncoding($encoded);
        $wrapped = '';
        $limit = mb_strlen($encoded, $detectedCharacterEncoding);
        $index = 0;
        while ($index < $limit) {
            if ($wrapped != '') {
                $wrapped .= "\r\n";
            }
            $wrapped .= mb_substr($encoded, $index, 76);
            $index += 76;
        }

        return $wrapped;

    }

    /**
     * Encodes a single character to Base64.
     *
     * @param  $input the character to encode
     * @return the base64 encoded character
     */
    public function encodeCharacter($input)
    {
        $detectedCharacterEncoding = Codec::detectEncoding($input);
        $c = mb_substr($input, 0, 1, $detectedCharacterEncoding);

        return $this->encode($c, false);
    }


    /**
     * Decodes the given input string from Base64 to plain text.
     *
     * @param  $input the base64 encoded input string
     * @return the decoded string
     */
    public function decode($input)
    {
        return base64_decode($input);
    }

    /**
     * Decodes a character from Base64 to plain text
     *
     * @param  $input the character to decode
     * @return the decoded character
     */
    public function decodeCharacter($input)
    {
        return $this->decode($input);
    }
}
