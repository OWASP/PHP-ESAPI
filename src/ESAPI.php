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
 * @author Andrew van der Stock < vanderaj .(at). owasp.org > 
 * @created 2008
 * @since 1.4
 * @package org.owasp.esapi
 */

/**
 * ESAPI locator class is provided to make it easy to gain access to the current ESAPI classes in use.
 * Use the set methods to override the reference implementations with instances of any custom ESAPI implementations.
 *
 * @author 
 * @since 1.4
 */
class ESAPI
{
    private static $accessController = null;
    private static $authenticator = null;
    private static $encoder = null;
    private static $encryptor = null;
    private static $executor = null;
    private static $httpUtilities = null;
    private static $intrusionDetector = null;
    private static $defaultLogger = null;
    private static $randomizer = null;
    private static $securityConfiguration = null;
    private static $validator = null;

    /**
     * prevent instantiation of this class
     */
    public function __construct()
    {
    	self::getLogger("ESAPI Startup");
    	self::getIntrusionDetector();
    }

    /**
     * Get the current HTTP Servlet Request being processed.
     * @return the current HTTP Servlet Request.
     */
    public static function currentRequest()
    {
        return self::getHttpUtilities()->getCurrentRequest();
    }

    /**
     * Get the current HTTP Servlet Response being generated.
     * @return the current HTTP Servlet Response.
     */
    public static function currentResponse()
    {
        return self::getHttpUtilities()->getCurrentResponse();
    }

    /**
     * @return the current ESAPI AccessController object being used to maintain the access control rules for this application. 
     */
    public static function getAccessController()
    {
        if ( is_null( self::$accessController) )
        {
            require_once dirname(__FILE__).'/reference/FileBasedAccessController.php';
            self::$accessController = new FileBasedAccessController();
        }
            
        return self::$accessController;
    }

    /**
     * Change the current ESAPI AccessController to the AccessController provided. 
     * @param accessController
     *            the AccessController to set to be the current ESAPI AccessController. 
     */
    public static function setAccessController($accessController)
    {
        self::$accessController = $accessController;
    }

    /**
     * @return the current ESAPI Authenticator object being used to authenticate users for this application. 
     */
    public static function getAuthenticator()
    {
        if ( is_null(self::$authenticator) )
        {
                require_once dirname(__FILE__).'/reference/FileBasedAuthenticator.php';
        	self::$authenticator = new FileBasedAuthenticator();
        }
            
        return self::$authenticator;
    }

    /**
     * Change the current ESAPI Authenticator to the Authenticator provided. 
     * @param authenticator
     *            the Authenticator to set to be the current ESAPI Authenticator. 
     */
    public static function setAuthenticator($authenticator)
    {
        self::$authenticator = $authenticator;
    }

    /**
     * @return the current ESAPI Encoder object being used to encode and decode data for this application. 
     */
    public static function getEncoder()
    {
        if ( is_null(self::$encoder) )
        {
                require_once dirname(__FILE__).'/reference/DefaultEncoder.php';
        	self::$encoder = new DefaultEncoder();
        }
            
        return self::$encoder;
    }

    /**
     * Change the current ESAPI Encoder to the Encoder provided. 
     * @param encoder
     *            the Encoder to set to be the current ESAPI Encoder. 
     */
    public static function setEncoder($encoder)
    {
        self::$encoder = $encoder;
    }

    /**
     * @return the current ESAPI Encryptor object being used to encrypt and decrypt data for this application. 
     */
    public static function getEncryptor()
    {
        if ( is_null(self::$encryptor) )
        {
                require_once dirname(__FILE__).'/reference/DefaultEncryptor.php';
        	self::$encryptor = new DefaultEncryptor();
        }
            
        return self::$encryptor;
    }

    /**
     * Change the current ESAPI Encryptor to the Encryptor provided. 
     * @param encryptor
     *            the Encryptor to set to be the current ESAPI Encryptor. 
     */
    public static function setEncryptor($encryptor)
    {
        self::$encryptor = $encryptor;
    }

    /**
     * @return the current ESAPI Executor object being used to safely execute OS commands for this application. 
     */
    public static function getExecutor()
    {
        if ( is_null(self::$executor) )
        {
                require_once dirname(__FILE__).'/reference/DefaultExecutor.php';
        	self::$executor = new DefaultExecutor();
        }
            
        return self::$executor;
    }

