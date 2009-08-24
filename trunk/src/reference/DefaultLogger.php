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
 * @author 
 * @created 2008
 * @since 1.4
 * @package org.owasp.esapi.reference
 */



#include apache log4php requirements
define("LOG4PHP_DIR",dirname(__FILE__)."/../../lib/apache-log4php/trunk/src/main/php/");

//define('LOG4PHP_CONFIGURATION', 'log4php.properties');
//define('LOG4PHP_CONFIGURATOR_CLASS', 'LoggerBasicConfigurator');
require_once LOG4PHP_DIR.'/Logger.php';

require_once dirname(__FILE__).'/../Log4PhpLogger.php';

class DefaultLogger implements Log4PhpLogger {
     const SECURITY = 0;



     const OFF = PHP_INT_MAX;
     const FATAL =1000;
     const ERROR = 800;
     const WARNING = 600;
     const INFO = 400;
     const DEBUG = 200;
     const TRACE = 100;
     //const ALL = (-1 * PHP_INT_MAX) - 1;

     private $logger;

     function __construct($name) {
         $this->logger = Logger::getLogger($name);
     }

      /**
         * {@inheritDoc}
         * Note: In this implementation, this change is not persistent,
         * meaning that if the application is restarted, the log level will revert to the level defined in the
         * ESAPI SecurityConfiguration properties file.
         */
        public function setLevel($level)
        {
                try {
                        $this->logger->setLevel($this->convertESAPILeveltoLoggerLevel( $level ));
                }
                catch (IllegalArgumentException $e) {
                        $this->logger->error(DefaultLogger::SECURITY,false, "", $e);
                }
         }


     /**
         * Converts the ESAPI logging level (a number) into the levels used by Java's logger.
         * @param level The ESAPI to convert.
         * @return The Log4J logging Level that is equivalent.
         * @throws IllegalArgumentException if the supplied ESAPI level doesn't make a level that is currently defined.
         */
        private function convertESAPILeveltoLoggerLevel($level)
        {
                switch ($level) {
                        case DefaultLogger::OFF:     return LoggerLevel::getLevelOff();
                        case DefaultLogger::FATAL:   return LoggerLevel::getLevelFatal();
                        case DefaultLogger::ERROR:   return LoggerLevel::getLevelError();
                        case DefaultLogger::WARNING: return LoggerLevel::getLevelWarn();
                        case DefaultLogger::INFO:    return LoggerLevel::getLevelInfo();
                        case DefaultLogger::DEBUG:   return LoggerLevel::getLevelDebug(); //fine
                        case DefaultLogger::TRACE:   return LoggerLevel::getLevelAll(); //finest
                        case DefaultLogger::ALL:     return LoggerLevel::getLevelAll();
                        default: {
                                throw new IllegalArgumentException("Invalid logging level Value was: " + $level);
                        }
                }
        }


    

    /**
     * Log a fatal level security event if 'fatal' level logging is enabled 
     * and also record the stack trace associated with the event.
     * 
     * @param type 
     * 		the type of event
     * @param success
     * 		False indicates this was a failed event (the typical value).
     * 		True indicates this was a successful event.  
     * @param message 
     * 		the message to log
     * @param throwable 
     * 		the exception to be logged
     */
    function fatal($type, $success, $message, $throwable = null)	{
        $this->log(DefaultLogger::FATAL,$type, $success, $message, $throwable);
    }

    /**
     * Allows the caller to determine if messages logged at this level
     * will be discarded, to avoid performing expensive processing.
     * 
     * @return true if fatal level messages will be output to the log
     */
    function isFatalEnabled()	{
        return $this->logger->isEnabledFor(LoggerLevel::getLevelFatal());
    }

    
    /**
     * Log an error level security event if 'error' level logging is enabled 
     * and also record the stack trace associated with the event.
     * 
     * @param type 
     * 		the type of event
     * @param success
     * 		False indicates this was a failed event (the typical value).
     * 		True indicates this was a successful event.  
     * @param message 
     * 		the message to log
     * @param throwable 
     * 		the exception to be logged
     */
    function error($type, $success, $message, $throwable = null)	{
        $this->log(DefaultLogger::ERROR,$type, $success, $message, $throwable);
    }

    /**
     * Allows the caller to determine if messages logged at this level
     * will be discarded, to avoid performing expensive processing.
     * 
     * @return true if error level messages will be output to the log
     */
    function isErrorEnabled()	{
         return $this->logger->isEnabledFor(LoggerLevel::getLevelError());
    }

    /**
     * Log a warning level security event if 'warning' level logging is enabled 
     * and also record the stack trace associated with the event.
     * 
     * @param type 
     * 		the type of event
     * @param success
     * 		False indicates this was a failed event (the typical value).
     * 		True indicates this was a successful event.  
     * @param message 
     * 		the message to log
     * @param throwable 
     * 		the exception to be logged
     */
    function warning($type, $success, $message, $throwable = null)	{
        $this->log(DefaultLogger::WARNING,$type, $success, $message, $throwable);
    }

    /**
     * Allows the caller to determine if messages logged at this level
     * will be discarded, to avoid performing expensive processing.
     * 
     * @return true if warning level messages will be output to the log
     */
    function isWarningEnabled()	{
        return $this->logger->isEnabledFor(LoggerLevel::getLevelWarn());
    }

