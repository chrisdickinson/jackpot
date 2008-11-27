<?php

class Field {
    var $name;

    public function __construct() {

    }

    public function build_field() {
        $settings = import('jackpot.conf.settings');
        $db = import("jackpot.db.engine.{$settings->DATABASE_ENGINE}");
        return $db->render_field($this->name, $this->default, $this->allow_null, $this->length);
    }

    public function foreign_key($object) {
        
    }

}

class Model {

}

function char_field() {
    return new Field();
}

function foreign_key() {
    return new Field();
}

class Blog extends Model {
}

?>
