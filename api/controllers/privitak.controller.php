<?php

namespace Znamenitosti;
require_once("models/privitak.model.php");

class PrivitakController
{

    public function dodaj() 
    {
        session_start();
        $znamenitost_id = $_POST['znamenitost_id'];
        $tip = $_FILES["file"]["type"];
        $korisnik_id = $_SESSION["korisnik"]["korisnik_id"];
        $naziv = $_FILES["file"]["name"];

        $privitak_model = new PrivitakModel();
        $id = $privitak_model->dodaj($korisnik_id, $znamenitost_id, $tip, $naziv);

        $x = move_uploaded_file($_FILES["file"]["tmp_name"], "./materijali/" . $naziv);
    }
}

?>