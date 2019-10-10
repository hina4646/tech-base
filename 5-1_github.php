<!DOCTYPE html>
<html lang="ja">
<head>
<title>mission_5-1.php</title>
<meta charset="utf-8">
</head>
<body>
<h1><font color=#0099CC>
掲示板
</font></h1>


<?php
//データベース接続
  $dsn='mysql:dbname=*******;host=localhost';
  $user='*******';
  $password='*******';
  $pdo=new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
	//テーブル作成
	$sql = "CREATE TABLE IF NOT EXISTS test_3"
	." ("
	. "id INT AUTO_INCREMENT PRIMARY KEY,"
	. "name char(32),"
	. "comment TEXT,"
	. "date_time DATETIME,"
	. "password char(32)"
	.");";
	$stmt = $pdo->query($sql);
//投稿フォーム
  if(!empty($_POST["name"])&&!empty($_POST["comment"])&&!empty($_POST["pass"])&&empty($_POST["hiddenid"])){
  $name=$_POST["name"];
  $comment=$_POST["comment"];
  $pass=$_POST["pass"];
  //日付データ
  date_default_timezone_set("Asia/Tokyo");
  $date_time=date("Y/m/d H:i:s");
  //insertでデータ入力
  $sql = $pdo -> prepare("INSERT INTO test_3 (name, comment, date_time, password) VALUES ('$name', '$comment', '$date_time', '$pass')");
	$sql -> bindParam(':name', $name, PDO::PARAM_STR);
	$sql -> bindParam(':comment', $comment, PDO::PARAM_STR);
	$sql -> bindParam(':date_time', $date_time, PDO::PARAM_STR);
	$sql -> bindParam(':password', $pass, PDO::PARAM_STR);	
	$sql -> execute();
  }
?>

<?php
//削除フォーム
  if(!empty($_POST["delete"])){
  $delete=$_POST["delete"];
  $delpass=$_POST["delpass"];
	//selectで投稿番号抽出
	$sql ="SELECT password FROM test_3 WHERE id='$delete'";
	$stmt = $pdo -> query($sql);
	$results = $stmt -> fetchAll();
	foreach ($results as $row){
	$pass = $row['password'];
		//入力したパスと設定したパスが一致したとき
		if($delpass == $pass){ 
		//deleteで削除
		$sql = "DELETE FROM test_3 WHERE id = '$delete'";
		$stmt = $pdo->prepare($sql);
		$stmt->bindParam(':id', $id, PDO::PARAM_INT);
		$stmt->execute();
		}elseif($delpass !== $pass){
		echo "<font color=\"red\">注意：パスワードが間違っています</font>";
		}
	}
  }
?>

  
<?php
//編集フォーム
if(!empty($_POST["edit"])&&!empty($_POST["editpass"])){
	$edit=$_POST["edit"];
	$editpass=$_POST["editpass"];
	$sql = "SELECT * FROM test_3 WHERE id = '$edit'";
	$stmt = $pdo->query($sql);
	$results = $stmt->fetchAll();
	foreach ($results as $row){
	$pass = $row['password'];
	$editname = $row['name'];
	$editcomment = $row['comment'];
	$edit =$row['id'];
		if($pass !== $editpass){
			$editname="";
			$editcomment="";
		echo "<font color=\"red\">注意：パスワードが間違っています</font>";
		}
		//名前とコメントを表示
		elseif($pass == $editpass){
			$editname=$row['name'];
			$editcomment=$row['comment'];			
		}
	}
  }
		//編集対象番号、名前、コメントが空でないとき
		if(!empty($_POST["hiddenid"]) && !empty($_POST["name"]) && !empty($_POST["comment"]) && !empty($_POST["pass"])){
		$id=$_POST["hiddenid"];
		$pass = $_POST["pass"];
		$name = $_POST["name"];
		$comment = $_POST["comment"];
			//時間の再設定
			date_default_timezone_set("Asia/Tokyo");
			$date_time=date("Y/m/d H:i:s");
			//updateで編集
			$sql = 'UPDATE test_3 SET name=:name,comment=:comment,date_time=:date_time,password=:password where id=:id';
			$stmt = $pdo->prepare($sql);
			$stmt->bindParam(':name', $name, PDO::PARAM_STR);
			$stmt->bindParam(':comment', $comment, PDO::PARAM_STR);
			$stmt->bindParam(':date_time', $date_time, PDO::PARAM_STR);
			$stmt->bindParam(':password', $pass, PDO::PARAM_STR);
			$stmt->bindParam(':id', $id, PDO::PARAM_INT);
			$stmt->execute();	
		  }
?>






<!-投稿・削除・編集フォーム、編集内容を受け取るために下に移動->
<form method="POST" action="mission_5-1.php">
<h3>【投稿フォーム】</h3>
お名前</br><input type="text" name="name" value=<?php if(isset($editname)){echo "$editname";}?>></br>
コメント</br><input type="text" name="comment" value=<?php if(isset($editcomment)){echo "$editcomment";}?>></br>
パスワード設定</br><input type="text" name="pass"></br>
<input type="submit" value="送信"></br>
<h3>【削除フォーム】</h3>
削除したい番号</br><input type="text" name="delete"></br>
パスワード入力</br><input type="text" name="delpass"></br>
<input type="submit" value="削除"></br>
<h3>【編集フォーム】</h3>
編集したい番号</br><input type="text" name="edit"></br>
パスワード入力</br><input type="text" name="editpass"></br>
<input type="submit" value="編集"></br>
<!-編集したい投稿番号を表示するテキストボックス->
<input type="hidden" name="hiddenid" value=<?php if(isset($edit)){echo "$edit";}?>></br>

<?php
 //フォームの下にselectで表示
  $sql = 'SELECT * FROM test_3';
  $stmt = $pdo->query($sql);
  $results = $stmt->fetchAll();
  foreach ($results as $row){
	echo $row['id'].',';
	echo $row['name'].',';
	echo $row['comment'].',';
	echo $row['date_time'].'<br>';
  }
?>

</form>
</body>
</html>