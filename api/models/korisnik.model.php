<?php 

namespace Znamenitosti;
include_once("config/database.php");

class Korisnik 
{
    private $table_name = "korisnik";
    private $connection;

    public function __construct() 
    {
		$this->$connection = Database::start_connection();
	}

    public function __destruct() 
    {
        $this->$connection->close();
    }

    public function login($email, $password_sha256) 
    {
        $query = "SELECT * FROM korisnik WHERE email=? AND lozinka_sha256=?";
        $statement = $this->$connection->prepare($query);
        $statement->bind_param("ss", $email, $password_sha256);
        $statement->execute();
        $result = $statement->get_result();
        $statement->close();
        return $result;
    }
}

?>
