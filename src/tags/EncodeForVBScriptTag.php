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
 * @package org.owasp.esapi.tags
 */

//import org.owasp.esapi.ESAPI;
//import org.owasp.esapi.Encoder;

class EncodeForVBScriptTag /* extends BodyTagSupport */ {

//	/**
//	 * 
//	 */
//	private static final long serialVersionUID = 1L;
//	private String name;
//	
//	public EncodeForVBScriptTag() {}
//	
//	
//	public int doStartTag() {
//					
//		//return EVAL_BODY_TAG; <-- Deprecated
//		return BodyTag.EVAL_BODY_BUFFERED;
//
//	}
//	
//	public int doEndTag() {
//		
//		return SKIP_BODY;
//		
//	}
//	
//	public int doAfterBody() throws JspTagException {
//
//
//		BodyContent body = getBodyContent();
//		
//		String content = body.getString();
//		JspWriter out = body.getEnclosingWriter();
//		
//		Encoder e = ESAPI.encoder();
//		
//		try {
//			
//			out.println( e.encodeForVBScript(content) );
//			body.clearBody();
//			
//		} catch (IOException ioe) {
//			throw new JspTagException("error in encodeForHTML tag doAfterBody()",ioe);
//		}
//		
//		return SKIP_BODY;
//
//	}
//	
//	public String getName() {
//		return name;
//	}
//	
//	public void setName(String name) {
//		this.name = name;
//	}

	
}
