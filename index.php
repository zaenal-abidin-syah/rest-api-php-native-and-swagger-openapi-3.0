<?php

// Atur header
header("Content-Type: application/json");
$userJson = "users.json";
$users = array(
  ['id' => 1, "username" => "user1", "email" => "user1@email.com", "phone_number" => "083275548455"],
  ['id' => 2, "username" => "user2", "email" => "user2@email.com", "phone_number" => "083475348435"],
  ['id' => 3, "username" => "user3", "email" => "user3@email.com", "phone_number" => "081243348435"],
  ['id' => 4, "username" => "user4", "email" => "user4@email.com", "phone_number" => "081985548435"],
  ['id' => 5, "username" => "user5", "email" => "user5@email.com", "phone_number" => "081985549865"],
  ['id' => 6, "username" => "user6", "email" => "user6@email.com", "phone_number" => "081985549865"],
  ['id' => 7, "username" => "user7", "email" => "user7@email.com", "phone_number" => "081243548455"],
  ['id' => 8, "username" => "user8", "email" => "user8@email.com", "phone_number" => "081275548265"],
);

if (!file_exists($userJson)) {
  file_put_contents($userJson, json_encode($users));
}
$users = json_decode(file_get_contents($userJson), true);


// tanpa menggunakan class
$requestUri = trim($_SERVER['REQUEST_URI'], '/');
$segments = explode('/', $requestUri);

// Cek apakah URL diawali dengan "users"
if (isset($segments[0]) && $segments[0] === 'users') {
  require 'RestAPI.php';
  $restApi = new RestAPI();

  // Mengambil ID jika tersedia (misalnya, /users/1)
  $id = isset($segments[1]) ? $segments[1] : null;

  if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if ($id !== null) {
      $notEmpty = false;
      foreach ($users as $user) {
        if ($user['id'] == strval($id)) {
          $notEmpty = true;
          http_response_code(200);
          echo json_encode($user);
        }
      }
      if (!$notEmpty) {
        http_response_code(404);
        echo json_encode([
          "message" => "user not found"
        ]);
      }
    } else {
      http_response_code(200);
      echo json_encode($users);
    }
  } else if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = file_get_contents("php://input");
    $data = json_decode($data, true);
    if (isset($data["username"]) and isset($data["email"]) and isset($data["phone_number"])) {
      http_response_code(400);
      echo json_encode(
        [
          "message" => "please, fill username, email and phone_number!"
        ]
      );
    }
    $lastUser = end($users);
    $newId = isset($lastUser['id']) ? $lastUser['id'] + 1 : 1;
    $data['id'] = $newId;


    $users[] = $data;
    file_put_contents($userJson, json_encode($users));
    http_response_code(201);
    echo json_encode(
      [
        "message" => "add user successfully!"
      ]
    );
  } else {
    http_response_code(405);
    $error = array('error' => 'Method not allowed');
    echo json_encode($error);
  }
} else if (isset($segments[0]) && $segments[0] === '') {
  header("Content-Type: text/html");
?>
  <!DOCTYPE html>
  <html lang="en">

  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="description" content="SwaggerUI" />
    <title>SwaggerUI</title>
    <link rel="stylesheet" href="https://unpkg.com/swagger-ui-dist@5.11.0/swagger-ui.css" />
  </head>

  <body>
    <div id="swagger-ui"></div>
    <script src="https://unpkg.com/swagger-ui-dist@5.11.0/swagger-ui-bundle.js" crossorigin></script>
    <script>
      window.onload = () => {
        window.ui = SwaggerUIBundle({
          url: 'users-openapi.json',
          dom_id: '#swagger-ui',
        });
      };
    </script>
  </body>

  </html>
<?php
}
?>


<?php
// untuk yang menggunakan Class
// $requestUri = trim($_SERVER['REQUEST_URI'], '/');
// $segments = explode('/', $requestUri);

// // Cek apakah URL diawali dengan "users"
// if (isset($segments[0]) && $segments[0] === 'users') {
//   require 'RestAPI.php';
//   $restApi = new RestAPI();

//   // Mengambil ID jika tersedia (misalnya, /users/1)
//   $id = isset($segments[1]) ? $segments[1] : null;

//   if ($_SERVER['REQUEST_METHOD'] === 'GET') {
//     if ($id !== null) {
//       $params = ["id" => $id];
//       echo $restApi->GET($params);
//     } else {
//       echo $restApi->GET(null);
//     }
//   } else if ($_SERVER['REQUEST_METHOD'] === 'POST') {
//     $data = file_get_contents("php://input");
//     echo $restApi->POST($data);
//   } else {
//     http_response_code(405);
//     $error = array('error' => 'Method not allowed');
//     echo json_encode($error);
//   }
// } else {
//   // Untuk request URL yang tidak sesuai, misalnya mengembalikan 404
//   http_response_code(404);
//   echo json_encode(['error' => 'Not Found']);
// }
?>
<?php

?>