    /**
     * Change the current ESAPI Executor to the Executor provided. 
     * @param executor
     *            the Executor to set to be the current ESAPI Executor. 
     */
    public static function setExecutor($executor)
    {
        self::$executor = $executor;
    }

    /**
     * @return the current ESAPI HTTPUtilities object being used to safely access HTTP requests and responses 
     * for this application. 
     */
    public static function getHttpUtilities()
    {
        if ( is_null(self::$httpUtilities) )
        {
                require_once dirname(__FILE__).'/reference/DefaultHTTPUtilities.php';
        	self::$httpUtilities = new DefaultHTTPUtilities();
        }
            
        return self::$httpUtilities;
    }

    /**
     * Change the current ESAPI HTTPUtilities object to the HTTPUtilities object provided. 
     * @param httpUtilities
     *            the HTTPUtilities object to set to be the current ESAPI HTTPUtilities object. 
     */
    public static function setHttpUtilities($httpUtilities)
    {
        self::$httpUtilities = $httpUtilities;
    }

    /**
     * @return the current ESAPI IntrusionDetector being used to monitor for intrusions in this application. 
     */
    public static function getIntrusionDetector()
    {
        if ( is_null(self::$intrusionDetector) )
        {
            require_once dirname(__FILE__).'/reference/DefaultIntrusionDetector.php';
            self::$intrusionDetector = new DefaultIntrusionDetector();
        }
        return self::$intrusionDetector;
    }

    /**
     * Change the current ESAPI IntrusionDetector to the IntrusionDetector provided. 
     * @param intrusionDetector
     *            the IntrusionDetector to set to be the current ESAPI IntrusionDetector. 
     */
    public static function setIntrusionDetector($intrusionDetector)
    {
        self::$intrusionDetector = $intrusionDetector;
    }

    /**
     * @param moduleName The module to associate the logger with.
     * @return The current Logger associated with the specified module.
     */
    public static function getLogger($moduleName)
    {
        if ( is_null(self::$defaultLogger) )
        {
                require_once dirname(__FILE__).'/reference/DefaultLogger.php';
        	self::$defaultLogger = new DefaultLogger();
        }
            
        return self::$defaultLogger;
    }

    /**
     * @return the current ESAPI Randomizer being used to generate random numbers in this application. 
     */
    public static function getRandomizer()
    {
        if ( is_null(self::$randomizer) )
        {
                require_once dirname(__FILE__).'/reference/DefaultRandomizer.php';
        	self::$randomizer = new DefaultRandomizer();
        }
            
        return self::$randomizer;
    }

    /**
     * Change the current ESAPI Randomizer to the Randomizer provided. 
     * @param randomizer
     *            the Randomizer to set to be the current ESAPI Randomizer. 
     */
    public static function setRandomizer($randomizer)
    {
        self::$randomizer = $randomizer;
    }

    /**
     * @return the current ESAPI SecurityConfiguration being used to manage the security configuration for 
     * ESAPI for this application. 
     */
    public static function getSecurityConfiguration()
    {
        if ( is_null(self::$securityConfiguration) )
        {
                require_once dirname(__FILE__).'/reference/DefaultSecurityConfiguration.php';
        	self::$securityConfiguration = new DefaultSecurityConfiguration();
        }
            
        return self::$securityConfiguration;
    }

    /**
     * Change the current ESAPI SecurityConfiguration to the SecurityConfiguration provided. 
     * @param securityConfiguration
     *            the SecurityConfiguration to set to be the current ESAPI SecurityConfiguration. 
     */
    public static function setSecurityConfiguration($securityConfiguration)
    {
        self::$securityConfiguration = $securityConfiguration;
    }

    /**
     * @return the current ESAPI Validator being used to validate data in this application. 
     */
    public static function getValidator()
    {
        if ( is_null(self::$validator) )
        {
                require_once dirname(__FILE__).'/reference/DefaultValidator.php';
        	self::$validator = new DefaultValidator();
        }
            
        return self::$validator;
    }

    /**
     * Change the current ESAPI Validator to the Validator provided. 
     * @param validator
     *            the Validator to set to be the current ESAPI Validator. 
     */
    public static function setValidator($validator)
    {
        self::$validator = $validator;
    }
}
?>