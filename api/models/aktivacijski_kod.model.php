<?php 

namespace Znamenitosti;
include_once("config/database.php");

class AktivacijskiKodModel
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

    public function insert_code($korisnik_id) 
    {
        $query = "INSERT INTO aktivacijski_kod (korisnik_id) VALUES (?)";
        $statement = $this->connection->prepare($query);
        $statement->bind_param("i", $korisnik_id);
        $statement->execute();
        $newId = $statement->insert_id;
        $statement->close();
        return $newId;
    }

    public function get_code($korisnik_id, $aktivacijski_kod_id) 
    {
        $query = "SELECT * FROM aktivacijski_kod WHERE korisnik_id=? AND aktivacijski_kod_id=?";
        $statement = $this->connection->prepare($query);
        $statement->bind_param("ii", $korisnik_id, $aktivacijski_kod_id);
        $statement->execute();
        $result = $statement->get_result();
        $statement->close();
        return $result;
    }
}