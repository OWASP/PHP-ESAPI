<?php
/**
 * OWASP Enterprise Security API (ESAPI)
 *
 * This file is part of the Open Web Application Security Project (OWASP)
 * Enterprise Security API (ESAPI) project. For details, please see
 * http://www.owasp.org/esapi.
 *
 * Copyright (c) 2007 - The OWASP Foundation
 *
 * The ESAPI is published by OWASP under the LGPL. You should read and accept the
 * LICENSE before you use, modify, and/or redistribute this software.
 *
 * @author Jeff Williams <a href="http://www.aspectsecurity.com">Aspect Security</a>
 * @author Andrew van der Stock <a href="http://www.aspectsecurity.com">Aspect Security</a>
 * @package org.owasp.esapi.errors;
 * @since 2008
 */

require_once('org.owasp.esapi.EnterpriseSecurityException.php');

/**
 * An AvailabilityException should be thrown when the availability of a limited
 * resource is in jeopardy. For example, if a database connection pool runs out
 * of connections, an availability exception should be thrown.
 *
 * @author Jeff Williams (jeff.williams@aspectsecurity.com)
 */
class IntegrityException extends EnterpriseSecurityException
{
    /**
     * Instantiates a new IntegrityException.
     *
     * @param message
     *            the message
     * @param cause
     *            the cause
     */
    function __construct($userMessage, $logMessage, $cause)
    {
        parent :: __construct($userMessage, $logMessage, $cause);
    }
}
?>