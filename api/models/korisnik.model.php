<?php 

namespace Znamenitosti;
include_once("config/database.php");

class KorisnikModel
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

    /**
     * Returns all the records matching provided email
     * Function returns non-sensitive data only about users.
     */
    public function login($korisnicko_ime) 
    {
        $query = "SELECT korisnik_id, ime, prezime, korisnicko_ime, naziv as uloga, broj_neuspjesnih_prijava, lozinka_sha256 FROM korisnik "  .
        "LEFT JOIN uloga on uloga.uloga_id=korisnik.uloga_id " .
        "WHERE korisnicko_ime=?";
        $statement = $this->$connection->prepare($query);
        $statement->bind_param("s", $korisnicko_ime);
        $statement->execute();
        $result = $statement->get_result();
        $statement->close();
        return $result;
    }

    /**
     * @return int - new users ID
     */
    public function register(
            $ime, $prezime, $korisnicko_ime, $email, $lozinka, $lozinka_sha256, $uloga_naziv="neregistrirani_korisnik"
        ) 
        {
        $query = "INSERT INTO korisnik (ime, prezime, korisnicko_ime, email, lozinka, lozinka_sha256, uloga_id) " .
                "SELECT ?, ?, ?, ?, ?, ?, uloga.uloga_id " . 
                "FROM uloga " . 
                "WHERE uloga.naziv=?";
        $statement = $this->$connection->prepare($query);
        $statement->bind_param("sssssss", $ime, $prezime, $korisnicko_ime, $email, $lozinka, $lozinka_sha256, $uloga_naziv);
        $statement->execute();
        $newId = $statement->insert_id;
        $statement->close();
        return $newId;
    }

    /**
     * @return bool - user does/doesn't exist
     */
    public function user_exists($korisnicko_ime) 
    {
        $query = "SELECT 1 FROM korisnik WHERE korisnicko_ime=?";
        $statement = $this->$connection->prepare($query);
        $statement->bind_param("s", $korisnicko_ime);
        $statement->execute();
        $result = $statement->get_result();
        $result = $result->num_rows > 0;
        $statement->close();
        return $result;
    }

    public function activate($korisnik_id)
    {
        $query = "UPDATE korisnik SET uloga_id=2 WHERE korisnik_id=?";
        $statement = $this->$connection->prepare($query);
        $statement->bind_param("i", $korisnik_id);
        $statement->execute();
        $statement->close();
    }

    public function inkrementiraj_neuspjesne_prijave($korisnik_id)
    {
        $query = "UPDATE korisnik SET broj_neuspjesnih_prijava=broj_neuspjesnih_prijava+1 WHERE korisnik_id=?";
        $statement = $this->$connection->prepare($query);
        $statement->bind_param("i", $korisnik_id);
        $statement->execute();
        $statement->close();
    }
}

?>
