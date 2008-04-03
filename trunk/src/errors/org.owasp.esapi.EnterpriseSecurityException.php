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
 * @author Jeff Williams (jeff.williams@aspectsecurity.com)
 */
class EnterpriseSecurityException extends Exception {

    /** The Constant serialVersionUID. */
    private static $serialVersionUID = 1;

    /** The logger. */
    protected static $logger;

    protected $logMessage = null;

    /**
     * Instantiates a new security exception.
     */
    protected function EnterpriseSecurityException() {
        // hidden
    }

    /**
     * Creates a new instance of EnterpriseSecurityException. This exception is automatically logged, so that simply by
     * using this API, applications will generate an extensive security log. In addition, this exception is
     * automatically registered with the IntrusionDetector, so that quotas can be checked.
     *
     * @param message the message
     */
    function EnterpriseSecurityException($userMessage, $logMessage, $cause = null) {
		 $logger = Logger::getLogger("ESAPI", "EnterpriseSecurityException");

    	// FIXME: AAA - add log level to exception to tell intrusion detector how to log it

    	if ( $cause ) {
    		super(userMessage, cause);
    	}
    	else {
    		super(userMessage);
    	}

        $this->logMessage = logMessage;
        ESAPI::intrusionDetector()->addException($this);
    }

    public function getUserMessage() {
        return $this->getMessage();
    }

    public function getLogMessage() {
        return $this->logMessage;
    }

}

?>