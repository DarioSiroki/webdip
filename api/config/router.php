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
