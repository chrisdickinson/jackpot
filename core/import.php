<?php

class Module {
    static private $included = array();

    var $names = array();
    var $name = '';
    public function &getIterator() {
        return new ArrayIterator($this->names);
    }

    public function __toString() {
        return $this->name;
    }

    public function __construct($path) {
        $this->name = $path;
    }

    public function import($path) {
        $current_path = explode('.', $this->name);
        $current_path = array_slice($current_path, 0, sizeof($current_path)-1);
        $current_path = implode('.', $current_path);
        $result = import("$current_path.$path");
        return $result;
    }

    private function return_rest ($module, $rest) {
        $start = $module;
        foreach($rest as $next) {
            $start = $start->$next;
        }
        return ($start);
    }

    public function build() {
        $path = explode('.', $this->name);
        $rest = array();    
        $__attempted_paths = array();
        while(!empty($path)) {
            $__attempted_paths[] = $__path = implode('/', $path).'.php';
            if(self::$included[$__path]) {
                //okay, we've got the module,
                $return = self::$included[$__path];
                if(!empty($rest)) {
                    $return = $this->return_rest($return, $rest);
                }
                return $return;
            }
            else {
                $includable = function () use ($__path) {
                    $paths = explode(':', get_include_path());
                    foreach($paths as $path) {
                        if(is_file("$path/$__path")) {
                            return true;
                        }
                    }
                    return false;
                };
                if($includable() && (include($__path))) {
                    $names = get_defined_vars();
                    $this->names = array_diff_key($names, $this->names);
                    $return = $this;
                    if(!empty($rest)) {
                        $return = $this->return_rest($this, $rest);
                    }
                    self::$included[$__path] = $this;

                    return $return; 
                } else {
                    $rest[] = array_pop($path); 
                }
            }
        }  
        throw new Exception("Could not import {$this->name}");
    }

    public function __get($name) {
        return $this->names[$name];
    }

    public function __set($name, $value) {
        $this->names[$name] = $value;
        return $this->names[$name];
    }
}

function import($string) {
    $module = new Module($string);
    return $module->build($string);
}

?>
