<?php
/**
 * @package org.owasp.esapi;
 */

require_once("interfaces/org.owasp.esapi.IAccessController.php");
require_once("interfaces/org.owasp.esapi.IAuthenticator.php");
require_once("interfaces/org.owasp.esapi.IEncoder.php");
require_once("interfaces/org.owasp.esapi.IEncryptor.php");
require_once("interfaces/org.owasp.esapi.IExecutor.php");
require_once("interfaces/org.owasp.esapi.IHTTPUtilities.php");
require_once("interfaces/org.owasp.esapi.IIntrusionDetector.php");
require_once("interfaces/org.owasp.esapi.IRandomizer.php");
require_once("interfaces/org.owasp.esapi.ISecurityConfiguration.php");
require_once("interfaces/org.owasp.esapi.IValidator.php");

/**
 * @author vanderaj
 *
 */
class ESAPI {
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
    function __construct() {
    	
    }
    
    /**
     * @return the accessController
     */
    public function accessController() {
        if ($this->accessController == null) {
        	$this->accessController = new AccessController();
        }
            
        return $this->accessController;
    }

    /**
     * @param accessController the accessController to set
     */
    public function setAccessController($accessController) {
        $this->accessController = $accessController;
    }

    /**
     * @return the authenticator
     */
    public static function authenticator() {
        if ($this->authenticator == null) {
        	$this->authenticator = new Authenticator();
        }
            
        return $this->authenticator;
    }

    /**
     * @param authenticator the authenticator to set
     */
    public static function setAuthenticator($authenticator) {
        $this->authenticator = $authenticator;
    }

    /**
     * @return the encoder
     */
    public static function encoder() {
        if ($this->encoder == null) {
        	$this->encoder = new Encoder();
        }
            
        return $this->encoder;
    }

    /**
     * @param encoder the encoder to set
     */
    public static function setEncoder($encoder) {
        $this->encoder = $encoder;
    }

    /**
     * @return the encryptor
     */
    public static function encryptor() {
        if ($this->encryptor == null) {
        	$this->encryptor = new Encryptor();
        }
            
        return $this->encryptor;
    }

    /**
     * @param encryptor the encryptor to set
     */
    public static function setEncryptor($encryptor) {
        $this->encryptor = $encryptor;
    }

    /**
     * @return the executor
     */
    public static function executor() {
        if ($this->executor == null) {
        	$this->executor = new Executor();
        }
            
        return $this->executor;
    }

    /**
     * @param executor the executor to set
     */
    public static function setExecutor($executor) {
        $this->executor = $executor;
    }

    /**
     * @return the httpUtilities
     */
    public static function httpUtilities() {
        if ($this->httpUtilities == null) {
        	$this->httpUtilities = new HTTPUtilities();
        }
            
        return $this->httpUtilities;
    }

    /**
     * @param httpUtilities the httpUtilities to set
     */
    public static function setHttpUtilities($httpUtilities) {
        $this->httpUtilities = $httpUtilities;
    }

    /**
     * @return the intrusionDetector
     */
    public static function intrusionDetector() {
        if ($this->intrusionDetector == null) {
        	$this->intrusionDetector = new IntrusionDetector();
        }
            
        return $this->intrusionDetector;
    }

    /**
     * @param intrusionDetector the intrusionDetector to set
     */
    public static function setIntrusionDetector($intrusionDetector) {
        $this->intrusionDetector = $intrusionDetector;
    }

	/**
	 * @return the logger
     */
	public static function getLogger() {
		if ($this->logger == null) {
			$this->logger = new Logger();
		}
			
		return $this->logger;
	}

    /**
     * @param logger the logger to set
     */
    public static function setLogger($logger) {
        $this->logger = $logger;
    }

    /**
     * @return the randomizer
     */
    public static function randomizer() {
        if ($this->randomizer == null) {
        	$this->randomizer = new Randomizer();
        }
            
        return $this->randomizer;
    }

    /**
     * @param randomizer the randomizer to set
     */
    public static function setRandomizer($randomizer) {
        $this->randomizer = $randomizer;
    }

    /**
     * @return the securityConfiguration
     */
    public static function securityConfiguration() {
        if ($this->securityConfiguration == null) {
        	$this->securityConfiguration = new SecurityConfiguration();
        }
            
        return $this->securityConfiguration;
    }

    /**
     * @param securityConfiguration the securityConfiguration to set
     */
    public static function setSecurityConfiguration($securityConfiguration) {
        $this->securityConfiguration = $securityConfiguration;
    }

    /**
     * @return the validator
     */
    public static function validator() {
        if ($this->validator == null) {
        	$this->validator = new Validator();
        }
            
        return $this->validator;
    }

    /**
     * @param validator the validator to set
     */
    public static function setValidator($validator) {
        $this->validator = $validator;
    }
    
}
?>