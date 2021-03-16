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

// ログインユーザの識別
$userID = $_SESSION['login_user']['id'];

// データベースからidに該当するデータを取得
$userData = getDataById($id);

// GETメソッドによるバリデーション
if(empty($_GET['id'])) {
  header('Location: ./blog_top.php');
  exit();
}

if(!$userData) {
  header('Location: ./blog_top.php');
  exit();
}

if($userID !== $userData['userID']) {
  header('Location: ./blog_top.php');
  exit();
}

// complete_edit.phpからheader関数によるページ遷移の際に受け取ったセッション変数をそれぞれ変数に格納
if(isset($_SESSION['token'])) {

  unset($_SESSION['token']);
}

if(isset($_SESSION['err_post'])) {
  $err = $_SESSION['err_post'];

  unset($_SESSION['err_post']);
}

if(isset($_SESSION['title'])) {
  $title = $_SESSION['title'];

  unset($_SESSION['title']);
}

if(isset($_SESSION['content'])) {
  $content = $_SESSION['content'];

  unset($_SESSION['content']);
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>ブログ編集ページ</title>
  <link rel="stylesheet" href="../css/edit.css">
</head>
<body>
  <h2>ブログ編集</h2>
  <form enctype="multipart/form-data" action="./complete_edit.php" method="POST">
    <input type="hidden" name="id" value="<?php echo $userData['id']?>">

    <div class="img">
      <img src="<?php echo $userData['file_path']?>">
    </div>
    
    <div class="title">
      <input type="text" name="title" id="title" autocomplete="off" placeholder="タイトル(30文字以内)"
      <?php if(!isset($title)) :?>
      value="<?php echo $userData['title'];?>"
      <?php else :?>
      value="<?php echo $title;?>"
      <?php endif ;?>>
    </div>

    <div class="msg">
      <?php if(isset($err['title'])) :?>
        <p><?php echo '※'.$err['title'] ;?></p>
      <?php endif ;?>
    </div>

    <div class="content">
      <textarea name="content" id="content" cols="30" rows="10" placeholder="キャプションは300字以内で入力して下さい"><?php echo $userData['content'] ;?></textarea>
    </div>
    <div class="msg">
      <?php if(isset($err['caption'])) :?>
        <p><?php echo '※'.$err['caption'] ;?></p>
      <?php endif ;?>
    </div>

    <div class="category">
      <label for="category">カテゴリ</label>
      <select name="category" id="category">
        <option value="1" <?php if($userData['category'] == 1) echo "selected" ;?>>日常</option>
        <option value="2" <?php if($userData['category'] == 2) echo "selected" ;?>>仕事</option>
        <option value="3" <?php if($userData['category'] == 3) echo "selected" ;?>>学校</option>
        <option value="4" <?php if($userData['category'] == 4) echo "selected" ;?>>旅行</option>
        <option value="5" <?php if($userData['category'] == 5) echo "selected" ;?>>その他</option>
      </select>
    </div>
    <div class="msg">
      <?php if(isset($err['category'])) :?>
        <p><?php echo '※'.$err['category'] ;?></p>
      <?php endif ;?>
    </div>

    <div class="publish_status">
      <label for="publish_status">公開ステータス</label>
        <input type="radio" name="publish_status" id="publish_status" value="1" <?php if($userData['publish_status'] == 1) echo "checked" ;?>>公開
        <input type="radio" name="publish_status" id="publish_status" value="2" <?php if($userData['publish_status'] == 2) echo "checked" ;?>>非公開
    </div>
    <div class="msg">
      <?php if(isset($err['publish_status'] )) :?>
        <p><?php echo '※'.$err['publish_status'] ;?></p>
      <?php endif ;?>
    </div>

    <input type="hidden" name="token" 
    <?php if(!isset($_SESSION['token'])) :?>
    value="<?php echo h(setToken()) ;?>"
    <?php endif ;?>> 
    
    <div class="submit"><input type="submit"  id="submit" value="投稿"></div>
    <div class="back"><a href="../php/blog_top.php">戻る</a></div>
  </form>
</body>
</html>