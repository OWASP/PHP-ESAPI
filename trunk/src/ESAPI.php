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

require_once ("reference/DefaultEncoder.php");
require_once ("reference/DefaultExecutor.php");
require_once ("reference/DefaultHTTPUtilities.php");
require_once ("reference/DefaultIntrusionDetector.php");
require_once ("reference/DefaultRandomizer.php");
require_once ("reference/DefaultSecurityConfiguration.php");
require_once ("reference/DefaultValidator.php");
require_once ("reference/FileBasedAccessController.php");
require_once ("reference/FileBasedAuthenticator.php");
require_once ("reference/DefaultEncryptor.php");
require_once ("reference/DefaultLogger.php");

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
    function __construct()
    {
    	$defaultLogger = new DefaultLogger();
    }

    /**
     * Get the current HTTP Servlet Request being processed.
     * @return the current HTTP Servlet Request.
     */
    function currentRequest()
    {
        return $this->httpUtilities() . getCurrentRequest();
    }

    /**
     * Get the current HTTP Servlet Response being generated.
     * @return the current HTTP Servlet Response.
     */
    function currentResponse()
    {
        return $this->httpUtilities() . getCurrentResponse();
    }

    /**
     * @return the current ESAPI AccessController object being used to maintain the access control rules for this application. 
     */
    function accessController()
    {
        if ($this->accessController == null)
            $this->accessController = new FileBasedAccessController();
        return $this->accessController;
    }

    /**
     * Change the current ESAPI AccessController to the AccessController provided. 
     * @param accessController
     *            the AccessController to set to be the current ESAPI AccessController. 
     */
    function setAccessController($accessController)
    {
        $this->accessController = $accessController;
    }

    /**
     * @return the current ESAPI Authenticator object being used to authenticate users for this application. 
     */
    function authenticator()
    {
        if ($this->authenticator == null)
            $this->authenticator = new FileBasedAuthenticator();
        return $this->authenticator;
    }

    /**
     * Change the current ESAPI Authenticator to the Authenticator provided. 
     * @param authenticator
     *            the Authenticator to set to be the current ESAPI Authenticator. 
     */
    function setAuthenticator($authenticator)
    {
        $this->authenticator = $authenticator;
    }

    /**
     * @return the current ESAPI Encoder object being used to encode and decode data for this application. 
     */
    function encoder()
    {
        if ($this->encoder == null)
            $this->encoder = new DefaultEncoder();
        return $this->encoder;
    }

    /**
     * Change the current ESAPI Encoder to the Encoder provided. 
     * @param encoder
     *            the Encoder to set to be the current ESAPI Encoder. 
     */
    function setEncoder($encoder)
    {
        $this->encoder = $encoder;
    }

    /**
     * @return the current ESAPI Encryptor object being used to encrypt and decrypt data for this application. 
     */
    function encryptor()
    {
        if ($this->encryptor == null)
            $this->encryptor = new DefaultEncryptor();
        return $this->encryptor;
    }

    /**
     * Change the current ESAPI Encryptor to the Encryptor provided. 
     * @param encryptor
     *            the Encryptor to set to be the current ESAPI Encryptor. 
     */
    function setEncryptor($encryptor)
    {
        $this->encryptor = $encryptor;
    }

    /**
     * @return the current ESAPI Executor object being used to safely execute OS commands for this application. 
     */
    function executor()
    {
        if ($this->executor == null)
            $this->executor = new DefaultExecutor();
        return $this->executor;
    }

    /**
     * Change the current ESAPI Executor to the Executor provided. 
     * @param executor
     *            the Executor to set to be the current ESAPI Executor. 
     */
    function setExecutor($executor)
    {
        $this->executor = $executor;
    }

    /**
     * @return the current ESAPI HTTPUtilities object being used to safely access HTTP requests and responses 
     * for this application. 
     */
    function httpUtilities()
    {
        if ($this->httpUtilities == null)
            $this->httpUtilities = new DefaultHTTPUtilities();
        return $this->httpUtilities;
    }

    /**
     * Change the current ESAPI HTTPUtilities object to the HTTPUtilities object provided. 
     * @param httpUtilities
     *            the HTTPUtilities object to set to be the current ESAPI HTTPUtilities object. 
     */
    function setHttpUtilities($httpUtilities)
    {
        $this->httpUtilities = $httpUtilities;
    }

    /**
     * @return the current ESAPI IntrusionDetector being used to monitor for intrusions in this application. 
     */
    function intrusionDetector()
    {
        if ($this->intrusionDetector == null)
            $this->intrusionDetector = new DefaultIntrusionDetector();
        return $this->intrusionDetector;
    }

    /**
     * Change the current ESAPI IntrusionDetector to the IntrusionDetector provided. 
     * @param intrusionDetector
     *            the IntrusionDetector to set to be the current ESAPI IntrusionDetector. 
     */
    function setIntrusionDetector($intrusionDetector)
    {
        $this->intrusionDetector = $intrusionDetector;
    }

    /**
     * @param moduleName The module to associate the logger with.
     * @return The current Logger associated with the specified module.
     */
    function getLogger($moduleName)
    {
        if ($this->defaultLogger == null)
        {
        	$this->defaultLogger = new DefaultLogger();
        }
            
        return $this->defaultLogger;
    }

    /**
     * @return the current ESAPI Randomizer being used to generate random numbers in this application. 
     */
    function randomizer()
    {
        if ($this->randomizer == null)
            $this->randomizer = new DefaultRandomizer();
        return $this->randomizer;
    }

    /**
     * Change the current ESAPI Randomizer to the Randomizer provided. 
     * @param randomizer
     *            the Randomizer to set to be the current ESAPI Randomizer. 
     */
    function setRandomizer($randomizer)
    {
        $this->randomizer = $randomizer;
    }

    /**
     * @return the current ESAPI SecurityConfiguration being used to manage the security configuration for 
     * ESAPI for this application. 
     */
    function securityConfiguration()
    {
        if ($this->securityConfiguration == null)
            $this->securityConfiguration = new DefaultSecurityConfiguration();
        return $this->securityConfiguration;
    }

    /**
     * Change the current ESAPI SecurityConfiguration to the SecurityConfiguration provided. 
     * @param securityConfiguration
     *            the SecurityConfiguration to set to be the current ESAPI SecurityConfiguration. 
     */
    function setSecurityConfiguration($securityConfiguration)
    {
        $this->securityConfiguration = $securityConfiguration;
    }

    /**
     * @return the current ESAPI Validator being used to validate data in this application. 
     */
    function validator()
    {
        if ($this->validator == null)
            $this->validator = new DefaultValidator();
        return $this->validator;
    }

    /**
     * Change the current ESAPI Validator to the Validator provided. 
     * @param validator
     *            the Validator to set to be the current ESAPI Validator. 
     */
    function setValidator($validator)
    {
        $this->validator = $validator;
    }

}
?>