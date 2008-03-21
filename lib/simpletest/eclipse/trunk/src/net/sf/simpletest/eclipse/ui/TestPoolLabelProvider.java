/**********************************************************************
 * Copyright (c) 2005 Ali Echihabi 
 * All rights reserved. This program and the accompanying materials
 * are made available under the terms of the Eclipse Public License v1.0
 * which accompanies this distribution, and is available at
 * http://www.eclipse.org/legal/epl-v10.html
 * 
 * Contributors:
 *     Ali Echihabi - initial API and implementation
 * 	   Steven Balthazor - rework for Simpletest
 **********************************************************************/
/**
 * This class is derived from net.sf.phpeclipse.phpunit.TestPoolLabelProvider
 * See the net.sf.phpeclipse.phpunit project for source
 */
package net.sf.simpletest.eclipse.ui;


import java.io.File;

import net.sf.simpletest.eclipse.testpool.TestCase;
import net.sf.simpletest.eclipse.testpool.TestGroup;
import net.sf.simpletest.eclipse.testpool.TestInstance;
import net.sf.simpletest.eclipse.SimpletestPlugin;

import org.eclipse.jface.viewers.LabelProvider;
import org.eclipse.swt.graphics.Image;
 
 
public class TestPoolLabelProvider extends LabelProvider {

    
    private final Image fOkIcon= SimpletestPlugin.createImage("testok.gif");
	private final Image fErrorIcon= SimpletestPlugin.createImage("testerr.gif"); 
	private final Image fFailureIcon= SimpletestPlugin.createImage("testfail.gif"); 
	private final Image fNoneIcon=SimpletestPlugin.createImage("test.gif");
	private final Image fSuiteIcon= SimpletestPlugin.createImage("tsuiteok.gif"); 
	private final Image fSuiteErrorIcon= SimpletestPlugin.createImage("tsuiteerror.gif"); 
	private final Image fSuiteFailIcon= SimpletestPlugin.createImage("tsuitefail.gif"); 
	private final Image fSuiteNoneIcon=SimpletestPlugin.createImage("tsuite.gif");
	
	public String getText(Object element) {
		if(element instanceof TestGroup) {
			String grouppath = ((TestGroup)element).getId();
            File groupfile = new File(grouppath);
            return groupfile.getName();
        }else if(element instanceof TestCase) {
			return ((TestCase)element).getId();
		}
		else if(element instanceof TestInstance) {
			return ((TestInstance)element).getTestInfo();
		}else{
		    return null;
		}
		
	}

	public Image getImage(Object element) {
		
		if(element instanceof TestGroup) {
			
			TestGroup tg = (TestGroup)element;

			if(tg.numTestErrors > 0)
			    return fSuiteErrorIcon;
			else if(tg.numTestFailures > 0)
			    return fSuiteFailIcon;
			else if(tg.numTestCount == tg.numTestRun)
				return fSuiteIcon;
			else	
			    return fSuiteIcon;	
		}else if(element instanceof TestCase) {
			
			TestCase tc = (TestCase)element;

			if(tc.numTestErrors > 0)
			    return fSuiteErrorIcon;
			else if(tc.numTestFailures > 0)
			    return fSuiteFailIcon;
			else if(tc.numTestRun > 0)
				return fSuiteIcon;
			else	
			    return fSuiteNoneIcon;		
			
		} else if(element instanceof TestInstance) {
			
			TestInstance test = (TestInstance)element;
				
			if(test.isError())
			    return fErrorIcon;
			else if(test.isFailure())
			    return fFailureIcon;
			else if(test.isPass())
			    return fOkIcon;
			else
			    return fNoneIcon;
				
					
		}
		return null;

		
	}

}
