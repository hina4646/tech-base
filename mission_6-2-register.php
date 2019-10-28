<!DOCTYPE html>
<html lang="ja">
<head>
<title>mission_6-2-register</title>
<meta charset="utf-8">
</head>
<h1>アカウント作成画面</h1></br>
アカウントを作成してください。</br>
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
  //エラーメッセージの初期化
  $errorMessage="";
  $name="";
  $password="";
  //登録ボタンが押されたとき
  if(isset($_POST["newlogin"])){
 	 //nameとpasswordの入力チェック
  	if(empty($_POST["name"]) && empty($_POST["password"])){
    	  $errorMassege="IDとパスワードが入力されていません";
  	}elseif(empty($_POST["name"])){
    	  $errorMassege= "IDが入力されていません";
  	}elseif(empty($_POST["password"])){
    	  $errorMassege= "パスワードが入力されていません";
  	}

 	//nameとpassが入力されていたら
  	if(!empty($_POST["name"]) && !empty($_POST["password"])){
    	  $name=$_POST["name"];
    	  $password=$_POST["password"];
  	//insertでデータ入力
    	$sql = $pdo -> prepare("INSERT INTO tbtest1 (name, password) VALUES (:name, :password)");
	  $sql -> bindParam(':name', $name, PDO::PARAM_STR);
	  $sql -> bindParam(':password', $password, PDO::PARAM_STR);
	  $sql -> execute();
	  $_SESSION['username']=$name;
	  $_SESSION['password']=$password;
		header('Location:/mission_6-2-complete.php');
  	}
  }

  //エラーメッセージ表示
  if(isset($errorMassage)){
	echo $errorMassege;
  }

?>

<form action ="mission_6-2-register.php" method="POST">
ID</br><input type="text" name="name"></br>
パスワード</br><input type="text" name="password"></br>
<input type="submit" name="newlogin"  value="登録">

<?php
  //エラーメッセージ
  if($errorMessage == 1){
	echo "IDとパスワードを設定してください";
	}
  elseif($errorMessage == 2){
	echo "IDを設定してください";
  	}
  elseif($errorMessage == 3){
	echo "パスワードを設定してください";
	}
?>

</body>
</html>