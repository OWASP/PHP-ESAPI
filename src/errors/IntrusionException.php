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
 * An IntrusionException should be thrown anytime an error condition arises that is likely to be the result of an attack
 * in progress. IntrusionExceptions are handled specially by the IntrusionDetector, which is equipped to respond by
 * either specially logging the event, logging out the current user, or invalidating the current user's account.
 * <P>
 * Unlike other exceptions in the ESAPI, the IntrusionException is a RuntimeException so that it can be thrown from
 * anywhere and will not require a lot of special exception handling.
 * 
 */
class IntrusionException extends Exception
{
    protected $logger; // ESAPI Logger class
    protected $logMessage = null; // Message to be sent to the log

    /**
     * Instantiates a new intrusion exception.
     * 
     * @param userMessage
     *            the message to display to users
     * @param logMessage
     * 			  the message logged
     * @param cause 
     *			  the cause
     */
    function __construct($userMessage = '', $logMessage = '')
    {
        global $ESAPI;

        parent::__construct($userMessage);
        $this->logMessage = $logMessage;
        $logger = $ESAPI->getAuditor("IntrusionException");
        $logger->error(DefaultAuditor::SECURITY, false, "INTRUSION - " . $logMessage);
    }

    /**
     * Returns a String containing a message that is safe to display to users
     * 
     * @return a String containing a message that is safe to display to users
     */
    public function getUserMessage()
    {
        return $this->getMessage();
    }

    /**
     * Returns a String that is safe to display in logs, but probably not to users
     * 
     * @return a String containing a message that is safe to display in logs, but probably not to users
     */
    public function getLogMessage()
    {
        return $this->logMessage;
    }

}
?>