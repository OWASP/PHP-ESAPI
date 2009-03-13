<?php
/**
 * OWASP Enterprise Security API (ESAPI)
 * 
 * This file is part of the Open Web Application Security Project (OWASP)
 * Enterprise Security API (ESAPI) project. For details, please see
 * <a href="http://www.owasp.org/index.php/ESAPI">http://www.owasp.org/index.php/ESAPI</a>.
 *
 * Copyright (c) 2007 - 2009 The OWASP Foundation
 * 
 * The ESAPI is published by OWASP under the BSD license. You should read and accept the
 * LICENSE before you use, modify, and/or redistribute this software.
 * 
 * @author 
 * @created 2008
 * @since 1.4
 * @package org.owasp.esapi.reference
 */

require_once('../src/Encoder.php');

class DefaultEncoder implements Encoder {
	
		/**
	 * This method performs canonicalization on data received to ensure that it
	 * has been reduced to its most basic form before validation. For example,
	 * URL-encoded data received from ordinary "application/x-www-url-encoded"
	 * forms so that it may be validated properly.
	 * <p>
	 * Canonicalization is simply the operation of reducing a possibly encoded
	 * string down to its simplest form. This is important, because attackers
	 * frequently use encoding to change their input in a way that will bypass
	 * validation filters, but still be interpreted properly by the target of
	 * the attack. Note that data encoded more than once is not something that a
	 * normal user would generate and should be regarded as an attack.
	 * <P>
	 * For input that comes from an HTTP servlet request, there are generally
	 * two types of encoding to be concerned with. The first is
	 * "applicaton/x-www-url-encoded" which is what is typically used in most
	 * forms and URI's where characters are encoded in a %xy format. The other
	 * type of common character encoding is HTML entity encoding, which uses
	 * several formats:
	 * <P>
	 * <PRE>&lt;</PRE>,
	 * <PRE>&#117;</PRE>, and
	 * <PRE>&#x3a;</PRE>.
	 * <P>
	 * Note that all of these formats may possibly render properly in a
	 * browser without the trailing semicolon.
	 * <P>
	 * Double-encoding is a particularly thorny problem, as applying ordinary decoders
	 * may introduce encoded characters, even characters encoded with a different
	 * encoding scheme. For example %26lt; is a < character which has been entity encoded
	 * and then the first character has been url-encoded. Implementations should
	 * throw an IntrusionException when double-encoded characters are detected.
	 * <P>
	 * Note that there is also "multipart/form" encoding, which allows files and
	 * other binary data to be transmitted. Each part of a multipart form can
	 * itself be encoded according to a "Content-Transfer-Encoding" header. See
	 * the HTTPUtilties.getSafeFileUploads() method.
	 * <P>
	 * For more information on form encoding, please refer to the 
	 * <a href="http://www.w3.org/TR/html4/interact/forms.html#h-17.13.4">W3C specifications</a>.
	 * 
	 * @see <a href="http://www.w3.org/TR/html4/interact/forms.html#h-17.13.4">W3C specifications</a>
	 *  
	 * @param input 
	 * 		the text to canonicalize
	 * @param strict 
	 * 		true if checking for double encoding is desired, false otherwise
	 * 
	 * @return a String containing the canonicalized text
	 * 
	 * @throws EncodingException 
	 * 		if canonicalization fails
	 */
	function canonicalize($input, $strict = true)
	{
		throw new EnterpriseSecurityException("Method Not implemented");	
	}

	/**
	 * Reduce all non-ascii characters to their ASCII form so that simpler
	 * validation rules can be applied. For example, an accented-e character
	 * will be changed into a regular ASCII e character.
	 * 
	 * @param input 
	 * 		the text to normalize
	 * 
	 * @return a normalized String
	 */
	function normalize($input)
	{
		throw new EnterpriseSecurityException("Method Not implemented");	
	}

	/**
	 * Encode data for use in Cascading Style Sheets (CSS) content.
	 * 
	 * @see <a href="http://www.w3.org/TR/CSS21/syndata.html#escaped-characters">CSS Syntax [w3.org]</a>
	 * 
	 * @param input 
	 * 		the text to encode for CSS
	 * 
	 * @return input encoded for CSS
	 */
	function encodeForCSS($input)
	{
		throw new EnterpriseSecurityException("Method Not implemented");	
	}

