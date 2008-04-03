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
 * An AuthenticationHostException should be thrown when there is a problem with
 * the host involved with authentication, particularly if the host changes unexpectedly.
 *
 * @author Jeff Williams (jeff.williams@aspectsecurity.com)
 */
class AuthenticationHostException extends AuthenticationException {

	/** The Constant serialVersionUID. */
	private static $serialVersionUID = 1;

	/**
	 * Instantiates a new authentication exception.
	 */
	protected function AuthenticationHostException() {
		// hidden
	}

	/**
	 * Creates a new instance of AuthenticationHostException.
	 *
	 * @param message
	 *            the message
	 * @param message
	 *            the message
	 */
	public function AuthenticationHostException($userMessage, $logMessage, $cause = null) {
		super($userMessage, $logMessage, $cause);
	}
}
?>