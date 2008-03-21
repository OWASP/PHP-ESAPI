/*******************************************************************************
 * Copyright (c) 2000, 2005 IBM Corporation and others.
 * All rights reserved. This program and the accompanying materials
 * are made available under the terms of the Eclipse Public License v1.0
 * which accompanies this distribution, and is available at
 * http://www.eclipse.org/legal/epl-v10.html
 * 
 * Contributors:
 *     IBM Corporation - initial API and implementation
 *     Sebastian Davids: sdavids@gmx.de bug: 26293, 27889 
 * 	   Steven Balthazor - rework from JUnitMainTab to SimpletestMainTab
 *******************************************************************************/
/**
 * This class is derived from org.eclipse.jdt.internal.junit.launcher.JUnitMainTab
 * See the org.eclipse.jdt.junit project for source
 */
package net.sf.simpletest.eclipse.launcher;

 
import java.lang.reflect.InvocationTargetException;

import net.sf.simpletest.eclipse.ui.ElementLabelProvider;
import net.sf.simpletest.eclipse.ui.SimpletestMessages;
import net.sf.simpletest.eclipse.SimpletestPlugin;

import org.eclipse.core.resources.IFile;
import org.eclipse.core.resources.IProject;
import org.eclipse.core.resources.IWorkspaceRoot;
import org.eclipse.core.resources.ResourcesPlugin;
import org.eclipse.core.runtime.CoreException;
import org.eclipse.core.runtime.Path;
import org.eclipse.debug.core.ILaunchConfiguration;
import org.eclipse.debug.core.ILaunchConfigurationWorkingCopy;
import org.eclipse.debug.ui.AbstractLaunchConfigurationTab;
import org.eclipse.jface.dialogs.Dialog;
import org.eclipse.jface.viewers.ILabelProvider;
import org.eclipse.jface.window.Window;
import org.eclipse.swt.SWT;
import org.eclipse.swt.events.ModifyEvent;
import org.eclipse.swt.events.ModifyListener;
import org.eclipse.swt.events.SelectionAdapter;
import org.eclipse.swt.events.SelectionEvent;
import org.eclipse.swt.graphics.Image;
import org.eclipse.swt.layout.GridData;
import org.eclipse.swt.layout.GridLayout;
import org.eclipse.swt.widgets.Button;
import org.eclipse.swt.widgets.Composite;
import org.eclipse.swt.widgets.Label;
import org.eclipse.swt.widgets.Text;
import org.eclipse.ui.PlatformUI;
import org.eclipse.ui.dialogs.ElementListSelectionDialog;


/**
 * This tab appears in the LaunchConfigurationDialog for launch configurations that
 * require Java-specific launching information such as a main type and JRE.
 */
public class PHPUnit2MainTab extends AbstractLaunchConfigurationTab { 
	
	// Project UI widgets
	private Label fProjLabel;
    
	private Text fProjText;
	private Button fProjButton;
	//private Button fKeepRunning;
	
	// Test class UI widgets
	private Text fTestText;
	private Button fSearchButton;
	private final Image fTestIcon= SimpletestPlugin.createImage("sample.gif"); 
	private Label fTestMethodLabel;
	private Label fTestLabel; 
	
	/**
	 * @see ILaunchConfigurationTab#createControl(org.eclipse.swt.widgets.Composite)
	 */
	public void createControl(Composite parent) {		
		Composite comp = new Composite(parent, SWT.NONE);
		setControl(comp);

		GridLayout topLayout = new GridLayout();
		topLayout.numColumns= 3;
		comp.setLayout(topLayout);		
		
		Label label = new Label(comp, SWT.NONE);
		GridData gd = new GridData();
		gd.horizontalSpan = 3;
		label.setLayoutData(gd);
		
		createSingleTestSection(comp);
	
		
		label = new Label(comp, SWT.NONE);
		gd = new GridData();
		gd.horizontalSpan = 3;
		label.setLayoutData(gd);
		
		Dialog.applyDialogFont(comp);
		validatePage();
	}
	
