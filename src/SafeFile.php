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
 * @author    Martin Reiche <martin.reiche.ka@googlemail.com>
 * @copyright 2009-2010 The OWASP Foundation
 * @license   http://www.opensource.org/licenses/bsd-license.php New BSD license
 * @version   SVN: $Id$
 * @link      http://www.owasp.org/index.php/ESAPI
 */

require_once dirname(__FILE__).'/errors/ValidationException.php';

/**
 * Extension to SplFileObject to prevent against null byte injections and
 * other unforeseen problems resulting from unprintable characters
 * causing problems in path lookups. This does _not_ prevent against
 * directory traversal attacks.
 *
 * @category  OWASP
 * @package   ESAPI
 * @author    Jeff Williams <jeff.williams@aspectsecurity.com>
 * @author    Andrew van der Stock <vanderaj@owasp.org>
 * @author    Martin Reiche <martin.reiche.ka@googlemail.com>
 * @copyright 2009-2010 The OWASP Foundation
 * @license   http://www.opensource.org/licenses/bsd-license.php New BSD license
 * @version   Release: @package_version@
 * @link      http://www.owasp.org/index.php/ESAPI
 */
class SafeFile extends SplFileObject
{

    private $_PERCENTS_PAT = "/(%)([0-9a-f])([0-9a-f])/i";
    private $_FILE_BLACKLIST_PAT = "/([\\/:*?<>|])/";
    private $_DIR_BLACKLIST_PAT = "/([*?<>|])/";

    /**
     * Creates an extended SplFileObject from the given filename, which
     * prevents against null byte injections and unprintable characters.
     *
     * @param string $path the path to the file (path && file name)
     *
     * @return does not return a value.
     */
    function __construct($path)
    {
        try {
            @parent::__construct($path);
        } catch (Exception $e) {
                throw new EnterpriseSecurityException(
                    'Failed to open stream',
                    'Failed to open stream ' . $e->getMessage()
                );
        }

        $this->_doDirCheck($this->getPath());
        $this->_doFileCheck($this->getFilename());
        $this->_doExtraCheck($path);
    }

    /**
     * Checks the directory against null bytes and unprintable characters.
     *
     * @param string $path the directory path (without the file name)
     *
     * @return does not return a value
     * @exception ValidationException thrown if check fails
     */
    private function _doDirCheck($path)
    {
        if ( preg_match($this->_DIR_BLACKLIST_PAT, $path) ) {
            throw new ValidationException(
                'Invalid directory',
                "Directory path ({$path}) contains illegal character. "
            );
        }

        if ( preg_match($this->_PERCENTS_PAT, $path) ) {
            throw new ValidationException(
                'Invalid directory',
                "Directory path ({$path}) contains encoded characters. "
            );
        }

        $ch = $this->_containsUnprintableCharacters($path);
        if ($ch != -1) {
            throw new ValidationException(
                'Invalid directory',
                "Directory path ({$path}) contains unprintable character. "
            );
        }
    }

    /**
     * Checks the file name against null bytes and unprintable characters.
     *
     * @param string $path the file name
     *
     * @return does not return a value
     * @exception ValidationException thrown if check fails
     */
    private function _doFileCheck($path)
    {
        if ( preg_match($this->_FILE_BLACKLIST_PAT, $path) ) {
            throw new ValidationException(
                'Invalid file',
                "File path ({$path}) contains illegal character.");
        }

        if ( preg_match($this->_PERCENTS_PAT, $path) ) {
            throw new ValidationException(
                'Invalid file',
                "File path ({$path}) contains encoded characters."
            );
        }

        $ch = $this->_containsUnprintableCharacters($path);
        if ($ch != -1) {
            throw new ValidationException(
                'Invalid file',
                "File path ({$path}) contains unprintable character."
            );
        }
    }

    /**
     * Checks the specified string for unprintable characters (ASCII range
     * from 0 to 31 and from 127 to 255).
     *
     * @param string $s the string to check for unprintable characters
     *
     * @return int the value of the first unprintable character found, or -1
     */
    private function _containsUnprintableCharacters($s)
    {
        for ($i = 0; $i < strlen($s); $i++) {
            $ch = $s[$i];
            if (ord($ch) < 32 || ord($ch) > 126) {
                return $ch;
            }
        }
        return -1;
    }

    /**
     * Checks if the last character is a slash
     *
     * @param string $path the string to check
     *
     * @return does not return a value
     * @exception ValidationException thrown if check fails
     */
    private function _doExtraCheck($path)
    {
        $last = substr($path, -1);
        if ($last === '/') {
            throw new ValidationException(
                'Invalid file',
                "File path ({$path}) contains an extra slash."
            );
        }
    }
}