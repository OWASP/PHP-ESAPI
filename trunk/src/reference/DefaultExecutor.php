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
 * @category  OWASP
 * @package   ESAPI
 * @version   1.0 
 * @author    Mike Boberski <boberski_michael@bah.com> 
 * @author    Linden Darling <linden.darling@jds.net.au>
 *  
 */

require_once  dirname(__FILE__).'/../Executor.php';

class DefaultExecutor implements Executor {
		
	// Logger
	private $logger = null;
	private $ApplicationName = null;
	private $LogEncodingRequired = null;
	private $LogLevel = null;
	private $LogFileName = null;
	private $MaxLogFileSize = null;
	
	//SecurityConfiguration
	private $config = null;
	
	function __construct()
	{
		$this->logger = ESAPI::getLogger('Executor');
		$this->config = ESAPI::getSecurityConfiguration();
 	}

    /**
         * Invokes the specified executable with default workdir and not logging parameters.
         * 
         * @param executable
         *            the command to execute
         * @param params
         *            the parameters of the command being executed
         */
	function executeSystemCommand($executable, $params)
	{
		$workdir = $this->config->getWorkingDirectory();
    	$logParams = false;
    	return $this->executeSystemCommand_effectLonghand($executable, $params, $workdir, $logParams);
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
         * @param workdir
         *            the working directory
         * @param codec
         *            the codec to use to encode for the particular OS in use
         * @param logParams
         *            use false if any parameters contains sensitive or confidential information.
         *            (this is an ESAPI 2.0 feature)
         * 
         * @return the output of the command being run
         * 
         * @throws ExecutorException
         *             the service exception
         *             
         * note: this is PHP's equivalent to ESAPI4JAVA's overloaded executeSystemCommand($executable, $params, $workdir, $codec, $logParams)
         * note: the codec argument has been eliminated from this implementation since PHP's escapeshellcmd function does enough to not require explicit OS codecs
         */
	function executeSystemCommandLonghand($executable, $params, $workdir, $logParams)
	{
    	try {
    		
	        // executable must exist
	        $resolved = $executable;
			if(substr(PHP_OS, 0, 3) == 'WIN')
			{
	        	$exploded = explode("%", $executable);
	        	$systemroot = getenv($exploded[1]);
	        	$resolved = $systemroot . $exploded[2];
			}
            if (!file_exists($resolved))
            {
                throw new ExecutorException("Execution failure, No such executable: $executable");
            }
            
            // executable must use canonical path
            if (!strcmp($resolved, realpath($resolved)))
            {
                throw new ExecutorException("Execution failure, Attempt to invoke an executable using a non-absolute path: $executable");
            }            
                     		
            // exact, absolute, canonical path to executable must be listed in ESAPI configuration 
            $approved = $this->config->getAllowedExecutables();
            if(!in_array($executable, $approved))
            {
                throw new ExecutorException("Execution failure, Attempt to invoke executable that is not listed as an approved executable in ESAPI configuration: " . $executable . " not listed in " . $approved);
            }            

            // escape any special characters in the parameters
            for ($i = 0; $i < count($params); $i++)
            {
            	$params[$i] = escapeshellcmd($params[$i]);  
            }
           
            // working directory must exist
	        $resolved_workdir = $workdir;
			if(substr(PHP_OS, 0, 3) == 'WIN')
			{
				if(substr_count($workdir,'%')>=2)
				{
					//only explode on % if at least 2x % chars exist in string
		        	$exploded = explode("%", $workdir);
		        	$systemroot = getenv($exploded[1]);
		        	$resolved_workdir = $systemroot . $exploded[2];
				}
			}
            if(!file_exists($resolved_workdir))
            {
                throw new ExecutorException("Execution failure, No such working directory for running executable: $workdir");
            }
 
            // run the command
            $paramstr = "";
            foreach($params as $param)
            {
            	$paramstr .= " ".$param;	//note: will yeild a paramstr with a leading whitespace
            }
            $output = $executable.$paramstr;	//note: no whitespace between $executable and $paramstr since $paramstr already has a leading whitespace
            return $output;
    	}
    	catch ( Exception $e )
    	{
        	$this->logger->warning(DefaultLogger::SECURITY, true, $e->getMessage());
        }
	
	}	
	
}
?>