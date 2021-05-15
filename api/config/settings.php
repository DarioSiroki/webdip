<?php 

namespace Znamenitosti;

class Settings {
    public static function parse_settings() 
    {
        return parse_ini_file('settings.ini', true);
    }
}

?>
