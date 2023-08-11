<?php
ini_set("display_errors", 1);
require '../vendor/autoload.php';
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Content-type: application/json; charst=UTF-8');
include_once "../config.php";

include_once "../config/database.php";
include_once "../classes/users.php";

$db = new Database();
$connection = $db->connect();
$users = new Users($connection);

if ($_SERVER['REQUEST_METHOD'] === "POST") {
    $data = json_decode(file_get_contents("php://input"));
    $headers = getallheaders();
    if (!empty($data->name) && !empty($data->description) && !empty($data->status)) {
        try {
            $jwt = $headers['jwt'];
            $secret_key = SECRET_KEY;

            $decoded_data = JWT::decode($jwt, new Key($secret_key, "HS512"));

            $users->user_id = $decoded_data->data->id;
            $users->project_name = $data->name;
            $users->status = $data->status;
            $users->description = $data->description;

            if ($users->create_project()) {
                http_response_code(200);
                echo json_encode(
                    array(
                        [
                            'message' => "Product Created,",
                            "status" => 1,
                        ],
                    )
                );
            } else {

                http_response_code(500);
                echo json_encode(
                    array(
                        [
                            'message' => "Cant create user,",
                            "status" => 0,
                        ],
                    )
                );}

        } catch (Exception $err) {
            http_response_code(500);
            echo json_encode(
                array(
                    [
                        'message' => $err->getMessage(),
                        "status" => 0,
                    ],
                )
            );
        }
    } else {
        http_response_code(404);
        echo json_encode(
            array(
                [
                    'message' => 'All field required',
                    "status" => 0,
                ],
            )
        );

    }

} else {
    http_response_code(503);
    echo json_encode(
        array(
            [
                'message' => 'Access Denied',
                "status" => 0,
            ],
        )
    );
}
