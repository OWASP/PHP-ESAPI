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
 * An ExecutorException should be thrown for any problems that arise during the
 * execution of a system executable.
 *
 * @author Jeff Williams (jeff.williams@aspectsecurity.com)
 */
class ExecutorException extends EnterpriseSecurityException {

	/** The Constant serialVersionUID. */
	private static $serialVersionUID = 1;

	/**
	 * Instantiates a new ExecutorException.
	 */
	protected function ExecutorException() {
		// hidden
	}

    /**
     * Instantiates a new ExecutorException.
     *
     * @param message
     *            the message
     * @param cause
     *            the cause
     */
    public function ExecutorException($userMessage, $logMessage, $cause = null ) {
        super($userMessage, $logMessage, $cause);
    }
}
?>