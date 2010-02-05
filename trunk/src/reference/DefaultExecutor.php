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

require_once  dirname(__FILE__).'/../Executor.php';

class DefaultExecutor implements Executor {
		
	// Logger
	
	private $ApplicationName = null;
	private $LogEncodingRequired = null;
	private $LogLevel = null;
	private $LogFileName = null;
	private $MaxLogFileSize = null;
	
	function __construct()
	{
 	}

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
     * 
     * @throws ExecutorException
     *             the service exception
     */    
	function executeSystemCommand($executable, $params) {
    	$config = ESAPI::getSecurityConfiguration();
    	$workdir = $config->getWorkingDirectory();
    	$logParams = false;

    	try {
    		
	        // executable must exist
	        $resolved = $executable;
			if(substr(PHP_OS, 0, 3) == 'WIN') {
	        	$exploded = explode("%", $executable);
	        	$systemroot = getenv($exploded[1]);
	        	$resolved = $systemroot . $exploded[2];
			}
            if (!file_exists($resolved)) {
                throw new ExecutorException("Execution failure, No such executable: $executable");
            }
            
            // executable must use canonical path
            if (!strcmp($resolved, realpath($resolved))) {
                throw new ExecutorException("Execution failure, Attempt to invoke an executable using a non-absolute path: $executable");
            }            
                     		
            // exact, absolute, canonical path to executable must be listed in ESAPI configuration 
            $approved = $config->getAllowedExecutables();
            if(!in_array($executable, $approved)){
                throw new ExecutorException("Execution failure, Attempt to invoke executable that is not listed as an approved executable in ESAPI configuration: " . $executable . " not listed in " + $approved);
            }            

            // escape any special characters in the parameters
            for ($i = 0; $i < count($params); $i++){
            	$params[$i] = escapeshellcmd($params[$i]);  
            }
           
            // working directory must exist
	        $resolved_workdir = $workdir;
			if(substr(PHP_OS, 0, 3) == 'WIN') {
	        	$exploded = explode("%", $workdir);
	        	$systemroot = getenv($exploded[1]);
	        	$resolved_workdir = $systemroot . $exploded[2];
			}
             if(!file_exists($resolved_workdir)){
                throw new ExecutorException("Execution failure, No such working directory for running executable: $workdir");
            }
 
            // run the command
            $paramstr = "";
            foreach($params as $param) {
            	$paramstr = $paramstr." ".$param;
            }
            $output = `$executable $paramstr`;
            return $output;
    	}
    	catch ( Exception $e ) {
        	$this->logSpecial($e->getMessage());
        }
	
	}	
	
}
?>