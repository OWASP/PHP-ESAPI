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
 * This class is derived from net.sf.phpeclipse.phpunit.reporthandling.ReportListener
 * See the net.sf.phpeclipse.phpunit project for source
 */
package net.sf.simpletest.eclipse.reporthandling;

import java.io.BufferedReader;
import java.io.IOException;
import java.io.InputStreamReader;
import java.net.Socket;

import net.sf.simpletest.eclipse.SimpletestPlugin;
import net.sf.simpletest.eclipse.ui.SimpletestView;


public class ReportListener extends Thread {

	Socket serviceSocket;
	
	
	public ReportListener(Socket serviceSocket) {
		this.serviceSocket = serviceSocket;
	}

	public void run() {

		InputStreamReader reader = null;
		try {
			reader = new InputStreamReader(serviceSocket.getInputStream());

			BufferedReader in = new BufferedReader(reader);
			StringBuffer report = new StringBuffer("");
			String tmpreport = null;
			char[] buf = new char[1];
            boolean inString = false;
            boolean inSlash = false;
            while (in.read(buf,0,1) != -1){
			    tmpreport = String.valueOf(buf, 0,1);
                report.append(tmpreport);
                //this means we finished a result
                if ("}".equals(tmpreport) && !inString){
                	SimpletestPlugin.logDebug(">>: " + report.toString());
                    SimpletestView.getDefault().handleReport(report.toString());
                    report = new StringBuffer("");
                    inString = false;
                }
                if ("\"".equals(tmpreport) && !inSlash){
                    inString = !inString;
                }
                //this goes last
                if ("\\".equals(tmpreport) && !inSlash){
                    inSlash = true;
                }else{
                    inSlash = false;
                }
			}
		} catch (IOException e) {
            e.printStackTrace();
            SimpletestPlugin.logError("Error reading from socket",e);
		} finally {
		    try {
                reader.close();
            } catch (IOException e) {
                //just eat the exception
            }
        }
        SimpletestView.getDefault().handleReport(null);
	}

}