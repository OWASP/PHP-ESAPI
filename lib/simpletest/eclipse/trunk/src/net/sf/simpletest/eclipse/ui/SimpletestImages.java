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
 * This class is derived from net.sf.phpeclipse.phpunit.PHPUnitImages
 * See the net.sf.phpeclipse.phpunit project for source
 */
package net.sf.simpletest.eclipse.ui;

import java.net.MalformedURLException;
import java.net.URL;

import net.sf.simpletest.eclipse.SimpletestPlugin;

import org.eclipse.core.runtime.Path;
import org.eclipse.jface.resource.ImageDescriptor;
import org.eclipse.jface.resource.ImageRegistry;
import org.eclipse.swt.graphics.Image;



public class SimpletestImages {

	protected static final String NAME_PREFIX =
		"net.sf.simpletest.eclipse";
	protected static final int NAME_PREFIX_LENGTH = NAME_PREFIX.length();

	protected final static URL iconBaseURL= SimpletestPlugin.findURL(new Path("icons"));

	protected static final ImageRegistry IMAGE_REGISTRY = new ImageRegistry();

	/*
	 * Available cached Images in the Java plugin image registry.
	 */

	public static final String IMG_SELECT_TEST_SUITE =
		NAME_PREFIX + "tsuite.gif";
	public static final String IMG_RUN_TEST_SUITE = NAME_PREFIX + "start.gif";
	public static final String IMG_TEST_ERROR = NAME_PREFIX + "testerr.gif";
	public static final String IMG_TEST_FAILURE = NAME_PREFIX + "testfail.gif";
	public static final String IMG_TEST_PASS = NAME_PREFIX + "testok.gif";
	public static final String IMG_TEST_SUITE_ERROR =
		NAME_PREFIX + "tsuiteerror.gif";
	public static final String IMG_TEST_SUITE_PASS =
		NAME_PREFIX + "tsuiteok.gif";
	public static final String IMG_TEST_SUITE_FAILURE =
		NAME_PREFIX + "tsuitefail.gif";

	public static final String IMG_ERROR = NAME_PREFIX + "error.gif";
	public static final String IMG_FAILURE = NAME_PREFIX + "failure.gif";

	public static final ImageDescriptor DESC_SELECT_TEST_SUITE =
		createManaged(IMG_SELECT_TEST_SUITE);
	public static final ImageDescriptor DESC_RUN_TEST_SUITE =
		createManaged(IMG_RUN_TEST_SUITE);
	public static final ImageDescriptor DESC_TEST_ERROR =
		createManaged(IMG_TEST_ERROR);
	public static final ImageDescriptor DESC_TEST_FAILURE =
		createManaged(IMG_TEST_FAILURE);
	public static final ImageDescriptor DESC_TEST_PASS =
		createManaged(IMG_TEST_PASS);
	public static final ImageDescriptor DESC_TEST_SUITE_ERROR =
		createManaged(IMG_TEST_SUITE_ERROR);
	public static final ImageDescriptor DESC_TEST_SUITE_PASS =
		createManaged(IMG_TEST_SUITE_PASS);
	public static final ImageDescriptor DESC_TEST_SUITE_FAILURE =
		createManaged(IMG_TEST_SUITE_FAILURE);

	public static final ImageDescriptor DESC_ERROR = createManaged(IMG_ERROR);
	public static final ImageDescriptor DESC_FAILURE =
		createManaged(IMG_FAILURE);
	/**
	 * Returns the image managed under the given key in this registry.
	 * 
	 * @param key the image's key
	 * @return the image managed under the given key
	 */
	public static Image get(String key) {
		return IMAGE_REGISTRY.get(key);
	}

	public static ImageRegistry getImageRegistry() {
		return IMAGE_REGISTRY;
	}


	protected static ImageDescriptor createManaged(String name) {
		try {
			ImageDescriptor result =
				ImageDescriptor.createFromURL(
					makeIconFileURL(name.substring(NAME_PREFIX_LENGTH)));
			IMAGE_REGISTRY.put(name, result);
			return result;
		} catch (MalformedURLException e) {
			return ImageDescriptor.getMissingImageDescriptor();
		}
	}

	protected static URL makeIconFileURL(String name)
		throws MalformedURLException {
		if (iconBaseURL == null)
			throw new MalformedURLException();

		return new URL(iconBaseURL, name);
	}
}