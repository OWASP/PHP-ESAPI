<?php
/**
 * OWASP Enterprise Security API (ESAPI)
 *
 * This file is part of the Open Web Application Security Project (OWASP)
 * Enterprise Security API (ESAPI) project. For details, please see
 * <a href="http://www.owasp.org/index.php/ESAPI">http://www.owasp.org/index.php/ESAPI</a>.
 *
 * Copyright (c) 2007 - The OWASP Foundation
 *
 * The ESAPI is published by OWASP under the BSD license. You should read and
 * accept the LICENSE before you use, modify, and/or redistribute this software.
 *
 * @author Rogan Dawes <a href="http://www.aspectsecurity.com">Aspect Security</a>
 * @since  1.6
 * @since  2008
 * @package org-owasp-esapi
 */


/**
 * The LogFactory interface is intended to allow substitution of various logging
 * packages, while providing a common interface to access them.
 *
 * @author Rogan Dawes <a href="http://www.aspectsecurity.com">Aspect Security</a>
 * @author Laura D. Bell
 * @since  1.6
 */
interface LogFactory {

    /**
     * Gets the logger associated with the specified module name. The module
     * name is used by the logger to log which module is generating the log
     * events. The implementation of this method should return any
     * preexisting Logger associated with this module name, rather than
     * creating a new Logger.
     *
     * @param $moduleName The name of the module requesting the logger.
     *
     * @return The DefaultLogger associated with this module.
     */
    function getLogger($moduleName);

}
