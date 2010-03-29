<?php
/**
 * OWASP Enterprise Security API (ESAPI)
 * 
 * This file is part of the Open Web Application Security Project (OWASP)
 * Enterprise Security API (ESAPI) project. For details, please see
 * <a href="http://www.owasp.org/index.php/ESAPI">http://www.owasp.org/index.php/ESAPI</a>.
 *
 * Copyright (c) 2007 - 2010 The OWASP Foundation
 * 
 * The ESAPI is published by OWASP under the BSD license. You should read and accept the
 * LICENSE before you use, modify,   and/or redistribute this software.
 * 
 * @author Martin Reiche <martin.reiche.ka@googlemail.com>
 * @since 1.4
 */

require_once dirname(__FILE__).'/errors/ValidationException.php';

/**
 * Extension to SplFileObject to prevent against null byte injections and
 * other unforeseen problems resulting from unprintable characters
 * causing problems in path lookups. This does _not_ prevent against
 * directory traversal attacks.
 * 
 * @since 1.4
 */
class SafeFile extends SplFileObject {

        private $PERCENTS_PAT = "/(%)([0-9a-f])([0-9a-f])/i";
        private $FILE_BLACKLIST_PAT = "/([\\/:*?<>|])/";
        private $DIR_BLACKLIST_PAT = "/([*?<>|])/";

        /**
         * Creates an extended SplFileObject from the given filename, which
         * prevents against null byte injections and unprintable characters.
         * @param String $path the path to the file (path && file name)
         */

        function __construct($path) {
            try {
                @parent::__construct($path);
            } catch (Exception $e) {
                throw new EnterpriseSecurityException("Failed to open stream", "Failed to open stream " . $e->getMessage());
            }
            
            $this->doDirCheck($this->getPath());
            $this->doFileCheck($this->getFilename());
            $this->doExtraCheck($path);
        }

        /**
         * Checks the directory against null bytes and unprintable characters.
         * @param String $path the directory path (without the file name)
         */
        private function doDirCheck($path) {
            if ( preg_match($this->DIR_BLACKLIST_PAT, $path) ) {
                throw new ValidationException("Invalid directory", "Directory path (" + $path + ") contains illegal character. ");
            }

            if ( preg_match($this->PERCENTS_PAT, $path) ) {
                throw new ValidationException("Invalid directory", "Directory path (" + $path + ") contains encoded characters. ");
            }

            $ch = $this->containsUnprintableCharacters($path);
            if ($ch != -1) {
                throw new ValidationException("Invalid directory", "Directory path (" + $path + ") contains unprintable character. ");
            }
        }

        /**
         * Checks the file name against null bytes and unprintable characters.
         * @param String $path the file name
         */
        private function doFileCheck($path) {
            if ( preg_match($this->FILE_BLACKLIST_PAT, $path) ) {
                throw new ValidationException("Invalid directory", "Directory path (" + $path + ") contains illegal character.");
            }

            if ( preg_match($this->PERCENTS_PAT, $path) ) {
                throw new ValidationException("Invalid file", "File path (" + $path + ") contains encoded characters.");
            }

            $ch = $this->containsUnprintableCharacters($path);
            if ($ch != -1) {
                throw new ValidationException("Invalid file", "File path (" + $path + ") contains unprintable character.");
            }
        }

        /**
         * Checks the specified string for unprintable characters (ASCII range
         * from 0 to 31 and from 127 to 255).
         * @param String $s the String to check for unprintable characters
         * @return int  the value of the first unprintable character found.
         *              If no unprintable character was found, -1 is returned.
         */
        private function containsUnprintableCharacters($s) {
            for ($i = 0; $i < strlen($s); $i++) {
                $ch = $s[$i];
                if (ord($ch) < 32 || ord($ch) > 126) {
                    return $ch;
                }
            }
            return -1;
        }
        
        /*
         * Checks if the last character is a slash
         * @param String $path the string to check
         */
        private function doExtraCheck($path) {
        	$last = substr($path, -1);
        	if ($last === '/') {
        		throw new ValidationException("Invalid file", "File path (" + $path + ") contains an extra slash.");
        	}
        }
}