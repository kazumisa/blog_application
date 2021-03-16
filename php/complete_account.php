<?php
session_start();
require_once('./dbc_account.php');

// POSTで受け取った値をそれぞれ変数に格納
$token = filter_input(INPUT_POST, 'token', FILTER_SANITIZE_SPECIAL_CHARS);
$email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_SPECIAL_CHARS);
$username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_SPECIAL_CHARS);
$password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_SPECIAL_CHARS);
$password_conf = filter_input(INPUT_POST, 'password_conf', FILTER_SANITIZE_SPECIAL_CHARS);

// emailとusernameはセッション変数に格納(前ページで利用するため)
$_SESSION['email'] = $email;
$_SESSION['username'] = $username;

// ログインユーザの確認
if(isset($_SESSION['login_user'])) {
  header('Location: ./blog_top.php');
}

// トークンバリデーション(XSS対策, 二重送信防止対策)
if(!$token || $token !== $_SESSION['token']) {
  header('Location: ./../html/create_and_login.html');
  exit();
}
// セッション削除
unset($_SESSION['token']);

// エラーメッセージを格納するための配列を用意
$err = array();
// バリデーション(メールアドレス)
if(empty($email)) {
  $err['email'] = 'メールアドレスを入力して下さい';
} 
$email_regexp = '/^([a-zA-Z0-9])+([a-zA-Z0-9\._-])*@([a-zA-Z0-9_-])+([a-zA-Z0-9\._-]+)+$/';
if(!preg_match($email_regexp, $email) && $email) {
  $err['email'] = '正しいメールアドレスを入力して下さい';
}
// データベース上に同じemailが存在するか確認
$all_email = checkEmail(); 
foreach($all_email as $each_email) {
  if($each_email['email'] === $email) {
    $err['email'] = '既に同じメールアドレスが登録されています';
  }
}

// バリデーション(ユーザ名)
if(empty($username)) {
  $err['username'] = 'ユーザ名を入力して下さい';
}
if(mb_strlen($username) > 24) {
  $err['username'] = 'ユーザ名は24文字以内で登録して下さい';
}

// バリデーション(パスワード)
if(empty($password)) {
  $err['password'] = 'パスワードを入力して下さい';
}
$pass_regexp = '/\A(?=.*?[a-z])(?=.*?[A-Z])(?=.*?\d)[a-zA-Z\d]{8,100}+\z/';
if(!preg_match($pass_regexp, $password) && $password) {
  $err['password'] = '半角英小文字大文字数字をそれぞれ1種類以上含む8文字以上100文字以下で入力して下さい';
}

// バリデーション(確認用パスワード)
if(empty($password_conf)){
  $err['password_conf'] = '確認用パスワードを入力して下さい';
}
if($password !== $password_conf) {
  $err['password_conf'] = '確認用パスワードが異なります';
}

if(count($err) === 0) {
  // アカウント作成
  $result = createAccount($email, $username, $password);
  if($result) {
    // アカウント作成後直ぐにログイン状態にする
    $_SESSION['login_user'] = getUserByEmail($email);
    $login_user = $_SESSION['login_user'];
  } else {
    echo 'アカウントの作成に失敗しました。';
  }
} else {
  $_SESSION['msg'] = $err;
  header('Location: ./create_account.php');
  exit();
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>アカウント作成完了画面</title>
  <link rel="stylesheet" href="../css/complete_account.css">
</head>
<body>
  <h2 class="top"><?php echo h($login_user['username']) ;?>さん、<span class="photoblog">photoblog</span> へようこそ！</h2>
  <p class="content">一日の出来事を写真と共にシェアしよう。またユーザ名はいつでも変更できます。</p>

  <div class="next"><a href="blog_top.php">次へ</a></div>
</body>
</html>

<?php 
