<?php

namespace Znamenitosti;
require_once("settings.php");
use Mysqli;

class Database 
{
    public static function start_connection() 
    {
        $settings = Settings::parse_settings();
        $connection = new mysqli(
            $settings["database"]["server_url"], 
            $settings["database"]["username"], 
            $settings["database"]["password"], 
            $settings["database"]["db_name"]
        );
        if ($connection->connect_error) 
        {
            die("Greška pri spajanju na bazu: " . $connection->connect_error);
        }
        return $connection;
    }
}

?>
