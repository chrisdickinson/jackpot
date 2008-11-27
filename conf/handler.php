<?php
require_once('jackpot/core/import.php');
import('jackpot.http.request');
import('jackpot.http.response');
import('jackpot.http.exceptions');
import('jackpot.conf.settings');
import('jackpot.middleware.core');
import('jackpot.template.template');
import('jackpot.template.context');
import('jackpot.core.urlresolver');
require_once('jackpot/models/core.php');

function request($request) {
    #ob_start();
    $request =& new HttpRequest($request);
    $settings =& Settings::configure(getenv('JACKPOT_SETTINGS'));

    foreach($settings->MIDDLEWARE_CLASSES as $middleware) {
        $module = import($middleware);
        $request = $module->process($request);
    }

    $urlresolver =& new URLResolver(import($settings->ROOT_URLCONF));
    $template_loaders = array();    
    foreach($settings->TEMPLATE_LOADERS as $template_loader) {
        $template_loaders = import($template_loader);
    }        
    $template_engine = import($settings->TEMPLATE_ENGINE);    
    template_initialize($settings);

    $response = null;
    if($settings->database) {
        ORM::initialize($settings->database);
    }
    try{
        $response = $urlresolver->resolve($request);
    }
    catch(Http404Exception $error) {
        foreach($settings->FALLBACK_CLASSES as $fallback) {
            $module = import($fallback);
            $response = $module->process($request);
            if(!empty($response)) {
                break;
            }
        }
        if(empty($response)) {
            $template = new Template($settings->DEBUG ? 'debug/404.html' : '404.html');
            $context = new Context();
            $context->error($error)->request($request)->settings($settings);
            $response =& new Http404($template->render($context));
        }
    }
    catch(Exception $error) {
        $template = new Template($settings->DEBUG ? 'debug/500.html' : '500.html');
        $context = new Context();
        $context->error($error)->request($request)->settings($settings);
        $response =& new Http500($template->render($context));
    }
    #ob_end_clean();
    return $response;
}

$response = request($_GET['q']);
$response->render();
?>
