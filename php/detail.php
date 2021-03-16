<?php
session_start();
require_once('./dbc_post.php');


// ログインユーザの存在の確認
if(!isset($_SESSION['login_user'])) {
  header('Location: ./login_form.php');
  exit();
}

// ログインユーザの識別
$userID = $_SESSION['login_user']['id'];

// GETメソッドによるidの受け取りを確認
if(isset($_GET['id'])) {
  $id = $_GET['id'];
} else {
  header('Location: ./blog_top.php');
  exit();
}

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

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>詳細ページ</title>
  <link rel="stylesheet" href="../css/detail.css">
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
</head>
<body>
  <section class="sp_detail">
    <header>
      <div class="arrow_back_ios"><a href="./blog_top.php"><span class="material-icons" id="arrow_back_ios">arrow_back_ios</span></a></div>
      <div class="header_middle">
        <p><?php echo $_SESSION['login_user']['username'] ;?></p>
        <h3>投稿</h3>
      </div>
      <div class="more_horiz"><span class="material-icons" id="more_horiz">more_horiz</span></div>
    </header>
    <img src="<?php echo h($userData['file_path']) ;?>">
    <main>
      <?php echo nl2br(h($userData['content'])) ;?>
    </main>
    <footer>
      <div class="time"><?php echo $userData['insert_time'];?></div>
    </footer>
  </section>

  <div class="mask">
  </div>

  <div class="window">
    <div class="line"><span class="material-icons" id="line">horizontal_rule</span></div>
    <div class="edit"><a href="./edit.php?id=<?php echo $userData['id']?>">編集</a></div>
    <div class="delete"><a href="./delete.php?id=<?php echo $userData['id']?>" id="delete">削除</a></div>
  </div>

  
  <section class="pc_detail">
    <h2 class="detail">ブログ詳細</h2>
    <div class="conteiner">
      <div>
        <div class="title">タイトル : <span><?php echo h($userData['title']);?></span></div>
        <div class="category">カテゴリ : <span><?php echo h(setCategory($userData['category'])) ;?></span></div>
        <div class="post_at">投稿日時 : <span><?php echo $userData['insert_time'];?></span></div>
      </div>
      <div class="option">
        <div class="edit"><a href="./edit.php?id=<?php echo $userData['id']?>" id="edit">編集</a></div>
        <div class="delete"><a href="./delete.php?id=<?php echo $userData['id']?>" id="delete">削除</a></div>
      </div>
    </div>
    <hr>  
    <div class="img"><img src="<?php echo $userData['file_path'];?>" width="50%"></div>
    <div class="content"><?php echo nl2br(h($userData['content'])) ;?></div> 
  </section>

  <script src="../js/detail.js"></script>
</body>
</html>