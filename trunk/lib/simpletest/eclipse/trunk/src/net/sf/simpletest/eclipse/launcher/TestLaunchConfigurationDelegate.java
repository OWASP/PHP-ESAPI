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

import java.io.BufferedInputStream;
import java.io.BufferedOutputStream;
import java.io.BufferedWriter;
import java.io.File;
import java.io.FileInputStream;
import java.io.FileNotFoundException;
import java.io.FileOutputStream;
import java.io.FileWriter;
import java.io.IOException;
import java.io.InputStream;
import java.io.InputStreamReader;
import java.net.URL;
import java.util.HashMap;
import java.util.Iterator;
import java.util.Map;
import java.util.zip.ZipEntry;
import java.util.zip.ZipInputStream;

import net.sf.simpletest.eclipse.SimpletestPlugin;
import net.sf.simpletest.eclipse.preferences.SimpletestPreferencePage;
import net.sf.simpletest.eclipse.ui.SimpletestView;
//import net.sourceforge.phpeclipse.xdebug.php.model.XDebugTarget;

import org.eclipse.core.runtime.CoreException;
import org.eclipse.core.runtime.IProgressMonitor;
import org.eclipse.core.runtime.IStatus;
import org.eclipse.core.runtime.Path;
import org.eclipse.core.runtime.Status;
import org.eclipse.debug.core.DebugPlugin;
import org.eclipse.debug.core.ILaunch;
import org.eclipse.debug.core.ILaunchConfiguration;
import org.eclipse.debug.core.ILaunchManager;
import org.eclipse.debug.core.model.IProcess;
import org.eclipse.debug.core.model.LaunchConfigurationDelegate;
import org.eclipse.jface.dialogs.ErrorDialog;

abstract class TestLaunchConfigurationDelegate extends LaunchConfigurationDelegate {
     private String TestType = getLaunchTestType(); 
     static final String TT_SIMPLETEST = "net.sf.simpletest.eclipse.launcher.stlaunchconfig";
     static final String TT_PHPUNIT2 = "net.sf.simpletest.eclipse.launcher.pulaunchconfig";
     static final String TT_PHPUNIT3 = "net.sf.simpletest.eclipse.launcher.pu3launchconfig";
     
     static final String ST_LAUNCH_PROCESS_TYPE = "net.sf.simpletest.eclipse.launcher.processType";

     abstract String getLaunchTestType();
     
