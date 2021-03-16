<?php
require_once('./dbc_post.php');

// 1ページに表示する記事の数を定義
define('max_article', 6);

// 必要なページ数を求める
function showPages($userID) {
  
  try {
    $pdo  = dbConnect();
    $sql  = 'SELECT COUNT(id) AS count FROM photoblog WHERE userID = :userID';
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':userID', $userID, PDO::PARAM_INT);
    $stmt->execute();
    $count = $stmt->fetch(PDO::FETCH_ASSOC);
    // エラーメッセージ「 Division by zero 」回避のための分岐
    if(!$count['count'] == 0) {
      $totalPages = ceil($count['count'] / max_article);
    } else {
      exit();
    }
    return $totalPages;
  } catch (PDOException $e) {
    exit($e->getMessage());
  }
}


// データベースから写真を取得
function getData_paging($userID) {
  showPages($userID);

  // 現在いるページのページ番号を取得
  if(!isset($_GET['page_id'])) {
    $now = 1;
  } else {
    $now = $_GET['page_id'];
  }

  try {
    $pdo = dbConnect();
    $sql = "SELECT * FROM photoblog  WHERE userID = :userID ORDER BY id DESC LIMIT :start,:max";
    $stmt = $pdo->prepare($sql);

    if($now == 1) {
      // 1ページ目の処理
      $stmt->bindValue(':userID', $userID, PDO::PARAM_INT);
      $stmt->bindValue(':start', $now - 1, PDO::PARAM_INT);
      $stmt->bindValue(':max', max_article, PDO::PARAM_INT);
    } else {
      // 1ページ目以外の処理
      $stmt->bindValue(':userID', $userID, PDO::PARAM_INT);
      $stmt->bindValue(':start', ($now - 1) * max_article, PDO::PARAM_INT);
      $stmt->bindValue(':max', max_article, PDO::PARAM_INT);
    }
    $stmt->execute();
    $userData = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $userData;
  } catch (PDOXception $e) {
    exit($e->getMessage());
  }
} 

// ページネーション作成
function showNumber($userID) {
  // 現在いるページのページ番号を取得
    if(!isset($_GET['page_id'])) {
      $now = 1;
    } else {
      $now = $_GET['page_id'];
    }
  $totalPages = showPages($userID);
  for($n = 1; $n <= $totalPages; $n++) {
    if($n == $now) {
      echo "<span style='padding: 5px;'>$now</span>";
    } else {
      echo "<a href='./blog_top.php?page_id=$n' style='padding: 5px;'>$n</a>";
    }
  }
}


?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
</head>
<body>
  


</body>
</html>