	protected void createSingleTestSection(Composite comp) {
	    GridData gd = new GridData();
		gd.horizontalSpan = 3;
	    
		fProjLabel = new Label(comp, SWT.NONE);
		fProjLabel.setText(SimpletestMessages.getString("PHPUnit2MainTab.label.project")); 
		gd= new GridData();
		gd.horizontalIndent = 25;
		fProjLabel.setLayoutData(gd);
		
		fProjText= new Text(comp, SWT.SINGLE | SWT.BORDER);
		fProjText.setLayoutData(new GridData(GridData.FILL_HORIZONTAL));
		fProjText.addModifyListener(new ModifyListener() {
			public void modifyText(ModifyEvent evt) {
				validatePage();
				updateLaunchConfigurationDialog();				
				fSearchButton.setEnabled(fProjText.getText().length() > 0);
			}
		});
			
		fProjButton = new Button(comp, SWT.PUSH);
		fProjButton.setText(SimpletestMessages.getString("PHPUnit2MainTab.label.browse")); 
		fProjButton.addSelectionListener(new SelectionAdapter() {
			public void widgetSelected(SelectionEvent evt) {
				handleProjectButtonSelected();
			}
		});
        fProjButton.setLayoutData(new GridData());

		
		fTestLabel = new Label(comp, SWT.NONE);
		gd = new GridData();
		gd.horizontalIndent = 25;
		fTestLabel.setLayoutData(gd);
		fTestLabel.setText(SimpletestMessages.getString("PHPUnit2MainTab.label.test")); 
		
	
		fTestText = new Text(comp, SWT.SINGLE | SWT.BORDER);
		fTestText.setLayoutData(new GridData(GridData.FILL_HORIZONTAL));
		fTestText.addModifyListener(new ModifyListener() {
			public void modifyText(ModifyEvent evt) {
				validatePage();
				updateLaunchConfigurationDialog();
			}
		});
		
		fSearchButton = new Button(comp, SWT.PUSH);
		fSearchButton.setEnabled(fProjText.getText().length() > 0);		
		fSearchButton.setText(SimpletestMessages.getString("PHPUnit2MainTab.label.search")); 
		fSearchButton.addSelectionListener(new SelectionAdapter() {
			public void widgetSelected(SelectionEvent evt) {
				handleSearchButtonSelected();
			}
		});
        fSearchButton.setLayoutData(new GridData());
		
		new Label(comp, SWT.NONE);
		
		fTestMethodLabel= new Label(comp, SWT.NONE);
		fTestMethodLabel.setText("");  //$NON-NLS-1$
		gd= new GridData();
		gd.horizontalSpan = 2;
		fTestMethodLabel.setLayoutData(gd);
		
	}


	/**
	 * @see ILaunchConfigurationTab#initializeFrom(ILaunchConfiguration)
	 */
	public void initializeFrom(ILaunchConfiguration config) {
		updateProjectFromConfig(config);
		updateTestPathFromConfig(config);
	}
	
	/**
	 * @see ILaunchConfigurationTab#setDefaults(ILaunchConfigurationWorkingCopy)
	 */
	public void setDefaults(ILaunchConfigurationWorkingCopy config) {
		/*
        IJavaElement javaElement = getContext();
		if (javaElement != null) {
			//find the project selected
		} else {
			// We set empty attributes for project so that when one config is
			// compared to another, the existence of empty attributes doesn't cause an
			// incorrect result (the performApply() method can result in empty values
			// for these attributes being set on a config if there is nothing in the
			// corresponding text boxes)
			//config.setAttribute(IJavaLaunchConfigurationConstants.ATTR_PROJECT_NAME, ""); //$NON-NLS-1$
		}
		//initialize based on the project selected
		 */
	}

	protected void updateProjectFromConfig(ILaunchConfiguration config) {
		String projectName= ""; //$NON-NLS-1$
		try {
			projectName = config.getAttribute(LaunchConfigurationConstants.ATTR_PROJECT_NAME, ""); //$NON-NLS-1$
		} catch (CoreException ce) {
		}
		fProjText.setText(projectName);
	}
	
	protected void updateTestPathFromConfig(ILaunchConfiguration config) {
		String testPath= ""; //$NON-NLS-1$
		try {
			testPath = config.getAttribute(SimpletestPlugin.PHP_FILE, ""); //$NON-NLS-1$
		} catch (CoreException ce) {			
		}
		fTestText.setText(testPath);
		
	}

	
	/**
	 * @see ILaunchConfigurationTab#performApply(ILaunchConfigurationWorkingCopy)
	 */
	public void performApply(ILaunchConfigurationWorkingCopy config) {
		config.setAttribute(LaunchConfigurationConstants.ATTR_PROJECT_NAME, fProjText.getText());
		config.setAttribute(SimpletestPlugin.PHP_FILE, fTestText.getText());
	}

	/**
	 * @see ILaunchConfigurationTab#dispose()
	 */
	public void dispose() {
		super.dispose();
		fTestIcon.dispose();
	}

	/**
	 * @see AbstractLaunchConfigurationTab#getImage()
	 */
	public Image getImage() {
		return fTestIcon;
	}

	/**
	 * Show a dialog that lists all main types
	 */
	protected void handleSearchButtonSelected() {
	    IFile file = chooseFile();
		if (file == null) {
			return;
		}
		
        fTestText.setText(file.getRawLocation().toOSString());	
	}
	
