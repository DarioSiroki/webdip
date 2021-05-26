<?php

namespace Znamenitosti;
require_once("models/znamenitost.model.php");

class ZnamenitostController
{

    public function dohvati_statistiku() 
    {
        $znamenitost_model = new ZnamenitostModel();
        $statistika = $znamenitost_model->dohvati_statistiku();
        
        echo json_encode($statistika);
    }
}

?>