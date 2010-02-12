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
define("LOG4PHP_DIR", dirname(__FILE__) . "/../../lib/apache-log4php/trunk/src/main/php/");
require_once LOG4PHP_DIR . '/Logger.php';


require_once dirname(__FILE__).'/../ESAPILogger.php';


class DefaultLogger implements ESAPILogger {

    private $logger;
    private $loggerName;
    private static $initialised = false;

    function __construct($name)
    {
        if (self::$initialised == false) {
            self::initialise();
        }
        $this->logger = Logger::getLogger($name);
        $this->loggerName = $name;
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
        catch (Exception $e) {
            $this->error(ESAPILogger::SECURITY, false, "IllegalArgumentException", $e);
        }
    }


    /**
     * Converts the ESAPI logging level (a number) or level defined in ESAPI.xml
     * (a string) into the levels used by Apache's log4php.
     * @param level The ESAPI logging level to convert.
     * @return The log4php logging Level that is equivalent.
     * @throws Exception if the supplied ESAPI level doesn't make a level that is currently defined.
     */
    private function convertESAPILeveltoLoggerLevel($level)
    {
        if(is_string($level)) {
            switch(strtoupper($level)) {
                case 'ALL':    return LoggerLevel::getLevelAll();
                case 'DEBUG':  return LoggerLevel::getLevelDebug();
                case 'INFO':   return LoggerLevel::getLevelInfo();
                case 'WARN':   return LoggerLevel::getLevelWarn();
                case 'ERROR':  return LoggerLevel::getLevelError();
                case 'SEVERE': return LoggerLevel::getLevelFatal();
              //case 'OFF':    return LoggerLevel::getLevelOff();     // TODO allow this?
                default: {
                    throw new Exception(
                        "Invalid logging level Value was: {$level}"
                    );
                }
            }
        } else {
            switch($level) {
                case ESAPILogger::TRACE:   return LoggerLevel::getLevelAll();
                case ESAPILogger::DEBUG:   return LoggerLevel::getLevelDebug();
                case ESAPILogger::INFO:    return LoggerLevel::getLevelInfo();
                case ESAPILogger::WARNING: return LoggerLevel::getLevelWarn();
                case ESAPILogger::ERROR:   return LoggerLevel::getLevelError();
                case ESAPILogger::FATAL:   return LoggerLevel::getLevelFatal();
                case ESAPILogger::OFF:     return LoggerLevel::getLevelOff();
                default: {
                    throw new Exception(
                        "Invalid logging level Value was: {$level}"
                    );
                }
            }
        }
    }


