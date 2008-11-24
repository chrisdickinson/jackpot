<?php

class Settings {
    private static $_this;

    private function __construct($file) {
        $this->build_settings($file);
    }

    private function build_settings($file) {
        if($file == '') {
            throw new Exception("No settings file supplied! Check your .htaccess file!");
        }
        try {
            $defaults = import('jackpot.conf.defaults');
            $settings = import($file);
            foreach($settings as $setting => $value) {
                $this->$setting = $value;
            }
        }
        catch ( Exception $ex ) {
            throw new Exception("Could not find settings file $file");
        }        

    }

    public static function configure($file) {
        Settings::$_this =& new Settings($file);
        return Settings::$_this;
    }

    public static function &options() {
        return $_this;
    }

}

?>
