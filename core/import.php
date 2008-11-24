<?php

class ModuleFunction {
    var $name; 
    public function __construct($name) {
        $this->name = $name;
    }

    public function __toString() {
        return "{$this->name}";
    }
}

class Module implements IteratorAggregate {
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
            $functions = get_defined_functions();
            $functions = $functions['user'];
            $__attempted_paths[] = $__path = implode('/', $path).'.php';
            if(self::$included[$__path]) {
                //okay, we've got the module,
                $return = self::$included[$__path];
                if(!empty($rest)) {
                    $return = $this->return_rest($return, $rest);
                }
                return $return;
            }
            else if((@include($__path))) {

                $names = get_defined_vars();
                $new_functions = get_defined_functions();
                $new_functions = array_diff($new_functions['user'], $functions);
                $this->names = $names;
                foreach($new_functions as $fnname) { 
                    $this->names[$fnname] = new ModuleFunction($fnname);
                }
                $this->names = array_merge($this->names, $names);
                
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
        throw new Exception("Could not import {$this->name}");
    }

    public function __get($name) {
        return $this->names[$name];
    }
}

function import($string) {
    $module = new Module($string);
    return $module->build($string);
}

?>
