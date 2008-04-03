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
 * A CertificateException should be thrown for any problems that arise during
 * processing of digital certificates.
 *
 * @author Jeff Williams (jeff.williams@aspectsecurity.com)
 */
class CertificateException extends EnterpriseSecurityException {

	/** The Constant serialVersionUID. */
	private static $serialVersionUID = 1;

	/**
	 * Instantiates a new certificate exception.
	 */
	protected function CertificateException() {
		// hidden
	}

    /**
     * Instantiates a new CertificateException.
     *
     * @param message
     *            the message
     * @param cause
     *            the cause
     */
    public function CertificateException($userMessage, $logMessage, $cause) {
        super($userMessage, $logMessage, $cause);
    }

}
?>