<?php
session_start();
require_once('./dbc_account.php');

$token = filter_input(INPUT_POST, 'token', FILTER_SANITIZE_SPECIAL_CHARS);

// ログインユーザの存在を確認
if(!isset($_SESSION['login_user'])) {
  header('Location: ./login_form.php');
  exit();
}

// トークンの確認(XSS対策,二重送信防止対策)
if(!$token || $_SESSION['token'] !== $token) {
  header('Location: ./blog_top.php');
  exit();
}
// トークン削除
unset($_SESSION['token']);

// ログアウト
logout();

// ログアウト後の挙動(ログイン画面に戻す)
header('Location: ../html/create_and_login.html');
