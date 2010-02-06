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
 * The ESAPI is published by OWASP under the BSD license. You should read and accept the
 * LICENSE before you use, modify, and/or redistribute this software.
 *
 * @author martin.reiche
 * @created 2009
 * @since 1.4
 * @package org.owasp.esapi.codecs
 */

require_once dirname ( __FILE__ ) . '/Codec.php';
require_once dirname ( __FILE__ ) . '/../ESAPI.php';

/**
 * Implementation of the Codec Interface for Base64 en/decoding.
 */
class Base64Codec extends Codec
{

    /**
     * Public Constructor
     */
    function __construct()
    {
        parent::__construct();
        $logger = ESAPI::getLogger("Base64");
    }

    /**
     * Encodes the input string to Base64. This function will not touch
     * characters that are provided in the $immune array.
     *
     * @param immune the array of characters that shall not be encoded
     * @param input the input string to be encoded
     * @return the encoded string
     */
    public function encode($immune, $input)
    {
        /* The simple variant if no immune characters are given */
        if (empty($immune)){
            return base64_encode($input);
        }

        /* The more expensive variant if we want to have immune chars */
        foreach($input as $inputchar) {
            $b64end .= $this->encodeCharacter($immune, $inputchar);
        }
        return $b64end;
    }

    /**
     * Encodes the character $c if it is not listed in the $immune array.
     *
     * @param immune array of characters that should not be encoded
     * @param c the character to encode
     * @returns the base64 encoded character
     */
    public function encodeCharacter($immune, $c)
    {
        /* only encode if char is no immune char */
        return in_array($c, $immune) ? base64_encode($c) : $c;
    }


    /**
     * Decodes the given input string from Base64 to plain text.
     *
     * @param input the base64 encoded input string
     * @return the decoded string
     */
    public function decode($input)
    {
        return base64_decode($input);
    }

    /**
     * Decodes a character from Base64 to plain text
     *
     * @param input the character to decode
     * @return the decoded character
     */
    public function decodeCharacter($input)
    {
        return $this->decode($input);
    }
}
?>
