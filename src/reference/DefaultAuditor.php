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
 * The ESAPI is published by OWASP under the BSD license. You should read and
 * accept the LICENSE before you use, modify, and/or redistribute this software.
 *
 * @author  Andrew van der Stock
 * @since   2008
 * @since   1.6
 * @package org-owasp-esapi-reference
 */


/**
 *
 */
require_once dirname(__FILE__) .
    '/../../lib/apache-log4php/trunk/src/main/php/Logger.php';
require_once dirname(__FILE__).'/../Auditor.php';


/**
 * Reference Implementation of the ESAPILogger Interface.
 *
 * This implementation makes use of Apache's Log4PHP {@link
 * http://incubator.apache.org/log4php/index.html} and implements five of the
 * six requirements for logging {@see ESAPILogger}.
 *
 *
 * @author Andrew van der Stock
 * @author Laura D. Bell
 * @author jah (at jahboite.co.uk)
 * @since  1.6
 *
 */
class DefaultAuditor implements Auditor {

    /**
     * An instance of Apache Log4PHP.
     *
     * @var Logger
     */
    private $logger;
    private $loggerName;
    private $appName = null;
    private static $initialised = false;


    /**
     *
     * @param $name the identifying name of an instance of Log4PHP Logger.
     */
    function __construct($name)
    {
        if (self::$initialised == false) {
            self::initialise();
        }
        $this->logger = Logger::getLogger($name);
        $this->loggerName = $name;

        // set ApplicationName only if it is to be logged.
        $sc = ESAPI::getSecurityConfiguration();
        if ($sc->getLogApplicationName()) {
            $this->appName = $sc->getApplicationName();
        }
    }


    /**
     * {@inheritDoc}
     * Note: In this implementation, this change is not persistent, meaning that
     * if the application is restarted, the log level will revert to the level
     * defined in the ESAPI SecurityConfiguration properties file.
     *
     * @param $level the level to set - an Logger Level constant.
     */
    public function setLevel($level)
    {
        try
        {
            $this->logger->setLevel(
                $this->convertESAPILeveltoLoggerLevel($level)
            );
        }
        catch (Exception $e)
        {
            $this->error(
                Logger::SECURITY,
                false,
                'IllegalArgumentException',
                $e
            );
        }
    }


    /**
     * Log a fatal level security event if 'fatal' level logging is enabled and
     * also record the stack trace associated with the event.
     *
     * @param $type the type of event - an Logger Type constant.
     * @param $success boolean true indicates this was a successful event, false
     *        indicates this was a failed event (the typical value).
     * @param $message the message to log.
     * @param $throwable the exception to be logged.
     */
    function fatal($type, $success, $message, $throwable = null)
    {
        $this->log(Auditor::FATAL, $type, $success, $message, $throwable);
    }


    /**
     * Allows the caller to determine if messages logged at this level will be
     * discarded, to avoid performing expensive processing.
     *
     * @return true if fatal level messages will be output to the log.
     */
    function isFatalEnabled()
    {
        return $this->logger->isEnabledFor(LoggerLevel::getLevelFatal());
    }


    /**
     * Log an error level security event if 'error' level logging is enabled and
     * also record the stack trace associated with the event.
     *
     * @param $type the type of event - an Logger Type constant.
     * @param $success boolean true indicates this was a successful event, false
     *        indicates this was a failed event (the typical value).
     * @param $message the message to log.
     * @param $throwable the exception to be logged.
     */
    function error($type, $success, $message, $throwable = null)
    {
        $this->log(Auditor::ERROR, $type, $success, $message, $throwable);
    }


    /**
     * Allows the caller to determine if messages logged at this level will be
     * discarded, to avoid performing expensive processing.
     *
     * @return true if error level messages will be output to the log.
     */
    function isErrorEnabled()
    {
        return $this->logger->isEnabledFor(LoggerLevel::getLevelError());
    }


