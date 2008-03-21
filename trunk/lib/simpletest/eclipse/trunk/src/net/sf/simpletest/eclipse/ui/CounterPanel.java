/*******************************************************************************
 * Copyright (c) 2000, 2005 IBM Corporation and others.
 * All rights reserved. This program and the accompanying materials
 * are made available under the terms of the Eclipse Public License v1.0
 * which accompanies this distribution, and is available at
 * http://www.eclipse.org/legal/epl-v10.html
 *
 * Contributors:
 *     IBM Corporation - initial API and implementation
 *     Steven Balthazor - rework for Simpletest
 *******************************************************************************/
/**
 * This class is derived from org.eclipse.jdt.internal.junit.ui.CounterPanel
 * See the org.eclipse.jdt.junit plugin for source
 */
package net.sf.simpletest.eclipse.ui;


import java.text.MessageFormat;

import org.eclipse.swt.SWT;
import org.eclipse.swt.events.DisposeEvent;
import org.eclipse.swt.events.DisposeListener;
import org.eclipse.swt.graphics.Image;
import org.eclipse.swt.layout.GridData;
import org.eclipse.swt.layout.GridLayout;
import org.eclipse.swt.widgets.Composite;
import org.eclipse.swt.widgets.Label;
import org.eclipse.swt.widgets.Text;


/**
 * A panel with counters for the number of Runs, Errors and Failures.
 */
public class CounterPanel extends Composite {
    protected Text fNumberOfErrors;
    protected Text fNumberOfFailures;
    protected Text fNumberOfRuns;
    protected int fTotal;
    
    private final Image fErrorIcon= SimpletestImages.get(SimpletestImages.IMG_TEST_ERROR); 
        //TestRunnerViewPart.createImage("ovr16/error_ovr.gif");
    private final Image fFailureIcon= SimpletestImages.get(SimpletestImages.IMG_TEST_FAILURE); 
        //TestRunnerViewPart.createImage("ovr16/failed_ovr.gif"); //$NON-NLS-1$
            
    public CounterPanel(Composite parent) {
        super(parent, SWT.WRAP);
        GridLayout gridLayout= new GridLayout();
        gridLayout.numColumns= 9;
        gridLayout.makeColumnsEqualWidth= false;
        gridLayout.marginWidth= 0;
        setLayout(gridLayout);
        
        fNumberOfRuns= createLabel(SimpletestMessages.getString("CounterPanel.label_runs"), null, " 0/0  "); 
        fNumberOfErrors= createLabel(SimpletestMessages.getString("CounterPanel.label_errors"), fErrorIcon, " 0 "); 
        fNumberOfFailures= createLabel(SimpletestMessages.getString("CounterPanel.label_failures"), fFailureIcon, " 0 "); 

        addDisposeListener(new DisposeListener() {
            public void widgetDisposed(DisposeEvent e) {
                disposeIcons();
            }
        });
    }
 
    private void disposeIcons() {
        fErrorIcon.dispose();
        fFailureIcon.dispose();
    }

    private Text createLabel(String name, Image image, String init) {
        Label label= new Label(this, SWT.NONE);
        if (image != null) {
            image.setBackground(label.getBackground());
            label.setImage(image);
        }
        label.setLayoutData(new GridData(GridData.HORIZONTAL_ALIGN_BEGINNING));
        
        label= new Label(this, SWT.NONE);
        label.setText(name);
        label.setLayoutData(new GridData(GridData.HORIZONTAL_ALIGN_BEGINNING));
        //label.setFont(JFaceResources.getBannerFont());
        
        Text value= new Text(this, SWT.READ_ONLY);
        value.setText(init);
        // bug: 39661 Junit test counters do not repaint correctly [JUnit] 
        value.setBackground(getDisplay().getSystemColor(SWT.COLOR_WIDGET_BACKGROUND));
        value.setLayoutData(new GridData(GridData.FILL_HORIZONTAL | GridData.HORIZONTAL_ALIGN_BEGINNING));
        return value;
    }

    public void reset() {
        setErrorValue(0);
        setFailureValue(0);
        setRunValue(0);
        fTotal= 0;
    }
    
    public void setTotal(int value) {
        fTotal= value;
    }
    
    public int getTotal(){
        return fTotal;
    }
    
    public void setRunValue(int value) {
       
        String runString= MessageFormat.format(SimpletestMessages.getString("CounterPanel.runcount"), new String[] { Integer.toString(value), Integer.toString(fTotal) }); 
        fNumberOfRuns.setText(runString);

        fNumberOfRuns.redraw();
        redraw();
    }
    
    public void setErrorValue(int value) {
        fNumberOfErrors.setText(Integer.toString(value));
        redraw();
    }
    
    public void setFailureValue(int value) {
        fNumberOfFailures.setText(Integer.toString(value));
        redraw();
    }
}
