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
 * @created 2008
 * @since 1.4
 * @package org.owasp.esapi.codecs
 */


require_once ('Codec.php');

/**
 * Implementation of the Codec interface for MySQL strings. See http://mirror.yandex.ru/mirrors/ftp.mysql.com/doc/refman/5.0/en/string-syntax.html
 * for more information.
 * 
 * @author 
 * @since 1.4
 * @see org.owasp.esapi.Encoder
 */
class MySQLCodec extends Codec
{
	const MYSQL_ANSI	= 0;
	const MYSQL_STD		= 1;
	
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