    /**
     * Log a warning level security event if 'warning' level logging is enabled and
     * also record the stack trace associated with the event.
     *
     * @param $type the type of event - an Logger Type constant.
     * @param $success boolean true indicates this was a successful event, false
     *        indicates this was a failed event (the typical value).
     * @param $message the message to log.
     * @param $throwable the exception to be logged.
     */
    function warning($type, $success, $message, $throwable = null)
    {
        $this->log(Auditor::WARNING, $type, $success, $message, $throwable);
    }


    /**
     * Allows the caller to determine if messages logged at this level will be
     * discarded, to avoid performing expensive processing.
     *
     * @return true if warning level messages will be output to the log.
     */
    function isWarningEnabled()
    {
        return $this->logger->isEnabledFor(LoggerLevel::getLevelWarn());
    }


    /**
     * Log an info level security event if 'info' level logging is enabled and
     * also record the stack trace associated with the event.
     *
     * @param $type the type of event - an Logger Type constant.
     * @param $success boolean true indicates this was a successful event, false
     *        indicates this was a failed event (the typical value).
     * @param $message the message to log.
     * @param $throwable the exception to be logged.
     */
    function info($type, $success, $message, $throwable = null)
    {
        $this->log(Auditor::INFO, $type, $success, $message, $throwable);
    }


    /**
     * Allows the caller to determine if messages logged at this level will be
     * discarded, to avoid performing expensive processing.
     *
     * @return true if info level messages will be output to the log.
     */
    function isInfoEnabled()
    {
        return $this->logger->isEnabledFor(LoggerLevel::getLevelInfo());
    }


    /**
     * Log a debug level security event if 'debug' level logging is enabled and
     * also record the stack trace associated with the event.
     *
     * @param $type the type of event - an Logger Type constant.
     * @param $success boolean true indicates this was a successful event, false
     *        indicates this was a failed event (the typical value).
     * @param $message the message to log.
     * @param $throwable the exception to be logged.
     */
    function debug($type, $success, $message, $throwable = null)
    {
        $this->log(Auditor::DEBUG,$type, $success, $message, $throwable);
    }


    /**
     * Allows the caller to determine if messages logged at this level will be
     * discarded, to avoid performing expensive processing.
     *
     * @return true if debug level messages will be output to the log.
     */
    function isDebugEnabled()
    {
        return $this->logger->isEnabledFor(LoggerLevel::getLevelDebug());
    }


    /**
     * Log a trace level security event if 'trace' level logging is enabled and
     * also record the stack trace associated with the event.
     *
     * @param $type the type of event - an Logger Type constant.
     * @param $success boolean true indicates this was a successful event, false
     *        indicates this was a failed event (the typical value).
     * @param $message the message to log.
     * @param $throwable the exception to be logged.
     */
    function trace($type, $success, $message, $throwable = null)
    {
        $this->log(Auditor::TRACE, $type, $success, $message, $throwable);
    }


    /**
     * Allows the caller to determine if messages logged at this level will be
     * discarded, to avoid performing expensive processing.
     *
     * @return true if trace level messages will be output to the log.
     */
    function isTraceEnabled()
    {
        return $this->logger->isEnabledFor(LoggerLevel::getLevelAll());
    }


