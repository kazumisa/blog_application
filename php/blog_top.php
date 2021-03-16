<?php
session_start();
require_once('./dbc_post.php');

// 投稿画面へ移っても、タイトルのセッションが残ってしまうバグの一時改善策
unset($_SESSION['title']);

// ログインユーザの存在を確認
if(!isset($_SESSION['login_user'])) {
  header('Location: ./login_form.php');
  exit();
}

// トークンバリデーション(XSS対策, 二重送信防止対策)
if(isset($_SESSION['token'])) {

  unset($_SESSION['token']);
}

// ユーザを識別するため
$userID = $_SESSION['login_user']['id'];

// データベースからデータを取得し一覧表示する
$fileData = getAllFile($userID);

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>ユーザトップページ</title>
  <link rel="stylesheet" href="../css/blog_top.css">
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
</head>
<body>
  <section class="sp_sc">
    <header>
      <h2><?php echo $_SESSION['login_user']['username']?></h2>

      <nav> 
        <span class="material-icons" id="add">add</span>
        <span class="material-icons" id="menu">menu</span>
      </nav>
    </header>

    <section class="sp_main">
      <ul>
        <?php if(isset($fileData)) :?>
          <?php foreach($fileData as $file) :?>
            <li>
              <a href="./detail.php?id=<?php echo $file['id']?>">
                <img src="<?php echo h($file['file_path']) ;?>">
              </a>
            </li>
          <?php endforeach ;?>
        <?php endif ;?>
      </ul>
    </section>
    
    <div class="mask">
    </div>

    <div class="window">
      <div class="line"><span class="material-icons" id="line">horizontal_rule</span></div>
      <h3>作成</h3>
      <div class="create">
        <div><span class="material-icons" id="create">create</span></div>
        <div class="create_blog"><a href="./blog_form.php" id="create_blog">投稿</a></div>
      </div>
    </div>

    <div class="menw_window">
      <div class="line"><span class="material-icons" id="line">horizontal_rule</span></div>
      <div class="setting">
        <div><span class="material-icons" id="settings_suggest">settings_suggest</span></div>
        <div class="settings_suggest"><a href="">設定</a></div>
      </div>
      <div class="contact">
        <div><span class="material-icons" id="contact_support">contact_support</span></div>
        <div class="contact_support"><a href="./form.php">お問い合わせ</a></div>
    </div>

      <div class="logout">
        <div><span class="material-icons" id="logout">logout</span></div>
        <div class="logout_text">
          <form action="./logout.php" method="POST">
            <input type="hidden" name="token" value="<?php echo h(setToken())?>">
            <div class="logout_button"><input type="submit" name="logout" id="input_button" value="ログアウト"></div> 
          </form>
        </div>
      </div>
    </div>
  </section>
  
  
  <script src="../js/blog_top.js"></script>
</body>
</html>