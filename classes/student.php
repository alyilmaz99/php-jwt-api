<?php

class Student
{

    public $name;
    public $email;
    public $mobile;

    private $conn;

    public function __construct($db)
    {

        $this->conn = $db;

    }
    public function create_data()
    {
        $query = "INSERT INTO table_students SET name=?,email=?,mobile=?";

        $obj = $this->conn->prepare($query);

        $this->name = htmlspecialchars(strip_tags($this->name));
        $this->email = htmlspecialchars(strip_tags($this->email));
        $this->mobile = htmlspecialchars(strip_tags($this->mobile));

        $obj->bind_param("sss", $this->name, $this->email, $this->mobile);
        if ($obj->execute()) {

            return true;
        }
        return false;
    }

}
