/**
 * ========================================================================
 * 
 * Copyright 2001-2003,2006 The Apache Software Foundation.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 * 
 *   http://www.apache.org/licenses/LICENSE-2.0
 * 
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 * 
 * ========================================================================
 */
/**
 * This class is derived from org.apache.cactus.eclipse.runner.ui.CactusPreferencePage
 * See the org.apache.cactus.eclipse.runner project for source
 * Contributors:
 * 		Steven Balthazor - conversion from cactus to simpletest
 */
package net.sf.simpletest.eclipse.preferences;

import net.sf.simpletest.eclipse.SimpletestPlugin;

import org.eclipse.jface.preference.DirectoryFieldEditor;
import org.eclipse.jface.preference.FieldEditorPreferencePage;
import org.eclipse.jface.preference.FileFieldEditor;
import org.eclipse.jface.preference.StringFieldEditor;
import org.eclipse.ui.IWorkbenchPreferencePage;
import org.eclipse.ui.IWorkbench;


/**
 * This class represents a preference page that
 * is contributed to the Preferences dialog. By 
 * subclassing <samp>FieldEditorPreferencePage</samp>, we
 * can use the field support built into JFace that allows
 * us to create a page that is small and knows how to 
 * save, restore and apply itself.
 * <p>
 * This page is used to modify preferences only. They
 * are stored in the preference store that belongs to
 * the main plug-in class. That way, preferences can
 * be accessed directly via the preference store.
 */

public class SimpletestPreferencePage
	extends FieldEditorPreferencePage
	implements IWorkbenchPreferencePage {
		
		
	public static final String PHP_PATH = "PhpPathPreference";
	public static final String SIMPLETEST_PATH = "SimpleTestPathPreference";
	public static final String TEST_SUFFIX = "TestSuffixPreference";
	public static final String TEST_INCLFILE = "TestIncludeFilePreference";
    public static final String PHP_INI = "PhpIniPreference";
    public static final String PHPUNIT_PATH = "PhpUnitPath";
    public static final String PHPUNIT3_PATH = "PhpUnit3Path";
    

	public SimpletestPreferencePage() {
		super(GRID);
		setPreferenceStore(SimpletestPlugin.getDefault().getPreferenceStore());
		//setDescription("Please browse for the folder containing the Simpletest php files (among them: \"simple_test.php\" and \"unit_tester.php\"). If you don't have it, please download the latest version from https://sourceforge.net/project/showfiles.php?group_id=76550&release_id=153280 first. ");
		initializeDefaults();
	}
/**
 * Sets the default values of the preferences.
 */
	private void initializeDefaults() {
	
	}
	
/**
 * Creates the field editors. Field editors are abstractions of
 * the common GUI blocks needed to manipulate various types
 * of preferences. Each field editor knows how to save and
 * restore itself.
 */

	public void createFieldEditors() {

		addField(new FileFieldEditor(PHP_PATH, 
				"Php.exe File:", getFieldEditorParent()));
        addField(new FileFieldEditor(PHP_INI, 
                "php.ini File:", getFieldEditorParent()));
		addField(new DirectoryFieldEditor(SIMPLETEST_PATH, 
				"&Simpletest Path:", getFieldEditorParent()));
		addField(new FileFieldEditor(TEST_INCLFILE, 
				"Include File for Tests (optional):", getFieldEditorParent()));
		addField(new StringFieldEditor(TEST_SUFFIX,
		        "Test File Suffix:", getFieldEditorParent()));
        addField(new DirectoryFieldEditor(PHPUNIT_PATH, 
                "&PHPUnit2 Path:", getFieldEditorParent()));
        /*
        addField(new DirectoryFieldEditor(PHPUNIT3_PATH, 
                "&PHPUnit3 Path (Framework.php should be in the selected directory):", getFieldEditorParent()));
		*/

	}
	
	public void init(IWorkbench workbench) {
		
	}
}