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
 * @package org.owasp.esapi.errors;
 * @created 2007
 */


/**
 * An AvailabilityException should be thrown when the availability of a limited
 * resource is in jeopardy. For example, if a database connection pool runs out
 * of connections, an availability exception should be thrown.
 * 
 * @author Jeff Williams (jeff.williams@aspectsecurity.com)
 */
class IntegrityException extends EnterpriseSecurityException {

	/** The Constant serialVersionUID. */
	private static $serialVersionUID = 1;

	/**
	 * Instantiates a new availability exception.
	 */
	protected function IntegrityException() {
		// hidden
	}

    /**
     * Instantiates a new IntegrityException.
     * 
     * @param message
     *            the message
     * @param cause
     *            the cause
     */
    public function IntegrityException($userMessage, $logMessage, $cause) {
        super($userMessage, $logMessage, $cause);
    }
}
?>