/**********************************************************************
 * Copyright (c) 2005 Steven Balthazor
 * All rights reserved. This program and the accompanying materials
 * are made available under the terms of the Eclipse Public License v1.0
 * which accompanies this distribution, and is available at
 * http://www.eclipse.org/legal/epl-v10.html
 * 
 * Contributors:
 *     Steven Balthazor - initial API and implementation
 **********************************************************************/
package net.sf.simpletest.eclipse;

import java.io.IOException;
import java.net.MalformedURLException;
import java.net.URL;
import java.util.MissingResourceException;
import java.util.ResourceBundle;

import net.sf.simpletest.eclipse.ui.SimpletestView;
//import net.sourceforge.phpeclipse.xdebug.php.launching.IXDebugConstants;

import org.eclipse.core.resources.IWorkspace;
import org.eclipse.core.resources.ResourcesPlugin;
import org.eclipse.core.runtime.Path;
import org.eclipse.core.runtime.Platform;
import org.eclipse.debug.core.DebugPlugin;
import org.eclipse.debug.core.ILaunch;
import org.eclipse.debug.core.ILaunchListener;
import org.eclipse.debug.core.ILaunchManager;
import org.eclipse.jface.resource.ImageDescriptor;
import org.eclipse.swt.SWT;
import org.eclipse.swt.graphics.Color;
import org.eclipse.swt.graphics.Image;
import org.eclipse.swt.widgets.Display;
import org.eclipse.swt.widgets.Shell;
import org.eclipse.ui.IWorkbench;
import org.eclipse.ui.IWorkbenchPage;
import org.eclipse.ui.IWorkbenchWindow;
import org.eclipse.ui.PartInitException;
import org.eclipse.ui.PlatformUI;
import org.eclipse.ui.console.ConsolePlugin;
import org.eclipse.ui.console.IConsole;
import org.eclipse.ui.console.IConsoleManager;
import org.eclipse.ui.console.IOConsole;
import org.eclipse.ui.console.IOConsoleOutputStream;
import org.eclipse.ui.plugin.AbstractUIPlugin;
import org.osgi.framework.BundleContext;

/**
 * The main plugin class to be used in the desktop.
 * ILaunchListener is used to start the Results View if it does not exist yet
 */
public class SimpletestPlugin extends AbstractUIPlugin implements ILaunchListener {

	private static SimpletestPlugin plugin;

    
	public static final String PLUGIN_ID= "net.sf.simpletest.eclipse"; 
    
    public final static String consoleName = "SimpleTest";
	public static final String PHP_FILE = "PhpFile";
    public static final String PHP_PROJECT_PATH = "PhpProjectPath";
	//private static final String LOG_PROPERTIES_FILE = "logger.properties";
    
    public final static Color colorConsoleMsg = getDisplay().getSystemColor(SWT.COLOR_DARK_GREEN);
    public final static Color colorConsoleErr = getDisplay().getSystemColor(SWT.COLOR_RED); 
    
    //there has got to be a better way...
    //basically this causes plugin debug messages to be dumped to Console/System Out
    public final static boolean debug = false;
    
	
	//Resource bundle.
	private ResourceBundle resourceBundle;
	
	public SimpletestPlugin() {
		super();
        if (plugin == null){
            plugin = this;
    		try {
    			resourceBundle = ResourceBundle.getBundle("net.sf.simpletest.eclipse.SimpletestPluginResources");
    		} catch (MissingResourceException x) {
    			resourceBundle = null;
    		}
        }
	}
    
    /**
     * This method is called when the plug-in is started
     */
    public void start(BundleContext context) throws Exception {
        super.start(context);
        ILaunchManager launchManager= getLaunchManager();
        launchManager.addLaunchListener(this);
    }

    /**
     * This method is called when the plug-in is stopped
     */
    public void stop(BundleContext context) throws Exception {
        try {
            ILaunchManager launchManager= getLaunchManager();
            launchManager.removeLaunchListener(this);
        } finally {
            super.stop(context);
            plugin = null;
        }
    }
	
	
	/**
	 * Returns the shared instance.
	 */
	public static SimpletestPlugin getDefault() {
		return plugin;
	}
	
	public static String getUniqueIdentifier() {
		return PLUGIN_ID;
	}
    
    public static IConsole getConsole(){
        IConsoleManager consoleManager = ConsolePlugin.getDefault().getConsoleManager();
        IConsole[] existing = consoleManager.getConsoles();
        for (int i=0; i<existing.length; ++i) {
            if (existing[i].getName().equals(consoleName)) {
                return existing[i];
            }
        }
        return null;
    }
    
    private static IOConsoleOutputStream getConsoleStream() {
        IConsole cons = getConsole();
        if (cons instanceof IOConsole){
            IOConsole iocons = (IOConsole) cons; 
            return iocons.newOutputStream();
        }
        return null;
    }
    
