<?php
require_once('jackpot/http/exceptions.php');


class URLConf {
    var $regex;
    var $target;
    var $name;

    public function __construct($regex, $target,  $name) {
        $this->regex = str_replace('/','\/',$regex);        //make sure that urls like blah/blah can be typed without having to do blah\/blah
        $this->target = $target;
        $this->name = $name;
    }


    public function match($url) {
        $matches = array();
        if(preg_match("/{$this->regex}/", $url, $matches) > 0) {
            return $matches;
        }
        return null;
    } 

    public function reverse($args) {
        $ordered_args = array();
        foreach($this->args as $arg) {
            $ordered_args[] = $args[$arg];
        }

        return $this;
    }
}

function url($regex, $target, $name) {
    return new URLConf($regex, $target, $name);
}

class URLResolver {
    var $urls;
    var $parent;
    public function __construct($urlconf) {
        $this->urls = $urlconf->urls;
    }

    public function resolve($request) {
        $attempted_urls = array();
        foreach($this->urls as $url) {
            $attempted_urls[] = $url->regex;
            if($matches = $url->match($request->uri)) {
                $response = null;
                $module = $url->target;

                if(is_string($url->target)) {
                    $module = import($module);
                }

                if($module instanceof Closure) {
                    $response = $module($request, $matches);
                    return $response;
                }
                else if($module instanceof Module) {
                    $internal_urlconf = new URLResolver($module);
                    try {
                        $request->uri = str_replace($matches[0],'',$request->uri);
                        $response = $internal_urlconf->resolve($request);
                        return $response;
                    }
                    catch (Http404Exception $ex) { 
                        foreach($ex->attempted_urls as $exurl) {
                            $attempted_urls[] = $url->regex . $exurl;
                        }
                    }
                }
            }
        }
        throw new Http404Exception($request, $attempted_urls);
    } 

}

?>
