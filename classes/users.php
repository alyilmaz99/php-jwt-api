<?php

class Users
{

    public $name;
    public $email;
    public $password;
    public $user_id;
    public $project_name;
    public $description;
    public $status;

    private $conn;

    public function __construct($db)
    {

        $this->conn = $db;

    }

    public function create_user()
    {
        $sql = "INSERT INTO table_users (name, email, password) VALUES (?, ?, ?)";
        $query = $this->conn->prepare($sql);

        if ($query) {
            $query->bind_param('sss', $this->name, $this->email, $this->password);

            if ($query->execute()) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }

    }
    public function check_email()
    {
        $sql = "SELECT * from table_users WHERE email=?";
        $query = $this->conn->prepare($sql);
        $query->bind_param('s', $this->email);
        if ($query->execute()) {
            $data = $query->get_result()->fetch_assoc();
            return $data;
        } else {
            return array();
        }

    }
}
