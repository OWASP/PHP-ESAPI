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

require_once dirname(__FILE__).'/../Encryptor.php';

class DefaultEncryptor implements Encryptor {
	
	/**
	 * Returns a string representation of the hash of the provided plaintext and
	 * salt. The salt helps to protect against a rainbow table attack by mixing
	 * in some extra data with the plaintext. Some good choices for a salt might
	 * be an account name or some other string that is known to the application
	 * but not to an attacker. 
	 * See <a href="http://www.matasano.com/log/958/enough-with-the-rainbow-tables-what-you-need-to-know-about-secure-password-schemes/">
	 * this article</a> for more information about hashing as it pertains to password schemes.
	 * 
	 * @param plaintext
	 * 		the plaintext String to encrypt
	 * @param salt
	 *      the salt to add to the plaintext String before hashing
	 * 
	 * @return 
	 * 		the encrypted hash of 'plaintext' stored as a String
	 * 
	 * @throws EncryptionException
	 *      if the specified hash algorithm could not be found or another problem exists with 
	 *      the hashing of 'plaintext'
	 */
	function hash($plaintext, $salt) 	{ 		throw new EnterpriseSecurityException("Method Not implemented");	 	}

	/**
	 * Encrypts the provided plaintext and returns a ciphertext string.
	 * 
	 * @param plaintext
	 *      the plaintext String to encrypt
	 * 
	 * @return 
	 * 		the encrypted String representation of 'plaintext'
	 * 
	 * @throws EncryptionException
	 *      if the specified encryption algorithm could not be found or another problem exists with 
	 *      the encryption of 'plaintext'
	 */
	function encrypt($plaintext) 	{ 		throw new EnterpriseSecurityException("Method Not implemented");	 	}

	/**
	 * Decrypts the provided ciphertext string (encrypted with the encrypt
	 * method) and returns a plaintext string.
	 * 
	 * @param ciphertext
	 *      the ciphertext (encrypted plaintext)
	 * 
	 * @return 
	 * 		the decrypted ciphertext
	 * 
	 * @throws EncryptionException
	 *      if the specified encryption algorithm could not be found or another problem exists with 
	 *      the encryption of 'plaintext'
	 */
	function decrypt($ciphertext) 	{ 		throw new EnterpriseSecurityException("Method Not implemented");	 	}

	/**
	 * Create a digital signature for the provided data and return it in a
	 * string.
	 * 
	 * @param data
	 *      the data to sign
	 * 
	 * @return 
	 * 		the digital signature stored as a String
	 * 
	 * @throws EncryptionException
	 * 		if the specified signature algorithm cannot be found
	 */
	function sign($data) 	{ 		throw new EnterpriseSecurityException("Method Not implemented");	 	}

	/**
	 * Verifies a digital signature (created with the sign method) and returns
	 * the boolean result.
	 * 
	 * @param signature
	 *      the signature to verify against 'data'
	 * @param data
	 *      the data to verify against 'signature'
	 * 
	 * @return 
	 * 		true, if the signature is verified, false otherwise
	 * 
	 */
	function verifySignature($signature, $data) 	{ 		throw new EnterpriseSecurityException("Method Not implemented");	 	}

	/**
	 * Creates a seal that binds a set of data and includes an expiration timestamp.
	 * 
	 * @param data
	 *      the data to seal
	 * @param timestamp
	 *      the absolute expiration date of the data, expressed as seconds since the epoch
	 * 
	 * @return 
	 * 		the seal
	 * 
	 */
	function seal($data, $timestamp) 	{ 		throw new EnterpriseSecurityException("Method Not implemented");	 	}

	/**
	 * Unseals data (created with the seal method) and throws an exception
	 * describing any of the various problems that could exist with a seal, such
	 * as an invalid seal format, expired timestamp, or decryption error.
	 * 
	 * @param seal
	 *      the sealed data
	 * 
	 * @return 
	 * 		the original (unsealed) data
	 * 
	 * @throws EncryptionException 
	 * 		if the unsealed data cannot be retrieved for any reason
	 */
	function unseal($seal) 	{ 		throw new EnterpriseSecurityException("Method Not implemented");	 	}
	
	/**
	 * Verifies a seal (created with the seal method) and throws an exception
	 * describing any of the various problems that could exist with a seal, such
	 * as an invalid seal format, expired timestamp, or data mismatch.
	 * 
	 * @param seal
	 *      the seal to verify
	 * 
	 * @return 
	 * 		true, if the seal is valid.  False otherwise
	 */
	function verifySeal($seal) 	{ 		throw new EnterpriseSecurityException("Method Not implemented");	 	}
	
	/**
	 * Gets an absolute timestamp representing an offset from the current time to be used by
	 * other functions in the library.
	 * 
	 * @param offset 
	 * 		the offset to add to the current time
	 * 
	 * @return 
	 * 		the absolute timestamp
	 */
	function getRelativeTimeStamp($offset) 	{ 		throw new EnterpriseSecurityException("Method Not implemented");	 	}
	
	
	/**
	 * Gets a timestamp representing the current date and time to be used by
	 * other functions in the library.
	 * 
	 * @return 
	 * 		a timestamp representing the current time
	 */
	function getTimeStamp() 	{ 		throw new EnterpriseSecurityException("Method Not implemented");	 	}
	
	
}
?>