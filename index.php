<?php

// Atur header
header("Content-Type: application/json");


// bisa menggunakan database
// $users = array(
//   ['id' => 1, "username" => "user1", "email" => "user1@email.com", "phone_number" => "083275548455"],
//   ['id' => 2, "username" => "user2", "email" => "user2@email.com", "phone_number" => "083475348435"],
//   ['id' => 3, "username" => "user3", "email" => "user3@email.com", "phone_number" => "081243348435"],
//   ['id' => 4, "username" => "user4", "email" => "user4@email.com", "phone_number" => "081985548435"],
//   ['id' => 5, "username" => "user5", "email" => "user5@email.com", "phone_number" => "081985549865"],
//   ['id' => 6, "username" => "user6", "email" => "user6@email.com", "phone_number" => "081985549865"],
//   ['id' => 7, "username" => "user7", "email" => "user7@email.com", "phone_number" => "081243548455"],
//   ['id' => 8, "username" => "user8", "email" => "user8@email.com", "phone_number" => "081275548265"],
// );



require "RestAPI.php";

$restApi = new RestAPI();
// Menggunakan Method GET
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
  // Return the list of users as JSON
  if (isset($_GET['id'])) {
    $params = ["id" => $_GET["id"]];
    echo $restApi->GET($params);
  } else {
    echo $restApi->GET(null);
  }
} else if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $data = file_get_contents("php://input");
  echo $restApi->POST($data);
} else {
  http_response_code(405);
  $error = array('error' => 'Method not allowed');
  echo json_encode($error);
}
