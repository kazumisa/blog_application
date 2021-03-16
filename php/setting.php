<?php 
session_start(); 

// ログインユーザの存在を確認
if(!isset($_SESSION['login_user'])) {
  header('Location: ./login_form.php');
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>設定画面</title>
</head>
<body>
</body>
</html>