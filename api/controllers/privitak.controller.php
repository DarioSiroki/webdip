<?php

namespace Znamenitosti;
require_once("models/privitak.model.php");

class PrivitakController
{

    public function dodaj() 
    {
        session_start();
        $znamenitost_id = $_POST['znamenitost_id'];
        $tip = $_POST['fileType'];
        $korisnik_id = $_SESSION["korisnik"]["korisnik_id"];
        $naziv = $_FILES["file"]["name"];

        $privitak_model = new PrivitakModel();
        $id = $privitak_model->dodaj($korisnik_id, $znamenitost_id, $tip, $naziv);

        $x = move_uploaded_file($_FILES["file"]["tmp_name"], "./materijali/" . $naziv);
    }

    public function get() 
    {
        $privitak_model = new PrivitakModel();
        $lista = $privitak_model->get();

        echo json_encode($lista);
    }
}

?>