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
 * @author Jeff Williams <a href="http://www.aspectsecurity.com">Aspect Security</a>
 * @since  2008
 * @since  1.6
 * @package org-owasp-esapi-reference
 */


/**
 * 
 */
require_once dirname(__FILE__).'/../AuditorFactory.php';
require_once dirname(__FILE__).'/DefaultAuditor.php';


/**
 * Reference implementation of the ESAPILogFactory interface.
 *
 * This implementation maintains an associative array of DefaultLogger instances
 * which may retrieved by name.  Instances of DefaultLogger are created only if
 * the name does not exist as a key in the array.
 *
 * @author Laura D. Bell
 * @since  1.6
 */
class DefaultAuditorFactory implements AuditorFactory {

    private $loggerMap = array();


    /**
     * Null argument constructor for this implementation of the LogFactory
     * interface needed for dynamic configuration.
     */
    function __construct()
    {
        // NoOp
    }


    /**
     * {@inheritDoc}
     */
    public function getLogger($moduleName) {

        // If a logger for this module already exists, we return the same one,
        // otherwise we create a new one.
        if (   array_key_exists($moduleName, $this->loggerMap)
            && $this->loggerMap[$moduleName] instanceof DefaultAuditor
        ) {
            return $this->loggerMap[$moduleName];
        } else {
            $moduleLogger = new DefaultAuditor($moduleName);
            $this->loggerMap[$moduleName] = $moduleLogger;
            return $moduleLogger;
        }
    }
}