	/**
	 * Encode data for use in HTML using HTML entity encoding
	 * <p> 
	 * Note that the following characters:
	 * 00–08, 0B–0C, 0E–1F, and 7F–9F 
	 * <p>cannot be used in HTML. 
	 * 
	 * @see <a href="http://en.wikipedia.org/wiki/Character_encodings_in_HTML">HTML Encodings [wikipedia.org]</a> 
	 * @see <a href="http://www.w3.org/TR/html4/sgml/sgmldecl.html">SGML Specification [w3.org]</a>
     * @see <a href="http://www.w3.org/TR/REC-xml/#charsets">XML Specification [w3.org]</a>
	 * 
	 * @param input 
	 * 		the text to encode for HTML
	 * 
	 * @return input encoded for HTML
	 */
	function encodeForHTML($input)
	{
		throw new EnterpriseSecurityException("Method Not implemented");	
	}

	/**
	 * Encode data for use in HTML attributes.
	 * 
	 * @param input 
	 * 		the text to encode for an HTML attribute
	 * 
	 * @return input encoded for use as an HTML attribute
	 */
	function encodeForHTMLAttribute($input)
	{
		throw new EnterpriseSecurityException("Method Not implemented");	
	}

	/**
	 * Encode data for insertion inside a data value in JavaScript. Putting user data directly
	 * inside a script is quite dangerous. Great care must be taken to prevent putting user data
	 * directly into script code itself, as no amount of encoding will prevent attacks there.
	 * 
	 * @param input 
	 * 		the text to encode for JavaScript
	 * 
	 * @return input encoded for use in JavaScript
	 */
	function encodeForJavaScript($input)
	{
		throw new EnterpriseSecurityException("Method Not implemented");	
	}

	/**
	 * Encode data for insertion inside a data value in a Visual Basic script. Putting user data directly
	 * inside a script is quite dangerous. Great care must be taken to prevent putting user data
	 * directly into script code itself, as no amount of encoding will prevent attacks there.
	 * 
	 * This method is not recommended as VBScript is only supported by Internet Explorer
	 * 
	 * @param input 
	 * 		the text to encode for VBScript
	 * 
	 * @return input encoded for use in VBScript
	 */
	function encodeForVBScript($input)
	{
		throw new EnterpriseSecurityException("Method Not implemented");	
	}


	/**
	 * Encode input for use in a SQL query, according to the selected codec 
	 * (appropriate codecs include the MySQLCodec and OracleCodec).
	 * 
	 * This method is not recommended. The use of the PreparedStatement 
	 * interface is the preferred approach. However, if for some reason 
	 * this is impossible, then this method is provided as a weaker 
	 * alternative. 
	 * 
	 * The best approach is to make sure any single-quotes are double-quoted.
	 * Another possible approach is to use the {escape} syntax described in the
	 * JDBC specification in section 1.5.6.
	 * 
	 * However, this syntax does not work with all drivers, and requires
	 * modification of all queries.
	 * 
	 * @see <a href="http://java.sun.com/j2se/1.4.2/docs/guide/jdbc/getstart/statement.html">JDBC Specification</a>
	 *  
	 * @param codec 
	 * 		a Codec that declares which database 'input' is being encoded for (ie. MySQL, Oracle, etc.)
	 * @param input 
	 * 		the text to encode for SQL
	 * 
	 * @return input encoded for use in SQL
	 */
	function encodeForSQL($codec, $input)
	{
		throw new EnterpriseSecurityException("Method Not implemented");	
	}

	/**
	 * Encode for an operating system command shell according to the selected codec (appropriate codecs include
	 * the WindowsCodec and UnixCodec).
	 * 
	 * @param codec 
	 * 		a Codec that declares which operating system 'input' is being encoded for (ie. Windows, Unix, etc.)
	 * @param input 
	 * 		the text to encode for the command shell
	 * 
	 * @return input encoded for use in command shell
	 */
	function encodeForOS($codec, $input)
	{
		throw new EnterpriseSecurityException("Method Not implemented");	
	}

	/**
	 * Encode data for use in LDAP queries.
	 * 
	 * @param input 
	 * 		the text to encode for LDAP
	 * 
	 * @return input encoded for use in LDAP
	 */
	function encodeForLDAP($input)
	{
		throw new EnterpriseSecurityException("Method Not implemented");	
	}

	/**
	 * Encode data for use in an LDAP distinguished name.
	 * 
	 *  @param input 
	 *  		the text to encode for an LDAP distinguished name
	 * 
	 *  @return input encoded for use in an LDAP distinguished name
	 */
	function encodeForDN($input)
	{
		throw new EnterpriseSecurityException("Method Not implemented");	
	}

