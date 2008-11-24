<?php

class Context {
    public function __call($method, $args) {
        $this->$method = $args[0];
        return $this;
    }
}

?>
