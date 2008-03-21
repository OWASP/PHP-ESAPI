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
 * A TestPool contains a HashMap of one or more TestGroups
 */ 
public class TestPool {
	private HashMap testGroups;
	public int numTestCount;
	public int numTestRun;
	public int numTestFailures;
	public int numTestErrors;
	

	public TestPool(String rootTitle) {
		numTestCount = 0;
		numTestFailures = 0;
		numTestErrors = 0;
		numTestRun = 0;
		testGroups = new HashMap();
		
	}
	
	public void addTestGroup(TestGroup tg) {
		testGroups.put(tg.getId(), tg);	
	} 
	
	public boolean containsGroup(String groupId){
	    return testGroups.containsKey(groupId);
	}
	
	public Collection getGroups(){
	    return testGroups.values();
	}
	
	public TestGroup getGroup(String groupId){
	    TestGroup tg = null;
	    if (containsGroup(groupId)){
	        tg = (TestGroup) testGroups.get(groupId);
	    }
	    return tg;
	   
	}
	
	public TestCase getCase(String groupId, String caseId){
	    TestCase tc = null;
	    TestGroup tg = getGroup(groupId);
	    if (tg != null){
	        tc = tg.getCase(caseId);
	    }
	    return tc;
	}
	
	public void addTestCase(String groupId, TestCase tc){
	    TestGroup tg = getGroup(groupId);
	    tg.addTestCase(tc);
	}
	
	public TestInstance getInstance(String groupId, String caseId, String testId){
	    TestInstance ti = null;
	    TestCase tc = getCase(groupId, caseId);
	    if (tc != null){
	        ti = tc.getInstance(testId);
	    }
	    return ti;
	}
	
	public void addTestInstance(String groupId, String caseId, TestInstance ti){
	    TestCase tc = getCase(groupId,caseId);
	    tc.addTestInstance(ti);
	}
	
	public void setResult(String groupId,String caseId,String testId,String code,String desc){
	    TestGroup tg = getGroup(groupId);
	    TestCase tc = getCase(groupId,caseId);
	    numTestCount++;
	    tg.numTestCount++;
	    tc.numTestCount++;
	    if(TestInstance.FAIL.equals(code)){
	        tg.numTestFailures++;
	        tc.numTestFailures++;
	        numTestFailures++;
	        tg.numTestRun++;
	        tc.numTestRun++;
	        numTestRun++;
	        tc.expanded = true;
	        tg.expanded = true;
	    }else if (TestInstance.ERROR.equals(code)){
	        tg.numTestErrors++;
	        tc.numTestErrors++;
	        numTestErrors++;
	        tg.numTestRun++;
	        tc.numTestRun++;
	        numTestRun++;
	        tc.expanded = true;
	        tg.expanded = true;
	    }else if (TestInstance.PASS.equals(code)){
	        tg.numTestRun++;
	        tc.numTestRun++;
	        numTestRun++;
	    }
	        
	    TestInstance ti = getInstance(groupId,caseId,testId);
	    ti.setResult(code,desc);
	}

}
