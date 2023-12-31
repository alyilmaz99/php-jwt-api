<?php

class Student
{

    public $name;
    public $email;
    public $mobile;
    public $id;
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

    public function get_all_data()
    {
        $sql = 'SELECT * FROM table_students';
        $query = $this->conn->prepare($sql);
        $query->execute();
        return $query->get_result();

    }

    public function get_single_student()
    {
        $sql = "SELECT * from table_students WHERE id = ?";
        $query = $this->conn->prepare($sql);

        $query->bind_param("i", $this->id);

        $query->execute();
        $data = $query->get_result()->fetch_assoc();

        $query->close();
        return $data;
    }
    public function update_student()
    {
        $sql = "UPDATE table_students SET name=?, email=?, mobile=? WHERE id=?";
        $query = $this->conn->prepare($sql);
        $this->name = htmlspecialchars(strip_tags($this->name));
        $this->email = htmlspecialchars(strip_tags($this->email));
        $this->mobile = htmlspecialchars(strip_tags($this->mobile));
        $this->id = htmlspecialchars(strip_tags($this->id));

        $query->bind_param('sssi', $this->name, $this->email, $this->mobile, $this->id);

        if ($query->execute()) {
            return true;
        } else {
            return false;
        }
    }
    public function delete_student()
    {
        $sql = "DELETE from table_students WHERE id=?";
        $query = $this->conn->prepare($sql);
        $this->id = htmlspecialchars(strip_tags($this->id));
        $query->bind_param('i', $this->id);

        if ($query->execute()) {
            return true;
        } else {
            return false;
        }
    }

}