    /**
     *  initialise() configures Apache's Log4PHP RootLogger to append events to
     *  STDOUT and a RollingFile, the latter of which takes its properties from
     *  SecurityConfiguration.
     *
     */
    private static function initialise()
    {
        self::$initialised = true;

        $secConfig = ESAPI::getSecurityConfiguration();
        $logLevel = $secConfig->getLogLevel();

        // Patterns representing the format of Log entries
        // d date, p priority (level), m message, n newline
        $dateFormat = $secConfig->getLogFileDateFormat();
        $logfileLayoutPattern = "%d{{$dateFormat}} %-5p %m %n";
        $consoleLayoutPattern = "%d{{$dateFormat}} %-5p %m <br />%n";

        // LogFile properties.
        $logFileName = $secConfig->getLogFileName();
        $maxLogFileSize = $secConfig->getMaxLogFileSize();
        $maxLogFileBackups = $secConfig->getMaxLogFileBackups();

        // LogFile layout
        $logfileLayout = new LoggerLayoutPattern();
        $logfileLayout->setConversionPattern($logfileLayoutPattern); // no idea why the constructor doesn't do this!

        // Get a LoggerFilter - Use LevelMatch to deny DEBUG in the logfile.
        // TODO remove LoggerFilter when codec debugging is done and before
        // release.
        $loggerFilter = new LoggerFilterLevelMatch();
        $loggerFilter->setLevelToMatch(LoggerLevel::DEBUG);
        $loggerFilter->setAcceptOnMatch("false");
        $loggerFilter->activateOptions();

        // LogFile RollingFile Appender
        $appenderLogfile = new LoggerAppenderRollingFile('ESAPI LogFile');
        $appenderLogfile->setFile($logFileName, true);
        $appenderLogfile->setMaxFileSize($maxLogFileSize);
        $appenderLogfile->setMaxBackupIndex($maxLogFileBackups);
        $appenderLogfile->addFilter($loggerFilter); // TODO remove temp filter
        $appenderLogfile->setLayout($logfileLayout);
        $appenderLogfile->activateOptions();

        // Console layout
        $consoleLayout = new LoggerLayoutPattern();
        $consoleLayout->setConversionPattern($consoleLayoutPattern);

        // Console Echo Appender
        $appenderEcho = new LoggerAppenderEcho('Echo Output');
        $appenderEcho->setLayout($consoleLayout);
        $appenderEcho->activateOptions();

        // Get the RootLogger and reset it, before adding our Appenders and
        // setting our Loglevel
        $rootLogger = Logger::getRootLogger();
        $rootLogger->removeAllAppenders();
        $rootLogger->addAppender($appenderEcho);
        $rootLogger->addAppender($appenderLogfile);
        $rootLogger->setLevel(
            self::convertESAPILeveltoLoggerLevel($logLevel)
        );

    }


    /**
     * Log a fatal level security event if 'fatal' level logging is enabled
     * and also record the stack trace associated with the event.
     *
     * @param type
     *         the type of event
     * @param success
     *         False indicates this was a failed event (the typical value).
     *         True indicates this was a successful event.
     * @param message
     *         the message to log
     * @param throwable
     *         the exception to be logged
     */
    function fatal($type, $success, $message, $throwable = null)
    {
        $this->log(ESAPILogger::FATAL, $type, $success, $message, $throwable);
    }


    /**
     * Allows the caller to determine if messages logged at this level
     * will be discarded, to avoid performing expensive processing.
     *
     * @return true if fatal level messages will be output to the log
     */
    function isFatalEnabled()
    {
        return $this->logger->isEnabledFor(LoggerLevel::getLevelFatal());
    }


    /**
     * Log an error level security event if 'error' level logging is enabled
     * and also record the stack trace associated with the event.
     *
     * @param type
     *         the type of event
     * @param success
     *         False indicates this was a failed event (the typical value).
     *         True indicates this was a successful event.
     * @param message
     *         the message to log
     * @param throwable
     *         the exception to be logged
     */
    function error($type, $success, $message, $throwable = null)
    {
        $this->log(ESAPILogger::ERROR, $type, $success, $message, $throwable);
    }


    /**
     * Allows the caller to determine if messages logged at this level
     * will be discarded, to avoid performing expensive processing.
     *
     * @return true if error level messages will be output to the log
     */
    function isErrorEnabled()
    {
        return $this->logger->isEnabledFor(LoggerLevel::getLevelError());
    }


    /**
     * Log a warning level security event if 'warning' level logging is enabled
     * and also record the stack trace associated with the event.
     *
     * @param type
     *         the type of event
     * @param success
     *         False indicates this was a failed event (the typical value).
     *         True indicates this was a successful event.
     * @param message
     *         the message to log
     * @param throwable
     *         the exception to be logged
     */
    function warning($type, $success, $message, $throwable = null)
    {
        $this->log(ESAPILogger::WARNING, $type, $success, $message, $throwable);
    }


    /**
     * Allows the caller to determine if messages logged at this level
     * will be discarded, to avoid performing expensive processing.
     *
     * @return true if warning level messages will be output to the log
     */
    function isWarningEnabled()
    {
        return $this->logger->isEnabledFor(LoggerLevel::getLevelWarn());
    }