    /**
     * Log the supplied event.
     *
     * If the supplied logging level is at or above the current logging
     * threshold then log the message after optionally encoding any special
     * characters that might be dangerous when viewed by an HTML based log
     * viewer. Also encode any carriage returns and line feeds to prevent log
     * injection attacks. This logs all the supplied parameters: level, event
     * type, whether the event represents success or failure and the log
     * message. In addition, the application name, logger name/category, local
     * IP address and port, the identity of the user and their source IP
     * address, a logging specific user session ID, and the current date/time
     * are also logged.
     * If the supplied logging level is below the current logging threshold then
     * the message will be discarded.
     *
     * @param $level the priority level of the event - an Logger Level
     *        constant.
     * @param $type the type of the event - an Logger Event constant.
     * @param $success boolean true indicates this was a successful event, false
     *        indicates this was a failed event (the typical value).
     * @param $message the message to be logged.
     * @param $throwable the throwable Exception.
     */
    private function log($level, $type, $success, $message, $throwable)
    {
        // If this log level is below the threshold we can quit now.
        $logLevel = self::convertESAPILeveltoLoggerLevel($level);
        if (! $this->logger->isEnabledFor($logLevel)) {
            return;
        }

        $encoder   = ESAPI::getEncoder();
        $secConfig = ESAPI::getSecurityConfiguration();

        // Add some context to log the message.
        $context = '';

        // The output of log level is handled here instead of providing a
        // LayoutPattern to Log4PHP.  This allows us to print TRACE instead of
        // ALL and WARNING instead of WARN.
        $levelStr = $logLevel->toString();
        if ($levelStr == 'ALL') {
            $levelStr = 'TRACE';
        } else if ($levelStr == 'WARN') {
            $levelStr = 'WARNING';
        }
        $context .= $levelStr;

        // Application name.
        // $this->appName is set only if it is to be logged.
        if ($this->appName !== null) {
            $context .= $this->appName;
        }

        // Logger name (Category in Log4PHP parlance)
        $context .= ' ' . $this->loggerName;

        // Event Type
        if (! is_string($type)) {
            $type = 'EVENT_UNKNOWN';
        }
        $context .= ' ' . $type;

        // Success or Failure of Event
        if ($success === true) {
            $context .= '-SUCCESS';
        } else {
            $context .= '-FAILURE';
        }

        // Local IP:PortNumber, Generated Session ID and Remote User ID and Host
        // Note that until getCurrentRequest is implemented and I can determine
        // whether it's possible to return a request object whilst, for example,
        // testing ESAPI via the command line I shall be checking for a null
        // request.
        $request = null; // TODO ESAPI::getHttpUtilities()->getCurrentRequest();
        if ($request != null)
        {
            $context .=
                ' ' .
                $request->getLocalAddr() .
                ':' .
                $request->getLocalPort();

            // username and remote address
            $context .=
                ' ' .
                $request->getRemoteUser() .
                '@' .
                $request->getRemoteAddr() .
                ':';

            // create a random session number for the user to represent the
            // user's session, if it doesn't exist already
            $userSessionIDforLogging = 'SessionUnknown';
            try
            {
                $session = $request->getSession(false);
                $userSessionIDforLogging = $session->getAttribute('ESAPI_SESSION');
                // if there is no session ID for the user yet, we create one and store it in the user's session
                if ( $userSessionIDforLogging == null ) {
                    $userSessionIDforLogging = '' . ESAPI::getRandomizer()->getRandomInteger(0, 1000000);
                    $session->setAttribute('ESAPI_SESSION', $userSessionIDforLogging);
                }
            }
            catch( Exception $e )
            {
                // continue
            }
            $context .= "[ID:{$userSessionIDforLogging}]";
        }

        // Now comes the message.
        if (! is_string($message)) {
            $message = '';
        }

        // Encode CRLF - this bit might have to go in a try block
        $crlfEncoded = $this->replaceCRLF($message, null /* '_' FIXME when we don't want pretty CodecDebug output */);

        // Encode for HTML if ESAPI.xml says so
        $encodedMessage = null;
        if ($secConfig->getLogEncodingRequired() )
        {
            try
            {
                $encodedMessage = $encoder->encodeForHTML($crlfEncoded);
                if ($encodedMessage !== $crlfEncoded) {
                    $encodedMessage .= ' (This log message was encoded for HTML)';
                }
            }
            catch (Exception $e)
            {
                $exType = get_type($e);
                $encodedMessage = "The supplied log message generated an Exception of type {$exType} and was not included";
            }
        }
        else
        {
            $encodedMessage = $crlfEncoded;
        }

        // Now handle the exception
        $dumpedException = '';
        if ($throwable !== null && $throwable instanceof Exception)
        {
            $dumpedException = ' ' . $this->replaceCRLF($throwable, ' | ');
        }

        $messageForLog = $context . ' ' . $encodedMessage . $dumpedException;

        $this->logger->log($logLevel, $messageForLog, $this);
    }


