<?php

function backend_log_in_ok($username, $password)
{
    require 'model/database.php';
     
          $sql = "SELECT * FROM admin WHERE name = ? ;";
          $stmt = mysqli_stmt_init($db);
          if(!mysqli_stmt_prepare($stmt,$sql)){
                return false;
          }
          else{
             
              mysqli_stmt_bind_param($stmt,"s",$username);
              mysqli_stmt_execute($stmt);
              $result = mysqli_stmt_get_result($stmt);

              if($row = mysqli_fetch_assoc($result)){
                  $pwd_check = password_verify($password, $row['password']);
                  if($pwd_check == false){
                      return false;
                exit();
                  }
                  else if($pwd_check == true){
                      return true;
                  }
              }
              else{
                  return false;
                  
              }
          }
}

$username = "";
$logged_in = false;

if (array_key_exists('username', $_POST) and array_key_exists('password', $_POST)) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $logged_in = backend_log_in_ok($username, $password);
}

$expire = $logged_in ? 0 : time() - 3600;

setcookie('username', $username, $expire, '/');
setcookie('is_admin', 'true', $expire, '/');

header("Content-Type: application/json; charset=UTF-8");
echo json_encode(['status' => ($logged_in ? 'ok' : 'fail')]);
