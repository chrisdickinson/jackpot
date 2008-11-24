<?php

class Module implements IteratorAggregate {
    var $names = array();
    var $name = '';
    public function &getIterator() {
        return new ArrayIterator($this->names);
    }

    public function __toString() {
        var_dump($this->name);
        return $this->name;
    }

    public function __construct($path) {
        $this->name = $path;
    }

    public function build() {
        $path = explode('.', $this->name);
        $rest = array();    
        while(!empty($path)) {
            $names = get_defined_vars();
            $functions = get_defined_functions();
            $functions = $functions['user'];
            if((@include(implode('/',$path).'.php'))) {
                $names = get_defined_vars();
                $new_functions = get_defined_functions();
                $new_functions = array_diff($new_functions['user'], $functions);
                $this->names = $names;
                foreach($new_functions as $fnname) { 
                    $this->names[$fnname] = $fnname;
                }
                if(!empty($rest)) {
                    return $this->names[implode('',$rest)];
                }
                return $this;
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
