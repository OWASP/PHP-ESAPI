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
 * An EncryptionException should be thrown for any problems related to
 * encryption, hashing, or digital signatures.
 * 
 * @author Jeff Williams (jeff.williams@aspectsecurity.com)
 */
class EncryptionException extends EnterpriseSecurityException {

	/** The Constant serialVersionUID. */
	private static $serialVersionUID = 1;

	/**
	 * Instantiates a new EncryptionException.
	 */
	protected function EncryptionException() {
		// hidden
	}

    /**
     * Instantiates a new EncryptionException.
     * 
     * @param message
     *            the message
     * @param cause
     *            the cause
     */
    public function EncryptionException($userMessage, $logMessage, $cause = null) {
        super($userMessage, $logMessage, $cause);
    }
}
?>