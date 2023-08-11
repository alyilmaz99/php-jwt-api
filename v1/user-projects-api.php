<?php
ini_set("display_errors", 1);
require '../vendor/autoload.php';
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');

include_once "../config.php";

include_once "../config/database.php";
include_once "../classes/users.php";

$db = new Database();
$connection = $db->connect();
$users = new Users($connection);

if ($_SERVER['REQUEST_METHOD'] === "GET") {
    $headers = getallheaders();
    $jwt = $headers['jwt'];
    if (!empty($jwt)) {
        try {
            $secret_key = SECRET_KEY;

            $decoded_data = JWT::decode($jwt, new Key($secret_key, "HS512"));
            $users->user_id = $decoded_data->data->id;
            $datas = $users->get_user_projects();
            if ($datas->num_rows > 0) {
                $projects_arr = array();
                while ($row = $datas->fetch_assoc()) {
                    $projects_arr[] = array(
                        "id" => $row['id'],
                        'name' => $row['name'],
                        'description' => $row['description'],
                        'user_id' => $row['user_id'],
                        'status' => $row['status'],
                        'created_at' => $row['created_at'],
                    );
                }
                http_response_code(200);
                echo json_encode(
                    array(
                        'message' => 'success to get all projects',
                        'projects' => $projects_arr,
                    )
                );

            } else {
                http_response_code(404);
                echo json_encode(
                    array(
                        "message" => 'there is no data',
                        'error' => 0,
                    )
                );
            }

        } catch (Exception $err) {
            http_response_code(404);
            echo json_encode(
                array(
                    "message" => 'cant get projects',
                    'error' => $err->getMessage(),
                )
            );
        }
    }

} else {
    http_response_code(503);
    echo json_encode(array(
        'message' => 'Access Denied',
        'status' => 0,
    ));
}
