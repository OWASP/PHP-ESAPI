/*******************************************************************************
 * Copyright (c) 2000, 2005 IBM Corporation and others.
 * All rights reserved. This program and the accompanying materials
 * are made available under the terms of the Eclipse Public License v1.0
 * which accompanies this distribution, and is available at
 * http://www.eclipse.org/legal/epl-v10.html
 * 
 * Contributors:
 *     IBM Corporation - initial API and implementation
 *     Steven Balthazor - conversion to work with Simpletest
 *******************************************************************************/
/**
 * This class is derived from org.eclipse.jdt.internal.junit.util.TestSearchEngine
 * See the org.eclipse.jdt.junit project for source
 */
package net.sf.simpletest.eclipse.launcher;

import java.lang.reflect.InvocationTargetException;
import java.util.HashSet;
import java.util.Set;

import net.sf.simpletest.eclipse.preferences.SimpletestPreferencePage;
import net.sf.simpletest.eclipse.ui.SimpletestMessages;
import net.sf.simpletest.eclipse.SimpletestPlugin;

import org.eclipse.core.runtime.CoreException;
import org.eclipse.core.runtime.IProgressMonitor;
import org.eclipse.core.runtime.SubProgressMonitor;

import org.eclipse.core.resources.IContainer;
import org.eclipse.core.resources.IFile;
import org.eclipse.core.resources.IFolder;
import org.eclipse.core.resources.IResource;

import org.eclipse.jface.operation.IRunnableContext;
import org.eclipse.jface.operation.IRunnableWithProgress;



/**
 * Custom Search engine for suite() methods
 */
public class TestSearchEngine {

	public final static String suffix = SimpletestPlugin.getDefault().getPreferenceStore().getString(SimpletestPreferencePage.TEST_SUFFIX);


	public static IFile[] findTests(IRunnableContext context, final Object[] elements) throws InvocationTargetException, InterruptedException {
		final Set result= new HashSet();
		
			if (elements.length > 0) {
				IRunnableWithProgress runnable= new IRunnableWithProgress() {
					public void run(IProgressMonitor pm) throws InterruptedException {
						doFindTests(elements, result, pm);
					}
				};
				context.run(true, true, runnable);			
			}
			return (IFile[]) result.toArray(new IFile[result.size()]) ;
	}



	public static void doFindTests(Object[] elements, Set result, IProgressMonitor pm) throws InterruptedException {
		int nElements= elements.length;
		pm.beginTask(SimpletestMessages.getString("TestSearchEngine.message.searching"), nElements);  //$NON-NLS-1$
		try {
			for (int i= 0; i < nElements; i++) {
				try {
					collectFiles(elements[i], new SubProgressMonitor(pm, 1), result);
				} catch (CoreException e) {
                    e.printStackTrace();
                    SimpletestPlugin.logError(e.getStatus().toString(),e);
				}
				if (pm.isCanceled()) {
					throw new InterruptedException();
				}
			}
		} finally {
			pm.done();
		}
	}

	private static void collectFiles(Object element, IProgressMonitor pm, Set result) throws CoreException/*, InvocationTargetException*/ {
	    
	    IResource[] resources = null;
	    if (element instanceof IContainer){
	       
	        resources= ((IContainer) element).members();
	    
			for (int i= 0; i < resources.length; i++){
			    if (resources[i] instanceof IFile){
			        IFile theFile = (IFile) resources[i];
			        if (theFile.getName().endsWith(suffix)){
			            result.add(resources[i]);
			        }
			    }else if (resources[i] instanceof IFolder){
			        collectFiles(resources[i],pm,result);
			    }
			}
			return;
	    }
	    if (element instanceof IFile){
	        IFile theFile = (IFile) element;
	        if (theFile.getName().endsWith(suffix)){
	            result.add(element);
	        }
	        return;
		}
	    /* try to do without
	    if (element instanceof IPackageFragment){  
	        resources = ((IResource[]) ((IPackageFragment) element).getNonJavaResources());
		    
			for (int i= 0; i < resources.length; i++){
			    if (resources[i] instanceof IFile){
			        IFile theFile = (IFile) resources[i];
			        if (theFile.getName().endsWith(suffix)){
			            result.add(resources[i]);
			        }
			    }else if (resources[i] instanceof IFolder){
			        collectFiles(resources[i],pm,result);
			    }
			}
			return;
		}
		*/
	}

	
	
		
}
