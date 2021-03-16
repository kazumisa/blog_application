<?php
require_once('./env.php');
ini_set('display_errors', 1);


// データベース接続
function dbConnect() {
  $host = DB_HOST;
  $db   = DB_NAME;
  $user = DB_USER;
  $pass = DB_PASS;

  $dsn  = "mysql:host=$host;dbname=$db;charset=utf8mb4";

  try {
    $pdo = new PDO($dsn, $user, $pass, 
    [
      PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
      PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
    return $pdo;
  } catch (PDOException $e) {
    exit($e->getMessage());
  }
}

/**
 * データベースにデータを登録
 * @param  string $email
 * @param  string $username
 * @param  string $password
 * @return bool   $result
 */
function createAccount($email, $username, $password) {
  $hash_pass = password_hash($password, PASSWORD_DEFAULT);
  try {
    $pdo = dbConnect();
    $sql = "INSERT INTO  
                   app_account (email, username, password) 
            VALUES (:email, :username, :password)";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':email', $email, PDO::PARAM_STR);
    $stmt->bindValue(':username', $username, PDO::PARAM_STR);
    $stmt->bindValue(':password', $hash_pass, PDO::PARAM_STR);
    $result = $stmt->execute();
    return $result;
  } catch (PDOXception $e) {
    exit($e->getMessage());
  }
}

/**
 * データベースからデータを取得
 * @param  void
 * @return $userData
 */
function getData() {
  try {
    $pdo = dbConnect();
    $sql = "SELECT * FROM app_account";
    $stmt = $pdo->query($sql);
    $userData = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $userData;
  } catch (PDOException $e) {
    exit($e->getMessage());
  }
}


/**
 * ログイン機能
 * @param  string $email 
 * @param  string $password 
 * @return bool   $result 
 */
function login($email, $password) {
  $result = false;

  $msg = array();
  $userData = getUserByEmail($email);
  if(!$userData) {
    $msg['email'] = 'メールアドレスが一致しません';
    $_SESSION['msg'] = $msg;
    return $result;
  }

  // パスワード照会
  if(password_verify($password, $userData['password'])) {
    session_regenerate_id(true);
    $_SESSION['login_user'] = $userData;
    $result = true;
    return $result;
  } else {
    $msg['password'] = 'パスワードが一致しません';
    $_SESSION['msg'] = $msg;
    return $result;
  }

}

/**
 * 入力されたメールアドレスを元にユーザデータを取得
 * @param  string      $email
 * @return array|false $userData|false
 */
function getUserByEmail($email) {
  try {
    $pdo = dbConnect();
    $sql = "SELECT * FROM app_account WHERE email = :email";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':email', $email, PDO::PARAM_STR);
    $stmt->execute();
    $userData = $stmt->fetch(PDO::FETCH_ASSOC);
    return $userData;
  } catch (PDOException $e) {
    exit($e->getMessage());
  }
}

/**
 * 入力されたメールアドレスの被りをチェック
 * @param void
 * @param bool $result
 */
function checkEmail() {
  try {
    $pdo = dbConnect();
    $sql = "SELECT email FROM app_account";
    $stmt = $pdo->query($sql);
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $result;
  } catch (PDOException $e) {
    exit($e->getMessage());
  }
}

// ログアウト機能
function logout() {
  $_SESSION = array();
  session_destroy();
}

// CSRF対策 : ワンタイムトークン
function setToken() {
  $token = bin2hex(random_bytes(32));
  $_SESSION['token'] = $token;
  return $token;
}

// XSS対策 : エスケープ処理
function h($str) {
  return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}
