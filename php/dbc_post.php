<?php
require_once('./dbc_account.php');

/**
 * データベースに投稿データを登録
 * @param  string $filename
 * @param  string $save_path
 * @param  string $title
 * @param  string $content
 * @param  int    $category
 * @param  int    $publish_status
 * @return $result
 */
function createData_post($userID, $filename, $save_path, $title, $content, $category, $publish_status) {
  $result = false;
  try {
    $pdo = dbConnect();
    $sql = "INSERT INTO  
                   photoblog (userID, file_name, file_path, title, content, category, publish_status) 
            VALUES (:userID, :file_name, :file_path, :title, :content, :category, :publish_status)";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':userID', $userID, PDO::PARAM_INT);
    $stmt->bindValue(':file_name', $filename, PDO::PARAM_STR);
    $stmt->bindValue(':file_path', $save_path, PDO::PARAM_STR);
    $stmt->bindValue(':title', $title, PDO::PARAM_STR);
    $stmt->bindValue(':content', $content, PDO::PARAM_STR);
    $stmt->bindValue(':category', $category, PDO::PARAM_INT);
    $stmt->bindValue(':publish_status', $publish_status, PDO::PARAM_INT);
    $result = $stmt->execute();
    return $result;
  } catch (PDOXception $e) {
    echo $e->getMessage();
    return $result;
  }
}

/**
 * データベースからファイルデータを取得する
 * @param  int   $userID
 * @return array $fileData
 */
function getAllFile($userID) {
  try {
    $pdo = dbConnect();
    $sql = "SELECT * FROM photoblog  WHERE userID = :userID ORDER BY id DESC";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':userID', $userID, PDO::PARAM_INT);
    $stmt->execute();
    $userData = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $userData;
  } catch (PDOXception $e) {
    exit($e->getMessage());
  }
}

/**
 * カテゴリの型をint型からstringg型に変換
 * @param int $int
 * @param string $str
 */
function setCategory($int) {
  if($int == 1) {
    return '日常';
  } else if ($int == 2) {
    return '仕事';
  } else if ($int == 3) {
    return '学校';
  } else if ($int == 4) {
    return '旅行';
  } else if ($int == 5) {
    return 'その他';
  }
}
/**
 * 公開ステータスの型をint型からstringg型に変換
 * @param int $int
 * @param string $str
 */
function setPublishStatus($int) {
  if($int == 1) {
    return '公開';
  } else if ($int == 2) {
    return '非公開';
  } 
}

/**
 * データベースからidと一致するデータを取得
 * @param string $id
 * @return array|bool $userData|false
 */
function getDataById($id) {
  try {
    $pdo = dbConnect();
    $sql = "SELECT * FROM photoblog WHERE id=:id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $userData = $stmt->fetch(PDO::FETCH_ASSOC);
    return $userData;
  } catch (PDOXception $e) {
    exit($e->getMessage());
  }
}


/**
 * データをアップデート
 * @param int    $id
 * @param string $title
 * @param string $content
 * @param string $category
 * @param string $publish_status
 * @return array $upDateData
 */
function dataUpDate($id ,$title, $content, $category, $publish_status) {
  try {
    $pdo = dbConnect();
    $sql = "UPDATE photoblog
            SET title = :title, content = :content, category = :category, publish_status = :publish_status
            WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':id', $id, PDO::PARAM_INT);
    $stmt->bindValue(':title', $title, PDO::PARAM_STR);
    $stmt->bindValue(':content', $content, PDO::PARAM_STR);
    $stmt->bindValue(':category', $category, PDO::PARAM_INT);
    $stmt->bindValue(':publish_status', $publish_status, PDO::PARAM_INT);
    $result = $stmt->execute();
    return $result;
  } catch (PDOXception $e) {
    echo $e->getMessage();
    return $result;
  }
}

/**
 * ブログ削除機能
 * @param string $id
 * @return bool  $delete
 */
function deleteBlog($id) {
  try {
    $pdo = dbConnect();
    $sql = "DELETE FROM photoblog WHERE id=:id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':id', $id, PDO::PARAM_INT);
    $delete = $stmt->execute();
    return $delete; 
  } catch (PDOXception $e) {
    exit($e->getMessage());
  }
}
