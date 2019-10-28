<!DOCTYPE html>
<html lang="ja">
<head>
<title>mission_6-2-toukou</title>
<meta charset="utf-8">
</head>

<body>
<h1>投稿ページ</h1>
<body bgcolor="#d8bfd8">

<?php

  //セッション開始
  session_start();

 //データベース接続
  $dsn='mysql:dbname=tb210353db;host=localhost';
  $user='tb-210353';
  $password='Z3zG4JEHvc';
  $pdo=new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));

  //テーブル作成
  $sql = "CREATE TABLE IF NOT EXISTS mediatest2" 
  ."("
  . "id INT AUTO_INCREMENT PRIMARY KEY," 
  . "fname TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL," 
  . "extension TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL," 
  . "raw_data LONGBLOB NOT NULL"
  .");";
  $stmt = $pdo->query($sql);

	//コメントのメッセージ初期化
	$cmt="";
        //ファイルアップロードがあったとき
        if(!empty($_POST["comment"]) && isset($_FILES['upfile']['error']) && is_int($_FILES['upfile']['error']) && $_FILES["upfile"]["name"] !== ""){
            //エラーチェック
            switch ($_FILES['upfile']['error']) {
                case UPLOAD_ERR_OK: // OK
                    break;
                case UPLOAD_ERR_NO_FILE:   // 未選択
                    throw new RuntimeException('ファイルが選択されていません', 400);
                case UPLOAD_ERR_INI_SIZE:  // php.ini定義の最大サイズ超過
                    throw new RuntimeException('ファイルサイズが大きすぎます', 400);
                default:
                    throw new RuntimeException('その他のエラーが発生しました', 500);
            }

            //画像・動画をバイナリデータにする．
            $raw_data = file_get_contents($_FILES['upfile']['tmp_name']);

            //拡張子を見る
            $tmp = pathinfo($_FILES["upfile"]["name"]);
            $extension = $tmp["extension"];
            if($extension === "jpg" || $extension === "jpeg" || $extension === "JPG" || $extension === "JPEG"){
                $extension = "jpeg";
            }
            elseif($extension === "png" || $extension === "PNG"){
                $extension = "png";
            }
            elseif($extension === "gif" || $extension === "GIF"){
                $extension = "gif";
            }
            elseif($extension === "mp4" || $extension === "MP4"){
                $extension = "mp4";
            }
            else{
                echo "非対応ファイルです．<br/>";
                echo ("<a href=\mission_6-2-toukou.php\">戻る</a><br/>");
                exit(1);
            }

            //DBに格納するファイルネーム設定
            //サーバー側の一時的なファイルネームと取得時刻を結合した文字列にsha256をかける．
            $date = getdate();
            $fname = $_FILES["upfile"]["tmp_name"].$date["year"].$date["mon"].$date["mday"].$date["hours"].$date["minutes"].$date["seconds"];
            $fname = hash("sha256", $fname);

            //画像・動画をDBに格納．
            $sql = "INSERT INTO mediatest2(fname, extension, raw_data) VALUES (:fname, :extension, :raw_data);";
            $stmt = $pdo->prepare($sql);
            $stmt -> bindValue(":fname",$fname, PDO::PARAM_STR);
            $stmt -> bindValue(":extension",$extension, PDO::PARAM_STR);
            $stmt -> bindValue(":raw_data",$raw_data, PDO::PARAM_STR);
            $stmt -> execute();


            
//投稿テーブルに保存        
	$name = $_SESSION["username"];
	$comment = $_POST["comment"]; 
	date_default_timezone_set("Asia/Tokyo");
	$nowtime = date("Y/m/d H:i:s");

//4-5insertを用いてデータを入力

	$sql = $pdo -> prepare("INSERT INTO mediapost (name, comment, nowtime, photofilename, extension) VALUES ('$name', '$comment', '$nowtime', '$fname' , '$extension')");
	$sql -> bindParam(':name', $name, PDO::PARAM_STR);
	$sql -> bindParam(':comment', $comment, PDO::PARAM_STR);
	$sql -> bindParam(':nowtime', $nowtime, PDO::PARAM_STR);
	$sql -> bindParam(':photofilename', $fname, PDO::PARAM_STR);
	$sql -> bindParam(':extension', $extension, PDO::PARAM_STR);
	$sql -> execute();
	}elseif(empty($_POST["comment"])){
	  $cmt="コメントを入力してください";
	}
?>

 <form action="mission_6-2-toukou.php" enctype="multipart/form-data" method="POST">
        <label>画像/動画アップロード</label></br>
        <input type="file" name="upfile"></br>
	<label>コメント</label></br>
	<input type="text" size="40" name="comment">
        <br>
        ※画像はjpeg方式，png方式，gif方式に対応しています．動画はmp4方式のみ対応しています．<br>
        <input type="submit" value="投稿する">
    </form>

    <?php
    //DBから取得して表示する．
    $user=$_SESSION["username"];
    $sql = "SELECT * FROM mediapost WHERE name='$name'";
    $stmt = $pdo->prepare($sql);
    $stmt -> execute();
    foreach($results as $row){
        //動画と画像で場合分け
        $target = $row["photofilename"];
        if($row["extension"] == "mp4"){
            echo ("<video src=\"mission_6-2-toukou.php?target=$target\" width=\"426\" height=\"240\" controls></video>");
        }
        elseif($row["extension"] == "jpeg" || $row["extension"] == "png" || $row["extension"] == "gif"){
            echo ("<img src='mission_6-2-toukou.php?target=$target'>");
        }
        	echo "<br>";
		echo $row['id'].'';
		echo $row['name']."<br>";
		echo $row['comment']."<br>";
		echo $row['nowtime']."<br>";
    }


?>


</body>
</html>