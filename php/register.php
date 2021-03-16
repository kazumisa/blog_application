<?php
session_start();
require_once('./dbc_post.php');

// ユーザを識別するため
$userID = $_SESSION['login_user']['id'];

// ファイル関連の取得
$file = $_FILES['img'];
$filename = basename($file['name']);
$tmp_path = $file['tmp_name'];
$file_err = $file['error'];
$filesize = $file['size'];
$upload_dir = '../images/'; 
$save_filename = date('YmdHis').$filename;
$save_path = $upload_dir.$save_filename;

// POSTで受け取った値をそれぞれ変数に格納
$title = filter_input(INPUT_POST, 'title');
$content = filter_input(INPUT_POST, 'content');
$category = filter_input(INPUT_POST, 'category', FILTER_SANITIZE_SPECIAL_CHARS);
$publish_status = filter_input(INPUT_POST, 'publish_status', FILTER_SANITIZE_SPECIAL_CHARS);
$token = filter_input(INPUT_POST, 'token', FILTER_SANITIZE_SPECIAL_CHARS);

// セッション変数に格納(前ページで利用するため)
$_SESSION['title'] = $title;
$_SESSION['content'] = $content;
$_SESSION['category'] = $category;
$_SESSION['publish_status'] = $publish_status;

// トークンバリデーション(XSS対策, 二重送信防止対策)
if(!$token || $_SESSION['token'] !== $token) {
  header('Location: ./blog_top.php');
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
  $err['content'] = 'キャプションは300文字以内で入力して下さい';
}

// カテゴリのバリデーション
if(empty($category)) {
  $err['category'] = 'カテゴリを選択して下さい';
}

// 公開ステータスのバリデーション
if(empty($publish_status)) {
  $err['publish_status'] = '公開ステータスを入力して下さい';
}

// ファイルのバリデーション
$allow_ext = array('jpg', 'jpeg', 'png');
$file_ext  = pathinfo($filename, PATHINFO_EXTENSION);

if(!in_array(strtolower($file_ext), $allow_ext)) {
  $err['file'] = '画像ファイルを選択して下さい';
}
if($filesize > 1048576 || $file_err === 2) {
  $err['file'] = 'ファイルサイズは1MB未満にして下さい';
}

if(count($err) === 0) {
  if(is_uploaded_file($tmp_path)) {
    if(move_uploaded_file($tmp_path, $save_path)) {
      // データベースに保存    
      $result = createData_post($userID, $filename, $save_path, $title, $content, $category, $publish_status);
    } else {
      $err['err_msg'] = 'ファイルが保存できませんでした';
      $_SESSION['err'] = $err;
      header('Location: ./blog_form.php');
      exit();
    }
  } else {
    $err['err_msg'] = 'ファイルが選択されていません';
    $_SESSION['err'] = $err;
    header('Location: ./blog_form.php');
    exit();
  }
} else {
  $_SESSION['err'] = $err;
  header('Location: ./blog_form.php');
  exit();
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../css/register.css">
  <title>投稿完了画面</title>
</head>
<body>
  <h2 class="title">ブログを投稿しました！</h2>
  <p class="content">投稿した内容はブログ一覧から閲覧できます。また編集や削除もできます。</p>
  <div class="go_top"><a href="blog_top.php">ブログ一覧へ</a></div>
</body>
</html>


