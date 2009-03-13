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

require_once dirname(__FILE__).'/../EncryptedProperties.php';

class DefaultEncryptedProperties implements EncryptedProperties {
	
		/**
	 * Gets the property value from the encrypted store, decrypts it, and returns the plaintext value to the caller.
	 * 
	 * @param key
	 *      the name of the property to get 
	 * 
	 * @return 
	 * 		the decrypted property value
	 * 
	 * @throws EncryptionException
	 *      if the property could not be decrypted
	 */
	function getProperty($key)
	{
		throw new EnterpriseSecurityException("Method Not implemented");	
	}

	/**
	 * Encrypts the plaintext property value and stores the ciphertext value in the encrypted store.
	 * 
	 * @param key
	 *      the name of the property to set
	 * @param value
	 * 		the value of the property to set
	 * 
	 * @return 
	 * 		the encrypted property value
	 * 
	 * @throws EncryptionException
	 *      if the property could not be encrypted
	 */
	function setProperty($key, $value)
	{
		throw new EnterpriseSecurityException("Method Not implemented");	
	}
	
	/**
	 * Returns a Set view of properties. The Set is backed by a Hashtable, so changes to the 
	 * Hashtable are reflected in the Set, and vice-versa. The Set supports element 
	 * removal (which removes the corresponding entry from the Hashtable), but not element addition.
	 * 
	 * @return 
	 * 		a set view of the properties contained in this map.
	 */
	function keySet()
	{
		throw new EnterpriseSecurityException("Method Not implemented");	
	}
		
	/**
	 * Reads a property list (key and element pairs) from the input stream.
	 * 
	 * @param in
	 * 		the input stream that contains the properties file
	 * 
	 * @throws IOException
	 *      Signals that an I/O exception has occurred.
	 */
	function load($in)
	{
		throw new EnterpriseSecurityException("Method Not implemented");	
	}
	
	/**
	 * Writes this property list (key and element pairs) in this Properties table to 
	 * the output stream in a format suitable for loading into a Properties table using the load method. 
	 * 
	 * @param out
	 * 		the output stream that contains the properties file
	 * @param comments
	 *            a description of the property list (ex. "Encrypted Properties File").
	 * 
	 * @throws IOException
	 *             Signals that an I/O exception has occurred.
	 */
	function store($out, $comments)
	{
		throw new EnterpriseSecurityException("Method Not implemented");	
	}
}
?>