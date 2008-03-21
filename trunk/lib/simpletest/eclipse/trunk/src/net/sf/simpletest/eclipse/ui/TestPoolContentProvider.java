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
 * This class is derived from net.sf.phpeclipse.phpunit.TestPoolContentProvider
 * See the net.sf.phpeclipse.phpunit project for source
 */
package net.sf.simpletest.eclipse.ui;

import java.util.Vector;


import net.sf.simpletest.eclipse.testpool.TestCase;
import net.sf.simpletest.eclipse.testpool.TestGroup;
import net.sf.simpletest.eclipse.testpool.TestPool;

import org.eclipse.jface.viewers.ITreeContentProvider;
import org.eclipse.jface.viewers.Viewer;


/* (non-Javadoc)
 * @see org.eclipse.jface.viewers.ITreeContentProvider
 */
public class TestPoolContentProvider implements ITreeContentProvider {



	/* (non-Javadoc)
	 * @see org.eclipse.jface.viewers.ITreeContentProvider#getChildren(java.lang.Object)
	 */
	public Object[] getChildren(Object parentElement) {

		if(parentElement instanceof TestPool) {
			Vector allChildren = new Vector();
			allChildren.addAll(((TestPool) parentElement).getGroups());
			return allChildren.toArray();
		} else if (parentElement instanceof TestGroup){
		    Vector allChildren = new Vector();
		    allChildren.addAll(((TestGroup) parentElement).getCases());
		    return allChildren.toArray();
		} else if (parentElement instanceof TestCase){
		    Vector allChildren = new Vector();
		    allChildren.addAll(((TestCase)parentElement).getInstances());
		    return allChildren.toArray();
		} else{
		    return new Vector().toArray();
		}
		
		
	}

	/* (non-Javadoc)
	 * @see org.eclipse.jface.viewers.ITreeContentProvider#getParent(java.lang.Object)
	 */
	public Object getParent(Object element) {
		return null;
	}

	/* (non-Javadoc)
	 * @see org.eclipse.jface.viewers.ITreeContentProvider#hasChildren(java.lang.Object)
	 */
	public boolean hasChildren(Object element) {
		return getChildren(element).length > 0;
	}

	/* (non-Javadoc)
	 * @see org.eclipse.jface.viewers.IStructuredContentProvider#getElements(java.lang.Object)
	 */
	public Object[] getElements(Object inputElement) {
		
		return getChildren(inputElement);
		
	}

	/* (non-Javadoc)
	 * @see org.eclipse.jface.viewers.IContentProvider#dispose()
	 */
	public void dispose() {
		
	}

	/* (non-Javadoc)
	 * @see org.eclipse.jface.viewers.IContentProvider#inputChanged(org.eclipse.jface.viewers.Viewer, java.lang.Object, java.lang.Object)
	 */
	public void inputChanged(Viewer viewer, Object oldInput, Object newInput) {
		// TODO Auto-generated method stub

	}

}
