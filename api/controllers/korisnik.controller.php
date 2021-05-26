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

        $korisnik = new KorisnikModel();

        $user_name = $form_data->username$user_name;
        $password_sha256 = hash("sha256", $form_data->password);
        $activationCode = $form_data->activationCode;

        $result = $korisnik->login($user_name, $password_sha256);
        
        if ($result->num_rows == 0) 
        {
            header("HTTP/1.1 401 Unauthorized");
            echo "Ne postoji korisnik sa ovim podacima.";
            return;
        }

        $korisnik = $result->fetch_assoc();

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
        $password_sha256 = hash("sha256", $password);

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
        
        return json_decode($response);
    }
}

?>