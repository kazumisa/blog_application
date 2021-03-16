<?php
session_start();
require_once('./dbc_account.php');

// ログインユーザの存在を確認
if(isset($_SESSION['login_user'])) {
  header('Location: ./blog_top.php');
}

// complete_login.phpからheader関数によるページ遷移の際に受け取ったセッション変数をそれぞれ変数に格納
if(isset($_SESSION['token'])) {
  $token = $_SESSION['token'];

  unset($_SESSION['token']);
}

if(isset($_SESSION['err'])) {
  $err = $_SESSION['err'];

  unset($_SESSION['err']);
}

if(isset($_SESSION['msg'])) {
  $msg = $_SESSION['msg'];

  unset($_SESSION['msg']);
}

if(isset($_SESSION['email'])) {
  $email = $_SESSION['email'];

  unset($_SESSION['email']);
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>アカウント作成画面</title>
  <link rel="stylesheet" href="../css/login_form.css">
</head>
<body>
  <form action="complete_login.php" method="POST">
    <h2>メールアドレス</h2>
    <div class="email"><input type="email" name="email" id="email" autocomplete="on"
    <?php if(isset($email)) :?>
    value="<?php echo $email ;?>"
    <?php endif ;?>>
  
    </div>
    <div class="email_err">
      <div>
        <?php if(isset($err['email'])) :?>
          <p><?php echo $err['email'] ;?></p>
        <?php endif ;?>
      </div>
      <div>
        <?php if(isset($msg['email'])) :?>
          <p><?php echo $msg['email'] ;?></p>
        <?php endif ;?>
      </div>
    </div>

    <h2>パスワード</h2>
    <div class="password"><input type="password" name="password" id="password"></div>
    <div class="pass_err">
      <div>
        <?php if(isset($err['password'])) :?>
          <p><?php echo $err['password'] ;?></p>
        <?php endif ;?>
      </div>
      <div>
        <?php if(isset($msg['password'])) :?>
          <p><?php echo $msg['password'] ;?></p>
        <?php endif ;?>
      </div>   
    </div>

    <input type="hidden" name="token" 
    <?php if(!isset($_SESSION['token'])) :?>
    value="<?php echo h(setToken()) ;?>"
    <?php endif ;?>>
    <div class="submit"><input type="submit" id="submit" value="ログイン"></div>
    <div class="back"><a href="../html/create_and_login.html">戻る</a></div>
  </form>
</body>
</html>