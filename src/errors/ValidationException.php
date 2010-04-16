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
 * @author Andrew van der Stock <vanderaj .(at). owasp.org> 
 * @created 2008
 * @since 1.4
 * @package ESAPI_Errors
 */

require_once  dirname(__FILE__).'/EnterpriseSecurityException.php';

/**
 * A ValidationException should be thrown to indicate that the data provided by
 * the user or from some other external source does not match the validation
 * rules that have been specified for that data.
 */
class ValidationException extends EnterpriseSecurityException
{

    /** The UI reference that caused this ValidationException */
    private $context;

    /**
     * Instantiates a new ValidationException.
     * 
     * @param userMessage
     *            the message to display to users
     * @param logMessage
     * 			  the message logged
     * @param cause
     *            the cause
     * @param context
     *            the source that caused this exception
     */
    function __construct($userMessage = '', $logMessage = '', $context = '')
    {
        parent::__construct($userMessage, $logMessage);
        $this->setContext($context);
    }

    /**
     * Returns the UI reference that caused this ValidationException
     *  
     * @return context, the source that caused the exception, stored as a string
     */
    public function getContext()
    {
        return $this->context;
    }

    /**
     * Set's the UI reference that caused this ValidationException
     *  
     * @param context
     * 			the context to set, passed as a String
     */
    public function setContext($context)
    {
        $this->context = $context;
    }
}
?>