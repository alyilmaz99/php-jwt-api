<?php
ini_set("display_errors", 1);
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");

header("Content-type: application/json; charset=UTF-8");

include_once "../config/database.php";
include_once "../classes/users.php";

$db = new Database();
$connection = $db->connect();
$users = new Users($connection);

if ($_SERVER['REQUEST_METHOD'] === "POST") {
    $data = json_decode(file_get_contents("php://input"));

    if (!empty($data->name) && !empty($data->email) && !empty($data->password)) {

        $users->name = $data->name;
        $users->email = $data->email;
        $users->password = password_hash($data->password, PASSWORD_DEFAULT);

        $email_checker = $users->check_email();
        if (!empty($email_checker)) {
            http_response_code(404);
            echo json_encode(array(
                [
                    'message' => 'email has used ',
                    'status' => 0,

                ],
            ));
        } else {
            if ($users->create_user()) {
                http_response_code(200);
                echo json_encode(array(
                    [
                        'message' => 'user created',
                        'status' => 1,

                    ],
                ));
            } else {
                http_response_code(500);
                echo json_encode(array(
                    [
                        'message' => 'failed create',
                        'status' => 0,

                    ],
                ));
            }
        }

    } else {
        http_response_code(500);
        echo json_encode(array(
            [
                'message' => 'All field required',
                'status' => 0,
            ],
        ));
    }
} else {

    http_response_code(503);
    echo json_encode(array(
        [
            'message' => 'Access Denied',
            'status' => 0,
        ],
    ));
}
