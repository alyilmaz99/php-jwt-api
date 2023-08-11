<?php
ini_set('display_errors', 1);
require '../vendor/autoload.php';
use \Firebase\JWT\JWT;

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
    if (!empty($data->email) && !empty($data->password)) {
        $users->email = $data->email;

        $userData = $users->check_login();
        if (!empty($userData)) {
            $name = $userData['name'];
            $email = $userData['email'];
            $password = $userData['password'];

            $iss = "localhost";
            $iat = time();
            $nbf = $iat + 5;
            $exp = $iat + 60;
            $aud = "myusers";
            $user_arr_data = array("id" => $userData['id'], "name" => $userData['name'], "email" => $userData['email']);

            $secret_key = SECRET_KEY;

            if (password_verify($data->password, $password)) {
                $payload_info = array(
                    "iss" => $iss,
                    "iat" => $iat,
                    "nbf" => $nbf,
                    "exp" => $exp,
                    "aud" => $aud,
                    "data" => $user_arr_data,
                );

                $jwt = JWT::encode($payload_info, $secret_key, 'HS512');

                http_response_code(200);
                echo json_encode(
                    array(
                        [
                            'message' => 'loged in successfully',
                            'jwt' => $jwt,
                            'status' => 1,
                        ],
                    )
                );
            } else {
                http_response_code(404);
                echo json_encode(
                    array(
                        [
                            'message' => 'wrong pass',
                            'status' => 1,
                        ],
                    )
                );
            }
        } else {
            http_response_code(404);
            echo json_encode(
                array(
                    [
                        'message' => 'invalid cred',
                        'status' => 0,
                    ],
                )
            );
        }

    } else {
        http_response_code(404);
        echo json_encode(
            array(
                [
                    'message' => 'email and password pls',
                    'status' => 0,
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
                'status' => 0,
            ],
        )
    );

}