	protected IFile chooseFile() {
		IFile[] files= new IFile[0];
		//need the project to find the files...
		IProject thisProject = getProject();
		try {
			files= TestSearchEngine.findTests(PlatformUI.getWorkbench().getProgressService(), new Object[] {thisProject}); 
		} catch (InterruptedException e) {
			setErrorMessage(e.getMessage());
			return null;
		} catch (InvocationTargetException e) {
            e.printStackTrace();
            SimpletestPlugin.logError(e.getTargetException().toString(),e);
			return null;
		}
		
		
		
		ILabelProvider labelProvider= new ElementLabelProvider();
		ElementListSelectionDialog dialog= new ElementListSelectionDialog(getShell(), labelProvider);
		dialog.setTitle(SimpletestMessages.getString("PHPUnit2MainTab.testdialog.title")); 
		dialog.setMessage(SimpletestMessages.getString("PHPUnit2MainTab.testdialog.message")); 
		dialog.setElements(files);
		
		IFile thisFile = getFile();
		
		if (thisFile != null) {
			dialog.setInitialSelections(new Object[] { thisFile });
		}
		if (dialog.open() == Window.OK) {			
			return (IFile) dialog.getFirstResult();
		}			
		return null;	
	}
		
	/**
	 * Show a dialog that lets the user select a project.  This in turn provides
	 * context for the main type, allowing the user to key a main type name, or
	 * constraining the search for main types to the specified project.
	 */
	protected void handleProjectButtonSelected() {
		IProject project = chooseProject();
		if (project == null) {
			return;
		}
		
		String projectName = project.getName();
		fProjText.setText(projectName);		
		fTestText.setText("");
	}
	
	/**
	 * Realize a Java Project selection dialog and return the first selected project,
	 * or null if there was none.
	 */
	protected IProject chooseProject() {
		IProject[] projects;
		
	    projects= getWorkspaceRoot().getProjects();

		
		ILabelProvider labelProvider= new ElementLabelProvider();
		ElementListSelectionDialog dialog= new ElementListSelectionDialog(getShell(), labelProvider);
		dialog.setTitle(SimpletestMessages.getString("PHPUnit2MainTab.projectdialog.title")); 
		dialog.setMessage(SimpletestMessages.getString("PHPUnit2MainTab.projectdialog.message")); 
		dialog.setElements(projects);
		
		IProject thisProject = getProject();
		if (thisProject != null) {
			dialog.setInitialSelections(new Object[] { thisProject });
		}
		if (dialog.open() == Window.OK) {			
			return (IProject) dialog.getFirstResult();
		}			
		return null;		
	}
	
	/**
	 * Return the IProject corresponding to the project name in the project name
	 * text field, or null if the text does not match a project name.
	 */
	protected IProject getProject() {
		String projectName = fProjText.getText().trim();
		if (projectName.length() < 1) {
			return null;
		}
		return getWorkspaceRoot().getProject(projectName);		
	}
	
	/**
	 * Return the IFile corresponding to the selected file
	 */
	protected IFile getFile() {
		String filePath = fTestText.getText().trim();
		if (filePath.length() < 1) {
			return null;
		}
		IFile returnFile= null;
		
		try {
            returnFile = getWorkspaceRoot().getFile(new Path(filePath));
        } catch (RuntimeException e) {
            //this does not resolve to a path so let's blank it out...
            fTestText= null;
        }
		
		return returnFile;		
	}
	
	/**
	 * Convenience method to get the workspace root.
	 */
	private IWorkspaceRoot getWorkspaceRoot() {
		return ResourcesPlugin.getWorkspace().getRoot();
	}
	
	/**
	 * @see ILaunchConfigurationTab#isValid(ILaunchConfiguration)
	 */
	public boolean isValid(ILaunchConfiguration config) {		
		return getErrorMessage() == null;
	}
	
	private void validatePage() {
		setErrorMessage(null);
		setMessage(null);
		
		String projectName = fProjText.getText().trim();
		if (projectName.length() == 0) {
			setErrorMessage(SimpletestMessages.getString("PHPUnit2MainTab.error.projectnotdefined")); 
			return;
		}
			
		IProject project = getWorkspaceRoot().getProject(projectName);
		if (!project.exists()) {
			setErrorMessage(SimpletestMessages.getString("PHPUnit2MainTab.error.projectnotexists")); 
			return;
		}
		
		IFile tmpFile = getFile();
		if (tmpFile == null){
			setErrorMessage(SimpletestMessages.getString("PHPUnit2MainTab.error.testnotdefined")); 
			return;
		}
		if (project.getFile(tmpFile.getFullPath()) == null){
		    setErrorMessage(SimpletestMessages.getString("PHPUnit2MainTab.error.testnotdefined")); 
			return;
		}
	}

	
	/**
	 * @see ILaunchConfigurationTab#getName()
	 */
	public String getName() {
		return SimpletestMessages.getString("PHPUnit2MainTab.tab.label"); 
	}
}
