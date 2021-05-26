<?php 

namespace Znamenitosti;
include_once("config/database.php");

class ZnamenitostModel
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

    public function dohvati_statistiku() 
    {
        $query = 
        "
        SELECT g.naziv as naziv, broj_znamenitosti
        FROM grad g
        LEFT JOIN (select grad_id, count(*) as broj_znamenitosti
                FROM znamenitost 
               GROUP BY grad_id) z
          ON g.grad_id = z.grad_id
        ";
        $result = $this->$connection->query($query);
        $statistika = array();
        if ($result->num_rows > 0) {
            while($red = $result->fetch_assoc()) {
                if ($red["broj_znamenitosti"] == null) {
                    $red["broj_znamenitosti"] = 0;
                }
                $statistika[] = $red;
            }   
        }
        return $statistika;
    }
}