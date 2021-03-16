<?php
session_start();
require_once('./dbc_account.php');

// ログインユーザの存在を確認
if(!isset($_SESSION['login_user'])) {
  header('Location: ./login_form.php');
}

if(isset($_SESSION['token'])) {
  $token = $_SESSION['token'];

  unset($_SESSION['token']);
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>お問い合わせフォーム</title>
  <link rel="stylesheet" href="../css/form.css">
</head>
<body>
  <h2>お問い合わせフォーム</h2>
  <form action="" mthod=POST>
    <p>お問い合わせ内容 : </p>
    <textarea name="form" id="form" cols="30" rows="10" placeholder=""></textarea>

    <input type="hidden" name="token" 
    <?php if(!isset($_SESSION['token'])) :?>
    value="<?php echo h(setToken()) ;?>"
    <?php endif ;?>>

    <div class="submit"><input type="submit" id="send" value="送信"></div>
    <div class="back"><a href="../php/blog_top.php">戻る</a></div>
  </form>
</body>
</html>