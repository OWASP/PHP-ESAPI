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
 * @author    Jeff Williams <jeff.williams@owasp.org>
 * @copyright 2009-2010 The OWASP Foundation
 * @license   http://www.opensource.org/licenses/bsd-license.php New BSD license
 * @link      http://www.owasp.org/index.php/ESAPI
 */


/**
 * Implementations require IntrusionException.
 */
require_once dirname(__FILE__) . '/errors/IntrusionException.php';


/**
 * The IntrusionDetector interface is intended to track security relevant events
 * and identify attack behavior. The implementation can use as much state as
 * necessary to detect attacks, but note that storing too much state will burden
 * your system.
 *
 * <img src="doc-files/IntrusionDetector.jpg">
 *
 * The interface is currently designed to accept exceptions as well as custom
 * events. Implementations can use this stream of information to detect both
 * normal and abnormal behavior.
 *
 * PHP version 5.2.9
 *
 * @category  OWASP
 * @package   ESAPI
 * @version   1.0
 * @author    Jeff Williams <jeff.williams@owasp.org>
 * @copyright 2009-2010 The OWASP Foundation
 * @license   http://www.opensource.org/licenses/bsd-license.php New BSD license
 * @link      http://www.owasp.org/index.php/ESAPI
 */
interface IntrusionDetector {

    /**
     * Adds an exception to the IntrusionDetector.
     * 
     * This method should immediately log the exception so that developers
     * throwing an IntrusionException do not have to remember to log every
     * error.  The implementation should store the exception somewhere for the
     * current user in order to check if the User has reached the threshold for
     * any Enterprise Security Exceptions.  The User object is the recommended
     * location for storing the current user's security exceptions.  If the User
     * has reached any security thresholds, the appropriate security action can
     * be taken and logged.
     *
     * @param $exception string exception thrown.
     */
    function addException($exception);


    /**
     * Adds an event to the IntrusionDetector.
     * 
     * This method should immediately log the event.  The implementation should
     * store the event somewhere for the current user in order to check if the
     * User has reached the threshold for any Enterprise Security Exceptions.
     * The User object is the recommended location for storing the current
     * user's security event.  If the User has reached any security thresholds,
     * the appropriate security action can be taken and logged.
     *
     * @param $eventName string event to add.
     * @param $logMessage string message to log with the event.
     */
    function addEvent($eventName, $logMessage);


}
