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
 * @author Andrew van der Stock
 * @created 2008
 * @since 1.4
 * @package org.owasp.esapi.reference
 */

require_once dirname(__FILE__) . '/../SecurityConfiguration.php';

class DefaultSecurityConfiguration implements SecurityConfiguration
{
	// SimpleXML reads the entire file into memory, so there's no penalty for keeping the result here
	private $xml = null;
	
	// Authenticator
	
	private $RememberTokenDuration = null;
	private $AllowedLoginAttempts = null;
	private $MaxOldPasswordHashes = null;
	private $UsernameParameterName = null;
	private $PasswordParameterName = null;
	private $IdleTimeoutDuration = null;
	private $AbsoluteTimeoutDuration = null;
	
	// Encoder
	
	// Executor
	
	// Encryptor
	
	private $CharacterEncoding = null;
	private $MasterKey = null;
	private $MasterSalt = null;
	private $EncryptionAlgorithm = null;
	private $HashAlgorithm = null;
	private $DigitalSignatureAlgorithm = null;
	private $RandomAlgorithm = null;
	
	// HTTPUtilities
	
	private $AllowedFileExtensions = null;
	private $maxUploadSize = null;
	private $ResponseContentType = null;
	
	// Logger
	
	private $ApplicationName = null;
	private $LogEncodingRequired = null;
	private $LogLevel = null;
	private $LogFileName = null;
	private $MaxLogFileSize = null;
	
	// Validator
	
	private $patternCache = array();
	
		
	// IntrusionDetector
	
	private $resourceDir = null;
	
	function __construct($path = '')
	{
        try
        {
			$this->loadConfiguration($path);
			$this->setResourceDirectory(dirname(realpath($path)));
        } 
        catch ( Exception $e ) 
        {
        	$this->logSpecial($e->getMessage());
        }
	}
	
	private function loadConfiguration($path)
	{
		if ( file_exists($path) ) {
			$this->xml = simplexml_load_file($path);
			
			if ( $this->xml === false ) {
				throw new Exception("Failed to load security configuration.");
			}
		} else {
			throw new Exception("Security configuration file does not exist.");
		}
	}

	private function logSpecial($msg) {
		echo $msg;
	}
	
	private function getESAPIStringProperty($prop, $def) {
		$val = $def;
		
		$var = $this->xml->xpath('/esapi-properties/'.$prop);

		if ( $var === false ) {
			$this->logSpecial( 'SecurityConfiguration for /esapi-properties/' . $prop . ' not found in ESAPI.xml. Using default: '. $def);
		}

		if (isset($var[0]) ) {
			$val = (string) $var[0];
		}
		
		return $val;
	}
	
	private function getESAPIArrayProperty($prop, $def) {
		$val = $def;
		
		$var = $this->xml->xpath('/esapi-properties/'.$prop);

		if ( $var === false ) {
			$this->logSpecial( 'SecurityConfiguration for /esapi-properties/' . $prop . ' not found in ESAPI.xml. Using default: '. $def);
		}

		$result = array();
		if (isset($var[0]) ) {
			while(list( , $node) = each($var)) {
 				$result[] = (string) $node[0];
			}
			
			$val = $result;
		}
		
		return $val;
	}
	
	private function getESAPIEncodedStringProperty($prop, $def) {
		return base64_decode($this->getESAPIStringProperty($prop, $def));
	}

	private function getESAPIIntProperty($prop, $def) {
		$val = $def;
		
		$var = $this->xml->xpath('/esapi-properties/'.$prop);

		if ( $var === false ) {
			$this->logSpecial( 'SecurityConfiguration for /esapi-properties/' . $prop . ' not found in ESAPI.xml. Using default: '. $def);
		}

		if (isset($var[0]) ) {
			$val = (int) $var[0];
		}
		
		return $val;
	}
	
	private function getESAPIBooleanProperty($prop, $def) {
		$val = $this->getESAPIStringProperty($prop, $def);
		
		if ( $val !== $def ) {
			$val = ( strtolower($val) == "false" ) ? false : true;
		}
		
		return $val;
	}

	/**
	 * Gets the application name, used for logging
	 * 
	 * @return the name of the current application
	 */
	function getApplicationName()
	{
		if ( $this->ApplicationName === null ) {
			$this->ApplicationName = $this->getESAPIStringProperty("Logger/ApplicationName", 'DefaultName');
		}
		
		return $this->ApplicationName;
	}

	/**
	 * Gets the length of the time to live window for remember me tokens (in milliseconds).
	 * 
	 * @return The time to live length for generated remember me tokens.
	 */
	function getRememberTokenDuration()
	{
		if ( $this->RememberTokenDuration === null ) {
			$this->RememberTokenDuration = $this->getESAPIIntProperty("Authenticator/RememberTokenDuration", 14);
		}
		
		return $this->RememberTokenDuration * 1000 * 60 * 60 * 24;
	}