	/**
	 * Encode data for use in an XPath query.
	 * 
	 * NB: The reference implementation encodes almost everything and may over-encode. 
	 * 
	 * The difficulty with XPath encoding is that XPath has no built in mechanism for escaping
	 * characters. It is possible to use XQuery in a parameterized way to
	 * prevent injection. 
	 * 
	 * For more information, refer to <a
	 * href="http://www.ibm.com/developerworks/xml/library/x-xpathinjection.html">this
	 * article</a> which specifies the following list of characters as the most
	 * dangerous: ^&"*';<>(). <a
	 * href="http://www.packetstormsecurity.org/papers/bypass/Blind_XPath_Injection_20040518.pdf">This
	 * paper</a> suggests disallowing ' and " in queries.
	 * 
	 * @see <a href="http://www.ibm.com/developerworks/xml/library/x-xpathinjection.html">XPath Injection [ibm.com]</a>
	 * @see <a href="http://www.packetstormsecurity.org/papers/bypass/Blind_XPath_Injection_20040518.pdf">Blind XPath Injection [packetstormsecurity.org]</a>
	 *  
	 * @param input
	 *      the text to encode for XPath
	 * @return 
	 * 		input encoded for use in XPath
	 */
	function encodeForXPath($input)
	{
		throw new EnterpriseSecurityException("Method Not implemented");	
	}

	/**
	 * Encode data for use in an XML element. The implementation should follow the <a
	 * href="http://www.w3schools.com/xml/xml_encoding.asp">XML Encoding
	 * Standard</a> from the W3C.
	 * <p>
	 * The use of a real XML parser is strongly encouraged. However, in the
	 * hopefully rare case that you need to make sure that data is safe for
	 * inclusion in an XML document and cannot use a parse, this method provides
	 * a safe mechanism to do so.
	 * 
	 * @see <a href="http://www.w3schools.com/xml/xml_encoding.asp">XML Encoding Standard</a>
	 * 
	 * @param input
	 * 			the text to encode for XML
	 * 
	 * @return
	 *			input encoded for use in XML
	 */
	function encodeForXML($input)
	{
		throw new EnterpriseSecurityException("Method Not implemented");	
	}

	/**
	 * Encode data for use in an XML attribute. The implementation should follow
	 * the <a href="http://www.w3schools.com/xml/xml_encoding.asp">XML Encoding
	 * Standard</a> from the W3C.
	 * <p>
	 * The use of a real XML parser is highly encouraged. However, in the
	 * hopefully rare case that you need to make sure that data is safe for
	 * inclusion in an XML document and cannot use a parse, this method provides
	 * a safe mechanism to do so.
	 * 
	 * @see <a href="http://www.w3schools.com/xml/xml_encoding.asp">XML Encoding Standard</a>
	 * 
	 * @param input
	 * 			the text to encode for use as an XML attribute
	 * 
	 * @return 
	 * 			input encoded for use in an XML attribute
	 */
	function encodeForXMLAttribute($input)
	{
		throw new EnterpriseSecurityException("Method Not implemented");	
	}

	/**
	 * Encode for use in a URL. This method performs <a
	 * href="http://en.wikipedia.org/wiki/Percent-encoding">URL encoding</a>
	 * on the entire string.
	 * 
	 * @see <a href="http://en.wikipedia.org/wiki/Percent-encoding">URL encoding</a>
	 * 
	 * @param input 
	 * 		the text to encode for use in a URL
	 * 
	 * @return input 
	 * 		encoded for use in a URL
	 * 
	 * @throws EncodingException 
	 * 		if encoding fails
	 */
	function encodeForURL($input)
	{
		throw new EnterpriseSecurityException("Method Not implemented");	
	}

	/**
	 * Decode from URL. Implementations should first canonicalize and
	 * detect any double-encoding. If this check passes, then the data is decoded using URL
	 * decoding.
	 * 
	 * @param input 
	 * 		the text to decode from an encoded URL
	 * 
	 * @return 
	 * 		the decoded URL value
	 * 
	 * @throws EncodingException 
	 * 		if decoding fails
	 */
	function decodeFromURL($input)
	{
		throw new EnterpriseSecurityException("Method Not implemented");	
	}

	/**
	 * Encode for Base64.
	 * 
	 * @param input 
	 * 		the text to encode for Base64
	 * @param wrap
	 * 		the encoder will wrap lines every 64 characters of output
	 * 
	 * @return input encoded for Base64
	 */
	function encodeForBase64($input, $wrap = false)
	{
		throw new EnterpriseSecurityException("Method Not implemented");	
	}
	
	/**
	 * Decode data encoded with BASE-64 encoding.
	 * 
	 * @param input 
	 * 		the Base64 text to decode
	 * 
	 * @return input 
	 * 		decoded from Base64
	 * 
	 * @throws IOException
	 */
	function decodeFromBase64($input)
	{
		throw new EnterpriseSecurityException("Method Not implemented");	
	}
}
?>