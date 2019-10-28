<!DOCTYPE html>
<html lang="ja">
<head>
<title>mission_6-2-mypage</title>
<meta charset="utf-8">
</head>

<body>
<h1>マイページ</h1>
<body bgcolor="#d8bfd8">

<?php
  //データベース接続
  $dsn='mysql:dbname=tb210353db;host=localhost';
  $user='tb-210353';
  $password='Z3zG4JEHvc';
  $pdo=new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
	//テーブル作成
	$sql = "CREATE TABLE IF NOT EXISTS tbtest1"
	." ("
	. "id INT AUTO_INCREMENT PRIMARY KEY,"
	. "name char(32),"
	. "password char(32)"
	.");";
	$stmt = $pdo->query($sql);
  
  //セッション開始
  session_start();
  echo $_SESSION['username']."さん、マイページへようこそ。何をしますか？";
?>

<form action="mission_6-2-mypage.php" method="POST">
<input type="button" name="toukou" onclick="location.href='/mission_6-2-toukou.php'" value="投稿する"></br>


</body>
</html>