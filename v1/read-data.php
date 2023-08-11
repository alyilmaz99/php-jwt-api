<?php
ini_set('display_errors', 1);
require "../vendor/autoload.php";
use Firebase\JWT\Key;
use \Firebase\JWT\JWT;
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Content-type: application/json; charst=UTF-8');

include_once "../config/database.php";
include_once "../classes/users.php";

$db = new Database();
$connection = $db->connect();
$users = new Users($connection);

if ($_SERVER['REQUEST_METHOD'] === "POST") {

    $data = json_decode(file_get_contents("php://input"));

    if (!empty($data->jwt)) {
        try {
            $secret_key = "G5NzbPBrb2B5Db+U";

            $decoded_data = JWT::decode($data->jwt, new Key($secret_key, "HS512"));
            http_response_code(200);
            $user_id = $decoded_data->data->id;
            echo json_encode(
                array([
                    'user_id' => $user_id,
                    'message' => "jwt ok",
                    'status' => 1,
                    'user_data' => $decoded_data,
                ])
            );
        } catch (Exception $er) {
            http_response_code(404);
            echo json_encode(
                array([
                    'message' => $er->getMessage(),
                    'status' => 0,

                ])
            );
        }

    }
}
