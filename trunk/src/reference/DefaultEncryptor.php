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
 * @author  Arnaud Labenne - dotsafe.fr
 * @created 2009
 * @since   1.4
 * @package org.owasp.esapi.reference
 */

require_once dirname(__FILE__).'/../Encryptor.php';

/**
 * The DefaultEncryptor class provides a set of methods for performing common
 * encryption, random number, and hashing operations.
 * 
 * @author  Arnaud Labenne - dotsafe.fr
 * @created 2010
 *
 */
class DefaultEncryptor implements Encryptor
{
    private $_hashAlgorithm;
    private $_masterSalt;
    private $_encryptionAlgorithm;
    private $_masterKey;
    private $_encryptionIV;

    public function __construct()
    {
        $this->_hashAlgorithm = ESAPI::getSecurityConfiguration()->getHashAlgorithm();
        $this->_masterSalt = ESAPI::getSecurityConfiguration()->getMasterSalt();
        $this->_encryptionAlgorithm = ESAPI::getSecurityConfiguration()->getEncryptionAlgorithm();
        $this->_masterKey = ESAPI::getSecurityConfiguration()->getMasterKey();
        
        //$this->_encryptionIV = ESAPI::getSecurityConfiguration()->getEncryptionIV();
        //For the moment, let's take a constant value
        $this->_encryptionIV = "912320c2b650cc51d5ef3d7f6ba91b91";
    }

    /**
     * Returns a string representation of the hash of the provided plaintext and
     * salt. The salt helps to protect against a rainbow table attack by mixing
     * in some extra data with the plaintext. Some good choices for a salt might
     * be an account name or some other string that is known to the application
     * but not to an attacker.
     * See <a href="http://www.matasano.com/log/958/enough-with-the-rainbow-tables-what-you-need-to-know-about-secure-password-schemes/">
     * this article</a> for more information about hashing as it pertains to password schemes.
     *
     * @param string $plaintext
     *      the plaintext String to encrypt
     * @param string $salt
     *      the salt to add to the plaintext String before hashing
     *
     * @return
     * 		the encrypted hash of 'plaintext' stored as a String
     *
     * @throws EncryptionException
     *      if the specified hash algorithm could not be found or another problem exists with
     *      the hashing of 'plaintext'
     */
    private function _hash($plaintext, $salt)
    {
        if ($this->_hashAlgorithm != self::ESAPI_CRYPTO_MODE_SHA1) {
            throw new EncryptionException("Encryption failure", "Can't find hash algorithm.");
        }
        
        //Hash with MasterSalt, Salt and plaintext
        $hash = sha1($this->_masterSalt);   
        $hash = sha1($hash . $salt);        
        $hash = sha1($hash . $plaintext);     
           
        // rehash a number of times to help strengthen weak passwords
        for ($i = 0; $i < 1024; $i++) {
            $hash = sha1($hash);
        }
        
        //Encode the hash in base64
        return ESAPI::getEncoder()->encodeForBase64($hash);        
    }
    
    /**
     * Returns the good configuration before calling MCrypt
     * 
     * @throws EncryptionException
     * 		if the mode or the algorithm do not exist in mcrypt
     * 
     * @return array 
     * 		the mcrypt configuration
     */
    private function _getMcryptConfiguration()
    {
        //Extract the mode and the algorithm informations
        $modeAndAlgorithm = str_replace('ESAPI_CRYPTO_MODE_', '', $this->_encryptionAlgorithm);
        
        $modeAndAlgorithm = explode('_', $modeAndAlgorithm);
        
        if (count($modeAndAlgorithm) != 2) {
            throw new EncryptionException("Encryption failure", "Can't determine the mode or the algorithm.");
        }
        
        //Extract algorithm
        $algorithm = strtolower($modeAndAlgorithm[0]);
        
        //Extract mode
        $mode = strtolower($modeAndAlgorithm[1]);
          
        //Check that informations and modes exist in Mcrypt
        $mcryptAlgorithms = mcrypt_list_algorithms();
        $mcryptModes = mcrypt_list_modes();
        
        if (!in_array($mode, $mcryptModes)) {
            throw new EncryptionException("Encryption failure", "The specified mode does not exist in Encryption.");
        }
        
        if (!in_array($algorithm, $mcryptAlgorithms)) {
            throw new EncryptionException("Encryption failure", "The specified algorithm does not exist in Encryption.");
        }
        
        return array(
            "mode" => $mode,
            "algorithm" => $algorithm,
        );
    }
    
    /**
     * 	Encrypt data using MCrypt
     * 
     * @param string $data 
     * 		the plaintext to encrypt
     * 
     * @return string 
     * 		the encrypted String representation of 'plaintext'
     * 
     * @throws EncryptionException
     *      if the specified algorithm could not be found or another problem occurs
     */
    private function _encrypt($data)
    {
        $mcryptConfiguration = $this->_getMcryptConfiguration();
        
        $td = mcrypt_module_open($mcryptConfiguration['algorithm'], '', $mcryptConfiguration['mode'], '');

        if (mcrypt_generic_init($td, $this->_masterKey, $this->_encryptionIV) == -1) {
            throw new EncryptionException("Encryption failure", "Unable to encrypt.");
        }
        
        $encryptedData = mcrypt_generic($td, $data);
        
        mcrypt_generic_deinit($td);
        mcrypt_module_close($td);
        
        //Return base64 encoded 
        $encryptedData = ESAPI::getEncoder()->encodeForBase64($encryptedData);         
        return $encryptedData;
    }
    
    /**
     * 	Decrypt data using MCrypt
     * 
     * @param string $data 
     * 		the plaintext to decrypt
     * 
     * @return string 
     * 		the decrypted String representation of 'plaintext'
     * 
     * @throws EncryptionException
     *      if the specified algorithm could not be found or another problem occurs
     */
    private function _decrypt($data)
    {
        //Prepare data
        $data = ESAPI::getEncoder()->decodeFromBase64($data);
        
        $mcryptConfiguration = $this->_getMcryptConfiguration();        
        $td = mcrypt_module_open($mcryptConfiguration['algorithm'], '', $mcryptConfiguration['mode'], '');
             
        if (mcrypt_generic_init($td, $this->_masterKey, $this->_encryptionIV) == -1) {
            throw new EncryptionException("Encryption failure", "Unable to decrypt.");
        }
        
        //Encrypt data        
        $decryptedData = mdecrypt_generic($td, $data);
        
        //Clean up
        mcrypt_generic_deinit($td);
        mcrypt_module_close($td);
        
        //The data is padded with "\0", so we have to clean
        $decryptedData = rtrim($decryptedData, "\0");
        
        return $decryptedData;
    }

    /**
     * {@inheritDoc}
     */
    public function getCrypto($operation, $data, $salt = null)
    {
        if (empty($data)) {
            return null;
        }
        
        switch ($operation) { 
        case self::ESAPI_CRYPTO_OP_HASH_ASCII_HEX:
            return $this->_hash($data, $salt);                
            break;
                
        case self::ESAPI_CRYPTO_OP_ENCRYPT_ASCII_HEX:
            return $this->_encrypt($data);
            break;
                
        case self::ESAPI_CRYPTO_OP_DECRYPT_ASCII_HEX:
            return $this->_decrypt($data);
            break;
                
        default:
            throw new EncryptionException("Encryption failure", "This operation does not exist in Encryptor.");
        }
    }

    /**
     * {@inheritDoc}
     */
    function getRelativeTimeStamp($offset)
    {
        return time() + intval($offset);
    }

    /**
     * {@inheritDoc}
     */
    public function getTimeStamp()
    {
        return time();
    }


}
?>