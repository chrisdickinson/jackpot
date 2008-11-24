<?php


class HttpRequest {
    var $uri;
    var $full_uri;
    var $get;
    var $post;

    public function __construct( $variables ) {
        $this->full_uri = $this->uri = $_GET['q'];
        $this->get = $_GET;
        $this->post = $_POST;   
    }

    public function __toString() {
        return "<HttpRequest \"{$this->uri}\">";
    }    

}


?>
