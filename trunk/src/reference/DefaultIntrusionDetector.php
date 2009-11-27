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
 * @author jah (at jahboite.co.uk)
 * @created 2009
 * @since 1.4
 * @package org.owasp.esapi.reference
 */

require_once dirname(__FILE__).'/../IntrusionDetector.php';

class DefaultIntrusionDetector implements IntrusionDetector {
	
    private $logger     = null;
    private $userEvents = null;
    
    function __construct()
    {
    	$this->logger = ESAPI::getLogger('IntrusionDetector');
    	$this->userEvents = array();
    }
    
    /**
     * Adds the exception to the IntrusionDetector.  This method should immediately log the exception so that developers throwing an 
     * IntrusionException do not have to remember to log every error.  The implementation should store the exception somewhere for the current user
     * in order to check if the User has reached the threshold for any Enterprise Security Exceptions.  The User object is the recommended location for storing
     * the current user's security exceptions.  If the User has reached any security thresholds, the appropriate security action can be taken and logged.
     * 
     * @param exception 
     * 		the exception thrown
     * 
     * @throws IntrusionException 
     * 		the intrusion exception
     */
    public function addException($exception)
    {
    	if (is_a($exception, 'EnterpriseSecurityException'))
    	{
    		$this->logger->warning( DefaultLogger::SECURITY, false, $exception->getLogMessage(), $exception );
    	}
    	else
    	{
    		$this->logger->warning( DefaultLogger::SECURITY, false, $exception->getMessage(), $exception );
    	}
    	
    	 // FIXME: when getCurrentUser() is implemented, remove next 1 line which prevents infinite loop of addException() calls!
    	while (1) return;
    	    	
    	// add the exception to the current user, which may trigger a detector 
		$user = ESAPI::getAuthenticator()->getCurrentUser();
    	$eventName = get_class($exception);
    	
    	if (is_a($exception, 'IntrusionException'))
        {
        	return;
        }
        
        // add the exception to the user's store, handle IntrusionException if thrown
        try
        {
			$this->addSecurityEvent($user, $eventName);
		}
		catch (IntrusionException $intrusionException)
		{
			$quota = ESAPI::getSecurityConfiguration()->getQuota($eventName);
            $actionsList = '[' . implode(', ', $quota->actions) . ']';
            foreach ($quota->actions as $action)
            {
            	$message = 'User exceeded quota of ' . $quota->count . ' per ' . $quota->interval .
            	 			' seconds for event ' . $eventName . '. Taking actions ' . $actionsList;
            	$this->takeSecurityAction($action, $message);
            }
		}
        
        
    }

    /**
     * Adds the event to the IntrusionDetector.  This method should immediately log the event.  The implementation should store the event somewhere for the current user
     * in order to check if the User has reached the threshold for any Enterprise Security Exceptions.  The User object is the recommended location for storing
     * the current user's security event.  If the User has reached any security thresholds, the appropriate security action can be taken and logged.
     * 
     * @param eventName 
     * 		the event to add
     * @param logMessage 
     * 		the message to log with the event
     * 
     * @throws IntrusionException 
     * 		the intrusion exception
     */
    public function addEvent($eventName, $logMessage)
    {
    	$this->logger->warning( DefaultLogger::SECURITY, false, 'Security event ' . $eventName . ' received : ' . $logMessage);
    	
    	// add the event to the current user, which may trigger a detector
    	$user = ESAPI::getAuthenticator()->getCurrentUser();
    	try
    	{
    		$this->addSecurityEvent($user, $eventName);
    	}
    	catch (IntrusionException $intrusionException)
    	{
    		$quota = ESAPI::getSecurityConfiguration()->getQuota($eventName);
    		$actionsList = '[' . implode(', ', $quota->actions) . ']';
    		foreach ($quota->actions as $action)
    		{
    			$message = 'User exceeded quota of ' . $quota->count . ' per ' . $quota->interval .
            	 			' seconds for event ' . $eventName . '. Taking actions ' . $actionsList;
    			$this->takeSecurityAction($action, $message);
    		}
    	}
    }
	
	/**
     * Take a specified security action.  In this implementation, acceptable
     * actions are: log, disable, logout.
     * 
     * @param action
     * 		the action to take (log, disable, logout)
     * @param message
     * 		the message to log if the action is "log"
     */
    private function takeSecurityAction($action, $message)
	{
		if ($action === 'log' )
		{
			$this->logger->fatal(DefaultLogger::SECURITY, false, 'INTRUSION - ' . $message);
		}
		$user = ESAPI::getAuthenticator()->getCurrentUser();
    	    	
		if ($user === DefaultUser::ANONYMOUS )
		{
			return;
		}
		if ($action === 'disable' )
		{
			$user->disable();
		}
		if ($action === 'logout' )
		{
			$user->logout();
		}
	}
    
 	 /**
	 * Adds a security event to the user.  These events are used to check that the user has not
	 * reached the security thresholds set in the properties file.
	 * 
	 * @param user
	 * 			The user that caused the event.
	 * @param eventName
	 * 			The name of the event that occurred.
	 */
	private function addSecurityEvent($user, $eventName)
	{
		$events = null;
		if (array_key_exists($user->getAccountName(), $this->userEvents))
		{
			$events = $this->userEvents[$user->getAccountName()];
		}
		if (get_type($events) != 'array' )
		{
			$events = array();
			$this->userEvents[$user->getAccountName()] = $events;
		}
		$event = null;
		if (array_key_exists($events, $eventName))
		{
			$event = $events[$eventName];
		}
		if (!is_a($event, 'Event'))
		{
			$event = new Event($eventName);
			$events[$eventName] = $event;
		}
		
		$q = ESAPI::getSecurityConfiguration()->getQuota($eventName);
		if ($q->count > 0)
		{
			$event->increment($q->count, $q->interval);
		}
	}	
}
?>