<?php
/**
 * OWASP Enterprise Security API (ESAPI)
 *
 * This file is part of the Open Web Application Security Project (OWASP)
 * Enterprise Security API (ESAPI) project.
 * 
 * PHP version 5.2
 *
 * LICENSE: This source file is subject to the New BSD license.  You should read
 * and accept the LICENSE before you use, modify, and/or redistribute this
 * software.
 *
 * @category  OWASP
 * @package   ESAPI
 * @author    jah <jah@jahboite.co.uk>
 * @copyright 2009-2010 The OWASP Foundation
 * @license   http://www.opensource.org/licenses/bsd-license.php New BSD license
 * @version   SVN: $Id$
 * @link      http://www.owasp.org/index.php/ESAPI
 */
require_once dirname(__FILE__) . '/../src/ESAPI.php';

//Make sure to run this script as a "PHP Web Page"

$ESAPI = new ESAPI(dirname(__FILE__) . "/testresources/ESAPI.xml");
ob_start();
session_start();

$view = '';
$tests = null;
if (isset($_SESSION) && isset($_SESSION['tests'])) {
    $tests = & $_SESSION['tests'];
} else {
    $tests = array(
        'csi' => 'changeSessionIdentifier',
        'token' => 'verifyCSRFToken',
        'cookie' => 'killAllCookies (incl. killCookie)',
        'log' => 'logHTTPRequest',
        'logo' => 'logHTTPRequestObfuscate'
    );
    $_SESSION['tests'] = & $tests;
}

$util = ESAPI::getHTTPUtilities();
$req = new SafeRequest;
$uri = ESAPI::getEncoder()->encodeForHTML($req->getRequestURI());

if ($req->getParameter('req') == 'test1') {
    try {
        $util->verifyCSRFToken($req);
        $view .= '<p>Your Request contained the CSRF token we have in your session. Good!</p>';
    } catch (IntrusionException $e) {
        $view .= '<p>Your Request did NOT contain the CSRF token we have in your session. Did you tamper??</p>';
    }
    $tests['token'] .= ' - DONE';
    
    $oldSessID = session_id();
    $sr = $util->changeSessionIdentifier();
    if ($sr === true) {
        $view .= '<p>Your session was regenerated. ID went from: ';
        $view .= ESAPI::getEncoder()->encodeForHTML($oldSessID);
        $view .= ' to: ';
        $view .= ESAPI::getEncoder()->encodeForHTML(session_id());
        $view .= '</p>';
    } else {
        $view .= '<p>Your session was not regenerated. Is the session started?';
    }
    $tests['csi'] .= ' - DONE';
    
    $util->killAllCookies($req);
    $view .= '<p>The response should have requested your User Agent to delete your cookies. Let us see if it will honour that request.';
    $view .= " <a href=\"{$uri}?req=test2\">click me!</a></p>";
} else if ($req->getParameter('req') == 'test2') {
    $view .= '<p>Cookies received in that request: ';
    $view .= ESAPI::getEncoder()->encodeForHTML(print_r($req->getCookies(), true));
    $view .= '</p>';
    $view .= '<p>';
    if ($req->getCookie('testcookie') === null) {
        $view .= 'It worked! testcookie was not received in that request.';
    } else {
        $view .= 'It did not work. testcookie was received in that request.';
    }
    $view .= '</p>';
    $tests['cookie'] .= ' - DONE';
    
    $a = ESAPI::getAuditor('HTTPUtilsExtraTests');
    $log = $util->logHTTPRequest($req, $a);
    $logO = $util->logHTTPRequestObfuscate($req, $a, array('req'));
    $view .= '<p>Please check the ESAPI Auditor logfile for two INFO entries which log that request.  The second entry will contain the obfuscated "req" parameter.';
    $view .= '</p>';
    $tests['log'] .= ' - DONE';
    $tests['logo'] .= ' - DONE';
    session_destroy();
} else {
    $href = $util->addCSRFToken("{$uri}?req=test1");
    $view .= '<p>testcookie has been set with a value \'testcookieValue\'. now <a href="';
    $view .= $href;
    $view .= '">click me</a> to have it deleted. (Please ensure logging is on before you continue!)</p>';
    setcookie('testcookie', 'testcookieValue');
}
$view .= '<p>Under Test:</p>';
$view .= '<ul>';
foreach ($tests as $test) {
    $view .= "<li>{$test}</li>";
}
$view .= '</ul>';
?>
<html>
<head>
	
</head>
<body>
	<div><?php echo $view; ?></div>
	<div id="js"></div>
	<script type="text/javascript">
	var js = document.getElementById('js');
	var text = document.createTextNode('JavaScript says document.cookie is: ' + document.cookie);
	js.appendChild(text);
	</script>
	<noscript><div>if you had javascript enabled, i could show you your cookies!</div></noscript>
</body>
</html>
