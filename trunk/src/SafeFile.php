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
 * @package org.owasp.esapi
 */

require_once("errors/ValidationException.php");

/**
 * Extension to java.io.File to prevent against null byte injections and
 * other unforeseen problems resulting from unprintable characters
 * causing problems in path lookups. This does _not_ prevent against
 * directory traversal attacks.
 * 
 * @author 
 * @since 1.4
 */
class SafeFile extends File {

	function SafeFile($path) {
//		super(path);
//		doDirCheck(this.getParent());
//		doFileCheck(this.getName());
	}

//	public SafeFile(String parent, String child) throws ValidationException {
//		super(parent, child);
//		doDirCheck(this.getParent());
//		doFileCheck(this.getName());
//	}
//
//	public SafeFile(File parent, String child) throws ValidationException {
//		super(parent, child);
//		doDirCheck(this.getParent());
//		doFileCheck(this.getName());
//	}
//
//	public SafeFile(URI uri) throws ValidationException {
//		super(uri);
//		doDirCheck(this.getParent());
//		doFileCheck(this.getName());
//	}

	
//	Pattern percents = Pattern.compile("(%)([0-9a-fA-F])([0-9a-fA-F])");	
//	Pattern dirblacklist = Pattern.compile("([*?<>|])");
	private function doDirCheck($path) {
//		Matcher m1 = dirblacklist.matcher( path );
//		if ( m1.find() ) {
//			throw new ValidationException( "Invalid directory", "Directory path (" + path + ") contains illegal character: " + m1.group() );
//		}
//
//		Matcher m2 = percents.matcher( path );
//		if ( m2.find() ) {
//			throw new ValidationException( "Invalid directory", "Directory path (" + path + ") contains encoded characters: " + m2.group() );
//		}
//		
//		int ch = containsUnprintableCharacters(path);
//		if (ch != -1) {
//			throw new ValidationException("Invalid directory", "Directory path (" + path + ") contains unprintable character: " + ch);
//		}
	}
	
//	Pattern fileblacklist = Pattern.compile("([\\\\/:*?<>|])");	
	private function doFileCheck($path) {
//		Matcher m1 = fileblacklist.matcher( path );
//		if ( m1.find() ) {
//			throw new ValidationException( "Invalid directory", "Directory path (" + path + ") contains illegal character: " + m1.group() );
//		}
//
//		Matcher m2 = percents.matcher( path );
//		if ( m2.find() ) {
//			throw new ValidationException( "Invalid file", "File path (" + path + ") contains encoded characters: " + m2.group() );
//		}
//		
//		int ch = containsUnprintableCharacters(path);
//		if (ch != -1) {
//			throw new ValidationException("Invalid file", "File path (" + path + ") contains unprintable character: " + ch);
//		}
	}

	private function containsUnprintableCharacters($s) {
//		for (int i = 0; i < s.length(); i++) {
//			char ch = s.charAt(i);
//			if (((int) ch) < 32 || ((int) ch) > 126) {
//				return (int) ch;
//			}
//		}
//		return -1;
	}
}
