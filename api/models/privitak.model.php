<?php 

namespace Znamenitosti;
include_once("config/database.php");

class PrivitakModel
{
    private $connection;

    public function __construct() 
    {
		$this->connection = Database::start_connection();
	}

    public function __destruct() 
    {
        $this->connection->close();
    }

    public function dodaj($korisnik_id, $znamenitost_id, $tip, $naziv) 
    {
        $query = "INSERT INTO privitak (korisnik_id, znamenitost_id, tip, naziv) VALUES (?, ?, ?, ?)";
        $statement = $this->connection->prepare($query);
        $statement->bind_param("iiss", $korisnik_id, $znamenitost_id, $tip, $naziv);
        $statement->execute();
        $newId = $statement->insert_id;
        $statement->close();
        return $newId;
    }

    public function get()
    {
        $query = "SELECT * from privitak";
        $result = $this->connection->query($query);
        $lista = array();
        if ($result->num_rows > 0) {
            while($red = $result->fetch_assoc()) {
                $lista[] = $red;
            }   
        }
        return $lista;
    }
}