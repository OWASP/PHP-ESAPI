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
 * This class is derived from net.sf.phpeclipse.phpunit.ProgressInfoComposite
 * See the net.sf.phpeclipse.phpunit project for source
 */
package net.sf.simpletest.eclipse.ui;


import net.sf.simpletest.eclipse.testpool.TestPool;
import net.sf.simpletest.eclipse.SimpletestPlugin;

import org.eclipse.swt.SWT;
import org.eclipse.swt.graphics.Image;
import org.eclipse.swt.layout.GridData;
import org.eclipse.swt.layout.GridLayout;
import org.eclipse.swt.widgets.Composite;
import org.eclipse.swt.widgets.Label;



public class ProgressInfoComposite extends Composite {
    
   	private Label labelRuns, labelRunsVal; // Runs: 12
	private Label labelErrors, labelErrorsImage, labelErrorsVal;
	private Label labelFailures, labelFailuresImage, labelFailuresVal;
	
	private final Image fFailureIcon= SimpletestPlugin.createImage("testfail.gif"); 
	private final Image fErrorIcon= SimpletestPlugin.createImage("testerr.gif"); 
	
	private int curstep=0;
		
	private ProgressBar progressBar;

	/**
	 * @param arg0
	 * @param arg1
	 */
	public ProgressInfoComposite(Composite parent) {
	    super(parent, SWT.NONE);
	    
		
		GridLayout gridLayout = new GridLayout();
		gridLayout.numColumns = 1;
		
		// set title and layout
		setLayout(gridLayout);
		

		// set the progress bar
		progressBar = new ProgressBar(parent);
		progressBar.setLayoutData(
			new GridData(GridData.GRAB_HORIZONTAL | GridData.FILL_HORIZONTAL));
		
			

		Composite labelsComposite =
			new Composite(this, SWT.WRAP);
		
		labelsComposite.setLayoutData(
			new GridData(GridData.GRAB_HORIZONTAL  | GridData.HORIZONTAL_ALIGN_CENTER));

        GridLayout lblGridLayout= new GridLayout();
        lblGridLayout.numColumns= 8;
        lblGridLayout.makeColumnsEqualWidth= false;
        lblGridLayout.marginWidth= 0;
        
        labelsComposite.setLayout(lblGridLayout);

		labelRuns = new Label(labelsComposite, SWT.NONE);
		labelRuns.setText("Runs: ");
        labelRuns.setLayoutData(new GridData(GridData.HORIZONTAL_ALIGN_BEGINNING));
		labelRunsVal = new Label(labelsComposite, SWT.READ_ONLY);
		labelRunsVal.setText("0 / 0");
        labelRunsVal.setLayoutData(new GridData(GridData.FILL_HORIZONTAL | GridData.HORIZONTAL_ALIGN_BEGINNING));
        

		labelFailuresImage = new Label(labelsComposite, SWT.NONE);
		labelFailuresImage.setImage(fFailureIcon);
        labelFailuresImage.setLayoutData(new GridData(GridData.HORIZONTAL_ALIGN_BEGINNING));
		labelFailures = new Label(labelsComposite, SWT.NONE);
		labelFailures.setText("Failures: ");
        labelFailures.setLayoutData(new GridData(GridData.HORIZONTAL_ALIGN_BEGINNING));
		labelFailuresVal = new Label(labelsComposite, SWT.READ_ONLY);
		labelFailuresVal.setText("0");
        labelFailuresVal.setLayoutData(new GridData(GridData.FILL_HORIZONTAL | GridData.HORIZONTAL_ALIGN_BEGINNING));
        


		labelErrorsImage = new Label(labelsComposite, SWT.NONE);
		labelErrorsImage.setImage(fErrorIcon);
        labelErrorsImage.setLayoutData(new GridData(GridData.HORIZONTAL_ALIGN_BEGINNING));
		labelErrors = new Label(labelsComposite, SWT.NONE);
		labelErrors.setText("Errors: ");
        labelErrors.setLayoutData(new GridData(GridData.HORIZONTAL_ALIGN_BEGINNING));
		labelErrorsVal = new Label(labelsComposite, SWT.READ_ONLY);
		labelErrorsVal.setText("0");	
        labelErrorsVal.setLayoutData(new GridData(GridData.FILL_HORIZONTAL | GridData.HORIZONTAL_ALIGN_BEGINNING));
        
	}

	public void resetInfo() {
	    curstep = 0;
		labelErrorsVal.setText("0");
		labelFailuresVal.setText("0");
		labelRunsVal.setText("0 / 0");
		progressBar.reset();
	}

	public void updateInfo(TestPool testPool) {
		
		int numTestsOverall = testPool.numTestCount;
		int numTestsRun = testPool.numTestRun;
		
		//update progress bar
		progressBar.setMaximum(numTestsOverall);
		while (curstep < numTestsRun){
		    progressBar.step(testPool.numTestFailures+testPool.numTestErrors);
		    curstep++;
		}
		
	
	
		//update labels
		labelRunsVal.setText(numTestsRun + " / " + numTestsOverall);
		labelFailuresVal.setText("" + testPool.numTestFailures);
		labelErrorsVal.setText("" + testPool.numTestErrors);
		
	}

}