    /**
     * Log an info level security event if 'info' level logging is enabled 
     * and also record the stack trace associated with the event.
     * 
     * @param type 
     * 		the type of event
     * @param success
     * 		False indicates this was a failed event (the typical value).
     * 		True indicates this was a successful event.  
     * @param message 
     * 		the message to log
     * @param throwable 
     * 		the exception to be logged
     */
    function info($type, $success, $message, $throwable = null)	{
        $this->log(DefaultLogger::INFO,$type, $success, $message, $throwable);
    }

    /**
     * Allows the caller to determine if messages logged at this level
     * will be discarded, to avoid performing expensive processing.
     * 
     * @return true if info level messages will be output to the log
     */
    function isInfoEnabled()	{
        return $this->logger->isEnabledFor(LoggerLevel::getLevelInfo());
    }

    /**
     * Log a debug level security event if 'debug' level logging is enabled 
     * and also record the stack trace associated with the event.
     * 
     * @param type 
     * 		the type of event
     * @param success
     * 		False indicates this was a failed event (the typical value).
     * 		True indicates this was a successful event.  
     * @param message 
     * 		the message to log
     * @param throwable 
     * 		the exception to be logged
     */
    function debug($type, $success, $message, $throwable = null)	{
        $this->log(DefaultLogger::DEBUG,$type, $success, $message, $throwable);
    }

    /**
     * Allows the caller to determine if messages logged at this level
     * will be discarded, to avoid performing expensive processing.
     * 
     * @return true if debug level messages will be output to the log
     */
    function isDebugEnabled()	{
        return $this->logger->isEnabledFor(LoggerLevel::getLevelDebug());
    }

    /**
     * Log a trace level security event if 'trace' level logging is enabled 
     * and also record the stack trace associated with the event.
     * 
     * @param type 
     * 		the type of event
     * @param success
     * 		False indicates this was a failed event (the typical value).
     * 		True indicates this was a successful event.  
     * @param message 
     * 		the message to log
     * @param throwable 
     * 		the exception to be logged
     */
    function trace($type, $success, $message, $throwable = null){
        $this->log(DefaultLogger::TRACE,$type, $success, $message, $throwable);
    }

    /**
     * Allows the caller to determine if messages logged at this level
     * will be discarded, to avoid performing expensive processing.
     * 
     * @return true if trace level messages will be output to the log
     */
    function isTraceEnabled()	{
        return $this->logger->isEnabledFor(LoggerLevel::getLevelAll());
    }

    /**
         * Log the message after optionally encoding any special characters that might be dangerous when viewed
         * by an HTML based log viewer. Also encode any carriage returns and line feeds to prevent log
         * injection attacks. This logs all the supplied parameters plus the user ID, user's source IP, a logging
         * specific session ID, and the current date/time.
         *
         * It will only log the message if the current logging level is enabled, otherwise it will
         * discard the message.
         *
         * @param level the severity level of the security event
         * @param type the type of the event (SECURITY, FUNCTIONALITY, etc.)
         * @param success whether this was a failed or successful event
         * @param message the message
         * @param throwable the throwable
         */
        private function log($level, $type,$success, $message, $throwable) {
                global $ESAPI;

                $level = $this->convertESAPILeveltoLoggerLevel( $level );
                // Check to see if we need to log
                if (!$this->logger->isEnabledFor($level)) return;

                // create a random session number for the user to represent the user's 'session', if it doesn't exist already
                $sid = null;
                
                //$request = $ESAPI->getHttpUtilities()->getCurrentRequest();
                $request = null;
                if ( $request != null ) {
                    $session = $request->getSession( false );
                    if ( $session != null ) {
                            $sid = $session->getAttribute("ESAPI_SESSION");
                            // if there is no session ID for the user yet, we create one and store it in the user's session
                                if ( $sid == null ) {
                                    
                                   $sid = "". $ESAPI->getRandomizer()->getRandomInteger(0, 1000000);
                                   $session->setAttribute("ESAPI_SESSION", $sid);
                                }
                    }
                }

                // ensure there's something to log
                if ( $message == null ) {
                    $message = "";
                }

                // ensure no CRLF injection into logs for forging records
                $clean = str_replace('\r', '_',str_replace( '\n', '_',$message ));
               
                if ($ESAPI->getSecurityConfiguration()->getLogEncodingRequired() ) {
                    $clean = $ESAPI->getEncoder()->encodeForHTML($message);
                    if (!$message == $clean) {
                        $clean .= " (Encoded)";
                    }
                }

                        // log user information - username:session@ipaddr
                        //TODO commented out as $ESAPI->getAuthenticator()->getCurrentUser(); not yet implemented
                        //$user = $ESAPI->getAuthenticator()->getCurrentUser();
                        $user = null;
                        $userInfo = "";
                        if ( $user != null && $type != null) {
                                $userInfo = $user->getAccountName(). ":" . $sid . "@". $user->getLastHostAddress();
                        }

                        // log server, port, app name, module name -- server:80/app/module
                        $appInfo = "";

                        //TODO commented out as $ESAPI->currentRequest() not yet implemented
                        //$currentRequest= $ESAPI->currentRequest();
                        $currentRequest = null;
                        if ($currentRequest  != null && $logServerIP ) {
                                $appInfo.= $ESAPI->currentRequest()->getLocalAddr() . ":" . $ESAPI->currentRequest()->getLocalPort();
                        }

                        // log the message                        
//                        $this->logger->log($level , "[" + $userInfo + " -> " + $appInfo + "] " + $clean, $throwable);
        	
        }
}
?>
