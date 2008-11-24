<?php

class HttpException extends Exception {
    var $request;
    var $attempted_urls;
    public function __construct($request, $attempted_urls) {
        $this->request = $request;
        $this->attempted_urls = $attempted_urls;
    }
}

class Http404Exception extends HttpException { }
class Http500Exception extends HttpException { }


?>
