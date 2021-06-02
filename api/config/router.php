<?php 
namespace Znamenitosti;

class Router 
{
    private $router;

    public function __construct() 
    {
        $this->init();
        $this->requireControllers();
        $this->initRoutes();
    }

    public function start() 
    {
        $this->$router->run();
    }

    private function initRoutes() 
    {
        $this->$router->set404(function () {
            echo 'Ova stranica ne postoji.';
        });


        $this->$router->get('/', function () {
            echo 'Znamenitosti API';
        });


        $this->$router->post('/login', 'KorisnikController@login');
        $this->$router->post('/register', 'KorisnikController@register');
        $this->$router->post('/logout', 'KorisnikController@log_out');
        $this->$router->post('/reset-password', 'KorisnikController@reset_password');

        $this->$router->get('/znamenitost/statistika', 'ZnamenitostController@dohvati_statistiku');
        $this->$router->get('/znamenitost/popis_znamenitosti_i_autora', 'ZnamenitostController@popis_znamenitosti_i_autora');
        $this->$router->before('GET', '/znamenitost/popis', function() {
            $this->is_registered_user();
        });
        $this->$router->get('/znamenitost/popis', 'ZnamenitostController@popis_paginated');

        $this->$router->get('/grad', 'GradController@dohvati_gradove');

        $this->$router->post('/neregistrirani_prijedlog', 'NeregistriraniPrijedlogController@dodaj');
    }

    public function is_registered_user()
    {
        session_start();
        $lvl = $_SESSION["korisnik"]["uloga"];
        if ($lvl != "registrirani_korisnik")
        {
            header("HTTP/1.1 401 Unauthorized");
            exit;
        }
    }

    private function init() 
    {
        $this->$router = new \Bramus\Router\Router();
        $this->$router->setNamespace('\Znamenitosti');
    }

    private function requireControllers()
    {
        $controllers = scandir("controllers");
        foreach($controllers as $controller) {
            if (strpos($controller, ".controller.php") != false)
            {
                require_once("controllers/" . $controller);
            }
        }
    }
}

?>
