<?php
/**
 * OWASP Enterprise Security API (ESAPI)
 *
 * This file is part of the Open Web Application Security Project (OWASP)
 * Enterprise Security API (ESAPI) project.
 *
 * PHP version 5.2
 *
 * LICENSE: This source file is subject to the New BSD license.  You should read
 * and accept the LICENSE before you use, modify, and/or redistribute this
 * software.
 *
 * @category  OWASP
 * @package   ESAPI_Errors
 * @author    Andrew van der Stock <vanderaj@owasp.org>
 * @author    jah <jah@jahboite.co.uk>
 * @copyright 2009-2010 The OWASP Foundation
 * @license   http://www.opensource.org/licenses/bsd-license.php New BSD license
 * @version   SVN: $Id$
 * @link      http://www.owasp.org/index.php/ESAPI
 */


/**
 * IntegrityException requires EnterpriseSecurityException.
 */
require_once dirname(__FILE__) . '/EnterpriseSecurityException.php';


/**
 * An IntegrityException should be thrown when the integrity of a resource
 * cannot be assured. For example, if Message Authentication Code does not have the
 * expected value for a given message.
 *
 * @category  OWASP
 * @package   ESAPI_Errors
 * @author    Andrew van der Stock <vanderaj@owasp.org>
 * @author    jah <jah@jahboite.co.uk>
 * @copyright 2009-2010 The OWASP Foundation
 * @license   http://www.opensource.org/licenses/bsd-license.php New BSD license
 * @version   Release: @package_version@
 * @link      http://www.owasp.org/index.php/ESAPI
 */
class IntegrityException extends EnterpriseSecurityException
{
    /**
     * Create a new IntegrityException
     *
     * @param string $userMessage A message to display to users.
     * @param string $logMessage  A message to be logged.
     * @param string $cause       The cause of the IntegrityException.
     *
     * @return null
     */
    public function __construct(
        $userMessage = '', $logMessage = '', $cause = 'Cause Not Supplied'
    ) {
        parent::__construct($userMessage, "{$logMessage} Caused by {$cause}");
    }
}
