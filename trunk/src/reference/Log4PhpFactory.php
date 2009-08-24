<?php

/**
 * Reference implementation of the LogFactory and Logger interfaces. This implementation uses the Apache Log4J package, and marks each
 * log message with the currently logged in user and the word "SECURITY" for security related events. See the
 * <a href="JavaLogFactory.JavaLogger.html">JavaLogFactory.JavaLogger</a> Javadocs for the details on the JavaLogger reference implementation.
 *
 * @author Mike H. Fauzy (mike.fauzy@aspectsecurity.com) <a href="http://www.aspectsecurity.com">Aspect Security</a>
 * @author Jim Manico (jim.manico .at. aspectsecurity.com) <a href="http://www.aspectsecurity.com">Aspect Security</a>
 * @author Jeff Williams (jeff.williams .at. aspectsecurity.com) <a href="http://www.aspectsecurity.com">Aspect Security</a>
 * @since June 1, 2007
 * @see org.owasp.esapi.LogFactory
 * @see org.owasp.esapi.reference.Log4JLogFactory.Log4JLogger
 */

require_once dirname(__FILE__).'/../LogFactory.php';
require_once dirname(__FILE__).'/DefaultLogger.php';

class Log4PhpFactory implements LogFactory {

        var $loggerMap = array();

        /**
        * Null argument constructor for this implementation of the LogFactory interface
        * needed for dynamic configuration.
        */
        function __construct() {
            
        }


       /**
        * {@inheritDoc}
        */
        public function getLogger($moduleName) {

            // If a logger for this module already exists, we return the same one, otherwise we create a new one.
            if(array_key_exists($moduleName,$this->loggerMap)
                && $this->loggerMap[$moduleName] instanceof Logger) {
                return $this->loggerMap[$moduleName];
            } else {
                $moduleLogger = new DefaultLogger($moduleName);
                $this->loggerMap[$moduleName] = $moduleLogger;
                    return $moduleLogger;
            }
        }

}

?>
