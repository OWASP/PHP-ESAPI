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
 * @package   ESAPI
 * @author    Jeff Williams <jeff.williams@aspectsecurity.com>
 * @author    Andrew van der Stock <vanderaj@owasp.org>
 * @author    Mike Boberski <boberski_michael@bah.com>
 * @copyright 2009-2010 The OWASP Foundation
 * @license   http://www.opensource.org/licenses/bsd-license.php New BSD license
 * @version   SVN: $Id$
 * @link      http://www.owasp.org/index.php/ESAPI
 */

/**
 * Implementations will require ExecutorException.
 */
require_once dirname(__FILE__) . '/errors/ExecutorException.php';

/**
 * The Executor interface is used to run an OS command with reduced security
 * risk.
 * 
 * Implementations should do as much as possible to minimize the risk of
 * injection into either the command or parameters. In addition, implementations
 * should timeout after a specified time period in order to help prevent denial
 * of service attacks.
 * 
 * The class should perform logging and error handling as well. Finally,
 * implementation should handle errors and generate an ExecutorException with
 * all the necessary information.
 *
 * <img src="doc-files/Executor.jpg">
 *
 * @category  OWASP
 * @package   ESAPI
 * @author    Jeff Williams <jeff.williams@aspectsecurity.com>
 * @author    Andrew van der Stock <vanderaj@owasp.org>
 * @author    Mike Boberski <boberski_michael@bah.com>
 * @author    Linden Darling <linden.darling@jds.net.au>
 * @author    Mike Boberski <boberski_michael@bah.com>
 * @copyright 2009-2010 The OWASP Foundation
 * @license   http://www.opensource.org/licenses/bsd-license.php New BSD license
 * @version   Release: @package_version@
 * @link      http://www.owasp.org/index.php/ESAPI
 */
interface Executor
{

    /**
     * Invokes the specified executable with default workdir and not logging 
     * parameters.
     * 
     * @param string $executable the command to execute
     * @param array  $params     the parameters of the command being executed
     * 
     * @return does not return a value.
     */
    function executeSystemCommand($executable, $params);

    /**
     * Executes a system command after checking that the executable exists and
     * escaping all the parameters to ensure that injection is impossible.
     * Implementations must change to the specified working
     * directory before invoking the command.
     *             
     * note: this is PHP's equivalent to ESAPI4JAVA's overloaded 
     * executeSystemCommand($executable, $params, $workdir, $codec, $logParams)
     * note: the codec argument has been eliminated from this implementation since 
     * PHP's escapeshellcmd function does enough to not require explicit OS codecs
     * 
     * @param string $executable the command to execute
     * @param array  $params     the parameters of the command being executed
     * @param string $workdir    the working directory
     * @param bool   $logParams  use false if any parameters contains sensitive or 
     *                           confidential information. (this is an ESAPI 2.0 
     *                           feature)
     * 
     * @return string the output of the command being run
     * @throws ExecutorException the service exception
     */
     function executeSystemCommandLonghand($executable, $params, $workdir, 
         $logParams
     );

}