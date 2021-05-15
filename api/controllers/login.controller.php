<?php

namespace Znamenitosti;
require_once("models/korisnik.model.php");

class Login 
{
    /**
     * Login user and store his information to session variable
     */
    public function login() 
    {
        $korisnik = new Korisnik();

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
}

?>