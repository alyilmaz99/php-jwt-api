<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
header('Access-Control-Allow-Origin: *');
header("Content-type:application/json charset:UTF-8");
header('Access-Control-Allow-Method: POST');

include_once "../config/database.php";
include_once "../classes/student.php";

$db = new Database();

$connection = $db->connect();

$student = new Student($connection);

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $student->name = $name;
    $student->email = $email;
    $student->mobile = $mobile;

    if ($student->create_data()) {
        echo "Student created";

    } else {
        echo "failed to create student";
    }
} else {

    echo 'Access denied';
}
