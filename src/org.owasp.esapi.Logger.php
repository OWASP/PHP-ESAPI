<?php
/**
 * OWASP Enterprise Security API (ESAPI)
 *
 * This file is part of the Open Web Application Security Project (OWASP)
 * Enterprise Security API (ESAPI) project. For details, please see
 * http://www.owasp.org/esapi.
 *
 * Copyright (c) 2007 - The OWASP Foundation
 *
 * The ESAPI is published by OWASP under the LGPL. You should read and accept the
 * LICENSE before you use, modify, and/or redistribute this software.
 *
 * @author Jeff Williams <a href="http://www.aspectsecurity.com">Aspect Security</a>
 * @package org.owasp.esapi;
 * @since 2007
 */

require_once ("interfaces/org.owasp.esapi.ILogger.php");

/**
 * Reference implementation of the ILogger interface. This implementation uses the Java logging package, and marks each
 * log message with the currently logged in user and the word "SECURITY" for security related events.
 *
 * @author Jeff Williams (jeff.williams .at. aspectsecurity.com) <a href="http://www.aspectsecurity.com">Aspect Security</a>
 * @since June 1, 2007
 * @see org.owasp.esapi.interfaces.ILogger
 */
class Logger implements ILogger {

    // FIXME: ENHANCE somehow make configurable so that successes and failures are logged according to a configuration.

// FIXME: ENHANCE Is this type approach right? Should it be configurable somehow?

	/** The SECURITY. */
	public $SECURITY = "SECURITY";

	/** The USABILITY. */
	public $USABILITY = "USABILITY";

	/** The PERFORMANCE. */
	public $PERFORMANCE = "PERFORMANCE";

    /** The jlogger. */
    private $jlogger = null;

    /** The application name. */
    private $applicationName = null;

    /** The module name. */
    private $moduleName = null;

    /**
     * Hide the constructor.
     *
     * @param applicationName the application name
     * @param moduleName the module name
     * @param jlogger the jlogger
     */
    private function Logger($applicationName, $moduleName, $jlogger) {
        $this->applicationName = $applicationName;
        $this->moduleName = $moduleName;
        $this->jlogger = $jlogger;
        // FIXME: AAA this causes some weird classloading problem, since SecurityConfiguration logs.
        // jlogger.setLevel(ESAPI.securityConfiguration().getLogLevel());
        $this->jlogger->setLevel( Level.ALL );
    }

    /**
     * Formats an HTTP request into a log suitable string. This implementation logs the remote host IP address (or
     * hostname if available), the request method (GET/POST), the URL, and all the querystring and form parameters. All
     * the paramaters are presented as though they were in the URL even if they were in a form. Any parameters that
     * match items in the parameterNamesToObfuscate are shown as eight asterisks.
     *
     * @see org.owasp.esapi.interfaces.ILogger#formatHttpRequestForLog(javax.servlet.http.HttpServletRequest)
     */
    public function logHTTPRequest($type, $request, $parameterNamesToObfuscate) {
        $params = "";

        $i = new ArrayObject($_REQUEST);
        $i = $i->getIterator();

        while ($i->valid()) {
            $key = $i->key();
            $value = $i->current();
            $params .= $key + "=";

            if (array_key_exists($key, $parameterNamesToObfuscate)) {
                $params .= "********";
            } else {
                $params .= $value;
            }

            if ($i->valid()) {
            	params.append("&");
            }
            $i->next();
        }
        $msg = request.getMethod() + " " + request.getRequestURL() + (params.length() > 0 ? "?" + params : "");
        logSuccess($type, $msg);
    }

    /**
     * Gets the logger.
     *
     * @param applicationName the application name
     * @param moduleName the module name
     * @return the logger
     */
    public static function getLogger($applicationName, $moduleName) {
        $jlogger = java.util.logging.Logger.getLogger(applicationName + ":" + moduleName);
        return new Logger(applicationName, moduleName, jlogger);
    }

    /*
     * (non-Javadoc)
     *
     * @see org.owasp.esapi.interfaces.ILogger#logTrace(short, java.lang.String, java.lang.String, java.lang.Throwable)
     */
    public function logTrace($type, $message, Throwable throwable) {
        log(Level.WARNING, type, message, throwable);
    }

    /*
     * (non-Javadoc)
     *
     * @see org.owasp.esapi.interfaces.ILogger#logTrace(java.lang.String, java.lang.String)
     */
    public function logTrace($type, $message) {
        log(Level.WARNING, type, message, null);
    }

    /*
     * (non-Javadoc)
     *
     * @see org.owasp.esapi.interfaces.ILogger#logDebug(short, java.lang.String, java.lang.String, java.lang.Throwable)
     */
    public function logDebug($type, $message, Throwable throwable) {
        log(Level.CONFIG, type, message, throwable);
    }

