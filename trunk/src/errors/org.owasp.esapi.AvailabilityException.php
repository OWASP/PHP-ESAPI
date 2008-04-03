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
 * An AvailabilityException should be thrown when the availability of a limited
 * resource is in jeopardy. For example, if a database connection pool runs out
 * of connections, an availability exception should be thrown.
 *
 * @author Jeff Williams (jeff.williams@aspectsecurity.com)
 */
class AvailabilityException extends EnterpriseSecurityException {

	/** The Constant serialVersionUID. */
	private static $serialVersionUID = 1;

	/**
	 * Instantiates a new availability exception.
	 */
	protected function AvailabilityException() {
		// hidden
	}

    /**
     * Instantiates a new AvailabilityException.
     *
     * @param message
     *            the message
     * @param cause
     *            the cause
     */
    public function AvailabilityException($userMessage, $logMessage, $cause = null) {
        super($userMessage, $logMessage, $cause);
    }
}
?>