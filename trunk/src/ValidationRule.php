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
 * @author    Johannes B. Ullrich <jullrich@sans.edu>
 * @author    Mike Boberski <boberski_michael@bah.com>
 * @copyright 2009-2010 The OWASP Foundation
 * @license   http://www.opensource.org/licenses/bsd-license.php New BSD license
 * @link      http://www.owasp.org/index.php/ESAPI
 */


/**
 * Implementations require ValidationException and IntrusionException.
 */
require_once dirname(__FILE__) . '/errors/IntrusionException.php';
require_once dirname(__FILE__) . '/errors/ValidationException.php';


/**
 * ValidationRule Interface.
 *
 * PHP version 5.2.9
 *
 * @category  OWASP
 * @package   ESAPI
 * @version   1.0
 * @author    Johannes B. Ullrich <jullrich@sans.edu>
 * @author    Mike Boberski <boberski_michael@bah.com>
 * @copyright 2009-2010 The OWASP Foundation
 * @license   http://www.opensource.org/licenses/bsd-license.php New BSD license
 * @link      http://www.owasp.org/index.php/ESAPI
 */
interface ValidationRule {
	public function getAllowNull();
	public function setTypeName($typeName);
	public function setEncoder($encoder);
	public function assertValid($context,$input);
	public function getValid($context,$input, $errorList=null);
	public function getSafe($context,$input);
	public function isValid($context,$input);
	public function whitelist($input,$list=null);
}
