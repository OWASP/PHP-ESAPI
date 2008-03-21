/*******************************************************************************
 * Copyright (c) 2000, 2005 IBM Corporation and others.
 * All rights reserved. This program and the accompanying materials
 * are made available under the terms of the Eclipse Public License v1.0
 * which accompanies this distribution, and is available at
 * http://www.eclipse.org/legal/epl-v10.html
 * 
 * Contributors:
 *     IBM Corporation - initial API and implementation
 * 	   Steven Balthazor - reworked for conversion from junit to simpletest
 *******************************************************************************/
/**
 * This class is derived from org.eclipse.jdt.internal.junit.launcher.JUnitLaunchShortcut
 * See the org.eclipse.jdt.junit project for source
 */
package net.sf.simpletest.eclipse.launcher;

import java.util.ArrayList;
import java.util.Collections;
import java.util.List;

import net.sf.simpletest.eclipse.SimpletestPlugin;

import org.eclipse.core.resources.IFile;
import org.eclipse.core.runtime.CoreException;
import org.eclipse.debug.core.ILaunchConfiguration;
import org.eclipse.debug.core.ILaunchConfigurationType;
import org.eclipse.debug.core.ILaunchConfigurationWorkingCopy;
import org.eclipse.debug.ui.ILaunchShortcut;

import org.eclipse.jface.viewers.ISelection;
import org.eclipse.jface.viewers.IStructuredSelection;
import org.eclipse.jface.viewers.StructuredSelection;
import org.eclipse.ui.IEditorInput;
import org.eclipse.ui.IEditorPart;

abstract class TestLaunchShortcut implements ILaunchShortcut
{
    
    public static String ATTR_LAUNCHING_CONFIG_HANDLE= SimpletestPlugin.PLUGIN_ID + "launching_config_handle"; //$NON-NLS-1$
    public static final String ID_SIMPLETEST_LAUNCHCONFIG = "net.sf.simpletest.eclipse.launcher.stlaunchconfig";
    public static final String ID_PHPUNIT2_LAUNCHCONFIG = "net.sf.simpletest.eclipse.launcher.pulaunchconfig";
    public static final String ID_PHPUNIT3_LAUNCHCONFIG = "net.sf.simpletest.eclipse.launcher.pu3slaunchconfig";
    
    //this handles right click on editor to launch (also from shortcuts)
	public void launch(IEditorPart editor, String mode) {
        IEditorInput input = editor.getEditorInput();
        ISelection selection = new StructuredSelection(input.getAdapter(IFile.class));
		if (selection != null) {
			launch(selection, mode);
		} 
	}
	
	
	/**
     * this handles right click in explorer to launch
	 * @see ILaunchShortcut#launch(ISelection, String)
	 */
	public void launch(ISelection search, String mode) {
        if (search instanceof IStructuredSelection) { 
	
            Object firstSelection = ((IStructuredSelection) search).getFirstElement();
            if (firstSelection instanceof IFile) { 
                
        		IFile file = (IFile)firstSelection;
        		
        		if (file != null) {
                    String filepath = file.getRawLocation().toOSString();
                    String project = file.getProject().getName();
                    ILaunchConfiguration config = findLaunchConfiguration(mode,filepath,project);
                    if (config == null) {
                        config= createConfiguration(project,filepath,filepath);
                    }
                    try {
                        if (config != null)
                            //this routes through the guts of eclipse and ends up
                            //running the org.eclipse.debug.core.launchConfigurationTypes delegate
                            config.launch(mode, null);
                    } catch (CoreException e) {
                        //e.printStackTrace();
                        //SimpletestPlugin.logError("Error launching",e);
                    }
        		}
            }
        }
	}
	
		
	private ILaunchConfiguration findLaunchConfiguration(String mode, String filepath, String project) {
		ILaunchConfigurationType configType= getLaunchConfigType();
		List candidateConfigs= Collections.EMPTY_LIST;
		try {
			ILaunchConfiguration[] configs= SimpletestPlugin.getLaunchManager().getLaunchConfigurations(configType);
			candidateConfigs= new ArrayList(configs.length);
			for (int i= 0; i < configs.length; i++) {
				ILaunchConfiguration config= configs[i];
				if ((config.getAttribute(SimpletestPlugin.PHP_FILE, "").equals(filepath)) && 
					(config.getAttribute(LaunchConfigurationConstants.ATTR_PROJECT_NAME, "").equals(project))) {  
						candidateConfigs.add(config);
				}
			}
		} catch (CoreException e) {
            e.printStackTrace();
            SimpletestPlugin.logError("Error finding lauch configurations",e);
		}
		
		// If there are no existing configs, create one.
		// If there is exactly one config associated with the filepath, return it.
		// Otherwise, if there is more than one config associated with the filepath, prompt the
		// user to choose one.
		int candidateCount= candidateConfigs.size();
		if (candidateCount < 1) {
			return null;
		} else if (candidateCount == 1) {
			return (ILaunchConfiguration) candidateConfigs.get(0);
		} 
		return null;
	}

	protected ILaunchConfiguration createConfiguration(
			String project, String filepath,String testName) {
				
		ILaunchConfiguration config= null;
		try {
			ILaunchConfigurationType configType= getLaunchConfigType();
			ILaunchConfigurationWorkingCopy wc = configType.newInstance(null, testName); 
			wc.setAttribute(LaunchConfigurationConstants.ATTR_PROJECT_NAME, project);
			wc.setAttribute(SimpletestPlugin.PHP_FILE, filepath);
			wc.setAttribute(LaunchConfigurationConstants.ATTR_COVERAGE, false);
			config= wc.doSave();		
		} catch (CoreException ce) {
            ce.printStackTrace();
            SimpletestPlugin.logError("Error creating Launch Configuration.",ce);
		}
		return config;
	}
	
    abstract protected ILaunchConfigurationType getLaunchConfigType();
		
}
