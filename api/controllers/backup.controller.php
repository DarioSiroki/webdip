<?php

namespace Znamenitosti;
require_once("config/settings.php");

class BackupController
{

    public function dodaj() 
    {
        $settings = Settings::parse_settings();
        $filename='backup_'.date(DATE_ATOM).'.sql';
        $path = "backups/$filename";
        $username = $settings["database"]["username"];
        $password = $settings["database"]["password"];
        $db_name = $settings["database"]["db_name"];
        $cmd = "mysqldump -u $username --password=$password $db_name privitak znamenitost > $path";

        $x = exec($cmd, $output);

        echo $filename;
    }

    public function dohvati() 
    {
        $backups = scandir("backups");
        $real_backups = array();
        foreach($backups as $backup)
        {
            if (strpos($backup, 'backup_') === 0) {
                $real_backups[] = $backup;
             }
        }
        echo json_encode($real_backups);
    }
}

?>