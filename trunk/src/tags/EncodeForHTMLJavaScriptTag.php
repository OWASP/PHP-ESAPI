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

class EncodeForHTMLJavaScriptTag /* extends BodyTagSupport */ {

//	/**
//	 * 
//	 */
//	private static final long serialVersionUID = 1L;
//	private String name;
//	
//	public EncodeForHTMLJavaScriptTag() {}
//	
//	
//	
//	public int doStartTag() {
//					
//		//return EVAL_BODY_TAG; <-- Deprecated
//		return BodyTag.EVAL_BODY_BUFFERED;
//	}
//
//	
//	public int doAfterBody() throws JspTagException {
//
//		
//		try {
//			
//			BodyContent body = getBodyContent();
//			
//			String content = body.getString();
//			JspWriter out = body.getEnclosingWriter();
//			
//			Encoder e = ESAPI.encoder();
//			
//			out.println( e.encodeForJavaScript(content) );
//			body.clearBody();
//			
//			return EVAL_PAGE;
//			
//		} catch (IOException ioe) {
//			throw new JspTagException("error in encodeForHTML tag doAfterBody()",ioe);
//		}
//		
//	}
//
//	
//	public String getName() {
//		return name;
//	}
//	
//	public void setName(String name) {
//		this.name = name;
//	}
	
}
