<?php
require_once('jackpot/http/exceptions.php');


class url {
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

class URLResolver {
    var $urls;
    var $parent;
    public function __construct($urlconf) {
        $module = import($urlconf);
        $this->urls = $module->urls;
    }

    public function resolve($request) {
        foreach($this->urls as $url) {
            if($matches = $url->match($request->uri)) {
                $module = import($url->target);
                return $module($request, $matches);
            }
        }
        throw new Http404Exception($request);
    } 

}

?>
