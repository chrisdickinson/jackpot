<?php

class TemplateLoader {    
    function get_valid_template ($file) {
        return null;
    }
}

class JackpotTemplateLoader {
    function get_valid_template ($file) {
        template_change_directory(dirname(__FILE__).'/templates');
        if(template_valid($file)) {
            return $file;
        }
        return null;
    }
}

class DefaultTemplateLoader {
    function get_valid_template ($file) {
        if(template_valid($file)) {
            return $file;
        }
    }
}

$Jackpot = new JackpotTemplateLoader();
$Default = new DefaultTemplateLoader();
?>
