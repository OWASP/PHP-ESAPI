/**********************************************************************
 * Copyright (c) 2006 Steven Balthazor
 * All rights reserved. This program and the accompanying materials
 * are made available under the terms of the Eclipse Public License v1.0
 * which accompanies this distribution, and is available at
 * http://www.eclipse.org/legal/epl-v10.html
 * 
 * Contributors:
 *     Steven Balthazor - initial API and implementation
 **********************************************************************/
package net.sf.simpletest.eclipse.reporthandling;

import net.sf.simpletest.eclipse.SimpletestPlugin;
import net.sf.simpletest.eclipse.json.JSONException;
import net.sf.simpletest.eclipse.json.JSONObject;
import net.sf.simpletest.eclipse.testpool.TestCase;
import net.sf.simpletest.eclipse.testpool.TestGroup;
import net.sf.simpletest.eclipse.testpool.TestInstance;
import net.sf.simpletest.eclipse.testpool.TestPool;


public class JSONReportHandler  {
    
    private TestPool pool;  
    private String groupId;
    private String caseId;
    private String testId;
    
    
    
    public void handle(String report, TestPool tp){
        this.pool = tp;
        
        try {
            JSONObject result = new JSONObject(report);
            if ("info".equals(result.getString("status"))){
            	SimpletestPlugin.logMessage(result.getString("message"));
            	return;
            }
            if ("coverage".equals(result.getString("status"))){
            	SimpletestPlugin.logMessage(result.getString("message"));
            	return;
            }

                
                try{
                    groupId = result.getString("group");
                    if (groupId != null && !"".equals(groupId)){
                        if (!pool.containsGroup(groupId)){
                            pool.addTestGroup(new TestGroup(groupId));
                        }
                    }else{
                        return;
                    }
                }catch (JSONException je){
                    //if this does not exist then skip it
                    return;
                }
                
                caseId = result.getString("case");
                if (caseId != null && !"".equals(caseId)){
                    if (pool.getCase(groupId,caseId) == null){
                        pool.addTestCase(groupId,new TestCase(caseId,groupId));
                    }
                }else{
                    return;
                }

                
                testId = result.getString("method");
                pool.addTestInstance(groupId,caseId,new TestInstance(testId,groupId));
                
                String status = result.getString("status");
                String message = result.getString("message");
                if ("pass".equals(status)){
                    pool.setResult(groupId,caseId,testId,TestInstance.PASS,message);
                }else if ("fail".equals(status)){
                    pool.setResult(groupId,caseId,testId,TestInstance.FAIL,message);
                }else if ("error".equals(status)){
                    pool.setResult(groupId,caseId,testId,TestInstance.ERROR,message);
                }
            
          
        } catch (JSONException e) {
            // TODO Auto-generated catch block
            e.printStackTrace();
        }
    
    }
}