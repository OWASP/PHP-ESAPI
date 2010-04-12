<?php
/**
 * OWASP Enterprise Security API (ESAPI)
 *
 * This file is part of the Open Web Application Security Project (OWASP)
 * Enterprise Security API (ESAPI) project.
 * 
 * PHP version 5.2
 *
 * LICENSE: This source file is subject to the New BSD license.  You should read
 * and accept the LICENSE before you use, modify, and/or redistribute this
 * software.
 *
 * @category  OWASP
 * @package   ESAPI
 * @author    Mike Fauzy <mike.fauzy@aspectsecurity.com>
 * @author    Rogan Dawes <rogan@dawes.za.net>
 * @author    Andrew van der Stock <vanderaj@owasp.org>
 * @author    Bipin Upadhyay <bipin.code@gmail.com>
 * @author    Mike Boberski <boberski_michael@bah.com>
 * @copyright 2009-2010 The OWASP Foundation
 * @license   http://www.opensource.org/licenses/bsd-license.php New BSD license
 * @version   SVN: $Id$
 * @link      http://www.owasp.org/index.php/ESAPI
 */

/**
 * ESAPI locator class is provided to make it easy to gain access to the current
 * ESAPI classes in use. Use the set methods to override the reference
 * implementations with instances of any custom ESAPI implementations.
 *
 * @category  OWASP
 * @package   ESAPI
 * @author    Mike Fauzy <mike.fauzy@aspectsecurity.com>
 * @author    Rogan Dawes <rogan@dawes.za.net>
 * @author    Andrew van der Stock <vanderaj@owasp.org>
 * @author    Bipin Upadhyay <bipin.code@gmail.com>
 * @author    Mike Boberski <boberski_michael@bah.com>
 * @copyright 2009-2010 The OWASP Foundation
 * @license   http://www.opensource.org/licenses/bsd-license.php New BSD license
 * @version   Release: @package_version@
 * @link      http://www.owasp.org/index.php/ESAPI
 */
class ESAPI
{
    private static $_accessController = null;
    private static $_encoder = null;
    private static $_encryptor = null;
    private static $_executor = null;
    private static $_httpUtilities = null;
    private static $_intrusionDetector = null;
    private static $_defaultLogger = null;
    private static $_auditorFactory= null;
    private static $_randomizer = null;
    private static $_securityConfiguration = null;
    private static $_validator = null;
    private static $_sanitizer = null;
    
    /**
     * This is the locator class' constructor, which prevents instantiation of this
     * class.
     * 
     * @param string $path the path of the ESAPI.xml configuration file.
     */
    public function __construct($path = '') 
    {
        self::getSecurityConfiguration($path);

        self::getLogger("ESAPI Startup");
        
        self::getIntrusionDetector();
    }

    /**
     * Get the current HTTP Servlet Request being processed.
     * 
     * @return the current HTTP Servlet Request.
     */
    public static function currentRequest() 
    {
        return self::getHttpUtilities()->getCurrentRequest();
    }

    /**
     * Get the current HTTP Servlet Response being generated.
     * 
     * @return the current HTTP Servlet Response.
     */
    public static function currentResponse() 
    {
        return self::getHttpUtilities()->getCurrentResponse();
    }

    /**
     * Get the current ESAPI AccessController object being used to maintain the 
     * access control rules for this application.
     * 
     * @return the current ESAPI AccessController.
     */
    public static function getAccessController() 
    {
        if ( is_null(self::$_accessController) ) {
            include_once dirname(__FILE__).
              '/reference/FileBasedAccessController.php';
            self::$_accessController = new FileBasedAccessController();
        }

        return self::$_accessController;
    }

    /**
     * Set the current ESAPI AccessController object being used to maintain the 
     * access control rules for this application.
     * 
     * @param AccessController $accessController the new ESAPI AccessController.
     * 
     * @return does not return a value.
     */
    public static function setAccessController($accessController) 
    {
        self::$_accessController = $accessController;
    }

