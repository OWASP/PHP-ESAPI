/**********************************************************************
 * Copyright (c) 2005 Steven Balthazor
 * All rights reserved. This program and the accompanying materials
 * are made available under the terms of the Eclipse Public License v1.0
 * which accompanies this distribution, and is available at
 * http://www.eclipse.org/legal/epl-v10.html
 * 
 * Contributors:
 *     Steven Balthazor - initial API and implementation
 **********************************************************************/
package net.sf.simpletest.eclipse.testpool;

import java.util.regex.Matcher;
import java.util.regex.Pattern;
import java.util.regex.PatternSyntaxException;



/**
 * A TestInstance contains the results from a test method 
 * the test method may contain one or more test assertions;
 * if the test method returns all pass then this will be set to pass
 * if the test method returns a fail then this will be set to fail with information on the first failure 
 */
public class TestInstance {

    public static final String NONE = "NONE";
	public static final String PASS = "PASS";
	public static final String FAIL = "FAIL";
	public static final String ERROR = "ERROR";

	private String id;
	private String verdict;
	private String details;
    //private String filePath;
	
	public TestInstance(String _id,String path) {
	    id = _id;
	    verdict = NONE;
        //filePath = path;
	}

	public String getId(){
	    return id;
	}
	
	
	public String getTestInfo(){
	    StringBuffer sb = new StringBuffer("");
	    sb.append(id);
	    if (isFailure()||isError()){
	        sb.append(" {");
	        sb.append(details);
	        sb.append("}");
	    }
	    return sb.toString();
	}

	public void setResult(String verdict,String details) {
	    if (NONE.equals(this.verdict) || PASS.equals(this.verdict)){
	        this.verdict = verdict;
	        this.details = details;
	    }
	}
    
    public String getTestFilePath(){
        /*
        return filePath;
        */
        
        Pattern pattern;
        String patternString = ".*\\[(.*) line (.*)\\]$";
        try {
            pattern = Pattern.compile(patternString, Pattern.CASE_INSENSITIVE);
        } catch(PatternSyntaxException mpe) {
            //eat the error
            return "";
        }
        Matcher m = pattern.matcher(details);
        if (!m.find()){
            return "";
        }
        String sline = m.group(1);
        return sline;
      
    }
    
    public int getLine(){
        Pattern pattern;
        String patternString = "\\[.* (.*)\\]$";
        try {
            pattern = Pattern.compile(patternString, Pattern.CASE_INSENSITIVE);
        } catch(PatternSyntaxException mpe) {
            //eat the error
            return 1;
        }
        Matcher m = pattern.matcher(details);
        if (!m.find()){
            return 1;
        }
        String sline = m.group(1);
        sline = sline.trim();
        return Integer.parseInt(sline);
    }

	public boolean isError() {
	    return ERROR.equals(verdict);
	}

	public boolean isFailure() {
	    return FAIL.equals(verdict);
	}

	public boolean isPass() {
	    return PASS.equals(verdict);
	}
    
    

}
