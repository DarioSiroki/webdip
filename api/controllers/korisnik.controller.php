<?php

namespace Znamenitosti;
require_once("models/korisnik.model.php");
require_once("models/aktivacijski_kod.model.php");
require_once("config/settings.php");

class KorisnikController
{
    /**
     * Login user, store his information to session variable and return the user info which is not sensitive.
     */
    public function login() 
    {
        $form_data = json_decode(file_get_contents("php://input"));
        $captcha_response = $this->get_captcha($form_data->token);

        if ($captcha_response->success == false || $captcha_response->score < 0.5) {
            header("HTTP/1.1 409 Too Many Requests");
            return;
        }

        $korisnik_model = new KorisnikModel();

        $user_name = $form_data->username;
        $password = $form_data->password;
        $activationCode = $form_data->activationCode;
        $password_sha256 = hash("sha256", $user_name . $password);

        $result = $korisnik_model->login($user_name);

        if ($result->num_rows == 0) 
        {
            header("HTTP/1.1 401 Unauthorized");
            return;
        }

        $korisnik = $result->fetch_assoc();

        if ($korisnik["broj_neuspjesnih_prijava"] >= 3)
        {
            header("HTTP/1.1 401 Unauthorized");
            echo "Pogrešno ste se prijavili previše puta. Kontaktirajte administratora da vam odblokira račun.";
            return;
        }

        if ($korisnik["lozinka_sha256"] != $password_sha256)
        {
            header("HTTP/1.1 401 Unauthorized");
            echo "Kriva lozinka.";
            if ($korisnik["uloga"] != "administrator") 
            {
                $korisnik_model->inkrementiraj_neuspjesne_prijave($korisnik["korisnik_id"]);
            }
            return;
        }

        unset($korisnik["lozinka_sha256"]);

        $wasActivated = false;
        if(strlen($activationCode) > 0) {
            $wasActivated = $this->activate_user($korisnik["korisnik_id"], $activationCode);
        }

        if ($korisnik["uloga"] == "neregistrirani_korisnik" && !$wasActivated) 
        {
            header("HTTP/1.1 401 Unauthorized");
            echo "Korisnik nije aktiviran";
            return;
        }

        if ($wasActivated) {
            $korisnik["uloga"] = "registrirani_korisnik";
        }

        session_start();
        $_SESSION["korisnik"] = $korisnik;
        $returnValue = json_encode($korisnik);
        echo $returnValue;
    }

    public function activate_user($userId, $activationCode) 
    {
        $aktivacijski_kod_model = new AktivacijskiKodModel();
        $activationCodeResult = $aktivacijski_kod_model->get_code($userId, (int)$activationCode);

        if ($activationCodeResult->num_rows == 0) 
        {
            header("HTTP/1.1 401 Unauthorized");
            echo "Ovaj aktivacijski kod ne postoji";
            exit;
        }
        
        $activationCodeEntity = $activationCodeResult->fetch_assoc();
        $kreirano = strtotime($activationCodeEntity["kreirano"]);
        $now = time();
        $diff = $now - $kreirano;
        if ($diff > 14*60*60) 
        {
            header("HTTP/1.1 401 Unauthorized");
            echo "Aktivacijski kod je istekao.";
            exit;
        }

        $korisnik = new KorisnikModel();
        $korisnik->activate($userId);

        return true;
    }

    /**
     * Registers a new user if user with his name doesn't already exist.
     */
    public function register() 
    {
        $form_data = json_decode(file_get_contents("php://input"));

        $email = $form_data->email;
        if (!preg_match("/^.+@.+\..+$/", $email)) 
        {
            header("HTTP/1.1 400 Bad request");
            echo "Neispravan email.";  
            return;
        }
        $password = $form_data->password;
        if (preg_match("/^(.{0,7}|[^0-9]*|[^A-Z]*|[^a-z]*|[a-zA-Z0-9]*)$/", $password)) {
            header("HTTP/1.1 400 Bad request");
            echo "Neispravna lozinka.";  
            return;
        }
        $first_name = $form_data->first_name;
        if (strlen($first_name) < 3) 
        {
            header("HTTP/1.1 400 Bad request");
            echo "Ime mora sadržavati barem 3 znaka.";  
            return;
        }
        $second_name = $form_data->second_name;
        if (strlen($second_name) < 3) 
        {
            header("HTTP/1.1 400 Bad request");
            echo "Prezime mora sadržavati barem 3 znaka.";  
            return;
        }
        $user_name = $form_data->user_name;
        if (strlen($user_name) < 3) 
        {
            header("HTTP/1.1 400 Bad request");
            echo "Korisničko ime mora sadržavati barem 3 znaka.";  
            return;
        }
        $password_sha256 = hash("sha256", $user_name . $password);

        $korisnik = new KorisnikModel();
        $user_exists = $korisnik->user_exists($user_name);

        if ($user_exists)
        {
          header("HTTP/1.1 409 Conflict");   
          return;
        }

        $newUserId = $korisnik->register($first_name, $second_name, $user_name, $email, $password, $password_sha256);
        
        $aktivacijski_kod = new AktivacijskiKodModel();
        $activationCode = $aktivacijski_kod->insert_code($newUserId);
        mail($email, "Znamenitosti Hrvatske - Aktivacijski kod", "Vaš aktivacijski kod: " . $activationCode);
    }

    public function reset_password()
    {
        $form_data = json_decode(file_get_contents("php://input"));
        
        $korisnik_model = new KorisnikModel();
        
        $korime = $form_data->user_name;

        $result = $korisnik_model->get_by_user_name($korime);

        if ($result->num_rows == 0)
        {
            header("HTTP/1.0 404 Not Found");
            echo "Ne postoji ovo korisnicko ime";
            return;
        }

        $korisnik = $result->fetch_assoc();

        $nova_lozinka = (string)rand(100000, 999999);
        $nova_lozinka_sha256 = hash("sha256", $korime . $nova_lozinka);
        echo "<pre>";
        echo $korime . $nova_lozinka;
        $korisnik_model->update_password($nova_lozinka, $nova_lozinka_sha256, $korime);

        mail($korisnik->email, "Znamenitosti Hrvatske - Reset lozinke", "Vaša nova lozinka: " . $nova_lozinka);
    }

    /**
     * Destroys the session for current user.
     */
    public function log_out() 
    {
        session_start();
        session_destroy();
    }

    /**
     * Returns captcha score for the token that user had submitted.
     */
    public function get_captcha($secret_key){
        $secret = Settings::get_recaptcha_site_key();
        $url = "https://www.google.com/recaptcha/api/siteverify?secret=" . $secret . "&response={$secret_key}";

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_URL, $url);
        $response = curl_exec($ch);
        curl_close($ch);
        // $response = json_decode(file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=".$secret."&response=".$secret_key."&remoteip=".$_SERVER['REMOTE_ADDR']), true);
        
        return json_decode($response);
    }

    public function dohvati_korisnike() 
    {
        $korisnik_model = new KorisnikModel();
        $korisnici = $korisnik_model->dohvati_korisnike();

        echo json_encode($korisnici);
    }
}

?>