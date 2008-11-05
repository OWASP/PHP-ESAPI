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
 * @package org.owasp.esapi
 */

require_once ("codecs/Codec.php");
require_once ("errors/ExecutorException.php");

/**
 * The Executor interface is used to run an OS command with reduced security risk.
 * 
 * <p>Implementations should do as much as possible to minimize the risk of
 * injection into either the command or parameters. In addition, implementations
 * should timeout after a specified time period in order to help prevent denial
 * of service attacks.</p> 
 * 
 * <p>The class should perform logging and error handling as
 * well. Finally, implementation should handle errors and generate an
 * ExecutorException with all the necessary information.</p>
 * <br />
 * <img src="doc-files/Executor.jpg">
 * <br />
 * 
 * <p>The reference implementation does all of the above.</p>
 * 
 * @author 
 * @since 1.4
 */
interface Executor
{

    /**
     * Executes a system command after checking that the executable exists and
     * escaping all the parameters to ensure that injection is impossible.
     * Implementations must change to the specified working
     * directory before invoking the command.
     * 
     * @param executable
     *            the command to execute
     * @param params
     *            the parameters of the command being executed
     * @param workdir
     *            the working directory
     * @param codec
     *            the codec to use to encode for the particular OS in use
     * 
     * @return the output of the command being run
     * 
     * @throws ExecutorException
     *             the service exception
     */
    function executeSystemCommand($executable, $params, $workdir, $codec);

}