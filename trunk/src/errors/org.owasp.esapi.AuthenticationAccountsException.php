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

require_once('org.owasp.esapi.AuthenticationException.php');

/**
 * An AuthenticationException should be thrown when anything goes wrong during
 * login or logout. They are also appropriate for any problems related to
 * identity management.
 *
 * @author Jeff Williams (jeff.williams@aspectsecurity.com)
 */
class AuthenticationAccountsException extends AuthenticationException
{
    /**
     * Creates a new instance of EnterpriseSecurityException.
     *
     * @param message
     *            the message
     */
    function __construct($userMessage, $logMessage, $cause = null)
    {
        parent :: __construct($userMessage, $logMessage, $cause);
    }
}
?>