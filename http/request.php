<?php


class HttpRequest {
    var $uri;
    var $get;
    var $post;

    public function __construct( $variables ) {
        $this->uri = $_GET['q'];
        $this->get = $_GET;
        $this->post = $_POST;   
    }

    public function __toString() {
        return "<HttpRequest \"{$this->uri}\">";
    }    

}


?>
