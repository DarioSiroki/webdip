<?php 

namespace Znamenitosti;

class Settings {
    public static function parse_settings() 
    {
        return parse_ini_file('settings.ini', true);
    }

    public static function get_recaptcha_site_key() 
    {
        $settings = Settings::parse_settings();
        return $settings["captcha"]["key"];
    }
}

?>
