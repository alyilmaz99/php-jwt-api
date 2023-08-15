<?php
include_once "../config.php";

class Database
{

    private $hostname;
    private $dbname;
    private $username;
    private $password;

    private $conn;

    public function connect()
    {
        $config = parse_ini_file("../config.ini", true);

        $this->hostname = DB_HOST;
        $this->dbname = $config["database"]["database"];
        $this->username = DB_USERNAME;
        $this->password = DB_PASSWORD;

        $this->conn = new mysqli($this->hostname, $this->username, $this->password, $this->dbname);

        if ($this->conn->connect_errno) {
            print_r($this->conn->connect_error);
            exit;

        } else {
            return $this->conn;
        }

    }

}

$db = new Database();
$db->connect();
