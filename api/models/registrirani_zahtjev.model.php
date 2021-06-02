<?php 

namespace Znamenitosti;
include_once("config/database.php");

class RegistriraniZahtjevModel
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
        $gradId, $naziv, $opis, $godina, $korisnik_id, $status = "na čekanju"
        ) 
        {
        $query = "INSERT INTO registrirani_zahtjev (grad_id, naziv, opis, godina, korisnik_id, status) " .
                "VALUES (?, ?, ?, ?, ?, ?)";
        $statement = $this->$connection->prepare($query);
        $statement->bind_param("issiis", $gradId, $naziv, $opis, $godina, $korisnik_id, $status);
        $statement->execute();
        $newId = $statement->insert_id;
        $statement->close();
        return $newId;
    }

    public function get()
    {
        $query = "SELECT * from registrirani_zahtjev";
        $result = $this->$connection->query($query);
        $zahtjevi = array();
        if ($result->num_rows > 0) {
            while($red = $result->fetch_assoc()) {
                $zahtjevi[] = $red;
            }   
        }
        return $zahtjevi;
    }
}

?>
