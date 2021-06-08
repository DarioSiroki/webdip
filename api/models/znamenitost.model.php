<?php 

namespace Znamenitosti;
include_once("config/database.php");

class ZnamenitostModel
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

    public function dohvati_statistiku() 
    {
        $query = 
        "
        SELECT g.naziv as naziv, broj_znamenitosti, g.grad_id
        FROM grad g
        LEFT JOIN (select grad_id, count(*) as broj_znamenitosti
                FROM znamenitost 
               GROUP BY grad_id) z
          ON g.grad_id = z.grad_id
        ";
        $result = $this->connection->query($query);
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

    public function popis_znamenitosti_i_autora() 
    {
        $query = 
        "
        SELECT z.naziv, k.ime as predlozio_ime, k.prezime as predlozio_prezime, k2.ime as odobrio_ime, k2.prezime as odobrio_prezime
        FROM znamenitost z
        LEFT JOIN korisnik k
        ON z.predlozio_korisnik_id=k.korisnik_id
        LEFT JOIN korisnik k2
        ON z.odobrio_korisnik_id=k2.korisnik_id
        ";
        $result = $this->connection->query($query);
        $popis = array();
        if ($result->num_rows > 0) {
            while($red = $result->fetch_assoc()) {
                $popis[] = $red;
            }   
        }
        return $popis;
    }

    public function get_all()
    {
        $query = 
        "
        SELECT * FROM znamenitost
        ";
        $result = $this->connection->query($query);
        $popis = array();
        if ($result->num_rows > 0) {
            while($red = $result->fetch_assoc()) {
                $popis[] = $red;
            }   
        }
        return $popis;
    }

    public function get_ten($grad)
    {
        $query = 
        "
        SELECT z.naziv, z.opis FROM znamenitost z
        LEFT JOIN grad g
        ON g.grad_id=z.grad_id
        WHERE g.grad_id=?
        LIMIT 10
        ";
        $statement = $this->connection->prepare($query);
        $statement->bind_param("i", $grad);
        $statement->execute();
        $result = $statement->get_result();
        $popis = array();
        if ($result->num_rows > 0) {
            while($red = $result->fetch_assoc()) {
                $popis[] = $red;
            }   
        }
        return $popis;
    }
}