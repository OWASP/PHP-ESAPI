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
 * @author 
 * @created 2008
 * @since 1.4
 * @package org.owasp.esapi.reference
 */

require_once ('../src/SecurityConfiguration.php');

class DefaultSecurityConfiguration implements SecurityConfiguration {

	/**
	 * Gets the application name, used for logging
	 * 
	 * @return the name of the current application
	 */
	function getApplicationName() {
		throw new EnterpriseSecurityException("Method Not implemented");
	}

	/**
	 * Gets the master password. This password can be used to encrypt/decrypt other files or types
	 * of data that need to be protected by your application.
	 * 
	 * @return the current master password
	 */
	function getMasterPassword() {
		throw new EnterpriseSecurityException("Method Not implemented");
	}

	/**
	 * Gets the keystore used to hold any encryption keys used by your application.
	 * 
	 * @return the current keystore
	 */
	function getKeystore() {
		throw new EnterpriseSecurityException("Method Not implemented");
	}

	/**
	 * Gets the master salt that is used to salt stored password hashes and any other location 
	 * where a salt is needed.
	 * 
	 * @return the current master salt
	 */
	function getMasterSalt() {
		throw new EnterpriseSecurityException("Method Not implemented");
	}

	/**
	 * Gets the allowed file extensions for files that are uploaded to this application.
	 * 
	 * @return a list of the current allowed file extensions
	 */
	function getAllowedFileExtensions() {
		throw new EnterpriseSecurityException("Method Not implemented");
	}

	/**
	 * Gets the maximum allowed file upload size.
	 * 
	 * @return the current allowed file upload size
	 */
	function getAllowedFileUploadSize() {
		throw new EnterpriseSecurityException("Method Not implemented");
	}

	/**
	 * Gets the name of the password parameter used during user authentication.
	 * 
	 * @return the name of the password parameter
	 */
	function getPasswordParameterName() {
		throw new EnterpriseSecurityException("Method Not implemented");
	}

	/**
	 * Gets the name of the username parameter used during user authentication.
	 * 
	 * @return the name of the username parameter
	 */
	function getUsernameParameterName() {
		throw new EnterpriseSecurityException("Method Not implemented");
	}

	/**
	 * Gets the encryption algorithm used by ESAPI to protect data.
	 * 
	 * @return the current encryption algorithm
	 */
	function getEncryptionAlgorithm() {
		throw new EnterpriseSecurityException("Method Not implemented");
	}

	/**
	 * Gets the hashing algorithm used by ESAPI to hash data.
	 * 
	 * @return the current hashing algorithm
	 */
	function getHashAlgorithm() {
		throw new EnterpriseSecurityException("Method Not implemented");
	}

	/**
	 * Gets the character encoding scheme supported by this application. This is used to set the
	 * character encoding scheme on requests and responses when setCharacterEncoding() is called
	 * on SafeRequests and SafeResponses. This scheme is also used for encoding/decoding URLs 
	 * and any other place where the current encoding scheme needs to be known.
	 * <br><br>
	 * Note: This does not get the configured response content type. That is accessed by calling 
	 * getResponseContentType().
	 * 
	 * @return the current character encoding scheme
	 */
	function getCharacterEncoding() {
		throw new EnterpriseSecurityException("Method Not implemented");
	}

	/**
	 * Gets the digital signature algorithm used by ESAPI to generate and verify signatures.
	 * 
	 * @return the current digital signature algorithm
	 */
	function getDigitalSignatureAlgorithm() {
		throw new EnterpriseSecurityException("Method Not implemented");
	}

	/**
	 * Gets the random number generation algorithm used to generate random numbers where needed.
	 * 
	 * @return the current random number generation algorithm
	 */
	function getRandomAlgorithm() {
		throw new EnterpriseSecurityException("Method Not implemented");
	}

	/**
	 * Gets the number of login attempts allowed before the user's account is locked. If this 
	 * many failures are detected within the alloted time period, the user's account will be locked.
	 * 
	 * @return the number of failed login attempts that cause an account to be locked
	 */
	function getAllowedLoginAttempts() {
		throw new EnterpriseSecurityException("Method Not implemented");
	}

