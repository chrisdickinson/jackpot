<?php

class HttpResponse {
    protected $mimetype;
    protected $content_type;
    protected $status;
    protected $headers = array();
    protected $cookies = array();
    protected $content;


    function __construct($content, $mimetype, $status, $content_type) {
        $this->content = $content;
        $this->mimetype = $mimetype;
        $this->status = $status;
        $this->content_type = $content_type;
    }

    function render() {
        foreach($this->headers as $header) {
            header($header);
        }

        foreach($this->cookies as $cookie) {
            setcookie($cookie->name,$cookie->value,$cookie->expire);    #more later
        }

        $content = '';
        if($this->content instanceof Traversable) {
            foreach($this->content as $line) {
                $content .= $line;
            }
        } else {
            $content = $this->content;
        }
        echo $content;
    }

    function add_cookie($name, $value, $exp=null) {
    }

    function remove_cookie($name) {
    }
}

class HttpResponse200 extends HttpResponse {
    function __construct($content, $mimetype='text/html', $content_type='text/html') {
            parent::__construct($content,$mimetype,200,$content_type);
    }
}

class HttpResponse404 extends HttpResponse { 
    function __construct($content=null) {
            parent::__construct($content, 'text/html', 500, null);
    }

    function render() {
        //attempt to load settings->template_dir/404.html or debugtemplate
    }
}


class HttpResponse500 extends HttpResponse {
    function __construct($content=null) {
            parent::__construct($content, 'text/html', 500, null);
    }

    function render() {
        //attempt to load settings->template_dir/404.html or debugtemplate
    }
}


#shortcuts
class Http404 extends HttpResponse404 { } 
class Http500 extends HttpResponse500 { }

?>
