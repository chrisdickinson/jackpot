<?php
class Template {
    var $file;

    public function __construct ( $stream ) {
        $this->file = $stream;
    }
    
    public function render ( $context ) {
        foreach($context as $key => $value) {
            template_assign($key, $value);
        }
        template_display($this->file);
    }
}

?>
