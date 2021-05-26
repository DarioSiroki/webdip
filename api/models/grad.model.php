<?php 

namespace Znamenitosti;
include_once("config/database.php");

class GradModel
{
    private $connection;

    public function __construct() 
    {
		$this->$connection = Database::start_connection();
	}

    public function __destruct() 
    {
        $this->$connection->close();
    }

    public function dohvati_gradove() 
    {
        $query = "SELECT * FROM grad";
        $result = $this->$connection->query($query);
        $gradovi = array();
        if ($result->num_rows > 0) {
            while($red = $result->fetch_assoc()) {
                $gradovi[] = $red;
            }   
        }
        return $gradovi;
    }
}