<?php

class HttpException extends Exception {
    var $request;
    public function __construct($request) {
        $this->request = $request;
    }
}

class Http404Exception extends HttpException { }
class Http500Exception extends HttpException { }


?>