    /**
     * Helper method to replace carriage return and line feed characters in the
     * supplied message with the supplied substitute character(s). The sequence
     * CRLF (\r\n) is treated as one character.
     *
     * @param $message string message to process.
     * @param $substitute string replacement for CR, LF or CRLF.
     *
     * @return string message with characters replaced.
     */
    private function replaceCRLF($message, $substitute)
    {
        if ($message === null || $substitute === null) {
            return $message;
        }
        $detectedEncoding = Codec::detectEncoding($message);
        $len = mb_strlen($message, $detectedEncoding);
        $crlfEncoded = '';
        $nextChar = null;
        $index = 0;
        for ($i = 0; $i < $len; $i++) {
            if ($i < $index) {
                continue;
            }
            if ($nextChar === null) {
                $thisChar = mb_substr($message, $i, 1, $detectedEncoding);
            } else {
                $thisChar = $nextChar;
            }
            if ($i+1 < $len) {
                $nextChar = mb_substr($message, $i+1, 1, $detectedEncoding);
            } else {
                $nextChar = null;
            }
            if ($thisChar == "\r" && $nextChar == "\n") {
                $index = $i+2;
                $nextChar = null;
                $crlfEncoded .= $substitute;
            } else if ($thisChar == "\r" || $thisChar == "\n") {
                $crlfEncoded .= $substitute;
            } else {
                $crlfEncoded .= $thisChar;
            }
        }
        return $crlfEncoded;
    }

    /**
     * Converts a logging level.
     *
     * Converts the ESAPI logging level (a number) or level defined in the ESAPI
     * properties file (a string) into the levels used by Apache's log4php. Note
     * that log4php does not define a TRACE level and so TRACE is simply an
     * alias of ALL which log4php does define.
     *
     * @param level The logging level to convert.
     *
     * @return The log4php logging Level equivalent.
     *
     * @throws Exception if the supplied level doesn't match a level currently
     *         defined.
     */
    private static function convertESAPILeveltoLoggerLevel($level)
    {
        if (is_string($level))
        {
            switch (strtoupper($level))
            {
                case 'ALL':
                    /* Same as TRACE */
                case 'TRACE':
                    return LoggerLevel::getLevelAll();
                case 'DEBUG':
                     return LoggerLevel::getLevelDebug();
                case 'INFO':
                    return LoggerLevel::getLevelInfo();
                case 'WARN':
                    return LoggerLevel::getLevelWarn();
                case 'ERROR':
                    return LoggerLevel::getLevelError();
                case 'FATAL':
                    return LoggerLevel::getLevelFatal();
                case 'OFF':
                     return LoggerLevel::getLevelOff();
                default: {
                    throw new Exception(
                        "Invalid logging level Value was: {$level}"
                    );
                }
            }
        }
        else
        {
            switch ($level)
            {
                case Auditor::ALL:
                    /* Same as TRACE */
                case Auditor::TRACE:
                    return LoggerLevel::getLevelAll();
                case Auditor::DEBUG:
                    return LoggerLevel::getLevelDebug();
                case Auditor::INFO:
                    return LoggerLevel::getLevelInfo();
                case Auditor::WARNING:
                    return LoggerLevel::getLevelWarn();
                case Auditor::ERROR:
                    return LoggerLevel::getLevelError();
                case Auditor::FATAL:
                    return LoggerLevel::getLevelFatal();
                case Auditor::OFF:
                    return LoggerLevel::getLevelOff();
                default: {
                    throw new Exception(
                        "Invalid logging level Value was: {$level}"
                    );
                }
            }
        }
    }


    /**
     *  Configures Apache's Log4PHP RootLogger based on values obtained from the
     *  ESAPI properties file.  All instances of Log4PHP Logger will inherit the
     *  configuration.
     */
    private static function initialise()
    {
        self::$initialised = true;

        $secConfig = ESAPI::getSecurityConfiguration();
        $logLevel = $secConfig->getLogLevel();

        // Patterns representing the format of Log entries
        // d date, p priority (level), m message, n newline
        $dateFormat = $secConfig->getLogFileDateFormat();
        $logfileLayoutPattern = "%d{{$dateFormat}} %m %n";
        $consoleLayoutPattern = "%d{{$dateFormat}} %m <br />%n";

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
        if($logLevel !== 'OFF') {
            $appenderLogfile->activateOptions();
        }
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
}
