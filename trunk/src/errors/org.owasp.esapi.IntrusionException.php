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

/**
 * An IntrusionException should be thrown anytime an error condition arises that is likely to be the result of an attack
 * in progress. IntrusionExceptions are handled specially by the IntrusionDetector, which is equipped to respond by
 * either specially logging the event, logging out the current user, or invalidating the current user's account.
 * <P>
 * Unlike other exceptions in the ESAPI, the IntrusionException is a RuntimeException so that it can be thrown from
 * anywhere and will not require a lot of special exception handling. // FIXME Remove this para
 *
 * @author Jeff Williams (jeff.williams@aspectsecurity.com)
 */
class IntrusionException extends Exception
{
    protected static $logger;
    protected $logMessage = null;
    /**
     * Creates a new instance of IntrusionException.
     *
     * @param message the message
     * @param cause the cause
     */
    function __construct($userMessage, $logMessage, $cause)
    {
        $logger = Logger :: getLogger("ESAPI", "IntrusionException");
        parent :: __construct($userMessage, $cause);
        $this->logMessage = $logMessage;
        $logger->logError(Logger :: SECURITY, "INTRUSION - " + $this->logMessage, $cause);
    }
    public function getUserMessage()
    {
        return $this->getMessage();
    }
    public function getLogMessage()
    {
        return $this->logMessage;
    }
}
?>