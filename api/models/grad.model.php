<?php 

namespace Znamenitosti;
include_once("config/database.php");

class GradModel
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

    public function dohvati_gradove() 
    {
        $query = "SELECT * FROM grad";
        $result = $this->connection->query($query);
        $gradovi = array();
        if ($result->num_rows > 0) {
            while($red = $result->fetch_assoc()) {
                $gradovi[] = $red;
            }   
        }
        return $gradovi;
    }

    public function uredi_grad($grad_id, $naziv, $opis, $postanski_broj, $povrsina, $broj_stanovnika)
    {
        $query = "UPDATE grad SET naziv=?, opis=?, postanski_broj=?, povrsina=?, broj_stanovnika=? WHERE grad_id=?";
        $statement = $this->connection->prepare($query);
        $statement->bind_param("sssdii", $naziv, $opis, $postanski_broj, $povrsina, $broj_stanovnika, $grad_id);
        $statement->execute();
        $statement->close();
    }

    public function dodaj_grad($naziv, $opis, $postanski_broj, $povrsina, $broj_stanovnika)
    {
        $query = "INSERT INTO grad (naziv, opis, postanski_broj, povrsina, broj_stanovnika) VALUES (?,?,?,?,?)";
        $statement = $this->connection->prepare($query);
        $statement->bind_param("sssdi", $naziv, $opis, $postanski_broj, $povrsina, $broj_stanovnika);
        $statement->execute();
        $newId = $statement->insert_id;
        $statement->close();
        return $newId;
    }
}