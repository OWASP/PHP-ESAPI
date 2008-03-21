/**********************************************************************
 * Copyright (c) 2006 Steven Balthazor
 * All rights reserved. This program and the accompanying materials
 * are made available under the terms of the Eclipse Public License v1.0
 * which accompanies this distribution, and is available at
 * http://www.eclipse.org/legal/epl-v10.html
 * 
 * Contributors:
 *     Steven Balthazor - initial API and implementation
 **********************************************************************/
package net.sf.simpletest.eclipse.launcher;

public class PHPUnit2LaunchConfigurationDelegate extends TestLaunchConfigurationDelegate {

    /**
     * Returns the local java launch config type
     */
    protected String getLaunchTestType() {
        return TT_PHPUNIT2;    
    }
}
