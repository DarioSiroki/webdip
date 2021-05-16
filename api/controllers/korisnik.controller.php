<?php

namespace Znamenitosti;
require_once("models/korisnik.model.php");

class KorisnikController
{
    /**
     * Login user and store his information to session variable
     */
    public function login() 
    {
        $korisnik = new KorisnikModel();

        $form_data = json_decode(file_get_contents("php://input"));

        $email = $form_data->email;
        $password_sha256 = hash("sha256", $form_data->password);

        $result = $korisnik->login($email, $password_sha256);
        
        if ($result->num_rows > 0) 
        {
            $korisnik = $result->fetch_assoc();
            session_start();
            $_SESSION["korisnik"] = $korisnik;
        } else 
        {
            header("HTTP/1.1 401 Unauthorized");
            exit;
        }
    }

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
}

?>