<?php
require_once('jackpot/core/import.php');
require_once('jackpot/http/request.php');
require_once('jackpot/http/response.php');
require_once('jackpot/http/exceptions.php');
require_once('jackpot/conf/settings.php');
require_once('jackpot/core/urlresolver.php');
require_once('jackpot/middleware/core.php');

function request($request) {
    #ob_start();
    $request =& new HttpRequest($request);
    $settings =& Settings::configure(getenv('JACKPOT_SETTINGS'));
    $request =  Middleware::process($settings->MIDDLEWARE_CLASSES, $request);
    $urlresolver =& new URLResolver(import($settings->ROOT_URLCONF));
    $response = null;
    if($settings->database) {
        ORM::initialize($settings->database);
    }
    try{
        $response = $urlresolver->resolve($request);
    }
    catch(Http404Exception $error) {
        
        if($settings->DEBUG) {
            var_dump($error);
        }
        $response =& new Http500();
    }
    #ob_end_clean();
    return $response;
}

$response = request($_GET['q']);
$response->render();
?>