    /**
     * Get the current ESAPI Encoder object being used to encode and decode data for
     * this application
     * 
     * @return the current ESAPI Encoder.
     */
    public static function getEncoder() 
    {
        if ( is_null(self::$_encoder) ) {
            include_once dirname(__FILE__).
              '/reference/DefaultEncoder.php';
            self::$_encoder = new DefaultEncoder();
        }

        return self::$_encoder;
    }

    /**
     * Set the current ESAPI Encoder object being used to encode and decode data
     * for this application.
     * 
     * @param Encoder $encoder the new ESAPI AccessController.
     * 
     * @return does not return a value.
     */
    public static function setEncoder($encoder) 
    {
        self::$_encoder = $encoder;
    }

    /**
     * Get the current ESAPI Encryptor object being used to encrypt and decrypt data
     * for this application.
     *
     * @return the current ESAPI Encryptor.
     */
    public static function getEncryptor() 
    {
       throw new EnterpriseSecurityException(
            'Method Not implemented',
            'Encryptor not implemented'
        );
    }

    /**
     * Set the current ESAPI Encryptor object being used to encrypt and decrypt 
     * data for this application.
     * 
     * @param Encryptor $encryptor the new ESAPI Encryptor.
     * 
     * @return does not return a value.
     */
    public static function setEncryptor($encryptor) 
    {
       throw new EnterpriseSecurityException(
            'Method Not implemented',
            'Encryptor not implemented'
        );
    }

    /**
     * Get the current ESAPI Executor object being used to safely execute OS 
     * commands for this application.
     * 
     * @return the current ESAPI Executor.
     */
    public static function getExecutor() 
    {
        if ( is_null(self::$_executor) ) {
            include_once dirname(__FILE__).
              '/reference/DefaultExecutor.php';
            self::$_executor = new DefaultExecutor();
        }

        return self::$_executor;
    }

    /**
     * Set the current ESAPI Executor object being used to safely execute OS 
     * commands for this application.
     * 
     * @param Executor $executor the new ESAPI Executor.
     * 
     * @return does not return a value.
     */
    public static function setExecutor($executor) 
    {
        self::$_executor = $executor;
    }

    /**
     * Get the current ESAPI HTTPUtilities object being used to safely access HTTP 
     * requests and responses for this application.
     * 
     * @return the current ESAPI HTTPUtilities.
     */
    public static function getHttpUtilities() 
    {
        if ( is_null(self::$_httpUtilities) ) {
            include_once dirname(__FILE__).
              '/reference/DefaultHTTPUtilities.php';
            self::$_httpUtilities = new DefaultHTTPUtilities();
        }

        return self::$_httpUtilities;
    }

    /**
     * Set the current ESAPI HttpUtilities object being used to safely access HTTP 
     * requests and responses for this application.
     * 
     * @param HttpUtilities $httpUtilities the new ESAPI HttpUtilities.
     * 
     * @return does not return a value.
     */
    public static function setHttpUtilities($httpUtilities) 
    {
        self::$_httpUtilities = $httpUtilities;
    }

    /**
     * Get the current ESAPI IntrusionDetector object being used to monitor for 
     * intrusions in this application.
     * 
     * @return the current ESAPI IntrusionDetector.
     */
    public static function getIntrusionDetector() 
    {
        if ( is_null(self::$_intrusionDetector) ) {
            include_once dirname(__FILE__).
              '/reference/DefaultIntrusionDetector.php';
            self::$_intrusionDetector = new DefaultIntrusionDetector();
        }
        return self::$_intrusionDetector;
    }

    /**
     * Set the current ESAPI AccessController object being used to to monitor for 
     * intrusions in this application.
     * 
     * @param IntrusionDetector $intrusionDetector the new ESAPI IntrusionDetector.
     * 
     * @return does not return a value.
     */
    public static function setIntrusionDetector($intrusionDetector) 
    {
        self::$_intrusionDetector = $intrusionDetector;
    }

    
    /**
     * Set then get the current ESAPI Logger factory object being used to create
     * the ESAPI Logger for this application.
     * 
     * @param string $logger the new ESAPI Logger factory name.
     * 
     * @return the current ESAPI Logger.
     */
    public static function getLogger($logger) 
    {
        if (self::$_auditorFactory == null) {
            include_once dirname(__FILE__).
              '/reference/DefaultAuditorFactory.php';
            self::setLogFactory(new DefaultAuditorFactory());
        }
        return self::$_auditorFactory->getLogger($logger);
    }

