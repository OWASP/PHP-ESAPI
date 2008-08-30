<?php
/**
 * OWASP Enterprise Security API (ESAPI)
 *
 * This file is part of the Open Web Application Security Project (OWASP)
 * Enterprise Security API (ESAPI) project. For details, please see
 * http://www.owasp.org/esapi.
 *
 * Copyright (c) 2007 - The OWASP Foundation
 *
 * The ESAPI is published by OWASP under the LGPL. You should read and accept the
 * LICENSE before you use, modify, and/or redistribute this software.
 *
 * @author Jeff Williams <a href="http://www.aspectsecurity.com">Aspect Security</a>
 * @package org.owasp.esapi;
 * @since 2007
 */

require_once("errors/org.owasp.esapi.EncodingException.php");
require_once("errors/org.owasp.esapi.IntrusionException.php");
require_once("errors/org.owasp.esapi.ValidationAvailabilityException.php");
require_once("errors/org.owasp.esapi.ValidationException.php");
require_once("interfaces/org.owasp.esapi.IValidator.php");
require_once("org.owasp.esapi.Logger.php");

/**
 * Reference implementation of the IValidator interface. This implementation
 * relies on the ESAPI Encoder, Java Pattern (regex), Date,
 * and several other classes to provide basic validation functions. This library
 * has a heavy emphasis on whitelist validation and canonicalization. All double-encoded
 * characters, even in multiple encoding schemes, such as <PRE>&amp;lt;</PRE> or
 * <PRE>%26lt;<PRE> or even <PRE>%25%26lt;</PRE> are disallowed.
 *
 * @author Jeff Williams (jeff.williams .at. aspectsecurity.com) <a
 *         href="http://www.aspectsecurity.com">Aspect Security</a>
 * @since June 1, 2007
 * @see org.owasp.esapi.interfaces.IValidator
 */
class Validator implements IValidator {

	/** The logger. */
	private static $logger;

	public function Validator() {
		 $this->logger = $ESAPI->getLogger("ESAPI", "Validator");
	}

