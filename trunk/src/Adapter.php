<?php
/**
 * OWASP Enterprise Security API (ESAPI)
 *
 * This file is part of the Open Web Application Security Project (OWASP)
 * Enterprise Security API (ESAPI) project.
 *
 * LICENSE: This source file is subject to the New BSD license.  You should read
 * and accept the LICENSE before you use, modify, and/or redistribute this
 * software.
 *
 * @category  OWASP
 * @package   ESAPI
 * @author    Mike Boberski <boberski_michael@bah.com>
 * @copyright 2009-2010 The OWASP Foundation
 * @license   http://www.opensource.org/licenses/bsd-license.php New BSD license
 * @link      http://www.owasp.org/index.php/ESAPI
 */


/**
 *
 */
require_once dirname(__FILE__) . '/errors/IntrusionException.php';
require_once dirname(__FILE__) . '/errors/ValidationException.php';


/**
 * The Adapter interface defines a set of functions as part of an extended
 * factory pattern implementation consisting of a new ESAPI security control
 * interface and corresponding implementation, which in turn calls ESAPI
 * security control reference implementations and/or security control reference
 * implementations that were replaced with your own implementations. The ESAPI
 * locator class would be called in order to retrieve a singleton instance of
 * your new security control, which in turn would call ESAPI security control
 * reference implementations and/or security control reference implementations
 * that were replaced with your own implementations.
 *
 * PHP version 5.2.9
 *
 * @category  OWASP
 * @package   ESAPI
 * @version   1.0
 * @author    Mike Boberski <boberski_michael@bah.com>
 * @copyright 2009-2010 The OWASP Foundation
 * @license   http://www.opensource.org/licenses/bsd-license.php New BSD license
 * @link      http://www.owasp.org/index.php/ESAPI
 */
interface Adapter {

    /**
     * Returns a valid employee ID as a string.
     * @param $eid Employee ID to validate.
     * @return A valid employee ID as a string.
     *
     */
    function getValidEmployeeID($eid);

    /**
     * Calls getValidEmployeeID and returns true if no exceptions are thrown.
     * @param $eid Employee ID to validate.
     * @return true, if employee ID is valid.
     */
    function isValidEmployeeID($eid);

}
