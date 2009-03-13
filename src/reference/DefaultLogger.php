<?php
/**
 * OWASP Enterprise Security API (ESAPI)
 * 
 * This file is part of the Open Web Application Security Project (OWASP)
 * Enterprise Security API (ESAPI) project. For details, please see
 * <a href="http://www.owasp.org/index.php/ESAPI">http://www.owasp.org/index.php/ESAPI</a>.
 *
 * Copyright (c) 2007 - 2008 The OWASP Foundation
 * 
 * The ESAPI is published by OWASP under the BSD license. You should read and accept the
 * LICENSE before you use, modify, and/or redistribute this software.
 * 
 * @author 
 * @created 2008
 * @since 1.4
 * @package org.owasp.esapi.reference
 */

require_once('../src/Logger.php');

class DefaultLogger implements Logger {
	const SECURITY = 0;
	
    /**
     * Dynamically set the logging severity level. All events of this level and higher will be logged from 
     * this point forward for all logs. All events below this level will be discarded.
     * 
     * @param level The level to set the logging level to. 
     */
    function setLevel($level) 	{
			
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
    function fatal($type, $success, $message, $throwable = null)	{ 			 	}

    /**
     * Allows the caller to determine if messages logged at this level
     * will be discarded, to avoid performing expensive processing.
     * 
     * @return true if fatal level messages will be output to the log
     */
    function isFatalEnabled()	{ 			 	}


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
    function error($type, $success, $message, $throwable = null)	{ 			 	}

    /**
     * Allows the caller to determine if messages logged at this level
     * will be discarded, to avoid performing expensive processing.
     * 
     * @return true if error level messages will be output to the log
     */
    function isErrorEnabled()	{ 			 	}

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
    function warning($type, $success, $message, $throwable = null)	{ 			 	}

    /**
     * Allows the caller to determine if messages logged at this level
     * will be discarded, to avoid performing expensive processing.
     * 
     * @return true if warning level messages will be output to the log
     */
    function isWarningEnabled()	{ 			 	}

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
    function info($type, $success, $message, $throwable = null)	{ 			 	}

    /**
     * Allows the caller to determine if messages logged at this level
     * will be discarded, to avoid performing expensive processing.
     * 
     * @return true if info level messages will be output to the log
     */
    function isInfoEnabled()	{ 			 	}

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
    function debug($type, $success, $message, $throwable = null)	{ 			 	}

    /**
     * Allows the caller to determine if messages logged at this level
     * will be discarded, to avoid performing expensive processing.
     * 
     * @return true if debug level messages will be output to the log
     */
    function isDebugEnabled()	{ 			 	}

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
    function trace($type, $success, $message, $throwable = null)	{ 			 	}

    /**
     * Allows the caller to determine if messages logged at this level
     * will be discarded, to avoid performing expensive processing.
     * 
     * @return true if trace level messages will be output to the log
     */
    function isTraceEnabled()	{ }
}
?>