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
 * The ESAPI is published by OWASP under the BSD license. You should read 
 * and accept the LICENSE before you use, modify, and/or redistribute this software.
 * 
 * @author Arnaud Labenne - dotsafe.fr
 * @created 2009
 */

require_once dirname(__FILE__).'/../../src/ESAPI.php';

class EncryptorTest extends UnitTestCase
{
    function setUp()
    {
        global $ESAPI;

        if ( !isset($ESAPI)) {
            $ESAPI = new ESAPI();
        }
        
        $this->encryptor = $ESAPI->getEncryptor();
    }
    
    function tearDown()
    {
        
    }
    
    function testHashSHA1()
    {
        $this->assertEqual(
            "MmYzM2M2NGI3MGM5YmZjYjhjOGI2NTk4ZDNkODRlMDg5OWVmMDQ4Ng==", 
            ESAPI::getEncryptor()->getCrypto(
                Encryptor::ESAPI_CRYPTO_OP_HASH_ASCII_HEX, 
                "OWASP ESAPI for PHP", 
                "Salt is my friend !"
            )
        );
    }
    
    function testHashNull()
    {
        $this->assertEqual(
            null, 
            ESAPI::getEncryptor()->getCrypto(
                Encryptor::ESAPI_CRYPTO_OP_HASH_ASCII_HEX, 
                null                
            )
        );
    }
    
    function testHashEmpty()
    {
        $this->assertEqual(
            null, 
            ESAPI::getEncryptor()->getCrypto(
                Encryptor::ESAPI_CRYPTO_OP_HASH_ASCII_HEX, 
                "", 
                "Salt is my friend !"
            )
        );
    }
    
    function testHashNoSaltSHA1()
    {
        $this->assertEqual(
            "MzcwZmUxMWExM2ZlNTkwOGYwMjlkNjljMjhhMDY1NzIyZTVlOTI4MA==", 
            ESAPI::getEncryptor()->getCrypto(
                Encryptor::ESAPI_CRYPTO_OP_HASH_ASCII_HEX, 
                "OWASP ESAPI for PHP"
            )
        );
    }
    
    function testEncryptAES256ECB()
    {
        $this->assertEqual(
            "XglezBjL4kLRg9xKKIUC6C0nMucKj3LFOyQbnaQr1FA=", 
            ESAPI::getEncryptor()->getCrypto(
                Encryptor::ESAPI_CRYPTO_OP_ENCRYPT_ASCII_HEX, 
                "OWASP ESAPI for PHP"
            )
        );
    }
    
    function testEncryptNull()
    {
        $this->assertEqual(
            null, 
            ESAPI::getEncryptor()->getCrypto(
                Encryptor::ESAPI_CRYPTO_OP_ENCRYPT_ASCII_HEX, 
                null
            )
        );
    }
    
    function testEncryptEmpty()
    {
        $this->assertEqual(
            null, 
            ESAPI::getEncryptor()->getCrypto(
                Encryptor::ESAPI_CRYPTO_OP_ENCRYPT_ASCII_HEX, 
                ""
            )
        );
    }
    
    function testDecryptNull()
    {
        $this->assertEqual(
            null, 
            ESAPI::getEncryptor()->getCrypto(
                Encryptor::ESAPI_CRYPTO_OP_DECRYPT_ASCII_HEX, 
                null
            )
        );
    }
    
    function testDecryptEmpty()
    {
        $this->assertEqual(
            null, 
            ESAPI::getEncryptor()->getCrypto(
                Encryptor::ESAPI_CRYPTO_OP_DECRYPT_ASCII_HEX, 
                ""
            )
        );
    }
    
    function testDecryptAES256ECB()
    {
        $this->assertEqual(
            "OWASP ESAPI for PHP", 
            ESAPI::getEncryptor()->getCrypto(
                Encryptor::ESAPI_CRYPTO_OP_DECRYPT_ASCII_HEX, 
                "XglezBjL4kLRg9xKKIUC6C0nMucKj3LFOyQbnaQr1FA="
            )
        );
    }

    function testEncryptDecrypt()
    {
        $secret = "OWASP ESAPI for PHP";

        $encryptedValue = ESAPI::getEncryptor()->getCrypto(
            Encryptor::ESAPI_CRYPTO_OP_ENCRYPT_ASCII_HEX, 
            $secret
        );

        $decryptedValue = ESAPI::getEncryptor()->getCrypto(
            Encryptor::ESAPI_CRYPTO_OP_DECRYPT_ASCII_HEX, 
            $encryptedValue
        );

        $this->assertEqual($secret, $decryptedValue);        
    }
}
?>