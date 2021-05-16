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
     * Returns all the records matching provided email and password.
     * Function returns non-sensitive data only about users.
     */
    public function login($email, $password_sha256) 
    {
        $query = "SELECT ime, prezime, korisnicko_ime, naziv as uloga FROM korisnik "  .
        "LEFT JOIN uloga on uloga.uloga_id=korisnik.uloga_id " .
        "WHERE email=? " .
        "AND lozinka_sha256=? ";
        $statement = $this->$connection->prepare($query);
        $statement->bind_param("ss", $email, $password_sha256);
        $statement->execute();
        $result = $statement->get_result();
        $statement->close();
        return $result;
    }

    /**
     * @return bool - true if user was successfully inserted, false otherwise
     */
    public function register(
            $ime, $prezime, $korisnicko_ime, $email, $lozinka, $lozinka_sha256, $uloga_naziv="registrirani_korisnik"
        ) 
        {
        $query = "INSERT INTO korisnik (ime, prezime, korisnicko_ime, email, lozinka, lozinka_sha256, uloga_id) " .
                "SELECT ?, ?, ?, ?, ?, ?, uloga.uloga_id " . 
                "FROM uloga " . 
                "WHERE uloga.naziv=?";
        $statement = $this->$connection->prepare($query);
        $statement->bind_param("sssssss", $ime, $prezime, $korisnicko_ime, $email, $lozinka, $lozinka_sha256, $uloga_naziv);
        $statement->execute();
        $result = $statement->get_result();
        $result = $statement->affected_rows == 1;
        $statement->close();
        return $result;
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
}

?>
