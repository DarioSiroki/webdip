<?php

namespace Znamenitosti;
require_once("models/registrirani_zahtjev.model.php");

class RegistriraniZahtjevController
{

    public function dodaj() 
    {
        session_start();
        $form_data = json_decode(file_get_contents("php://input"));

        $korisnik_id = $_SESSION["korisnik"]["korisnik_id"];
        $naziv = $form_data->naziv;
        $opis = $form_data->opis;
        $gradId = $form_data->gradId;
        $godina = $form_data->godina;

        $reg_zahtjev_model = new RegistriraniZahtjevModel();
        $newId = $reg_zahtjev_model->dodaj($gradId, $naziv, $opis, $godina, $korisnik_id);
        
        echo $newId;
    }

    public function get()
    {
        $reg_zahtjev_model = new RegistriraniZahtjevModel();
        $data = $reg_zahtjev_model->get();

        echo json_encode($data);
    }

    public function update()
    {
        session_start();
        $korisnik_id = $_SESSION["korisnik"]["korisnik_id"];
        $form_data = json_decode(file_get_contents("php://input"));
        $registrirani_zahtjev_id = $form_data->registrirani_zahtjev_id;
        $status = $form_data->status;
        $reg_zahtjev_model = new RegistriraniZahtjevModel();

        if ($status == "potvrđeno")
        {
            $reg_zahtjev_model->dodaj_znamenitost($registrirani_zahtjev_id, $korisnik_id);
        }

        $reg_zahtjev_model->update($registrirani_zahtjev_id, $status);
    }
}

?>