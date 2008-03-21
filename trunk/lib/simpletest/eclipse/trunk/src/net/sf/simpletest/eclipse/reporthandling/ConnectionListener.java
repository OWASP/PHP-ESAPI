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
 * This class is derived from net.sf.phpeclipse.phpunit.reporthandling.ConnectionListener
 * See the net.sf.phpeclipse.phpunit project for source
 */
package net.sf.simpletest.eclipse.reporthandling;

import java.io.IOException;
import java.net.ServerSocket;
import java.net.Socket;
import java.net.SocketException;



 
public class ConnectionListener extends Thread {

	private ServerSocket sSocket = null;
	private Socket connSocket = null;
	public int port = 0;
	
	private int initPort(){
        int to = 10000;

        int i = 0;
        int todiv10 = (to/10 +1);
        int tmpPort = 0;
        while (i<to && tmpPort==0){
            try {
                Thread.sleep(todiv10);
            } catch (InterruptedException e1) {
            }
            i = i + todiv10;
            try {
                tmpPort = sSocket.getLocalPort();
            } catch (RuntimeException e) {
                tmpPort = 0;
            }
        }
	    return tmpPort;
	}

	
	public void start() {
		super.start();
		this.port = this.initPort();
	}
    
    public int getPort(){
        return port;
    }
	
	public void run() {
	    try {
	        sSocket = new ServerSocket(this.port,5);
	        
	        
	        while (sSocket!=null){
	            try {
                    connSocket = sSocket.accept();
                    ReportListener rl = new ReportListener(connSocket);
                    rl.start();
	            } catch (SocketException se) {
                    //do nothing -- usually caused by killing off socket
                }
	            
	            
	        }
        } catch (IOException e) {
            e.printStackTrace();
            //SimpletestPlugin.logError("Error opening socket",e);
        }
	}


	public void shutdown()
    {
        try {
            connSocket.close();
            sSocket.close();
            sSocket = null;
        }
        catch (Exception ignore)
        {
            ignore.printStackTrace();
            //SimpletestPlugin.logError("Error shutting down socket",ignore);
        }
    }

}
