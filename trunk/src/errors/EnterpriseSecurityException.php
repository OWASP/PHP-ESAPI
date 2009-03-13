<?php
/**
 * OWASP Enterprise Security API (ESAPI)
 * 
 * This file is part of the Open Web Application Security Project (OWASP)
 * Enterprise Security API (ESAPI) project. For details, please see
 * <a href="http://www.owasp.org/index.php/ESAPI">http://www.owasp.org/index.php/ESAPI</a>.
 *
 * Copyright (c) 2007 - 2008 The OWASP Foundation
 * 
 * The ESAPI is published by OWASP under the BSD license. You should read and accept the
 * LICENSE before you use, modify, and/or redistribute this software.
 * 
 * @author Andrew van der Stock <vanderaj .(at). owasp.org> 
 * @created 2008
 * @since 1.4
 * @package org.owasp.esapi.errors
 */

/**
 * EnterpriseSecurityException is the base class for all security related exceptions. You should pass in the root cause
 * exception where possible. Constructors for classes extending EnterpriseSecurityException should be sure to call the
 * appropriate super() method in order to ensure that logging and intrusion detection occur properly.
 * <P>
 * All EnterpriseSecurityExceptions have two messages, one for the user and one for the log file. This way, a message
 * can be shown to the user that doesn't contain sensitive information or unnecessary implementation details. Meanwhile,
 * all the critical information can be included in the exception so that it gets logged.
 * <P>
 * Note that the "logMessage" for ALL EnterpriseSecurityExceptions is logged in the log file. This feature should be
 * used extensively throughout ESAPI implementations and the result is a fairly complete set of security log records.
 * ALL EnterpriseSecurityExceptions are also sent to the IntrusionDetector for use in detecting anomolous patterns of
 * application usage.
 * <P>
 */
class EnterpriseSecurityException extends Exception
{
    /** The logger. */
    protected $logger;
    protected $logMessage = null;

    /**
     * Creates a new instance of EnterpriseSecurityException that includes a root cause 
     * 
     * @param userMessage 
     * 			  the message displayed to the user
     * @param logMessage
     * 			  the message logged
     * @param cause the cause
     */
    public function __construct($userMessage = '', $logMessage = '')
    {
    	global $ESAPI;
    	$cause = 0;
    	
    	if ( empty($userMessage) ) {
    		$userMessage = null;
    		
    	}
    	    	
		parent::__construct($userMessage);
        
        $this->logMessage = $logMessage;
        $this->logger = $ESAPI->getLogger("EnterpriseSecurityException");
        $ESAPI->intrusionDetector()->addException($this);
    }

    /**
     * Returns message that is safe to display to users
     * 
     * @return a String containing a message that is safe to display to users
     */
    public function getUserMessage()
    {
        return $this->getMessage();
    }

    /**
     * Returns a message that is safe to display in logs, but probably not to users
     * 
     * @return a String containing a message that is safe to display in logs, but probably not to users
     */
    public function getLogMessage()
    {
        return $this->logMessage;
    }

}
?>