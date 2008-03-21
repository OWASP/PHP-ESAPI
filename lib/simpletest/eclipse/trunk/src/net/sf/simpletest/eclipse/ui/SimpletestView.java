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
 * This class is derived from net.sf.phpeclipse.phpunit.PHPUnitView
 * See the net.sf.phpeclipse.phpunit project for source
 */
//TODO: implement lock so only one test can run at a time -- make them queue
package net.sf.simpletest.eclipse.ui;



import net.sf.simpletest.eclipse.SimpletestPlugin;
import net.sf.simpletest.eclipse.reporthandling.ConnectionListener;
import net.sf.simpletest.eclipse.reporthandling.JSONReportHandler;
import net.sf.simpletest.eclipse.testpool.TestPool;

import org.eclipse.swt.layout.GridData;
import org.eclipse.swt.layout.GridLayout;
import org.eclipse.swt.widgets.Composite;
import org.eclipse.ui.IWorkbenchPage;
import org.eclipse.ui.PartInitException;
import org.eclipse.ui.part.ViewPart;



public class SimpletestView extends ViewPart {
    public static final String NAME= "net.sf.simpletest.eclipse.views.ResultView"; 
	private int port = 0;
    private static SimpletestView view;
    private JSONReportHandler handler;
    private TestPool testPool;

    private ProgressInfoComposite progressInfoComposite;
    private ResultsInfoComposite resultsInfoComposite;

    protected CounterPanel fCounterPanel;
    protected ConnectionListener conListener;
    
    public SimpletestView(){
        if (view == null){
            view = this;
        }
    }

    public static SimpletestView getDefault(){
        return view;
    }

    
    public void createPartControl(Composite parent) {
 
        GridLayout gridLayout = new GridLayout();
        gridLayout.numColumns = 1;

        // set title and layout
        parent.setLayout(gridLayout);


        //Build the progress info Composites		
        progressInfoComposite = new ProgressInfoComposite(parent);
        progressInfoComposite.setLayoutData(new GridData(GridData.GRAB_HORIZONTAL |
                GridData.FILL_HORIZONTAL | GridData.VERTICAL_ALIGN_BEGINNING));

        //Build the result info composite
        resultsInfoComposite = new ResultsInfoComposite(parent);
        resultsInfoComposite.setLayoutData(new GridData(GridData.GRAB_VERTICAL |
                GridData.FILL_BOTH));
        
   
    }

    /*
	 * @see WorkbenchPart#setFocus()
	 */
    public void setFocus() {
    }
    
    public int initListener(){
        reset();
        conListener = new ConnectionListener();
        conListener.start();
        port = conListener.getPort();
        if (port == 0){
            conListener.shutdown();
            return port;
        }
        handler = new JSONReportHandler();
        
        testPool = new TestPool(Integer.toString(port));
        return port;
    }

    public void handleReport(String report) {
        if (report == null){
            conListener.shutdown();
        }else{
            handler.handle(report, testPool);
            update();
        }
    }
    
    private void reset() {
        doAsyncRunnable(new Runnable() {
            public void run() {
                try{
                    progressInfoComposite.resetInfo();
                    resultsInfoComposite.resetInfo();
                }catch (Exception e){
                    e.printStackTrace();
                }
            }
        });
    }
    
    private void update() {
        doAsyncRunnable(new Runnable() {
            public void run() {
                try{
		            progressInfoComposite.updateInfo(testPool);
		            resultsInfoComposite.updateInfo(testPool);
                    showTestResultsView();
		        }catch (Exception e){
		            e.printStackTrace();
		        }
	        }
        });
    }

    private void doAsyncRunnable(Runnable runnable) {
		view.getSite().getShell().getDisplay().asyncExec(runnable);
	}
    
    public synchronized void dispose(){
        view = null;
    }

    private void showTestResultsView() {
        IWorkbenchPage page = SimpletestPlugin.getActivePage();

        if (page != null) {
            SimpletestView testRunner = (SimpletestView)page.findView(SimpletestView.NAME);
            if(testRunner == null) {
                try { // show the result view
                    testRunner= (SimpletestView)page.showView(SimpletestView.NAME);
                } catch (PartInitException pie) {
                    pie.printStackTrace();
                }
            } else{
                page.bringToTop(testRunner);
            }

        }
    }
    
   

} 