     public void launch(ILaunchConfiguration configuration, String mode, ILaunch launch, IProgressMonitor monitor) throws CoreException {
         //TODO: check for valid php.exe and php.ini here
         if ("".equals(SimpletestPlugin.getDefault().getPreferenceStore().getString(SimpletestPreferencePage.PHP_PATH))){
             String msg = "Please provide a path to the php executable in the preferences.";
             IStatus s = new Status(IStatus.ERROR, SimpletestPlugin.PLUGIN_ID, IStatus.OK, msg, null);
             ErrorDialog.openError(SimpletestPlugin.getShell(), "Launch Error",null, s); 
             throw new CoreException(s);
         }
         
         if ("".equals(SimpletestPlugin.getDefault().getPreferenceStore().getString(SimpletestPreferencePage.PHP_INI))){
             String msg = "Please provide a path to the php.ini in the preferences.";
             IStatus s = new Status(IStatus.ERROR, SimpletestPlugin.PLUGIN_ID, IStatus.OK, msg, null);
             ErrorDialog.openError(SimpletestPlugin.getShell(), "Launch Error",null, s); 
             throw new CoreException(s);
         }
         //TODO: remove this -- this is caused by unknown testtype
         if (!TT_PHPUNIT2.equals(TestType) && !TT_PHPUNIT3.equals(TestType)){
             TestType = TT_SIMPLETEST;
         }
         
         ILaunchConfiguration config = launch.getLaunchConfiguration();
         String testFile = "";
         try{
             testFile = config.getAttribute(SimpletestPlugin.PHP_FILE, ""); 
         }catch (Exception e) {
             String msg = "Bad input file specified.";
             IStatus s = new Status(IStatus.ERROR, SimpletestPlugin.PLUGIN_ID, IStatus.OK, msg, null);
             ErrorDialog.openError(SimpletestPlugin.getShell(), "Launch Error",null, s); 
             throw new CoreException(s);
         }
         //TODO: test that this works if the resultview is not yet opened
                 //from package explorer, from editor, and from runagain
         //TODO: test that this works if the resultview is just not on top
         //TODO: test if the resultview is opened in another perspective
         
         SimpletestView view = SimpletestView.getDefault();
         int tmpPort = view.initListener();
         
         testFile = (new Path(testFile)).toPortableString();
         StringBuffer sb = new StringBuffer();
         
         String path = "";
         String altpath = "";
         if (TT_PHPUNIT2.equals(TestType)){
                 altpath = SimpletestPlugin.getDefault().getPreferenceStore().getString(SimpletestPreferencePage.PHPUNIT_PATH);
                 if ("".equals(altpath)){
                     String msg = "Please provide a path to PHPUnit2 in the preferences.";
                     IStatus s = new Status(IStatus.ERROR, SimpletestPlugin.PLUGIN_ID, IStatus.OK, msg, null);
                     ErrorDialog.openError(SimpletestPlugin.getShell(), "Launch Error",null, s); 
                     throw new CoreException(s);
                 }
                 File pu2altfile = new File(altpath);
                 if (pu2altfile.getName().toLowerCase().equals("phpunit2")){
                     path = (new File(pu2altfile.getParent())).getAbsolutePath();
                 }else{
                     path = (new File(altpath)).getAbsolutePath();
                 }
         }else if (TT_PHPUNIT3.equals(TestType)){
                 altpath = SimpletestPlugin.getDefault().getPreferenceStore().getString(SimpletestPreferencePage.PHPUNIT3_PATH);
                 if ("".equals(altpath)){
                     String msg = "Please provide a path to PHPUnit3 in the preferences.";
                     IStatus s = new Status(IStatus.ERROR, SimpletestPlugin.PLUGIN_ID, IStatus.OK, msg, null);
                     ErrorDialog.openError(SimpletestPlugin.getShell(), "Launch Error",null, s); 
                     throw new CoreException(s);
                 }
                 File pu2altfile = new File(altpath);
                 if (pu2altfile.getName().toLowerCase().equals("phpunit")){
                     path = (new File(pu2altfile.getParent())).getAbsolutePath();
                 }else{
                     path = (new File(altpath)).getAbsolutePath();
                 }
         }else if (TT_SIMPLETEST.equals(TestType)){ 
                 altpath = SimpletestPlugin.getDefault().getPreferenceStore().getString(SimpletestPreferencePage.SIMPLETEST_PATH);
             
                 if ("".equals(altpath)){
                     Path tmppath = new Path("simpletest");
                     URL localurl = SimpletestPlugin.findURL(tmppath);
                     
                     if (null == localurl){
                         
                         //this means the folder does not exist
                         URL topurl;
                         try{
                             topurl = SimpletestPlugin.getFileURL(SimpletestPlugin.findURL(new Path("")));
                         } catch (IOException ioe2){
                             SimpletestPlugin.logError("Unable to find plugin directory.",ioe2);
                             return;
                         }
                         File stdir = new File(topurl.getPath() + "/simpletest");
                         
                         try {
                             if (!stdir.exists()){
                                 stdir.mkdir();
                                 BufferedOutputStream dest = null;
                                 FileInputStream fis = new FileInputStream(topurl.getPath() + "/simpletest_php.zip");
                                 ZipInputStream zis = new ZipInputStream(new BufferedInputStream(fis));
                                 ZipEntry entry;
                                 //may need to recurse directories
                                 while((entry = zis.getNextEntry()) != null) {
                                    int count;
                                    byte data[] = new byte[2048];
                                    // write the files to the disk
                                    String outfilename = stdir.getAbsolutePath() + "/" + entry.getName();
                                    FileOutputStream fos = new FileOutputStream(outfilename);
                                    dest = new BufferedOutputStream(fos, 2048);
                                    while ((count = zis.read(data, 0, 2048)) != -1) {
                                        dest.write(data, 0, count);
                                    }
                                    dest.flush();
                                    dest.close();
                                 }
                                 zis.close();
                                               
                             }
                         } catch (FileNotFoundException e) {
                             SimpletestPlugin.logError("Unable to find simpletest_php.zip file.",e);
                             return;
                         } catch (IOException e) {
                             SimpletestPlugin.logError("Error extracting simpletest_php.zip file.",e);
                             return;
                         }
                         
                         
                     }
                     try {
                            
                         URL url = SimpletestPlugin.getFileURL(SimpletestPlugin.findURL(new Path("simpletest")));
                         path = (new File(url.getPath())).getAbsolutePath();
                     } catch (IOException e) {
                         SimpletestPlugin.logError("Unknown Error getting simpletest php directory.",e);
                         return;
                     }
                     
                 }else{
                     path = (new File(altpath)).getAbsolutePath();
                 }
             
         }
        
         String inclfile = SimpletestPlugin.getDefault().getPreferenceStore().getString(SimpletestPreferencePage.TEST_INCLFILE);
         
         
         
         sb.append("$path='" + path + "';");
         sb.append("ini_set('include_path', get_include_path().PATH_SEPARATOR . realpath($path));");
         sb.append("ini_set('html_errors','0');");
         if (!"".equals(inclfile)){
             sb.append("include_once realpath('" + inclfile + "');");
         }
         sb.append("$fullpath = realpath('" + testFile + "');");
         sb.append("$pathparts = pathinfo($fullpath);");
         sb.append("$filename = $pathparts['basename'];");
         StringBuffer xb= new StringBuffer(sb.toString());
         StringBuffer ob = new StringBuffer(sb.toString());
         
         String phpcmd = SimpletestPlugin.getDefault().getPreferenceStore().getString(SimpletestPreferencePage.PHP_PATH);
         String phpcmdpath = (new File(phpcmd)).getAbsolutePath();
         String cmd = "\"" + phpcmdpath + "\" -q ";
         String phpini = SimpletestPlugin.getDefault().getPreferenceStore().getString(SimpletestPreferencePage.PHP_INI);
         if (!phpini.equals("")){
             cmd += "-c " + "\""+(new File(phpini)).getAbsolutePath()+"\"";
         }
         //This is triggered by a checkbox in the LaunchConfiguration add coverage as a checkbox on the run
         boolean coverage = config.getAttribute(LaunchConfigurationConstants.ATTR_COVERAGE, false);
         
         if (TT_PHPUNIT2.equals(TestType)){
                 String phpsuffix = SimpletestPlugin.getDefault().getPreferenceStore().getString(SimpletestPreferencePage.TEST_SUFFIX);
                 
                 xb.append("$_SERVER['argv'][1]=basename(\"$fullpath\",\""+phpsuffix+"\");");
                 xb.append("$_SERVER['argv'][2] = \"$fullpath\";");
                 xb.append("$_SERVER['PHPBIN']='"+phpcmdpath+" -q -c " + (new File(phpini)).getAbsolutePath()+"';");
                 xb.append("include_once('PHPUnit2/TextUI/TestRunner.php');");
                
                 
                 ob.append("$_SERVER['argv'][0]=\"--log-eclipse\";");
                 ob.append("$_SERVER['argv'][1]=\""+tmpPort+"\";");
    
                 ob.append("$_SERVER['argv'][2]=basename(\"$fullpath\",\""+phpsuffix+"\");");
                 ob.append("$_SERVER['argv'][3] = \"$fullpath\";");
                 ob.append("$_SERVER['PHPBIN']='"+phpcmdpath+" -q -c " + (new File(phpini)).getAbsolutePath()+"';");
    
                 ob.append("ob_start();");
                 ob.append("include_once('PHPUnit2/TextUI/TestRunner.php');");
                 ob.append("$output=ob_get_contents();");
                 ob.append("ob_end_clean();");
                 ob.append("fwrite(fopen('php://stdout','w'), $output);");
         }else if (TT_PHPUNIT3.equals(TestType)){
                 String phpsuffix = SimpletestPlugin.getDefault().getPreferenceStore().getString(SimpletestPreferencePage.TEST_SUFFIX);
                 
                 xb.append("$_SERVER['argv'][1]=basename(\"$fullpath\",\""+phpsuffix+"\");");
                 xb.append("$_SERVER['argv'][2] = \"$fullpath\";");
                 xb.append("$_SERVER['PHPBIN']='"+phpcmdpath+" -q -c " + (new File(phpini)).getAbsolutePath()+"';");
                 xb.append("include_once('PHPUnit/TextUI/Command.php');");
                
                 
                 ob.append("$_SERVER['argv'][0]=\"--log-eclipse\";");
                 ob.append("$_SERVER['argv'][1]=\""+tmpPort+"\";");
    
                 ob.append("$_SERVER['argv'][2]=basename(\"$fullpath\",\""+phpsuffix+"\");");
                 ob.append("$_SERVER['argv'][3] = \"$fullpath\";");
                 ob.append("$_SERVER['PHPBIN']='"+phpcmdpath+" -q -c " + (new File(phpini)).getAbsolutePath()+"';");
    
                 ob.append("ob_start();");
                 ob.append("include_once('PHPUnit/TextUI/Command.php');");
                 ob.append("$output=ob_get_contents();");
                 ob.append("ob_end_clean();");
                 ob.append("fwrite(fopen('php://stdout','w'), $output);");
         } else if (TT_SIMPLETEST.equals(TestType)){
             
                 xb.append("include_once('xml.php');");
                 xb.append("include_once('unit_tester.php');");
                 xb.append("include_once('mock_objects.php');");
                 xb.append("include_once('test_case.php');");
                 xb.append("include_once('invoker.php');");
                 xb.append("$test=new GroupTest($filename);");
                 xb.append("$test->addTestFile($fullpath);");
                 xb.append("$test->run(new XmlReporter());");
                 
             
                 ob.append("include_once('eclipse.php');");
                 ob.append("ob_start();");
                 ob.append("$test=new GroupTest($filename);");
                 ob.append("$test->addTestFile($fullpath);");
                 ob.append("$l=EclipseReporter::createListener("+tmpPort+");");
                 if (coverage){
                	 ob.append("$test->run(new EclipseReporter($l,true));");
                 }else{
                	 ob.append("$test->run(new EclipseReporter($l));");
                 }
                 //ob.append("echo dirname(__FILE__);");
                 ob.append("$output=ob_get_contents();");
                 ob.append("ob_end_clean();");
                 ob.append("$l->close();");
                 ob.append("$output.=$l->getError();");
                 ob.append("fwrite(fopen('php://stdout','w'), $output);");
         }
         File tempFile = null;
         try {
             String xml = "echo \"<?php " + xb.toString() + " ?>\" | " + cmd;
             String exec = "<?php " + ob.toString() + " ?>";
             tempFile = File.createTempFile("Simpletest",".php");
			 tempFile.deleteOnExit();
             BufferedWriter bout = new BufferedWriter(new FileWriter(tempFile));
             bout.write(exec);
             bout.close();
             
             int last;
             if (coverage){
            	 last = 6;
             }else{
            	 last = 4;
             }
             String[] cmdarr = new String[last+1];
             cmdarr[0] = phpcmdpath;
             cmdarr[1] = "-q";
             cmdarr[2] = "-c";
             cmdarr[3] = (new File(phpini)).getAbsolutePath();
             
             if (coverage){
             	cmdarr[4] = "-d";
             	cmdarr[5] = "xdebug.extended_info=1";
             }
             cmdarr[last] = tempFile.getAbsolutePath();
        
             String[] envp=DebugPlugin.getDefault().getLaunchManager().getEnvironment(configuration);
     		 // appends the environment to the native environment
			if (envp==null) {
				Map stringVars = DebugPlugin.getDefault().getLaunchManager().getNativeEnvironment();
				int idx=0;
				envp= new String[stringVars.size()];
				for (Iterator i = stringVars.keySet().iterator(); i.hasNext();) {
					String key = (String) i.next();
					String value = (String) stringVars.get(key);
					envp[idx++]=key+"="+value;
				}
			}
			
     		if (mode.equals(ILaunchManager.DEBUG_MODE)) {
     			String[] env = new String[envp.length+1];
     			for(int i=0;i<envp.length;i++)
     					env[i+1]=envp[i];
     			env[0]="XDEBUG_CONFIG=idekey=xdebug_test remote_enable=1";
     			envp=env;
     		}

     		Process phpProcess = DebugPlugin.exec(cmdarr,null,envp);
     		Map processAttributes = new HashMap();
 
     		processAttributes.put(IProcess.ATTR_CMDLINE, xml);
     		processAttributes.put(IProcess.ATTR_PROCESS_TYPE, ST_LAUNCH_PROCESS_TYPE);
     		processAttributes.put(IProcess.ATTR_PROCESS_LABEL, SimpletestPlugin.consoleName);
     		processAttributes.put(DebugPlugin.ATTR_CAPTURE_OUTPUT, "false");
     		IProcess process = DebugPlugin.newProcess(launch, phpProcess, testFile,processAttributes);
     		if (process == null){
     			String msg = "Could not execute PHP process. Was it cancelled?";
     			IStatus s = new Status(IStatus.ERROR, SimpletestPlugin.PLUGIN_ID, IStatus.OK, msg, null);
     			throw new CoreException(s);
     		}
             
             
            SimpletestPlugin.logMessage("Running: ");
            SimpletestPlugin.logMessage(xml);
            SimpletestPlugin.logMessage("*************");
            System.out.println(exec);
             
            StringBuffer err=new StringBuffer();
            StringBuffer out=new StringBuffer();  
            StreamReaderThread outThread=new StreamReaderThread(phpProcess.getInputStream(),out);
            StreamReaderThread errThread=new StreamReaderThread(phpProcess.getErrorStream(),err);
            outThread.start();
            errThread.start();
            
            
            
            /* First stab at debug...
             * XDebugTarget not yet implemented
            if (mode.equals(ILaunchManager.DEBUG_MODE)) {
            	//TODO: hardcoded the xdebug port!
            	XDebugTarget target = new XDebugTarget(launch, process, 9000);
            	launch.addDebugTarget(target);
            	target.sendRequest("status");
            	target.sendRequest("step_into");
            	target.sendRequest("status");
            	while (!target.isTerminated()){
    				try{
    					Thread.sleep(100);
    				}catch (InterruptedException e) {
    					//eat the exception
    				}
    			}
            	target.sendRequest("status");
            	System.out.println("Terminated");
            	
    		}
            */
            
            
            //wait for process to end
            int result=0;
            try {
                result = phpProcess.waitFor();
            } catch (InterruptedException e) {
                e.printStackTrace();
                SimpletestPlugin.logError("Execution interrupted in waitfor",e);
            }
            //finish reading whatever's left in the buffers
            try {
                outThread.join();
            } catch (InterruptedException e) {
                e.printStackTrace();
                SimpletestPlugin.logError("Execution interrupted in outthread join",e);
            }
            try {
                errThread.join();
            } catch (InterruptedException e) {
                e.printStackTrace();
                SimpletestPlugin.logError("Execution interrupted in errthread join",e);
            }
            
  
            if (result!=0 && result!=1){
                SimpletestPlugin.logMessage("Process returned: "+result);
            }
            if (!"".equals(out.toString())){
                SimpletestPlugin.logMessage(out.toString());
            }
            if (!"".equals(err.toString())){
                SimpletestPlugin.logMessage("Process error:\n"+err.toString());
            }
         }catch (IOException e1) {
   			 //TODO: this should popup a dialog box
   			 e1.printStackTrace();
                SimpletestPlugin.logError("Error creating tempfile",e1);
                return;
         } catch (Exception e) {
             e.printStackTrace();
         }finally{
        	 if (tempFile != null){
        		 //delete on Exit sometimes does not cleanup after itself
        		 tempFile.delete();
        	 }
         }
    }
    
    private static class StreamReaderThread extends Thread
    {
        StringBuffer mOut;
        InputStreamReader mIn;
        
        public StreamReaderThread(InputStream in, StringBuffer out)
        {
        mOut=out;
        mIn=new InputStreamReader(in);
        }
        
        public void run()
        {
        int ch;
        try {
            while(-1 != (ch=mIn.read()))
                mOut.append((char)ch);
            }
        catch (Exception e)
            {
        	//TODO: find out why this happens
            mOut.append("\nRead error:"+e.getMessage());
            mOut.append("\n"+e.getStackTrace());
            }
        }
    }
}