	/**
	 * Validates data received from the browser and returns a safe version. Only
	 * URL encoding is supported. Double encoding is treated as an attack.
	 *
	 * @param name
	 * @param type
	 * @param input
	 * @return
	 * @throws ValidationException
	 */
	public function getValidDataFromBrowser($context, $type, $input) {
	    try {
    		$canonical = ESAPI::encoder()->canonicalize( $input );

    		if ( $input == null )
    			throw new ValidationException("Bad input", $type + " (" + $context + ") input to validate was null" );

    		if ( type == null )
    			throw new ValidationException("Bad input", $type + " (" + $context + ") type to validate against was null" );

    		$p = ESAPI::securityConfiguration()).getValidationPattern( $type );
    		if ( $p == null )
    			throw new ValidationException("Bad input", $type + " (" + $context + ") type to validate against not configured in ESAPI.properties" );

    		if ( !p.matcher(canonical).matches() )
    			throw new ValidationException("Bad input", $type + " (" + $context + "=" + $input + ") input did not match type definition " + p );

    		// if everything passed, then return the canonical form
    		return $canonical;
	    } catch (EncodingException $ee) {
	        throw new ValidationException("Internal error", "Error canonicalizing user input", $ee);
	    }
	}

	/**
	 * Returns true if data received from browser is valid. Only URL encoding is
	 * supported. Double encoding is treated as an attack.
	 *
	 * @param name
	 * @param type
	 * @param value
	 * @return
	 */
	public function isValidDataFromBrowser($context, $type, $value) {
		try {
			getValidDataFromBrowser(context, type, value);
			return true;
		} catch( Exception e ) {
			return false;
		}
	}


	/*
	 * (non-Javadoc)
	 *
	 * @see org.owasp.esapi.interfaces.IValidator#getValidDate(java.lang.String)
	 */
	public function getValidDate($context, $input, DateFormat format) throws ValidationException {
		try {
			Date date = format.parse($input);
			return date;
		} catch (Exception e) {
			throw new ValidationException( "Invalid date", "Problem parsing date (" + context + "=" + $input + ") ",e );
		}
	}

	/*
	 * (non-Javadoc)
	 *
	 * @see org.owasp.esapi.interfaces.IValidator#isValidCreditCard(java.lang.String)
	 */
	public function isValidCreditCard($context, $value) {
		try {
			$canonical = getValidDataFromBrowser(context, "CreditCard", value);

			// perform Luhn algorithm checking
			StringBuffer digitsOnly = new StringBuffer();
			char c;
			for (int i = 0; i < canonical.length(); i++) {
				c = canonical.charAt(i);
				if (Character.isDigit(c)) {
					digitsOnly.append(c);
				}
			}

			int sum = 0;
			int digit = 0;
			int addend = 0;
			boolean timesTwo = false;

			for (int i = digitsOnly.length() - 1; i >= 0; i--) {
				digit = Integer.parseInt(digitsOnly.substring(i, i + 1));
				if (timesTwo) {
					addend = digit * 2;
					if (addend > 9) {
						addend -= 9;
					}
				} else {
					addend = digit;
				}
				sum += addend;
				timesTwo = !timesTwo;
			}

			int modulus = sum % 10;
			return modulus == 0;
		} catch( Exception e ) {
			return false;
		}
	}

	/**
	 * Returns true if the directory path (not including a filename) is valid.
	 *
	 * @see org.owasp.esapi.interfaces.IValidator#isValidDirectoryPath(java.lang.String)
	 */
	public function isValidDirectoryPath($context, $dirpath) {
		try {
	        $canonical = ESAPI::encoder().canonicalize(dirpath);

			// do basic validation
			Pattern directoryNamePattern = ((SecurityConfiguration)ESAPI::securityConfiguration()).getValidationPattern("DirectoryName");
			if ( !directoryNamePattern.matcher(canonical).matches() ) {
				new ValidationException("Invalid directory name", "Attempt to use a directory name (" + canonical + ") that violates the global rule in ESAPI.properties (" + directoryNamePattern.pattern() +")" );
				return false;
			}

			// get the canonical path without the drive letter if present
			$cpath = new File(canonical).getCanonicalPath().replaceAll("\\\\", "/");
			$temp = cpath.toLowerCase();
			if (temp.length() >= 2 && temp.charAt(0) >= 'a' && temp.charAt(0) <= 'z' && temp.charAt(1) == ':') {
				cpath = cpath.substring(2);
			}

			// prepare the input without the drive letter if present
			$escaped = canonical.replaceAll("\\\\", "/");
			temp = escaped.toLowerCase();
			if (temp.length() >= 2 && temp.charAt(0) >= 'a' && temp.charAt(0) <= 'z' && temp.charAt(1) == ':') {
				escaped = escaped.substring(2);
			}

			// the path is valid if the input matches the canonical path
			return escaped.equals(cpath.toLowerCase());
		} catch (IOException e) {
			return false;
		} catch (EncodingException ee) {
            throw new IntrusionException("Invalid directory", "Exception during directory validation", ee);
		}
	}


	/*
	 * (non-Javadoc)
	 *
	 * @see org.owasp.esapi.interfaces.IValidator#isValidFileUpload(java.lang.String,java.lang.String,byte[]
	 *      content)
	 */
	public function isValidFileContent($context, byte[] content) {
		// FIXME: AAA - temporary - what makes file content valid? Maybe need a parameter here?
		long length = ESAPI::securityConfiguration().getAllowedFileUploadSize();
		return (content.length < length);
		// FIXME: log something?
	}

	/*
	 * (non-Javadoc)
	 *
	 * @see org.owasp.esapi.interfaces.IValidator#isValidFileName(java.lang.String)
	 */

	//FIXME: AAA - getValidFileName eliminates %00 and other injections.
	//FIXME: AAA - this method should check for %00 injection too
	public function isValidFileName($context, $input) {
		if ($input == null || $input.length() == 0)
			return false;

		// detect path manipulation
		try {
	        $canonical = ESAPI::encoder().canonicalize($input);

			// do basic validation
			Pattern fileNamePattern = ((SecurityConfiguration)ESAPI::securityConfiguration()).getValidationPattern("FileName");
			if ( !fileNamePattern.matcher(canonical).matches() ) {
				new ValidationException("Invalid filename", "Attempt to use a filename (" + canonical + ") that violates the global rule in ESAPI.properties (" + fileNamePattern.pattern() +")" );
				return false;
			}

			File f = new File(canonical);
			$c = f.getCanonicalPath();
			$cpath = c.substring(c.lastIndexOf(File.separator) + 1);
			if (!$input.equals(cpath)) {
				new ValidationException("Invalid filename", "Invalid filename (" + canonical + ") doesn't match canonical path (" + cpath + ") and could be an injection attack");
				return false;
			}
		} catch (IOException e) {
			throw new IntrusionException("Invalid filename", "Exception during filename validation", e);
		} catch (EncodingException ee) {
            throw new IntrusionException("Invalid filename", "Exception during filename validation", ee);
		}

		// verify extensions
		List extensions = ESAPI::securityConfiguration().getAllowedFileExtensions();
		Iterator i = extensions.iterator();
		while (i.hasNext()) {
			$ext = (String) i.next();
			if ($input.toLowerCase().endsWith(ext.toLowerCase())) {
				return true;
			}
		}
		return false;
	}

	/*
	 * (non-Javadoc)
	 *
	 * @see org.owasp.esapi.interfaces.IValidator#isValidFileUpload(java.lang.String,
	 *      java.lang.String, byte[])
	 */
	public function isValidFileUpload($context, $filepath, $filename, byte[] content) {
		return isValidDirectoryPath(context, filepath) && isValidFileName(context, filename) && isValidFileContent(context, content);
	}

	/**
	 * Validate the parameters, cookies, and headers in an HTTP request against
	 * specific regular expressions defined in the SecurityConfiguration. Note
	 * that isValidDataFromBrowser uses the Encoder.canonicalize() method to ensure
	 * that all encoded characters are reduced to their simplest form, and that any
	 * double-encoded characters are detected and throw an exception.
	 */
	public function isValidHTTPRequest(HttpServletRequest request) {
		boolean result = true;

		Iterator i1 = request.getParameterMap().entrySet().iterator();
		while (i1.hasNext()) {
			Map.Entry entry = (Map.Entry) i1.next();
			$name = (String) entry.getKey();
			if ( !isValidDataFromBrowser( "http", "HTTPParameterName", name ) ) {
				// logger.logCritical(Logger.SECURITY, "Parameter name (" + name + ") violates global rule" );
				result = false;
			}

			String[] values = (String[]) entry.getValue();
			Iterator i3 = Arrays.asList(values).iterator();
			// FIXME:Enhance - consider throwing an exception if there are multiple parameters with the same name
			while (i3.hasNext()) {
				$value = (String) i3.next();
				if ( !isValidDataFromBrowser( name, "HTTPParameterValue", value ) ) {
					// logger.logCritical(Logger.SECURITY, "Parameter value (" + name + "=" + value + ") violates global rule" );
					result = false;
				}
			}
		}

		if (request.getCookies() != null) {
			Iterator i2 = Arrays.asList(request.getCookies()).iterator();
			while (i2.hasNext()) {
				Cookie cookie = (Cookie) i2.next();
				$name = cookie.getName();
				if ( !isValidDataFromBrowser( "http", "HTTPCookieName", name ) ) {
					// logger.logCritical(Logger.SECURITY, "Cookie name (" + name + ") violates global rule" );
					result = false;
				}

				$value = cookie.getValue();
				if ( !isValidDataFromBrowser( name, "HTTPCookieValue", value ) ) {
					// logger.logCritical(Logger.SECURITY, "Cookie value (" + name + "=" + value + ") violates global rule" );
					result = false;
				}
			}
		}

		Enumeration e = request.getHeaderNames();
		while (e.hasMoreElements()) {
			$name = (String) e.nextElement();
			if (name != null && !name.equalsIgnoreCase("Cookie")) {
				if ( !isValidDataFromBrowser( "http", "HTTPHeaderName", name ) ) {
					// logger.logCritical(Logger.SECURITY, "Header name (" + name + ") violates global rule" );
					result = false;
				}

				Enumeration e2 = request.getHeaders(name);
				while (e2.hasMoreElements()) {
					$value = (String) e2.nextElement();
					if ( !isValidDataFromBrowser( name, "HTTPHeaderValue", value ) ) {
						// logger.logCritical(Logger.SECURITY, "Header value (" + name + "=" + value + ") violates global rule" );
						result = false;
					}
				}
			}
		}
		return result;
	}

	/*
	 * (non-Javadoc)
	 *
	 * @see org.owasp.esapi.interfaces.IValidator#isValidListItem(java.util.List,
	 *      java.lang.String)
	 */
	public function isValidListItem(List list, $value) {
		return list.contains(value);
	}

	/*
	 * (non-Javadoc)
	 *
	 * @see org.owasp.esapi.interfaces.IValidator#isValidNumber(java.lang.String)
	 */
	public function isValidNumber($input) {
		try {
			double d = Double.parseDouble($input);
			return ( !Double.isInfinite( d ) && !Double.isNaN( d ) );
		} catch (NumberFormatException e) {
			return false;
		}
	}

	/*
	 * (non-Javadoc)
	 *
	 * @see org.owasp.esapi.interfaces.IValidator#isValidParameterSet(java.util.Set,
	 *      java.util.Set, java.util.Set)
	 */
	public function isValidParameterSet(Set requiredNames, Set optionalNames) {
		HttpServletRequest request = ((Authenticator)ESAPI::authenticator()).getCurrentRequest();
		Set actualNames = request.getParameterMap().keySet();

		// verify ALL required parameters are present
		Set missing = new HashSet(requiredNames);
		missing.removeAll(actualNames);
		if (missing.size() > 0) {
			return false;
		}

		// verify ONLY optional + required parameters are present
		Set extra = new HashSet(actualNames);
		extra.removeAll(requiredNames);
		extra.removeAll(optionalNames);
		if (extra.size() > 0) {
			return false;
		}
		return true;
	}

	/**
	 * Checks that all bytes are valid ASCII characters (between 33 and 126
	 * inclusive). This implementation does no decoding. http://en.wikipedia.org/wiki/ASCII. (non-Javadoc)
	 *
	 * @see org.owasp.esapi.interfaces.IValidator#isValidASCIIFileContent(byte[])
	 */
	public function isValidPrintable(byte[] $input) {
		for (int i = 0; i < $input.length; i++) {
			if ($input[i] < 33 || $input[i] > 126)
				return false;
		}
		return true;
	}

	/*
	 * (non-Javadoc)
	 * @see org.owasp.esapi.interfaces.IValidator#isValidPrintable(java.lang.String)
	 */
	public function isValidPrintable($input) {
	    try {
    		$canonical = ESAPI::encoder().canonicalize();
    		return isValidPrintable(canonical.getBytes());
	    } catch (EncodingException ee) {
	        logger.logError(Logger.SECURITY, "Could not canonicalize user input", ee);
	        return false;
	    }
	}

	/**
	 * (non-Javadoc).
	 *
	 * @param location
	 *            the location
	 * @return true, if is valid redirect location
	 * @see org.owasp.esapi.interfaces.IValidator#isValidRedirectLocation(String
	 *      location)
	 */
	public function isValidRedirectLocation($context, $location) {
		// FIXME: ENHANCE - it's too hard to put valid locations in as regex
		return ESAPI::validator().isValidDataFromBrowser(context, "Redirect", location);
	}

	/*
	 * (non-Javadoc)
	 *
	 * @see org.owasp.esapi.interfaces.IValidator#isValidSafeHTML(java.lang.String)
	 */
	public function isValidSafeHTML($name, $input) {
	    try {
    		$canonical = ESAPI::encoder().canonicalize();
    		// FIXME: AAA this is just a simple blacklist test - will use Anti-SAMY
    		return !(canonical.indexOf("<scri") > -1 ) && !(canonical.indexOf("onload") > -1);
	    } catch (EncodingException ee) {
	           throw new IntrusionException("Invalid input", "EncodingException during HTML validation", ee);
	    }
	}

	/*
	 * (non-Javadoc)
	 *
	 * @see org.owasp.esapi.interfaces.IValidator#getValidSafeHTML(java.lang.String)
	 */
	public function getValidSafeHTML( $context, $input ) {
		throw new Exception("unsupported operation.");
		/**
		AntiSamy as = new AntiSamy();
		try {
			CleanResults test = as.scan(input);
			// OutputFormat format = new OutputFormat(test.getCleanXMLDocumentFragment().getOwnerDocument());
			// format.setLineWidth(65);
			// format.setIndenting(true);
			// format.setIndent(2);
			// format.setEncoding(AntiSamyDOMScanner.ENCODING_ALGORITHM);
			return(test.getCleanHTML().trim());
		} catch (ScanException e) {
			throw new ValidationException( "Invalid HTML", "Problem parsing HTML (" + context + "=" + input + ") ",e );
		} catch (PolicyException e) {
			throw new ValidationException( "Invalid HTML", "HTML violates policy (" + context + "=" + input + ") ",e );
		}
		**/
	}


	/**
	 * This implementation reads until a newline or the specified number of
	 * characters.
	 *
	 * @param in
	 *            the in
	 * @param max
	 *            the max
	 * @return the string
	 * @throws ValidationException
	 *             the validation exception
	 * @see org.owasp.esapi.interfaces.IValidator#safeReadLine(java.io.InputStream,
	 *      int)
	 */
	public function safeReadLine($in, $max) {
		if ($max <= 0) {
			throw new ValidationAvailabilityException("Invalid input", "Must read a positive number of bytes from the stream");
		}

		$sb = "";
		$count = 0;
		$c = 0;

		// FIXME: AAA - verify this method's behavior exactly matches BufferedReader.readLine()
		// so it can be used as a drop in replacement.
		try {
			while (true) {
				$c = $in.read();
				if ( $c == -1 ) {
					if (empty($sb)) return null;
					break;
				}
				if ($c == '\n' || $c == '\r') break;
				$count++;
				if ($count > $max) {
					throw new ValidationAvailabilityException("Invalid input", "Read more than maximum characters allowed (" + max + ")");
				}
				$sb .= chr($c);
			}
			return $sb;
		} catch (IOException e) {
			throw new ValidationAvailabilityException("Invalid input", "Problem reading from input stream", e);
		}
	}

}

?>