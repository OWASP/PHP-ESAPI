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
 * @author Andrew van der Stock (vanderaj @ owasp.org), Arnaud Labenne - dotsafe.fr
 * @created 2009
 * @since 1.6
 */

require_once dirname(__FILE__).'/../../src/ESAPI.php';
require_once dirname(__FILE__).'/../../src/reference/DefaultEncryptedProperties.php';

class EncryptedPropertiesTest extends UnitTestCase
{
    function setUp()
    {
        global $ESAPI;

        if (!isset($ESAPI)) {
            $ESAPI = new ESAPI(dirname(__FILE__).'/../testresources/ESAPI.xml');
        }
    }

    function tearDown()
    {

    }

    function testGetProperty()
    {
        $instance = new DefaultEncryptedProperties();

        $key = 'test';
        $value = 'esapi';

        $instance->setProperty($key, $value);

        $this->assertEqual(
            $instance->getProperty($key),
            $value
        );

    }

	/**
     * Test if getProperty method throw an exception
     */
    function testGetPropertyUnknownKey()
    {
        $instance = new DefaultEncryptedProperties();

        $key = 'test';

        try {
            $instance->getProperty($key);

        } catch (EnterpriseSecurityException $e) {
            $this->assertTrue(true);
            return;
        }

        $this->fail();
    }

    function testSetProperty()
    {
        $instance = new DefaultEncryptedProperties();

        $key = 'test';
        $value = 'esapi';

        $instance->setProperty($key, $value);

        $this->assertEqual(
            $instance->getProperty($key),
            $value
        );
    }

    function testKeySet()
    {
        $instance = new DefaultEncryptedProperties();

        $key = 'test';
        $value = 'esapi';

        $cryptedValue = ESAPI::getEncryptor()->getCrypto(
            Encryptor::ESAPI_CRYPTO_OP_ENCRYPT_ASCII_HEX,
            $value
        );

        $instance->setProperty($key, $value);

        $keySet = $instance->keySet();

        $this->assertEqual(
            $cryptedValue,
            $keySet[$key]
        );
    }

    function testLoad()
    {
        $config = ESAPI::getSecurityConfiguration();
        
        $config->setResourceDirectory(
            realpath(
                dirname(__FILE__)
                .'/../testresources/'
            )
        );

        $file = $config->getResourceDirectory() . "/test.properties";

        $instance = new DefaultEncryptedProperties();

        $key = 'test';
        $value = 'esapi';

        $instance->setProperty($key, $value);

        $instance->store($file, "test");

        $instance2 = new DefaultEncryptedProperties();

        $instance2->load($file);

        $this->assertEqual(
            $value,
            $instance2->getProperty($key)
        );
    }

    /**
     * Test if load method throw an exception
     */    
    function testLoadException()
    {
        $config = ESAPI::getSecurityConfiguration();
        
        $config->setResourceDirectory(
            realpath(
                dirname(__FILE__)
                .'/../testresources/'
            )
        );

        $file = $config->getResourceDirectory() . "/test.filedoesnotexist";

        $instance = new DefaultEncryptedProperties();

        try {
            $instance->load($file);
        } catch (EnterpriseSecurityException $e) {
            $this->assertTrue(true);
            return;
        }

        $this->fail();
    }
    
    function testStore()
    {
        $config = ESAPI::getSecurityConfiguration();

        $config->setResourceDirectory(
            realpath(
                dirname(__FILE__)
                .'/../testresources/'
            )
        );

        $file = $config->getResourceDirectory() . "/test.properties";

        $instance = new DefaultEncryptedProperties();

        $key = 'test';
        $value = 'esapi';

        $instance->setProperty($key, $value);

        $instance->store($file, "test");

        $instance2 = new DefaultEncryptedProperties();

        $instance2->load($file);

        $this->assertEqual(
            $value,
            $instance2->getProperty($key)
        );
    }

    /**
     * Test if store method throw an exception
     */
    function testStoreException()
    {
        $config = ESAPI::getSecurityConfiguration();

        $config->setResourceDirectory(
            realpath(
                dirname(__FILE__)
                .'/../testresources/'
            )
        );

        $file = $config->getResourceDirectory() .
            "/test.directorydoesnotexist/test.properties";

        $instance = new DefaultEncryptedProperties();

        $key = 'test';
        $value = 'esapi';

        $instance->setProperty($key, $value);

        try {
            $instance->store($file, "test");
        } catch (EnterpriseSecurityException $e) {
            $this->assertTrue(true);
            return;
        }

        $this->fail();
    }
}
?>