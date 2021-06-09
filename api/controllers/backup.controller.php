<?php

namespace Znamenitosti;
require_once("config/settings.php");
require_once("config/database.php");
require_once("models/privitak.model.php");
require_once("models/korisnik.model.php");

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

    public function vrati()
    {
        // Obrisi staro
        $db = Database::start_connection();
        $db->query("DELETE FROM znamenitost");
        $db->query("DELETE FROM privitak");
        $db->close();

        // Importaj backup
        $filename = json_decode(file_get_contents("php://input"))->naziv;
        $path = "backups/$filename";
        $settings = Settings::parse_settings();
        $username = $settings["database"]["username"];
        $password = $settings["database"]["password"];
        $db_name = $settings["database"]["db_name"];
        $cmd = "mysql -u $username --password=$password $db_name < $path";
        echo $cmd;

        exec($cmd, $output);

        $korisnik_model = new KorisnikModel();
        $privitak_model = new PrivitakModel();

        // Fetchaj privitke iz baze i lokalne privitke
        $privici = $privitak_model->get();
        $privici_local = scandir("materijali");
        // Fetchaj i korisnike da se zna kome poslat mail
        $korisnici = $korisnik_model->dohvati_korisnike();

        // Loop kroz privitke iz baze
        foreach ($privici as $privitak)
        {
            $exists = false;
            // Pogledaj jel postoji privitak lokalno
            foreach($privici_local as $pl)
            {
                if ($pl == $privitak["naziv"])
                {
                    // Postoji 
                    $exists = true;
                }
            }

            // Ako ne postoji, pronadji korisnika i posalji mu mail
            if ($exists == false)
            {
                foreach($korisnici as $korisnik)
                {
                    if ($korisnik["korisnik_id"] == $privitak["korisnik_id"])
                    {
                        $email = $korisnik["email"];
                        $subject = "Znamenitosti Hrvatske - povratak materijala";
                        $body = "Poštovani, izgubili smo datoteku " . 
                                $privitak["naziv"] . 
                                ". Ako je imate uploadajte opet jer smo ju izgubili. Hvala.";
                        mail($email, $subject, $body);
                    }
                }
            }

        }
    }
}

?>