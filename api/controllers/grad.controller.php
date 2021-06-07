<?php

namespace Znamenitosti;
require_once("models/grad.model.php");

class GradController
{

    public function dohvati_gradove() 
    {
        $grad_model = new GradModel();
        $gradovi = $grad_model->dohvati_gradove();

        echo json_encode($gradovi);
    }

    public function uredi_grad() 
    {
        $form_data = json_decode(file_get_contents("php://input"));
        $grad_id = $form_data->grad_id;
        $naziv = $form_data->naziv;
        $opis = $form_data->opis;
        $postanski_broj = $form_data->postanski_broj;
        $povrsina = $form_data->povrsina;
        $broj_stanovnika = $form_data->broj_stanovnika;

        $grad_model = new GradModel();
        $grad_model->uredi_grad($grad_id, $naziv, $opis, $postanski_broj, $povrsina, $broj_stanovnika);
    }

    public function dodaj_grad() 
    {
        $form_data = json_decode(file_get_contents("php://input"));
        $naziv = $form_data->naziv;
        $opis = $form_data->opis;
        $postanski_broj = $form_data->postanski_broj;
        $povrsina = $form_data->povrsina;
        $broj_stanovnika = $form_data->broj_stanovnika;

        $grad_model = new GradModel();
        $newId = $grad_model->dodaj_grad($naziv, $opis, $postanski_broj, $povrsina, $broj_stanovnika);

        echo $newId;
    }
}

?>