<?php

namespace Znamenitosti;
require_once("models/korisnik.model.php");
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

        $email = $form_data->email;
        $password_sha256 = hash("sha256", $form_data->password);

        $result = $korisnik->login($email, $password_sha256);
        
        if ($result->num_rows > 0) 
        {
            $korisnik = $result->fetch_assoc();
            session_start();
            $_SESSION["korisnik"] = $korisnik;
            $returnValue = json_encode($korisnik);
            echo $returnValue;
        } else 
        {
            header("HTTP/1.1 401 Unauthorized");
            exit;
        }
    }

    /**
     * Registers a new user if user with his name doesn't already exist.
     */
    public function register() 
    {
        $korisnik = new KorisnikModel();

        $form_data = json_decode(file_get_contents("php://input"));

        $email = $form_data->email;
        $password = $form_data->password;
        $first_name = $form_data->first_name;
        $second_name = $form_data->second_name;
        $user_name = $form_data->user_name;
        $password_sha256 = hash("sha256", $password);

        $user_exists = $korisnik->user_exists($user_name);

        if ($user_exists)
        {
          header("HTTP/1.1 409 Conflict");   
          return;
        }

        $result = $korisnik->register($first_name, $second_name, $user_name, $email, $password, $password_sha256);

        if ($result == true) 
        {
            session_start();
            return;
        }

        header("HTTP/1.1 500 Internal server error");
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