	/**
	 * Gets the number of login attempts allowed before the user's account is locked. If this 
	 * many failures are detected within the alloted time period, the user's account will be locked.
	 * 
	 * @return the number of failed login attempts that cause an account to be locked
	 */
	function getAllowedLoginAttempts()
	{
		if ( $this->AllowedLoginAttempts === null )	{
			$this->AllowedLoginAttempts = $this->getESAPIIntProperty("Authenticator/AllowedLoginAttempts", 5);
		}
		
		return $this->AllowedLoginAttempts;
	}

	/**
	 * Gets the maximum number of old password hashes that should be retained. These hashes can 
	 * be used to ensure that the user doesn't reuse the specified number of previous passwords
	 * when they change their password.
	 * 
	 * @return the number of old hashed passwords to retain
	 */
	function getMaxOldPasswordHashes()
	{
		if ( $this->MaxOldPasswordHashes === null )	{
			$this->MaxOldPasswordHashes = $this->getESAPIIntProperty("Authenticator/MaxOldPasswordHashes", 12);
		}
		
		return $this->MaxOldPasswordHashes;
	}

	/**
	 * Gets the name of the password parameter used during user Authenticator.
	 * 
	 * @return the name of the password parameter
	 */
	function getPasswordParameterName()
	{
		if ( $this->PasswordParameterName === null )	{
			$this->PasswordParameterName = $this->getESAPIStringProperty("Authenticator/PasswordParameterName", 'password');
		}
		
		return $this->PasswordParameterName;
	}

	/**
	 * Gets the name of the username parameter used during user Authenticator.
	 * 
	 * @return the name of the username parameter
	 */
	function getUsernameParameterName()
	{
		if ( $this->UsernameParameterName === null )	{
			$this->UsernameParameterName = $this->getESAPIStringProperty("Authenticator/UsernameParameterName", 'username');
		}
		
		return $this->UsernameParameterName;
	}

	/**
	 * Gets the idle timeout length for sessions (in milliseconds). This is the amount of time that a session
	 * can live before it expires due to lack of activity. Applications or frameworks could provide a reauthenticate
	 * function that enables a session to continue after reauthentication.
	 * 
	 * @return The session idle timeout length.
	 */
	function getSessionIdleTimeoutLength()
	{
		if ( $this->IdleTimeoutDuration === null )	{
			$this->IdleTimeoutDuration = $this->getESAPIIntProperty("Authenticator/IdleTimeoutDuration", 20);
		}
		
		return $this->IdleTimeoutDuration * 1000 * 60;
	}

	/**
	 * Gets the absolute timeout length for sessions (in milliseconds). This is the amount of time that a session
	 * can live before it expires regardless of the amount of user activity. Applications or frameworks could 
	 * provide a reauthenticate function that enables a session to continue after reauthentication.
	 * 
	 * @return The session absolute timeout length.
	 */
	function getSessionAbsoluteTimeoutLength()
	{
		if ( $this->AbsoluteTimeoutDuration === null )	{
			$this->AbsoluteTimeoutDuration = $this->getESAPIIntProperty("Authenticator/AbsoluteTimeoutDuration", 20);
		}
		
		return $this->AbsoluteTimeoutDuration * 1000 * 60;
	}
	
	/**
	 * Gets the master password. This password can be used to encrypt/decrypt other files or types
	 * of data that need to be protected by your application.
	 * 
	 * @return the current master password
	 */
	function getMasterKey()
	{
		if ( $this->MasterKey === null )	{
			$this->MasterKey = $this->getESAPIEncodedStringProperty("Encryptor/secrets/MasterKey", null);
		}
		
		return $this->MasterKey;
	}

	/**
	 * Gets the master salt that is used to salt stored password hashes and any other location 
	 * where a salt is needed.
	 * 
	 * @return the current master salt
	 */
	function getMasterSalt()
	{
		if ( $this->MasterSalt === null )	{
			$this->MasterSalt = $this->getESAPIEncodedStringProperty("Encryptor/secrets/MasterSalt", null);
		}
		
		return $this->MasterSalt;
	}

	/**
	 * Gets the allowed file extensions for files that are uploaded to this application.
	 * 
	 * @return a list of the current allowed file extensions
	 */
	function getAllowedFileExtensions()
	{
		if ( $this->AllowedFileExtensions === null )	{
			$this->AllowedFileExtensions = $this->getESAPIArrayProperty("HttpUtilities/ApprovedUploadExtensions/extension", null);
		}
		
		return $this->AllowedFileExtensions;
	}

	/**
	 * Gets the maximum allowed file upload size.
	 * 
	 * @return the current allowed file upload size
	 */
	function getAllowedFileUploadSize()
	{
		if ( $this->maxUploadSize === null )	{
			$this->maxUploadSize = $this->getESAPIIntProperty("HttpUtilities/maxUploadFileBytes", 20);
		}
		
		return $this->maxUploadSize;
	}

	/**
	 * Gets the encryption algorithm used by ESAPI to protect data.
	 * 
	 * @return the current encryption algorithm
	 */
	function getEncryptionAlgorithm()
	{
		if ( $this->EncryptionAlgorithm === null )	{
			$this->EncryptionAlgorithm = $this->getESAPIStringProperty("Encryptor/EncryptionAlgorithm", 'AES');
		}
		
		return $this->EncryptionAlgorithm;
	}

