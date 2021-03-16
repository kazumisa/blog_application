<?php
session_start();
require_once('./dbc_account.php');

// ログインユーザの存在を確認
if(!isset($_SESSION['login_user'])) {
  header('Location: ./login_form.php');
}

// トークン削除
if(isset($_SESSION['token'])) {

  unset($_SESSION['token']);
}

// header関数によるページ遷移の際に受け取ったセッション変数をそれぞれ変数に格納
if(isset($_SESSION['err'])) {
  $err = $_SESSION['err'];

  unset($_SESSION['err']);
}
if(isset($_SESSION['title'])) {
  $title = $_SESSION['title'];

  unset($_SESSION['title']);
}
if(isset($_SESSION['content'])) {
  $content = $_SESSION['content'];

  unset($_SESSION['content']);
}

if(isset($_SESSION['category'])) {
  $category = $_SESSION['category'];

  unset($_SESSION['category']);
}

if(isset($_SESSION['publish_status'])) {
  $publish_status = $_SESSION['publish_status'];

  unset($_SESSION['publish_status']);
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>ブログ投稿画面</title>
  <link rel="stylesheet" href="../css/blog_form.css">
</head>
<body>
  <h2 class="top">ブログ投稿フォーム</h2>
  <!-- ファイルアップロードに関するエラー -->
  <div class="err_msg">
    <?php if(isset($err['err_msg'])) :?>
      <p><?php echo $err['err_msg'] ;?></p>
    <?php endif ;?>
  </div>
  <form enctype="multipart/form-data" action="./register.php" method="POST">
    <!-- ファイル -->
    <div class="file_up">
      <input type="hidden" name="MAX_FILE_SIZE" value="1048576">
      <input type="file" name="img" accept="image/*">
    </div>
    <div class="msg">
      <?php if(isset($err['file'])) :?>
        <p><?php echo '※'.$err['file'] ;?></p>
      <?php endif ;?>
    </div>
    
    <!-- タイトル -->
    <div class="title"> 
      <input type="text" name="title" id="title" autocomplete="off" placeholder="タイトル(30文字以内)"
      <?php if(isset($title)):?>
      value="<?php echo $title;?>"
      <?php endif ;?>>
    </div>
    <div class="msg">
      <?php if(isset($err['title'])) :?>
        <p><?php echo '※'.$err['title'] ;?></p>
      <?php endif ;?>
    </div>

    <!-- キャプション -->
    <div class="content">
      <textarea name="content" id="content" cols="30" rows="10" placeholder="キャプション(300文字以内)"><?php if(isset($title)) :?><?php echo $content;?><?php endif ;?></textarea>
    </div>
    <div class="msg">
      <?php if(isset($err['content'])) :?>
        <p><?php echo '※'.$err['content'] ;?></p>
      <?php endif ;?>
    </div>

    <!-- カテゴリ -->
    <div class="category">
      <label for="category">カテゴリ</label>
      <select name="category" id="category">
      <?php if(!isset($category)) :?>
        <option value="1" selected>日常</option>
        <option value="2">仕事</option>
        <option value="3">学校</option>
        <option value="4">旅行</option>
        <option value="5">その他</option>
      <?php endif ;?>
      <?php if(isset($category)) :?>
        <option value="1" <?php if($category == 1) echo "selected";?>>日常</option>
        <option value="2" <?php if($category == 2) echo "selected";?>>仕事</option>
        <option value="3" <?php if($category == 3) echo "selected";?>>学校</option>
        <option value="4" <?php if($category == 4) echo "selected";?>>旅行</option>
        <option value="5" <?php if($category == 5) echo "selected";?>>その他</option>
      <?php endif ;?>
      </select>
    </div>
    <div class="msg">
      <?php if(isset($err['category'])) :?>
        <p><?php echo '※'.$err['category'] ;?></p>
      <?php endif ;?>
    </div>

    <!-- 公開ステータス -->
    <div class="publish_status">
      <label for="publish_status">公開ステータス</label>
      <?php if(!isset($publish_status)) :?>
        <input type="radio" name="publish_status" id="publish_status" value="1" checked>公開
        <input type="radio" name="publish_status" id="publish_status" value="2">非公開
      <?php endif ;?>
      <?php if(isset($publish_status)) :?>
        <input type="radio" name="publish_status" id="publish_status" value="1" <?php if($publish_status == 1) echo "checked";?>>公開
        <input type="radio" name="publish_status" id="publish_status" value="2" <?php if($publish_status == 2) echo "checked";?>>非公開
      <?php endif ;?>
    </div>
    <div class="msg">
      <?php if(isset($err['publish_status'] )) :?>
        <p><?php echo '※'.$err['publish_status'] ;?></p>
      <?php endif ;?>
    </div>

    <!-- トークン -->
    <input type="hidden" name="token" 
    <?php if(!isset($_SESSION['token'])) :?>
    value="<?php echo h(setToken()) ;?>"
    <?php endif ;?>>

    <div class="submit"><input type="submit"  id="submit" value="投稿"></div>
    <div class="back"><a href="../php/blog_top.php">戻る</a></div>
  </form>
</body>
</html>