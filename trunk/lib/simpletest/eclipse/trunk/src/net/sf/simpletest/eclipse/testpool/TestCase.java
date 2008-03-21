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
 * A TestCase contains a Vector of TestInstances
 * A TestCase corresponds to a single test class
 */
public class TestCase {


    public HashMap testInstances;
    private String id;
    public int numTestCount;
	public int numTestRun;
	public int numTestFailures;
	public int numTestErrors;
    private String filePath;
    public boolean expanded;
    

    public TestCase(String id,String path) {
        numTestCount = 0;
		numTestFailures = 0;
		numTestErrors = 0;
		numTestRun = 0;
        this.id = id;
        testInstances = new HashMap();
        filePath = path;
        expanded = false;
    }
    
    public String getId(){
        return id;
    }
    
    public Collection getInstances(){
        return testInstances.values();
    }
    
	public void addTestInstance(TestInstance ti) {
		testInstances.put(ti.getId(), ti);	
	} 
	
	public boolean containsCase(String id){
	    return testInstances.containsKey(id);
	}
	
	public TestInstance getInstance(String id){
	    return (TestInstance) testInstances.get(id);
	}
    
    public String getTestFilePath(){
        return filePath;
    }
    
    public int getLine(){
        //TODO: figure out where in the file the test method is
        return 1;
    }

}
