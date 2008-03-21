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
 * This class is derived from net.sf.phpeclipse.phpunit.ResultsInfoComposite
 * See the net.sf.phpeclipse.phpunit project for source
 */
package net.sf.simpletest.eclipse.ui;



import net.sf.simpletest.eclipse.testpool.TestCase;
import net.sf.simpletest.eclipse.testpool.TestGroup;
import net.sf.simpletest.eclipse.testpool.TestInstance;
import net.sf.simpletest.eclipse.testpool.TestPool;

import org.eclipse.jface.action.Action;
import org.eclipse.jface.viewers.DoubleClickEvent;
import org.eclipse.jface.viewers.IDoubleClickListener;
import org.eclipse.jface.viewers.StructuredSelection;
import org.eclipse.jface.viewers.TreeViewer;
import org.eclipse.swt.SWT;
import org.eclipse.swt.layout.GridData;
import org.eclipse.swt.layout.GridLayout;
import org.eclipse.swt.widgets.Composite;
import org.eclipse.swt.widgets.Tree;
import org.eclipse.swt.widgets.TreeItem;


public class ResultsInfoComposite extends Composite {

	private TreeViewer treeViewer;
	
	public ResultsInfoComposite(Composite parent) {

		super(parent, SWT.BORDER);
		GridLayout layout = new GridLayout();
		layout.numColumns = 1;
		
		setLayout(layout);
		
		treeViewer = new TreeViewer(this, SWT.BORDER | SWT.SHADOW_ETCHED_IN);

		treeViewer.getControl().setLayoutData(new GridData(GridData.VERTICAL_ALIGN_FILL | GridData.FILL_BOTH | GridData.GRAB_VERTICAL));
		
		
		TestPoolLabelProvider labelProvider = new TestPoolLabelProvider();
		TestPoolContentProvider contentProvider= new TestPoolContentProvider();
		
		treeViewer.setContentProvider(contentProvider);
		treeViewer.setLabelProvider(labelProvider);
        treeViewer.addDoubleClickListener(new IDoubleClickListener() {
            public void doubleClick(DoubleClickEvent e) {
                handleDoubleClick(e);
            }
        });
		
	}
    
    private void handleDoubleClick(DoubleClickEvent e) {
        StructuredSelection ssel = (StructuredSelection) treeViewer.getSelection();
        
        
        if (ssel == null){
            return;
        }
        //TODO: handle cases of isel -- file or grouptest
        Object osel = ssel.getFirstElement();
        int line = 1;
        String testPath = "";
        if (osel instanceof TestInstance) {
            TestInstance ti = (TestInstance) ssel.getFirstElement();
            line = ti.getLine();
            testPath = ti.getTestFilePath();
        }else if (osel instanceof TestCase){
            //TODO: search the file for the right function
            TestCase tc = (TestCase) ssel.getFirstElement();
            line = tc.getLine();
            testPath = tc.getTestFilePath();
        }else if (osel instanceof TestGroup){
            line = 1;
            TestGroup tg = (TestGroup) ssel.getFirstElement();
            testPath = tg.getId();
        }
        if ("".equals(testPath)){
            return;
        }
        Action action = new OpenEditorAtLineAction(testPath, line);
        if (action.isEnabled()){
            action.run();  
        }
        
                                                             
    }
	
	public void resetInfo() {
		treeViewer.setInput(null);
	}
	
	public void updateInfo(TestPool testPool) {
		treeViewer.setInput(testPool);
		Tree tree = treeViewer.getTree();
		TreeItem[] testgroups = tree.getItems();
		for (int i = 0; i < testgroups.length; i++) {
			TestGroup tg = (TestGroup) testgroups[i].getData();
			if (tg != null && tg.expanded){
				treeViewer.setExpandedState(tg,true);
			}
			TreeItem[] testcases =  testgroups[i].getItems();
			for (int j = 0; j < testcases.length; ++j){
				TestCase tc = (TestCase) testcases[j].getData();
				if (tc != null && tc.expanded){
					treeViewer.setExpandedState(tc,true);
					break;
				}
			}
		}
	}

}
