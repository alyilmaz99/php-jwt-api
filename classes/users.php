<?php
ini_set('display_errors', 1);
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

    public function check_login()
    {
        $sql = "SELECT * from table_users WHERE email=? ";
        $query = $this->conn->prepare($sql);
        $query->bind_param('s', $this->email);
        if ($query->execute()) {
            $data = $query->get_result()->fetch_assoc();
            return $data;
        } else {
            return array();
        }
    }
    public function create_project()
    {
        $sql = "INSERT INTO table_projects SET user_id=?, name=?, description=?, status=?";
        $query = $this->conn->prepare($sql);
        $project_name = htmlspecialchars($this->project_name);
        $description = htmlspecialchars($this->description);
        $status = htmlspecialchars($this->status);

        $query->bind_param("isss", $this->user_id, $project_name, $description, $status);

        if ($query->execute()) {
            return true;
        } else {
            return false;
        }

    }
    public function list_all_projects()
    {
        $sql = "SELECT * FROM table_projects  ORDER BY id DESC";
        $query = $this->conn->prepare($sql);
        if ($query->execute()) {
            $data = $query->get_result();
            return $data;
        } else {
            return array();
        }
    }
    public function get_user_projects()
    {
        $sql = "SELECT * FROM table_projects WHERE user_id=? ORDER BY id DESC";
        $query = $this->conn->prepare($sql);
        $query->bind_param("i", $this->user_id);
        if ($query->execute()) {
            $data = $query->get_result();
            return $data;
        } else {
            return array();
        }
    }
}
