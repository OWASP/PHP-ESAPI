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
 * @author Andrew van der Stock <a href="http://www.aspectsecurity.com">Aspect Security</a>
 * @package org.owasp.esapi.interfaces;
 * @since 2008
 */

/**
 * The ILogger interface defines a set of methods that can be used to log
 * security events. Implementors should use a well established logging library
 * as it is quite difficult to create a high-performance logger.
 * <P>
 * <img src="doc-files/Logger.jpg" height="600">
 * <P>
 *
 * @author Jeff Williams (jeff.williams .at. aspectsecurity.com) <a
 * href="http://www.aspectsecurity.com">Aspect Security</a>
 * @since June 1, 2007
 */
interface ILogger
{
    /**
     * Format the Source IP address, URL, URL parameters, and all form
     * parameters into a string for the log file. The list of parameters to
     * obfuscate should be specified in order to prevent sensitive informatiton
     * from being logged. If a null list is provided, then all parameters will
     * be logged.
     *
     * @param type the type
     * @param request the request
     * @param sensitiveParams the sensitive params
     */
    public function logHTTPRequest($type, $request, $parameterNamesToObfuscate);

    /**
     * Log critical.
     *
     * @param type the type
     * @param message the message
     */
    public function logCritical($type, $message, $throwable = null);

    /**
     * Log debug.
     *
     * @param type the type
     * @param message the message
     * @param throwable the throwable
     */
    public function logDebug($type, $message, $throwable = null);

    /**
     * Log error.
     *
     * @param type the type
     * @param message the message
     * @param throwable the throwable
     */
    public function logError($type, $message, $throwable = null);

    /**
     * Log success.
     *
     * @param type the type
     * @param message the message
     * @param throwable the throwable
     */
    public function logSuccess($type, $message, $throwable = null);

    /**
     * Log trace.
     *
     * @param type the type
     * @param message the message
     * @param throwable the throwable
     */
    public function logTrace($type, $message, $throwable = null);

    /**
     * Log warning.
     *
     * @param type the type
     * @param message the message
     * @param throwable the throwable
     */
    public function logWarning($type, $message, $throwable = null);
}
?>