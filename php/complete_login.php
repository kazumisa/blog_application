<?php
session_start();
require_once('./dbc_account.php');

// POSTで受け取った値をそれぞれ変数に格納
$token = filter_input(INPUT_POST, 'token', FILTER_SANITIZE_SPECIAL_CHARS);
$email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_SPECIAL_CHARS);
$password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_SPECIAL_CHARS);

// emailはセッション変数に格納(前ページで利用するため)
$_SESSION['email'] = $email;

// トークンバリデーション(XSS対策, 二重送信防止対策)
if(!$token || $_SESSION['token'] !== $token) {
  header('Location: ./login_form.php');
  exit();
}
// トークン削除
unset($_SESSION['token']);

// エラーメッセージを格納するための配列を用意
$err = array();
// 未入力時のバリデーション
if(empty($email)) {
  $err['email'] = 'メールアドレスを入力して下さい';
}
if(empty($password)) {
  $err['password'] = 'パスワードを入力して下さい';
}

if(count($err) === 0) {
  // ログイン処理
  $result = login($email, $password);
  if(!$result) {
    header('Location: ./login_form.php');
    exit();
  } else {
    $login_user = $_SESSION['login_user'];
  }
} else {
  $_SESSION['err'] = $err;
  header('Location: ./login_form.php');
  exit();
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>ログイン完了画面</title>
  <link rel="stylesheet" href="../css/complete_login.css">
</head>
<body>
  <div class="window">
    <h2 class="come_back">おかえりなさい、<?php echo $login_user['username'];?> さん！</h2>
  </div>

  <div class="today">
    <p>早速、今日一日の出来事を投稿してみませんか？</p>
  </div>
  <div class="post">
    <a href="./blog_form.php">ブログ投稿フォームへ</a>
  </div>
  <div class="top">
    <a href="blog_top.php">トップページへ</a>
  </div>
</body>
</html>