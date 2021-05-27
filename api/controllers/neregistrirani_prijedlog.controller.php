<?php

namespace Znamenitosti;
require_once("models/neregistrirani_prijedlog.model.php");

class NeregistriraniPrijedlogController
{

    public function dodaj() 
    {
        $form_data = json_decode(file_get_contents("php://input"));

        $naziv = $form_data->naziv;
        $opis = $form_data->opis;
        $gradId = $form_data->gradId;
        $ime = $form_data->ime;
        $prezime = $form_data->prezime;
        $nadimak = $form_data->nadimak;

        $nereg_prijedlog_model = new NeregistriraniPrijedlogModel();
        $newId = $nereg_prijedlog_model->dodaj($gradId, $naziv, $opis, $ime, $prezime, $nadimak);
        
        echo $newId;
    }
}

?>