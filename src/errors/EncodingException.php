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
 * @author Andrew van der Stock <vanderaj .(at). owasp.org> 
 * @created 2008
 * @since 1.4
 * @package org.owasp.esapi.errors
 */

require_once  dirname(__FILE__).'/EnterpriseSecurityException.php';

/**
 * An EncodingException should be thrown for any problems that occur when
 * encoding or decoding data.
 * 
 * @author Jeff Williams (jeff.williams@aspectsecurity.com)
 */
class EncodingException extends EnterpriseSecurityException
{
    /**
     * Instantiates a new EncodingException.
     * 
     * @param userMessage
     *            the message displayed to the user
     * @param logMessage
     * 			  the message logged
     * @param cause
     *            the cause
     */
    function __construct($userMessage = '', $logMessage = '')
    {
        parent::__construct($userMessage, $logMessage);
    }
}
?>