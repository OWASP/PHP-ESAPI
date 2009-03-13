<?php
/**
 * OWASP Enterprise Security API (ESAPI)
 * 
 * This file is part of the Open Web Application Security Project (OWASP)
 * Enterprise Security API (ESAPI) project. For details, please see
 * <a href="http://www.owasp.org/index.php/ESAPI">http://www.owasp.org/index.php/ESAPI</a>.
 *
 * Copyright (c) 2007 - 2008 The OWASP Foundation
 * 
 * The ESAPI is published by OWASP under the BSD license. You should read and accept the
 * LICENSE before you use, modify, and/or redistribute this software.
 * 
 * @author 
 * @created 2008
 * @since 1.4
 * @package org.owasp.esapi.reference
 */

require_once('../src/Randomizer.php');

class DefaultRandomizer implements Randomizer {
	
	
	/**
	 * Gets a random string of a desired length and character set.  The use of java.security.SecureRandom
	 * is recommended because it provides a cryptographically strong pseudo-random number generator. 
	 * If SecureRandom is not used, the pseudo-random number gernerator used should comply with the 
	 * statistical random number generator tests specified in <a href="http://csrc.nist.gov/cryptval/140-2.htm">
	 * FIPS 140-2, Security Requirements for Cryptographic Modules</a>, section 4.9.1.
	 * 
	 * @param length 
	 * 		the length of the string
	 * @param characterSet 
	 * 		the set of characters to include in the created random string
	 * 
	 * @return 
	 * 		the random string of the desired length and character set
	 */
	function getRandomString($length, $characterSet)	{ 		throw new EnterpriseSecurityException("Method Not implemented");	 	}

	/**
	 * Returns a random boolean.  The use of java.security.SecureRandom
	 * is recommended because it provides a cryptographically strong pseudo-random number generator. 
	 * If SecureRandom is not used, the pseudo-random number gernerator used should comply with the 
	 * statistical random number generator tests specified in <a href="http://csrc.nist.gov/cryptval/140-2.htm">
	 * FIPS 140-2, Security Requirements for Cryptographic Modules</a>, section 4.9.1.
	 * 
	 * @return 
	 * 		true or false, randomly
	 */
	function getRandomBoolean()	{ 		throw new EnterpriseSecurityException("Method Not implemented");	 	}
	
	/**
	 * Gets the random integer. The use of java.security.SecureRandom
	 * is recommended because it provides a cryptographically strong pseudo-random number generator. 
	 * If SecureRandom is not used, the pseudo-random number gernerator used should comply with the 
	 * statistical random number generator tests specified in <a href="http://csrc.nist.gov/cryptval/140-2.htm">
	 * FIPS 140-2, Security Requirements for Cryptographic Modules</a>, section 4.9.1.
	 * 
	 * @param min 
	 * 		the minimum integer that will be returned
	 * @param max 
	 * 		the maximum integer that will be returned
	 * 
	 * @return 
	 * 		the random integer
	 */
	function getRandomInteger($min, $max)	{ 		throw new EnterpriseSecurityException("Method Not implemented");	 	}

	
	/**
	 * Gets the random long. The use of java.security.SecureRandom
	 * is recommended because it provides a cryptographically strong pseudo-random number generator. 
	 * If SecureRandom is not used, the pseudo-random number gernerator used should comply with the 
	 * statistical random number generator tests specified in <a href="http://csrc.nist.gov/cryptval/140-2.htm">
	 * FIPS 140-2, Security Requirements for Cryptographic Modules</a>, section 4.9.1.
	 * 
	 * @return 
	 * 		the random long
	 */
    function getRandomLong()	{ 		throw new EnterpriseSecurityException("Method Not implemented");	 	}
	
	
    /**
     * Returns an unguessable random filename with the specified extension.  This method could call
     * getRandomString(length, charset) from this Class with the desired length and alphanumerics as the charset 
     * then merely append "." + extension.
     * 
     * @param extension 
     * 		extension to add to the random filename
     * 
     * @return 
     * 		a random unguessable filename ending with the specified extension
     */
    function getRandomFilename( $extension )	{ 		throw new EnterpriseSecurityException("Method Not implemented");	 	}
    
    
	/**
	 * Gets the random real.  The use of java.security.SecureRandom
	 * is recommended because it provides a cryptographically strong pseudo-random number generator. 
	 * If SecureRandom is not used, the pseudo-random number gernerator used should comply with the 
	 * statistical random number generator tests specified in <a href="http://csrc.nist.gov/cryptval/140-2.htm">
	 * FIPS 140-2, Security Requirements for Cryptographic Modules</a>, section 4.9.1.
	 * 
	 * @param min 
	 * 		the minimum real number that will be returned
	 * @param max 
	 * 		the maximum real number that will be returned
	 * 
	 * @return 
	 * 		the random real
	 */
	function getRandomReal($min, $max)	{ 		throw new EnterpriseSecurityException("Method Not implemented");	 	}

    /**
     * Generates a random GUID.  This method could use a hash of random Strings, the current time,
     * and any other random data available.  The format is a well-defined sequence of 32 hex digits 
     * grouped into chunks of 8-4-4-4-12.  
     * 
     * @return 
     * 		the GUID
     * 
     * @throws 
     * 		EncryptionException if hashing or encryption fails 
     */
    function getRandomGUID()	{ 		throw new EnterpriseSecurityException("Method Not implemented");	 	}
	
}
?>