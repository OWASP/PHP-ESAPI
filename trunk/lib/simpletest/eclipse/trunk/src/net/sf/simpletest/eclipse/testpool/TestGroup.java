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

import java.util.Collection;
import java.util.HashMap;

/**
 * A TestGroup corresponds to a file
 * 
 *
 */
public class TestGroup {

    private HashMap testCases;
    private String id;
    public int numTestCount;
	public int numTestRun;
	public int numTestFailures;
	public int numTestErrors;
	public boolean expanded;
    

    public TestGroup(String id) {
        numTestCount = 0;
		numTestFailures = 0;
		numTestErrors = 0;
		numTestRun = 0;
        this.id = id;
        testCases = new HashMap();
        expanded = false;
    }
    
    public void addTestCase(TestCase tc){
        testCases.put(tc.getId(),tc);
    }
    
    public String getId(){
        return id;
    }
    
    public Collection getCases(){
        return testCases.values();
    }
	
	public boolean containsCase(String id){
	    return testCases.containsKey(id);
	}
	
	public TestCase getCase(String id){
	    return (TestCase) testCases.get(id);
	}

}
