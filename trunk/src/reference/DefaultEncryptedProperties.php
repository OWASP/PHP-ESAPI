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
 * @author	Andrew van der Stock, Arnaud Labenne - dotsafe.fr
 * @created	2009
 * @since	1.6
 * @package	org.owasp.esapi.reference
 */

require_once dirname(__FILE__).'/../EncryptedProperties.php';

class DefaultEncryptedProperties implements EncryptedProperties
{

    private $_properties;

    function __construct()
    {
        $this->_properties = array();
    }

    /**
     * Gets the property value from the encrypted store, 
     * decrypts it, and returns the plaintext value to the caller.
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
        if (array_key_exists($key, $this->_properties)) {
            try {
                return ESAPI::getEncryptor()->getCrypto(
                    Encryptor::ESAPI_CRYPTO_OP_DECRYPT_ASCII_HEX,
                    $this->_properties[$key]
                );

            } catch (Exception $e) {
                throw new EncryptionException("Property retrieval failure", 
                    "Couldn't decrypt property");
            }

        } else {
            throw new EncryptionException("Property retrieval failure", 
                "Couldn't find key");
        }
    }

    /**
     * Encrypts the plaintext property value and stores the ciphertext 
     * value in the encrypted store.
     *
     * @param 	key
     *      	the name of the property to set
     * @param 	value
     * 			the value of the property to set
     *
     * @return
     * 		the encrypted property value
     *
     * @throws EncryptionException
     *      if the property could not be encrypted
     */
    function setProperty($key, $value)
    {
        try {
            $crypted = ESAPI::getEncryptor()->getCrypto(
                Encryptor::ESAPI_CRYPTO_OP_ENCRYPT_ASCII_HEX,
                $value
            );
            $this->_properties[$key] = $crypted;

        } catch (Exception $e) {
            throw new EncryptionException("Property setting failure", 
                "Couldn't encrypt property");
        }
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
        return $this->_properties;
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
        $data = @file_get_contents($in);
        $unserialized = unserialize($data);
         
        if (!$data) {
            throw new EncryptionException("Properties loading failure", 
                "Couldn't open properties file");
        }

        if (!$unserialized) {
            throw new EncryptionException("Properties loading failure", 
                "Couldn't unserialize properties");
        }

        $this->_properties = $unserialized;
    }

    /**
     * Writes this property list (key and element pairs) in this Properties table to
     * the output stream in a format suitable for loading into a Properties table 
     * using the load method.
     *
     * @param out
     * 		the output stream that contains the properties file
     * @param comments
     *            a description of the property list 
     *            (ex. "Encrypted Properties File").
     *
     * @throws IOException
     *             Signals that an I/O exception has occurred.
     */
    function store($out, $comments)
    {
        $data = serialize($this->_properties);

        if (@file_put_contents($out, $data) == false) {
            throw new EncryptionException("Properties storing failure", 
                "Couldn't store properties");
        }
    }
}
?>