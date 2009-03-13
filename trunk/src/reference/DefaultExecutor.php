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

require_once  dirname(__FILE__).'/../Executor.php';

class DefaultExecutor implements Executor {
	
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
    function executeSystemCommand($executable, $params, $workdir, $codec) {
    	throw new EnterpriseSecurityException("Method Not implemented");
    }
	
	
}
?>