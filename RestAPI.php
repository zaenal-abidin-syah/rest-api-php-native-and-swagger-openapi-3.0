<?php

class RestAPI
{
  private $userJson = "users.json";
  private $users = array(
    ['id' => 1, "username" => "user1", "email" => "user1@email.com", "phone_number" => "083275548455"],
    ['id' => 2, "username" => "user2", "email" => "user2@email.com", "phone_number" => "083475348435"],
    ['id' => 3, "username" => "user3", "email" => "user3@email.com", "phone_number" => "081243348435"],
    ['id' => 4, "username" => "user4", "email" => "user4@email.com", "phone_number" => "081985548435"],
    ['id' => 5, "username" => "user5", "email" => "user5@email.com", "phone_number" => "081985549865"],
    ['id' => 6, "username" => "user6", "email" => "user6@email.com", "phone_number" => "081985549865"],
    ['id' => 7, "username" => "user7", "email" => "user7@email.com", "phone_number" => "081243548455"],
    ['id' => 8, "username" => "user8", "email" => "user8@email.com", "phone_number" => "081275548265"],
  );
  function __construct()
  {
    header("Content-Type: application/json");
    if (!file_exists($this->userJson)) {
      file_put_contents($this->userJson, json_encode($this->users));
    }
    $this->users = json_decode(file_get_contents($this->userJson), true);
  }
  public function GET($params)
  {
    if ($params == null) {
      // get https://url.api
      http_response_code(200);
      return json_encode($this->users);
    }
    // get https://url.api?id=9
    foreach ($this->users as $user) {
      if ($user['id'] == strval($params['id'])) {
        http_response_code(200);
        return json_encode($user);
      }
    }
    http_response_code(404);
    return json_encode([
      "error" => "user not found"
    ]);
  }
  public function POST($data)
  {
    // get https://url.api
    // body { user }
    $data = json_decode($data, true);
    $lastUser = end($this->users);
    $newId = isset($lastUser['id']) ? $lastUser['id'] + 1 : 1;
    $data['id'] = $newId;

    $this->users[] = $data;
    file_put_contents($this->userJson, json_encode($this->users));
    http_response_code(201);
    return json_encode(
      [
        "data" => $data,
        "message" => "add user successfully!"
      ]
    );
  }
}
