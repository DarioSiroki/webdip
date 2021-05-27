<?php 

namespace Znamenitosti;
include_once("config/database.php");

class NeregistriraniPrijedlogModel
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

    public function dodaj(
            $grad_id, $naziv, $opis, $ime, $prezime, $nadimak
        ) 
        {
        $query = "INSERT INTO neregistrirani_zahtjev (grad_id, naziv, opis, ime, prezime, nadimak) " .
                "VALUES (?, ?, ?, ?, ?, ?)";
        $statement = $this->$connection->prepare($query);
        $statement->bind_param("isssss", $grad_id, $naziv, $opis, $ime, $prezime, $nadimak);
        $statement->execute();
        $newId = $statement->insert_id;
        $statement->close();
        return $newId;
    }
}

?>
