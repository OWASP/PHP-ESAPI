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
 * This class is derived from org.eclipse.jdt.internal.junit.ui.OpenEditorAtLineAction
 * See the org.eclipse.jdt.junit plugin for source
 */
package net.sf.simpletest.eclipse.ui;

import net.sf.simpletest.eclipse.SimpletestPlugin;

import org.eclipse.core.resources.IFile;
import org.eclipse.core.resources.IWorkspaceRoot;
import org.eclipse.core.runtime.CoreException;
import org.eclipse.core.runtime.IPath;
import org.eclipse.core.runtime.Path;
import org.eclipse.jface.action.Action;
import org.eclipse.jface.dialogs.ErrorDialog;
import org.eclipse.jface.dialogs.MessageDialog;
import org.eclipse.jface.text.BadLocationException;
import org.eclipse.jface.text.IDocument;


import org.eclipse.ui.texteditor.ITextEditor;

/**
 * Open a test in the Java editor and reveal a given line
 */
public class OpenEditorAtLineAction extends Action{
	
		
    private int fLineNumber;
    private String fFileName;
	
	/**
	 * Constructor for OpenEditorAtLineAction.
	 */
	public OpenEditorAtLineAction( String fileName, int line) {
        fFileName= fileName;
		fLineNumber= line;
	}
    
    public void run() {
        ITextEditor textEditor= null;
        try {
            IFile element= findFile(fFileName);
            if (element == null) {
                MessageDialog.openError(SimpletestPlugin.getShell(), 
                        SimpletestMessages.getString("OpenEditorAction_error_cannotopen_title"), SimpletestMessages.getString("OpenEditorAction_error_cannotopen_message")); 
                return;
            } 
            textEditor= (ITextEditor)EditorUtility.openInEditor(element, true);         
        } catch (CoreException e) {
            ErrorDialog.openError(SimpletestPlugin.getShell(), SimpletestMessages.getString("OpenEditorAction_error_dialog_title"), SimpletestMessages.getString("OpenEditorAction_error_dialog_message"), e.getStatus()); 
            return;
        }
        if (textEditor == null) {
            //put a message somewhere?
            return;
        }
        reveal(textEditor);
    }
		
	protected void reveal(ITextEditor textEditor) {
		if (fLineNumber >= 0) {
			try {
				IDocument document= textEditor.getDocumentProvider().getDocument(textEditor.getEditorInput());
				textEditor.selectAndReveal(document.getLineOffset(fLineNumber-1), document.getLineLength(fLineNumber-1));
			} catch (BadLocationException x) {
				// marker refers to invalid text position -> do nothing
			}
		}
	}
    
     protected IFile findFile(String filepath){
        IWorkspaceRoot root = SimpletestPlugin.getWorkspace().getRoot();
        IPath ipath = Path.fromOSString(filepath);
        return root.getFileForLocation(ipath);
     }
	
	

	public boolean isEnabled() {
        //TODO: make sure this finds a file .tst.php file or whatever is specified
        return true;
	}
}