    public static void logMessage(String message){
        IOConsoleOutputStream cos = getConsoleStream();
        if (cos == null){
            System.out.println(message);
        }else{
            try {
                cos.setColor(colorConsoleMsg);
                cos.write(message);
                cos.write("\n");
            } catch (IOException e) {
                e.printStackTrace();
            }
        }
    }
    
    public static void logDebug(String message){
    	 if (debug){
    		 logMessage(message);
    	 }
    }

    public static void logError(String message, Throwable t){
        IOConsoleOutputStream cos = getConsoleStream();
        if (cos == null){
            System.err.println(message);
        }else{
            try {
                cos.setColor(colorConsoleMsg);
                cos.write(message);
                cos.write("\n");
            } catch (IOException e) {
                e.printStackTrace();
            }
        }
     }
    
	
	public static Display getDisplay() {
		Display display= Display.getCurrent();
		if (display == null) {
			display= Display.getDefault();
		}
		return display;
	}
    
    public static ILaunchManager getLaunchManager() {
        return  DebugPlugin.getDefault().getLaunchManager();
    }
    
    public static Shell getShell(){
        IWorkbenchWindow workBenchWindow = getActiveWorkbenchWindow();
        if (workBenchWindow == null)
        {
            return null;
        }
        return workBenchWindow.getShell();
    }
	
	public static IWorkbenchPage getActivePage() {
		IWorkbenchWindow activeWorkbenchWindow= getActiveWorkbenchWindow();
		if (activeWorkbenchWindow == null)
			return null;
		return activeWorkbenchWindow.getActivePage();
	}
    
    public static IWorkspace getWorkspace() {
          return ResourcesPlugin.getWorkspace();
    }

	/**
	 * Returns the string from the plugin's resource bundle,
	 * or 'key' if not found.
	 */
	public static String getResourceString(String key) {
		ResourceBundle bundle = SimpletestPlugin.getDefault().getResourceBundle();
		try {
			return (bundle != null) ? bundle.getString(key) : key;
		} catch (MissingResourceException e) {
			return key;
		}
	}

	/**
	 * Returns the plugin's resource bundle,
	 */
	public ResourceBundle getResourceBundle() {
		return resourceBundle;
	}
    
    /**
     * @return the active workbench window
     */
    public static IWorkbenchWindow getActiveWorkbenchWindow()
    {
        if (plugin == null)
        {
            return null;
        }
        IWorkbench workBench = plugin.getWorkbench();
        if (workBench == null)
        {
            return null;
        }
        
        return workBench.getActiveWorkbenchWindow();
    }

    public static ImageDescriptor createImageDescriptor(String path){
        ImageDescriptor id = null;
        try{
            id = ImageDescriptor.createFromURL(SimpletestPlugin.makeIconFileURL(path));
        }catch (MalformedURLException e){
            
        }
        return id;
    }
	
	public static Image createImage(String path) {

		ImageDescriptor id= createImageDescriptor(path);
        return id.createImage();

	}
	
	public static URL makeIconFileURL(String name) throws MalformedURLException {
	    String pathSuffix = "icons/";
	    URL iconBaseURL =
	        new URL(Platform.getBundle(PLUGIN_ID).getEntry("/"), pathSuffix);
	    return new URL(iconBaseURL, name);
	}
    
	//From here down handles starting up the Result View if it is not showing.
    
    public void launchRemoved(ILaunch launch){
    }
    
    public void launchAdded(ILaunch launch){
        if (SimpletestView.getDefault() == null){
            startView();
        }
    }
    
    public void launchChanged(ILaunch launch){
        
    
    }
        
    public static void startView(){
        final IWorkbench workbench = PlatformUI.getWorkbench(); 
        workbench.getDisplay().syncExec(new Runnable() { 
            public void run() { 
                IWorkbenchWindow window = workbench.getActiveWorkbenchWindow(); 
                if (window != null) { 
                    final IWorkbenchPage activePage = window.getActivePage();
                    if (activePage == null) {
                        return;
                    }
                    try {
                        activePage.showView(SimpletestView.NAME);
                    } catch (PartInitException e) {
                        e.printStackTrace();
                    }
                } 
            }
        });
    }
    
    public static URL findURL(Path path){
    	return SimpletestPlugin.getDefault().find(path);
    	//the "approved" method as of Eclipse 3.2
    	//FileLocator.find(SimpletestPlugin.getDefault().getBundle(), path, null);
    }
    
    public static URL getFileURL(URL url) throws IOException{
    	return Platform.asLocalURL(url);
    	//the "approved" method as of Eclipse 3.2
    	//FileLocator.toFileURL(url);
    }
    
    /* First stab at debug
    public static IBreakpoint[] getBreakpoints() {
		return getBreakpointManager().getBreakpoints(IXDebugConstants.ID_PHP_DEBUG_MODEL);
	}
	
	public static IBreakpointManager getBreakpointManager() {
		return DebugPlugin.getDefault().getBreakpointManager();
	}
    */
    
    
}
