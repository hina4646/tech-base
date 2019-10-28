<!DOCTYPE html>
<html lang="ja">
<head>
<title>mission_6-2-login</title>
<meta charset="utf-8">
</head>

<body>
<h1>ログイン画面</h1>
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
  //初期化
  $errorMessage="";
  $name="";
  $userpassword="";
  //ログインボタンが押されたとき
  if(isset($_POST["login"])){
	//nameとpasswordの入力チェック
	if(empty($_POST["name"]) && empty($_POST["password"])){
    	  $errorMassege="IDとパスワードが入力されていません";
  	}elseif(empty($_POST["name"])){
    	  $errorMassege="IDが入力されていません";
  	}elseif(empty($_POST["password"])){
    	  $errorMassege="パスワードが入力されていません";
  	}

	//nameとpasswordが入力されていたら
	if(!empty($_POST["name"]) && !empty($_POST["password"])){
	  $name=$_POST["name"];
	  $userpassword=$_POST["password"];
	//selectで抽出
	$sql = "SELECT * FROM tbtest1 WHERE name = '$name'";
	$stmt = $pdo->query($sql);
	$results = $stmt->fetchAll();
	foreach ($results as $row){
	  $password=$row['password'];
	}
		//入力されたパスワードと抽出したパスワードが一致したら
		if($userpassword == $password){
		$SESSION_['username']=$name;
		  header('Location:/mission_6-2-mypage.php');
  		}elseif($userpassword !== $password){
		  $errorMassege="IDかパスワードが間違っています";
		}
	}
  	//エラーメッセージ表示
  	if(isset($errorMassege)){
	  echo $errorMassege;
  	}
  }





?>

<form action="mission_6-2-login.php" method="POST">
ID</br><input type="text" name="name"></br>
パスワード</br><input type="text" name="password"></br>
<input type="submit" name="login" value="送信"></br>
アカウントをお持ちでない方は<a href="/mission_6-2-register.php">こちら</a>

</body>
</html>
