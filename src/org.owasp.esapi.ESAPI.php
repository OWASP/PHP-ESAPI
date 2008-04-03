<?php
/**
 * @package org.owasp.esapi;
 */
require_once ("org.owasp.esapi.AccessController.php");
require_once ("org.owasp.esapi.Authenticator.php");
require_once ("org.owasp.esapi.Encoder.php");
require_once ("org.owasp.esapi.Encryptor.php");
require_once ("org.owasp.esapi.Executor.php");
require_once ("org.owasp.esapi.HTTPUtilities.php");
require_once ("org.owasp.esapi.IntrusionDetection.php");
require_once ("org.owasp.esapi.Randomizer.php");
require_once ("org.owasp.esapi.SecurityConfiguration.php");
require_once ("org.owasp.esapi.Validator.php");
/**
 * @author vanderaj
 *
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
    private static $logger = null;
    private static $randomizer = null;
    private static $securityConfiguration = null;
    private static $validator = null;
    /**
     * Constructor (not necessary, but you can if you want)
     */
    function __construct()
    {
    }
    /**
     * @return the accessController
     */
    public function accessController()
    {
        if ($this->accessController == null)
        {
            $this->accessController = new AccessController();
        }
        return $this->accessController;
    }
    /**
     * @param accessController the accessController to set
     */
    public function setAccessController($accessController)
    {
        $this->accessController = $accessController;
    }
    /**
     * @return the authenticator
     */
    public function authenticator()
    {
        if ($this->authenticator == null)
        {
            $this->authenticator = new Authenticator();
        }
        return $this->authenticator;
    }
    /**
     * @param authenticator the authenticator to set
     */
    public function setAuthenticator($authenticator)
    {
        $this->authenticator = $authenticator;
    }
    /**
     * @return the encoder
     */
    public function encoder()
    {
        if ($this->encoder == null)
        {
            $this->encoder = new Encoder();
        }
        return $this->encoder;
    }
    /**
     * @param encoder the encoder to set
     */
    public function setEncoder($encoder)
    {
        $this->encoder = $encoder;
    }
    /**
     * @return the encryptor
     */
    public function encryptor()
    {
        if ($this->encryptor == null)
        {
            $this->encryptor = new Encryptor();
        }
        return $this->encryptor;
    }
    /**
     * @param encryptor the encryptor to set
     */
    public function setEncryptor($encryptor)
    {
        $this->encryptor = $encryptor;
    }
    /**
     * @return the executor
     */
    public function executor()
    {
        if ($this->executor == null)
        {
            $this->executor = new Executor();
        }
        return $this->executor;
    }
    /**
     * @param executor the executor to set
     */
    public function setExecutor($executor)
    {
        $this->executor = $executor;
    }
    /**
     * @return the httpUtilities
     */
    public function httpUtilities()
    {
        if ($this->httpUtilities == null)
        {
            $this->httpUtilities = new HTTPUtilities();
        }
        return $this->httpUtilities;
    }
    /**
     * @param httpUtilities the httpUtilities to set
     */
    public function setHttpUtilities($httpUtilities)
    {
        $this->httpUtilities = $httpUtilities;
    }
    /**
     * @return the intrusionDetector
     */
    public function intrusionDetector()
    {
        if ($this->intrusionDetector == null)
        {
            $this->intrusionDetector = new IntrusionDetector();
        }
        return $this->intrusionDetector;
    }
    /**
     * @param intrusionDetector the intrusionDetector to set
     */
    public function setIntrusionDetector($intrusionDetector)
    {
        $this->intrusionDetector = $intrusionDetector;
    }
    /**
     * @return the logger
     */
    public function getLogger()
    {
        if ($this->logger == null)
        {
            $this->logger = new Logger();
        }
        return $this->logger;
    }
    /**
     * @param logger the logger to set
     */
    public function setLogger($logger)
    {
        $this->logger = $logger;
    }
    /**
     * @return the randomizer
     */
    public function randomizer()
    {
        if ($this->randomizer == null)
        {
            $this->randomizer = new Randomizer();
        }
        return $this->randomizer;
    }
    /**
     * @param randomizer the randomizer to set
     */
    public function setRandomizer($randomizer)
    {
        $this->randomizer = $randomizer;
    }
    /**
     * @return the securityConfiguration
     */
    public function securityConfiguration()
    {
        if ($this->securityConfiguration == null)
        {
            $this->securityConfiguration = new SecurityConfiguration();
        }
        return $this->securityConfiguration;
    }
    /**
     * @param securityConfiguration the securityConfiguration to set
     */
    public function setSecurityConfiguration($securityConfiguration)
    {
        $this->securityConfiguration = $securityConfiguration;
    }
    /**
     * @return the validator
     */
    public function validator()
    {
        if ($this->validator == null)
        {
            $this->validator = new Validator();
        }
        return $this->validator;
    }
    /**
     * @param validator the validator to set
     */
    public function setValidator($validator)
    {
        $this->validator = $validator;
    }
}
?>