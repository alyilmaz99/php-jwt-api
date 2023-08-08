<?php
ini_set("display_errors", 1);
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');

include_once "../config/database.php";
include_once "../classes/student.php";

$db = new Database();

$connection = $db->connect();

$student = new Student($connection);

if ($_SERVER["REQUEST_METHOD"] === "GET") {

    $data = $student->get_all_data();

    if ($data->num_rows > 0) {
        $students["records"] = array();
        while ($row = $data->fetch_assoc()) {
            array_push($students["records"], array(
                "id" => $row["id"],
                "name" => $row["name"],
                "mobile" => $row["mobile"],
                "email" => $row["email"],
                "status" => $row["status"],
                "create_at" => date("Y-m-d", strtotime($row["create_at"])),

            ));
        }
        http_response_code(200);
        echo json_encode(
            array(
                "status: " => 1,
                "data: " => $students["records"],
            )
        );
    }

} else {
    http_response_code(503);
    echo json_encode([
        'status' => 0,
        'message' => 'Access Denied',
    ]);
}
