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

    public function popis_znamenitosti_i_autora() 
    {
        $znamenitost_model = new ZnamenitostModel();
        $popis = $znamenitost_model->popis_znamenitosti_i_autora();
        
        echo json_encode($popis);
    }

    public function popis_paginated()
    {
        $form_data = json_decode(file_get_contents("php://input"));
        $znamenitost_model = new ZnamenitostModel();
        $popis = $znamenitost_model->get_all();

        echo json_encode($popis);
    }

    public function popis_rss()
    {
        header( "Content-type: text/xml");
        $grad = $_GET["grad"];
        $znamenitost_model = new ZnamenitostModel();
        $popis = $znamenitost_model->get_ten($grad);
        
        $curr = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

        echo "<?xml version='1.0' encoding='UTF-8'?>
<rss version='2.0'>
<channel>
<title>Znamenitosti</title>
<description>Zadnjih 10 znamenitosti</description>
<link>".$curr."</link>
        ";
        
        foreach ($popis as $z)
        {
            echo "
<item>
<title>".$z['naziv']."</title>
<description>".$z['opis']."</description>
<link>". "a"."</link>
</item>
";
        }

        echo "</channel></rss>";
    }
}

?>