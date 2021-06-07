<?php

namespace Znamenitosti;
require_once("models/moderator.model.php");

class ModeratorController
{

    public function dohvati_moderatore() 
    {
        $moderator_model = new ModeratorModel();
        $moderatori = $moderator_model->dohvati_moderatore();

        echo json_encode($moderatori);
    }

    public function dodaj_moderatora() 
    {
        $form_data = json_decode(file_get_contents("php://input"));
        $korisnik_id = $form_data->korisnik_id;
        $grad_id = $form_data->grad_id;

        $moderator_model = new ModeratorModel();
        $moderator_model->dodaj_moderatora($grad_id, $korisnik_id);
    }

    public function obrisi_moderatora() 
    {
        $form_data = json_decode(file_get_contents("php://input"));
        $korisnik_id = $form_data->korisnik_id;
        $grad_id = $form_data->grad_id;

        $moderator_model = new ModeratorModel();
        $moderator_model->obrisi_moderatora($grad_id, $korisnik_id);
    }
}

?>