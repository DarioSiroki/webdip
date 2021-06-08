<?php 

namespace Znamenitosti;
include_once("config/database.php");

class ModeratorModel
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

    public function dohvati_moderatore() 
    {
        $query = "SELECT * FROM moderator";
        $result = $this->connection->query($query);
        $moderatori = array();
        if ($result->num_rows > 0) {
            while($red = $result->fetch_assoc()) {
                $moderatori[] = $red;
            }   
        }
        return $moderatori;
    }

    public function dodaj_moderatora($grad_id, $korisnik_id)
    {
        $query = "INSERT INTO moderator (grad_id, korisnik_id) VALUES (?, ?)";
        $statement = $this->connection->prepare($query);
        $statement->bind_param("ii", $grad_id, $korisnik_id);
        $statement->execute();
        $newId = $statement->insert_id;
        $statement->close();
        return $newId;
    }

    public function obrisi_moderatora($grad_id, $korisnik_id)
    {
        $query = "DELETE FROM moderator WHERE grad_id=? AND korisnik_id =?";
        $statement = $this->connection->prepare($query);
        $statement->bind_param("ii", $grad_id, $korisnik_id);
        $statement->execute();
        $statement->close();
    }
}