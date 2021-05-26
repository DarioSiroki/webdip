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
}

?>