    /**
     * Get the current ESAPI Logger object being used to to audit security-relevant
     * events for this application.
     * 
     * @return the current ESAPI Logger.
     */
    public static function log() 
    {
        if (self::$_defaultLogger == null) {
            self::$_defaultLogger = self::$_auditorFactory->getLogger("DefaultLogger");
        }
        return self::$_defaultLogger;
    }

    /**
     * Set the current ESAPI Logger factory object being used to create
     * the ESAPI Logger for this application.
     * 
     * @param string $factory the new ESAPI Logger factory.
     * 
     * @return does not return a value.
     */
    public static function setLogFactory($factory) 
    {
        self::$_auditorFactory = $factory;
    }


    /**
     * Get the current ESAPI Randomizer object being used to generate random numbers
     * for this application.
     * 
     * @return the current ESAPI Randomizer.
     */
    public static function getRandomizer() 
    {
        if ( is_null(self::$_randomizer) ) {
            include_once dirname(__FILE__).
              '/reference/DefaultRandomizer.php';
            self::$_randomizer = new DefaultRandomizer();
        }

        return self::$_randomizer;
    }

    /**
     * Set the current ESAPI Randomizer object being used to generate random numbers
     * for this application.
     * 
     * @param Randomizer $randomizer the new ESAPI Randomizer.
     * 
     * @return does not return a value.
     */
    public static function setRandomizer($randomizer) 
    {
        self::$_randomizer = $randomizer;
    }

    /**
     * Get the current ESAPI SecurityConfiguration object being used to manage the 
     * security configuration for this application.
     *  
     * @param string $path the path of the ESAPI.xml configuration file.
     * 
     * @return the current ESAPI SecurityConfiguration.
     */
    public static function getSecurityConfiguration($path = '') 
    {
        if ( is_null(self::$_securityConfiguration) ) {
            include_once dirname(__FILE__).
              '/reference/DefaultSecurityConfiguration.php';
            self::$_securityConfiguration = new DefaultSecurityConfiguration($path);
        }

        return self::$_securityConfiguration;
    }

    /**
     * Set the current ESAPI SecurityConfiguration object being used to manage the 
     * security configuration for this application.
     * 
     * @param SecurityConfiguration $securityConfiguration the new ESAPI 
     * SecurityConfiguration.
     * 
     * @return does not return a value.
     */
    public static function setSecurityConfiguration($securityConfiguration) 
    {
        self::$_securityConfiguration = $securityConfiguration;
    }

    /**
     * Get the current ESAPI Validator object being used to validate data for this 
     * application.
     * 
     * @return the current ESAPI Validator.
     */
    public static function getValidator() 
    {
        if ( is_null(self::$_validator) ) {
            include_once dirname(__FILE__).
              '/reference/DefaultValidator.php';
            self::$_validator = new DefaultValidator();
        }

        return self::$_validator;
    }

    /**
     * Set the current ESAPI Validator object being used to validate data for
     * this application.
     * 
     * @param Validator $validator the new ESAPI Validator.
     * 
     * @return does not return a value.
     */
    public static function setValidator($validator) 
    {
        self::$_validator = $validator;
    }

    /**
     * Get the current ESAPI Sanitizer object being used to sanitize data for
     * this application.
     * 
     * @return the current ESAPI Sanitizer.
     */
    public static function getSanitizer() 
    {
        if ( is_null(self::$_sanitizer) ) {
            include_once dirname(__FILE__).
              '/reference/DefaultSanitizer.php';
            self::$_sanitizer = new DefaultSanitizer();
        }

        return self::$_sanitizer;
    }

    /**
     * Set the current ESAPI Sanitizer object being used to sanitize data for
     * this application.
     * 
     * @param Sanitizer $sanitizer the new ESAPI Sanitizer.
     * 
     * @return does not return a value.
     */
    public static function setSanitizer($sanitizer) 
    {
        self::$_sanitizer = $sanitizer;
    }
 
}
?>