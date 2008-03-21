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
 * @author rdawes
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
    
//    private static ILogger logger = null;
    
    private static $randomizer = null;
    
    private static $securityConfiguration = null;
    
    private static $validator = null;

    /**
     * prevent instantiation of this class
     */
    private function ESAPI() {}
    
    /**
     * @return the accessController
     */
    public static function accessController() {
        if (ESAPI::accessController == null)
            ESAPI::accessController = new AccessController();
        return ESAPI::accessController;
    }

    /**
     * @param accessController the accessController to set
     */
    public static function setAccessController($accessController) {
        ESAPI::accessController = accessController;
    }

    /**
     * @return the authenticator
     */
    public static function authenticator() {
        if (ESAPI::authenticator == null)
            ESAPI::authenticator = new Authenticator();
        return ESAPI::authenticator;
    }

    /**
     * @param authenticator the authenticator to set
     */
    public static function setAuthenticator($authenticator) {
        ESAPI::authenticator = authenticator;
    }

    /**
     * @return the encoder
     */
    public static function encoder() {
        if (ESAPI::encoder == null)
            ESAPI::encoder = new Encoder();
        return ESAPI::encoder;
    }

    /**
     * @param encoder the encoder to set
     */
    public static function setEncoder($encoder) {
        ESAPI::encoder = encoder;
    }

    /**
     * @return the encryptor
     */
    public static function encryptor() {
        if (ESAPI::encryptor == null)
            ESAPI::encryptor = new Encryptor();
        return ESAPI::encryptor;
    }

    /**
     * @param encryptor the encryptor to set
     */
    public static function setEncryptor($encryptor) {
        ESAPI::encryptor = encryptor;
    }

    /**
     * @return the executor
     */
    public static function executor() {
        if (ESAPI::executor == null)
            ESAPI::executor = new Executor();
        return ESAPI::executor;
    }

    /**
     * @param executor the executor to set
     */
    public static function setExecutor($executor) {
        ESAPI::executor = executor;
    }

    /**
     * @return the httpUtilities
     */
    public static function httpUtilities() {
        if (ESAPI::httpUtilities == null)
            ESAPI::httpUtilities = new HTTPUtilities();
        return ESAPI::httpUtilities;
    }

    /**
     * @param httpUtilities the httpUtilities to set
     */
    public static function setHttpUtilities($httpUtilities) {
        ESAPI::httpUtilities = httpUtilities;
    }

    /**
     * @return the intrusionDetector
     */
    public static function intrusionDetector() {
        if (ESAPI::intrusionDetector == null)
            ESAPI::intrusionDetector = new IntrusionDetector();
        return ESAPI::intrusionDetector;
    }

    /**
     * @param intrusionDetector the intrusionDetector to set
     */
    public static function setIntrusionDetector($intrusionDetector) {
        ESAPI::intrusionDetector = intrusionDetector;
    }

//    /**
//     * @return the logger
//     */
//    public static  ILogger getLogger() {
//        if (ESAPI::logger == null)
//            return Logger();
//        return ESAPI::logger;
//    }
//
//    /**
//     * @param logger the logger to set
//     */
//    public static  void setLogger(ILogger logger) {
//        ESAPI::logger = logger;
//    }
//
    /**
     * @return the randomizer
     */
    public static function randomizer() {
        if (ESAPI::randomizer == null)
            ESAPI::randomizer = new Randomizer();
        return ESAPI::randomizer;
    }

    /**
     * @param randomizer the randomizer to set
     */
    public static function setRandomizer($randomizer) {
        ESAPI::randomizer = randomizer;
    }

    /**
     * @return the securityConfiguration
     */
    public static function securityConfiguration() {
        if (ESAPI::securityConfiguration == null)
            ESAPI::securityConfiguration = new SecurityConfiguration();
        return ESAPI::securityConfiguration;
    }

    /**
     * @param securityConfiguration the securityConfiguration to set
     */
    public static function setSecurityConfiguration($securityConfiguration) {
        ESAPI::securityConfiguration = securityConfiguration;
    }

    /**
     * @return the validator
     */
    public static function validator() {
        if (ESAPI::validator == null)
            ESAPI::validator = new Validator();
        return ESAPI::validator;
    }

    /**
     * @param validator the validator to set
     */
    public static function setValidator($validator) {
        ESAPI::validator = validator;
    }
    
}
?>