    /*
     * (non-Javadoc)
     *
     * @see org.owasp.esapi.interfaces.ILogger#logDebug(java.lang.String, java.lang.String)
     */
    public function logDebug($type, $message) {
        log(Level.CONFIG, type, message, null);
    }

    /*
     * (non-Javadoc)
     *
     * @see org.owasp.esapi.interfaces.ILogger#logError(short, java.lang.String, java.lang.String, java.lang.Throwable)
     */
    public function logError($type, $message, Throwable throwable) {
        log(Level.WARNING, type, message, throwable);
    }

    /*
     * (non-Javadoc)
     *
     * @see org.owasp.esapi.interfaces.ILogger#logError(java.lang.String, java.lang.String)
     */
    public function logError($type, $message) {
        log(Level.WARNING, type, message, null);
    }

    /*
     * (non-Javadoc)
     *
     * @see org.owasp.esapi.interfaces.ILogger#logSuccess(short, java.lang.String, java.lang.String,
     * java.lang.Throwable)
     */
    public function logSuccess($type, $message) {
        log(Level.INFO, type, message, null);
    }

    /*
     * (non-Javadoc)
     *
     * @see org.owasp.esapi.interfaces.ILogger#logSuccess(short, java.lang.String, java.lang.String,
     * java.lang.Throwable)
     */
    public function logSuccess($type, $message, Throwable throwable) {
        log(Level.INFO, type, message, throwable);
    }

    /*
     * (non-Javadoc)
     *
     * @see org.owasp.esapi.interfaces.ILogger#logWarning(short, java.lang.String, java.lang.String,
     * java.lang.Throwable)
     */
    public function logWarning($type, $message, Throwable throwable) {
        log(Level.WARNING, type, message, throwable);
    }

    /*
     * (non-Javadoc)
     *
     * @see org.owasp.esapi.interfaces.ILogger#logWarning(java.lang.String, java.lang.String)
     */
    public function logWarning($type, $message) {
        log(Level.WARNING, type, message, null);
    }

    /*
     * (non-Javadoc)
     *
     * @see org.owasp.esapi.interfaces.ILogger#logCritical(short, java.lang.String, java.lang.String,
     * java.lang.Throwable)
     */
    public function logCritical($type, $message, Throwable throwable) {
        log(Level.SEVERE, type, message, throwable);
    }

    /*
     * (non-Javadoc)
     *
     * @see org.owasp.esapi.interfaces.ILogger#logCritical(java.lang.String, java.lang.String)
     */
    public function logCritical($type, $message) {
        log(Level.SEVERE, type, message, null);
    }

    /**
     * Log the message after optionally encoding any special characters that might inject into an HTML based log viewer.
     *
     * @param message the message
     * @param level the level
     * @param type the type
     * @param throwable the throwable
     */
    private function log(Level level, $type, $message, Throwable throwable) {
        User user = ESAPI.authenticator().getCurrentUser();

        $clean = message;
        if ( ((SecurityConfiguration)ESAPI.securityConfiguration()).getLogEncodingRequired() ) {
        	clean = ESAPI.encoder().encodeForHTML(message);
            if (!message.equals(clean)) {
                clean += " (Encoded)";
            }
        }
        if ( throwable != null ) {
        	$fqn = throwable.getClass().getName();
        	int index = fqn.lastIndexOf('.');
        	if ( index > 0 ) fqn = fqn.substring(index + 1);
        	StackTraceElement ste = throwable.getStackTrace()[0];
        	clean += "\n    " + fqn + " @ " + ste.getClassName() + "." + ste.getMethodName() + "(" + ste.getFileName() + ":" + ste.getLineNumber() + ")";
        }
        $msg = "";
        if ( user != null ) {
        	msg = type + ": " + user.getAccountName() + "/" + user.getLastHostAddress() + " -- " + clean;
        }

        // FIXME: AAA need to configure Java logger not to show throwables
        // jlogger.logp(level, applicationName, moduleName, msg, throwable);
        jlogger.logp(level, applicationName, moduleName, msg);
    }

    /**
     * This special method doesn't include the current user's identity, and is only used during system initialization to
     * prevent loops with the Authenticator.
     *
     * @param level
     * @param message
     * @param throwable
     */
    // FIXME: this needs to go - note potential log injection problem
    public function void logSpecial($message, Throwable throwable) {
        // $clean = ESAPI.encoder().encodeForHTML(message);
        // if (!message.equals(clean)) {
        //     clean += "(Encoded)";
        // }
        $msg = "SECURITY" + ": " + "esapi" + "/" + "none" + " -- " + message;
        jlogger.logp(Level.WARNING, applicationName, moduleName, msg, throwable);
    }

}
?>