	/**
	 * Gets the maximum number of old password hashes that should be retained. These hashes can 
	 * be used to ensure that the user doesn't reuse the specified number of previous passwords
	 * when they change their password.
	 * 
	 * @return the number of old hashed passwords to retain
	 */
	function getMaxOldPasswordHashes() {
		throw new EnterpriseSecurityException("Method Not implemented");
	}

	/**
	 * Gets the intrusion detection quota for the specified event.
	 * 
	 * @param eventName the name of the event whose quota is desired
	 * 
	 * @return the Quota that has been configured for the specified type of event
	 */
	function getQuota($eventName) {
		throw new EnterpriseSecurityException("Method Not implemented");
	}

	/**
	 * Gets the name of the ESAPI resource directory as a String.
	 * 
	 * @return The ESAPI resource directory.
	 */
	function getResourceDirectory() {
		// TODO: throw new EnterpriseSecurityException("Method Not implemented");
		return '';
	}

	/**
	 * Sets the ESAPI resource directory.
	 * 
	 * @param dir The location of the resource directory.
	 */
	function setResourceDirectory($dir) {
		throw new EnterpriseSecurityException("Method Not implemented");
	}

	/**
	 * Gets the content type for responses used when setSafeContentType() is called.
	 * <br><br>
	 * Note: This does not get the configured character encoding scheme. That is accessed by calling 
	 * getCharacterEncoding().
	 * 
	 * @return The current content-type set for responses.
	 */
	function getResponseContentType() {
		throw new EnterpriseSecurityException("Method Not implemented");
	}

	/**
	 * Gets the length of the time to live window for remember me tokens (in milliseconds).
	 * 
	 * @return The time to live length for generated remember me tokens.
	 */
	function getRememberTokenDuration() {
		throw new EnterpriseSecurityException("Method Not implemented");
	}

	/**
	 * Gets the idle timeout length for sessions (in milliseconds). This is the amount of time that a session
	 * can live before it expires due to lack of activity. Applications or frameworks could provide a reauthenticate
	 * function that enables a session to continue after reauthentication.
	 * 
	 * @return The session idle timeout length.
	 */
	function getSessionIdleTimeoutLength() {
		throw new EnterpriseSecurityException("Method Not implemented");
	}

	/**
	 * Gets the absolute timeout length for sessions (in milliseconds). This is the amount of time that a session
	 * can live before it expires regardless of the amount of user activity. Applications or frameworks could 
	 * provide a reauthenticate function that enables a session to continue after reauthentication.
	 * 
	 * @return The session absolute timeout length.
	 */
	function getSessionAbsoluteTimeoutLength() {
		throw new EnterpriseSecurityException("Method Not implemented");
	}

	/**
	 * Returns whether HTML entity encoding should be applied to log entries.
	 * 
	 * @return True if log entries are to be HTML Entity encoded. False otherwise.
	 */
	function getLogEncodingRequired() {
		throw new EnterpriseSecurityException("Method Not implemented");
	}

	/**
	 * Get the log level specified in the ESAPI configuration properties file. Return a default 
	 * value if it is not specified in the properties file.
	 * 
	 * @return the logging level defined in the properties file. If none is specified, the default 
	 * of Logger.WARNING is returned.
	 */
	function getLogLevel() {
		throw new EnterpriseSecurityException("Method Not implemented");
	}

	/**
	 * Get the name of the log file specified in the ESAPI configuration properties file. Return a default value 
	 * if it is not specified.
	 * 
	 * @return the log file name defined in the properties file.
	 */
	function getLogFileName() {
		throw new EnterpriseSecurityException("Method Not implemented");
	}

	/**
	 * Get the maximum size of a single log file from the ESAPI configuration properties file. Return a default value 
	 * if it is not specified. Once the log hits this file size, it will roll over into a new log.
	 * 
	 * @return the maximum size of a single log file (in bytes).
	 */
	function getMaxLogFileSize() {
		throw new EnterpriseSecurityException("Method Not implemented");
	}

}
?>