<?php
session_start();
require_once('./dbc_post.php');

// ログインユーザの存在を確認
if(!isset($_SESSION['login_user'])) {
  header('Location: ./login_form.php');
  exit();
}

// GETメソッドによるidの受け取りを確認
if(isset($_GET['id'])) {
  $id = $_GET['id'];
} else {
  header('Location: ./blog_top.php');
}

// ブログ削除機能
deleteBlog($id);

// 削除後にトップ画面に遷移
header('Location: ./blog_top.php');
