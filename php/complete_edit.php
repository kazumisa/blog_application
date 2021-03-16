<?php 
session_start();
require_once('./dbc_post.php');

// ログインユーザの存在を確認
if(!isset($_SESSION['login_user'])) {
  header('Location: ./login_form.php');
  exit();
}

$id = filter_input(INPUT_POST,'id', FILTER_SANITIZE_SPECIAL_CHARS);
$title = filter_input(INPUT_POST, 'title', FILTER_SANITIZE_SPECIAL_CHARS);
$content = filter_input(INPUT_POST, 'content', FILTER_SANITIZE_SPECIAL_CHARS);
$category = filter_input(INPUT_POST, 'category', FILTER_SANITIZE_SPECIAL_CHARS);
$publish_status = filter_input(INPUT_POST, 'publish_status', FILTER_SANITIZE_SPECIAL_CHARS);
$token = filter_input(INPUT_POST, 'token', FILTER_SANITIZE_SPECIAL_CHARS);

$_SESSION['title'] = $title;
$_SESSION['content'] = $content;


// トークンバリデーション(XSS対策, 二重送信防止対策)
if(!$token || $_SESSION['token'] !== $token) {
  header('Location: ./login_form.php');
  exit();
}
// トークン削除
unset($_SESSION['token']);

// エラーメッセージを格納するための配列を用意
$err = array();

// タイトルのバリデーション
if(empty($title)) {
  $err['title'] = 'タイトルを入力して下さい';
}
if(mb_strlen($title) > 30) {
  $err['title'] = 'タイトルは30文字以内で入力して下さい';
}

// キャプションのバリデーション
if(mb_strlen($content) > 300) {
  $err['caption'] = 'キャプションは300文字以内で入力して下さい';
}

// カテゴリのバリデーション
if(empty($category)) {
  $err['category'] = 'カテゴリを選択して下さい';
}

// 公開ステータスのバリデーション
if(empty($publish_status)) {
  $err['publish_status'] = '公開ステータスを入力して下さい';
}


if(count($err) === 0) {
  // 編集完了処理
  $upDate = dataUpDate($id, $title, $content, $category, $publish_status);
  header('Location: ./blog_top.php');
} else {
  // エラーが存在する時の処理
  $_SESSION['err_post'] = $err;
  header("Location: ./edit.php?id=$id");
  exit();
}

?>