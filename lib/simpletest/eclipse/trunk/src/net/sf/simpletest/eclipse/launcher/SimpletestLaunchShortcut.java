/*******************************************************************************
 * Copyright (c) 2000, 2005 IBM Corporation and others.
 * All rights reserved. This program and the accompanying materials
 * are made available under the terms of the Eclipse Public License v1.0
 * which accompanies this distribution, and is available at
 * http://www.eclipse.org/legal/epl-v10.html
 * 
 * Contributors:
 *     IBM Corporation - initial API and implementation
 * 	   Steven Balthazor - reworked for conversion from junit to simpletest
 *******************************************************************************/
/**
 * This class is derived from org.eclipse.jdt.internal.junit.launcher.JUnitLaunchShortcut
 * See the org.eclipse.jdt.junit project for source
 */
package net.sf.simpletest.eclipse.launcher;

import net.sf.simpletest.eclipse.SimpletestPlugin;

import org.eclipse.debug.core.ILaunchConfigurationType;
import org.eclipse.debug.core.ILaunchManager;

public class SimpletestLaunchShortcut extends TestLaunchShortcut
{

    /**
     * Returns the local java launch config type
     */
    protected ILaunchConfigurationType getLaunchConfigType() {
        ILaunchManager lm= SimpletestPlugin.getLaunchManager();
        return lm.getLaunchConfigurationType(ID_SIMPLETEST_LAUNCHCONFIG);     
    }
}