    /**
     * Log an info level security event if 'info' level logging is enabled
     * and also record the stack trace associated with the event.
     *
     * @param type
     *         the type of event
     * @param success
     *         False indicates this was a failed event (the typical value).
     *         True indicates this was a successful event.
     * @param message
     *         the message to log
     * @param throwable
     *         the exception to be logged
     */
    function info($type, $success, $message, $throwable = null)
    {
        $this->log(ESAPILogger::INFO, $type, $success, $message, $throwable);
    }


    /**
     * Allows the caller to determine if messages logged at this level
     * will be discarded, to avoid performing expensive processing.
     *
     * @return true if info level messages will be output to the log
     */
    function isInfoEnabled()
    {
        return $this->logger->isEnabledFor(LoggerLevel::getLevelInfo());
    }


    /**
     * Log a debug level security event if 'debug' level logging is enabled
     * and also record the stack trace associated with the event.
     *
     * @param type
     *         the type of event
     * @param success
     *         False indicates this was a failed event (the typical value).
     *         True indicates this was a successful event.
     * @param message
     *         the message to log
     * @param throwable
     *         the exception to be logged
     */
    function debug($type, $success, $message, $throwable = null)
    {
        $this->log(ESAPILogger::DEBUG,$type, $success, $message, $throwable);
    }


    /**
     * Allows the caller to determine if messages logged at this level
     * will be discarded, to avoid performing expensive processing.
     *
     * @return true if debug level messages will be output to the log
     */
    function isDebugEnabled()
    {
        return $this->logger->isEnabledFor(LoggerLevel::getLevelDebug());
    }


    /**
     * Log a trace level security event if 'trace' level logging is enabled
     * and also record the stack trace associated with the event.
     *
     * @param type
     *         the type of event
     * @param success
     *         False indicates this was a failed event (the typical value).
     *         True indicates this was a successful event.
     * @param message
     *         the message to log
     * @param throwable
     *         the exception to be logged
     */
    function trace($type, $success, $message, $throwable = null)
    {
        $this->log(ESAPILogger::TRACE, $type, $success, $message, $throwable);
    }


    /**
     * Allows the caller to determine if messages logged at this level
     * will be discarded, to avoid performing expensive processing.
     *
     * @return true if trace level messages will be output to the log
     */
    function isTraceEnabled()
    {
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
    private function log($level, $type, $success, $message, $throwable)
    {
        // If this log level is below the threshold we can quit now.
        $logLevel = $this->convertESAPILeveltoLoggerLevel($level);
        if (!$this->logger->isEnabledFor($logLevel)) {
            return;
        }

        /* TODO Removed until AccessController is done.
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
        */

        // ensure there's something to log
        if ($message == null) {
            $message = '';
        }

        // Add some context to log the message.
        // Application name, Logger name, success or failure.
        $context = '';
        $logAppName = ESAPI::getSecurityConfiguration()->getLogApplicationName();
        if ($logAppName) {
            $context .= ESAPI::getSecurityConfiguration()->getApplicationName() . ' ';
        }
        $context .= $this->loggerName . ' ';
        if ($success === true) {
            $context .= 'Success: ';
        } else {
            $context .= 'Failure: ';
        }
        $message = $context . $message;

        // ensure no CRLF injection into logs for forging records
        // FIXME - fix this when we don't want pretty CodecDebug output - or maybe Log4PHP can do it on a layout basis...
        $clean = str_replace('\r', '_',str_replace( '\n', '_',$message ));

        if (ESAPI::getSecurityConfiguration()->getLogEncodingRequired() ) {
            $clean = ESAPI::getEncoder()->encodeForHTML($message);
            if ($message != $clean) {
                $clean .= ' (Encoded)';
            }
        }

        /* TODO Removed until AccessController is done.
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
        */
        // log the message
        // $this->logger->log($level, "[" . $userInfo . " -> " . $appInfo . "] " . $clean, $throwable);
        $this->logger->log($logLevel, $clean, $throwable);
    }
}