	/**
	 * Gets the hashing algorithm used by ESAPI to hash data.
	 * 
	 * @return the current hashing algorithm
	 */
	function getHashAlgorithm()
	{
		if ( $this->HashAlgorithm === null )	{
			$this->HashAlgorithm = $this->getESAPIStringProperty("Encryptor/HashAlgorithm", 'SHA-512');
		}
		
		return $this->HashAlgorithm;
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
	function getCharacterEncoding()
	{
		if ( $this->CharacterEncoding === null )	{
			$this->CharacterEncoding = $this->getESAPIStringProperty("Encryptor/CharacterEncoding", 'UTF-8');
		}
		
		return $this->CharacterEncoding;
	}

	/**
	 * Gets the digital signature algorithm used by ESAPI to generate and verify signatures.
	 * 
	 * @return the current digital signature algorithm
	 */
	function getDigitalSignatureAlgorithm()
	{
		if ( $this->DigitalSignatureAlgorithm === null )	{
			$this->DigitalSignatureAlgorithm = $this->getESAPIStringProperty("Encryptor/DigitalSignatureAlgorithm", 'DSA');
		}
		
		return $this->DigitalSignatureAlgorithm;
	}

	/**
	 * Gets the random number generation algorithm used to generate random numbers where needed.
	 * 
	 * @return the current random number generation algorithm
	 */
	function getRandomAlgorithm()
	{
		if ( $this->RandomAlgorithm === null )	{
			$this->RandomAlgorithm = $this->getESAPIStringProperty("Encryptor/RandomAlgorithm", 'SHA1PRNG');
		}
		
		return $this->RandomAlgorithm;
	}

	/**
	 * Gets the intrusion detection quota for the specified event.
	 * 
	 * @param eventName the name of the event whose quota is desired
	 * 
	 * @return the Quota that has been configured for the specified type of event
	 */
	function getQuota($eventName)
	{
		throw new EnterpriseSecurityException("Method Not implemented");
	}

	/**
	 * Gets the name of the ESAPI resource directory as a String.
	 * 
	 * @return The ESAPI resource directory.
	 */
	function getResourceDirectory()
	{
		return $this->resourceDir;
	}

	/**
	 * Sets the ESAPI resource directory.
	 * 
	 * @param dir The location of the resource directory.
	 */
	function setResourceDirectory($dir)
	{
		$this->resourceDir = $dir;
	}

	/**
	 * Gets the content type for responses used when setSafeContentType() is called.
	 * <br><br>
	 * Note: This does not get the configured character encoding scheme. That is accessed by calling 
	 * getCharacterEncoding().
	 * 
	 * @return The current content-type set for responses.
	 */
	function getResponseContentType()
	{
		if ( $this->ResponseContentType === null )	{
			$this->ResponseContentType = $this->getESAPIStringProperty("HttpUtilities/ResponseContentType", 'UTF-8');
		}
		
		return $this->ResponseContentType;
	}




	/**
	 * Returns whether HTML entity encoding should be applied to log entries.
	 * 
	 * @return True if log entries are to be HTML Entity encoded. False otherwise.
	 */
	function getLogEncodingRequired()
	{
		if ( $this->LogEncodingRequired === null )	{
			$this->LogEncodingRequired = $this->getESAPIBooleanProperty("Logger/LogEncodingRequired", false);
		}
		
		return $this->LogEncodingRequired;
	}

	/**
	 * Get the log level specified in the ESAPI configuration properties file. Return a default 
	 * value if it is not specified in the properties file.
	 * 
	 * @return the logging level defined in the properties file. If none is specified, the default 
	 * of Logger.WARNING is returned.
	 */
	function getLogLevel()
	{
		if ( $this->LogLevel === null )	{
			$this->LogLevel = $this->getESAPIStringProperty("Logger/LogLevel", 'WARNING');
		}
		
		return $this->LogLevel;
	}

	/**
	 * Get the name of the log file specified in the ESAPI configuration properties file. Return a default value 
	 * if it is not specified.
	 * 
	 * @return the log file name defined in the properties file.
	 */
	function getLogFileName()
	{
		if ( $this->LogFileName === null )	{
			$this->LogFileName = $this->getESAPIStringProperty("Logger/LogFileName", 'ESAPI_logging_file');
		}
		
		return $this->LogFileName;
	}

	/**
	 * Get the maximum size of a single log file from the ESAPI configuration properties file. Return a default value 
	 * if it is not specified. Once the log hits this file size, it will roll over into a new log.
	 * 
	 * @return the maximum size of a single log file (in bytes).
	 */
	function getMaxLogFileSize()
	{
		if ( $this->MaxLogFileSize === null )	{
			$this->MaxLogFileSize = $this->getESAPIIntProperty("Logger/MaxLogFileSize", 10000000);
		}
		
		return $this->MaxLogFileSize;
	}

}
?>