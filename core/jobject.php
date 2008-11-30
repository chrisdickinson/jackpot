<?php

class MethodDoesNotExist extends Exception {
}

class JObject extends StdClass {
    function __toString() {
        return $this->__call('__unicode__',array());
    }

    function &__call($method, $args) {
        if($this->$method instanceof Closure) {
            $method = $this->$method;

            return $method( $this, 
                            $args[0], $args[1], 
                            $args[2], $args[3], 
                            $args[4], $args[5], 
                            $args[6], $args[7]);
        } else if ( !empty($this->super) ) {
            $super = $this->super;
            while($super instanceof Closure) {
                $super = $super();
                $method = $super->$method;
                if($method instanceof Closure) {
                    return $method( $this, 
                                    $args[0], $args[1], 
                                    $args[2], $args[3], 
                                    $args[4], $args[5], 
                                    $args[6], $args[7]);
                }
                $super = $super->super;
            }        
        }
        #okay, well, try the __call__ method
        if($this->__call__ instanceof Closure) {
            return $this->__call('__call__', $args);
        }
        throw new MethodDoesNotExist("$this::$method does not exist!");
    }

    function extend ( $closure ) {
        $this->super = $closure;
    }
}

?>
