<?php
session_start();
require_once('./dbc_account.php');


// ログインユーザの存在を確認
if(isset($_SESSION['login_user'])) {
  header('Location: ./blog_top.php');
}

// トークンが存在したら削除
if(isset($_SESSION['token'])) {
  unset($_SESSION['token']);
}

// header関数によるページ遷移の際に受け取ったセッション変数をそれぞれ変数に格納
if(isset($_SESSION['msg'])) {
  $err = $_SESSION['msg'];
  
  unset($_SESSION['msg']);
}
if(isset($_SESSION['email'])) {
  $email = $_SESSION['email'];

  unset($_SESSION['email']);
}
if(isset($_SESSION['username'])) {
  $username = $_SESSION['username'];

  unset($_SESSION['username']);
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>アカウント作成画面</title>
  <link rel="stylesheet" href="../css/create_account.css">
</head>
<body>
  <form action="./complete_account.php" method="POST">
    <h2>メールアドレス</h2>
    <div class="email"><input type="email" name="email" id="email" autocomplete="on" 
      <?php if(isset($email)):?>
      value="<?php echo $email;?>"
      <?php endif ;?>>
    </div>
    <div class="msg">
      <?php if(isset($err['email'])) :?>
          <p><?php echo '※'.$err['email'];?></p>
      <?php endif ;?>
    </div>

    <h2>名前を追加</h2>
    <p class="user_p">ユーザ名は登録完了後、設定から編集できます。</p>
    <div class="username"><input type="text" name="username" id="username" autocomplete="off"
      <?php if(isset($username)):?>
      value="<?php echo $username;?>"
      <?php endif ;?>>
    </div>
      <div class="msg">
        <?php if(isset($err['username'])) :?>
            <p><?php echo '※'.$err['username'];?></p>
        <?php endif ;?>
      </div>

    <h2>パスワード</h2>
    <p class="pass_p">半角英小文字大文字数字をそれぞれ1種類以上含む8文字以上100文字以下</p>
    <div class="password">
      <input type="password" name="password" id="password">
    </div>
    <div class="msg">
      <?php if(isset($err['password'])) :?>
          <p><?php echo '※'.$err['password'];?></p>
      <?php endif ;?>
    </div>

    <h2>パスワード(確認用)</h2>
    <div class="password_conf"><input type="password" name="password_conf" id="password_conf"></div>
    <div class="msg">
      <?php if(isset($err['password_conf'])) :?>
          <p><?php echo '※'.$err['password_conf'];?></p>
      <?php endif ;?>
    </div>

    <input type="hidden" name="token" <?php if(!isset($_SESSION['token'])) :?>
    value="<?php echo h(setToken()) ;?>"
    <?php endif ;?>>
    <div class="submit"><input type="submit" id="submit" value="次へ"></div>
  </form>

  <div class="back"><a href="../html/create_and_login.html">戻る</a></div>

  <script src="../js/create_account.js"></script>
</body>
</html>