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
 * The ESAPI is published by OWASP under the BSD license. You should read and accept the
 * LICENSE before you use, modify, and/or redistribute this software.
 * 
 * @author 
 * @created 2009
 * @since 1.4
 * @package org.owasp.esapi.codecs
 */

require_once  dirname(__FILE__).'/Codec.php';

/**
 * Implementation of the Codec interface for base 64 encoding.
 * 
 * @author 
 * @since 1.4
 * @see org.owasp.esapi.Encoder
 */
class Base64Codec implements Codec
{

    /**
     * Public Constructor 
     */
    function __construct()
    {
    }

    /**
     * {@inheritDoc}
     */
    public function encode($input)
    {
    }

    /**
     * {@inheritDoc}
     */
    public function encodeCharacter($c)
    {
    }

    /**
     * {@inheritDoc}
     */
    public function decode($input)
    {
    }

    /**
     * {@inheritDoc}
     */
    public function decodeCharacter($input)
    {
    }
}