<html>
 <head>
  <meta charset="utf=8">
  <title>mission5-1</title>
 </head>
</html>

<?php

//データベース接続
$dsn = 'データベース名';
$user = 'ユーザー名';
$password = 'パスワード';
$pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));

//testという名のテーブルを作る
//create エラー防止 テーブル名
//(カラム名（idなど) データ型）
$sql = "CREATE TABLE IF NOT EXISTS test"
." ("
. "id INT AUTO_INCREMENT PRIMARY KEY,"
. "name char(5),"
. "comment TEXT,"
. "date TEXT,"
. "pass TEXT"
.");";
$stmt = $pdo->query($sql);



//空でないときに投稿
if(!empty($_POST["名前"]) and !empty($_POST["コメント"]) and !empty($_POST["パスワード"])){

//変数に代入
$name = $_POST["名前"];
$comment = $_POST["コメント"];
$date = date('Y/m/d H:i:s');
$pass = $_POST["パスワード"];

	//編集番号が入っているか
	if(!empty($_POST["編集番号"])){
	$sql = 'SELECT * FROM test';
	$stmt = $pdo->query($sql);
	//fetchAllで配列に
	$results = $stmt->fetchAll();
		foreach ($results as $row){
			//編集番号とパスワードが一致しているか
			if($_POST["編集番号"]==$row['id'] and $_POST["パスワード"]==$row['pass']){
			$hensyunum = $row['id']; //変更する投稿番号
			//update テーブル名 set whereどこのデータ
			$sql = 'update test set name=:name,comment=:comment,date=:date where id=:id';
			$stmt = $pdo->prepare($sql);
			$stmt->bindParam(':name', $name, PDO::PARAM_STR);
			$stmt->bindParam(':comment', $comment, PDO::PARAM_STR);
			$stmt->bindParam(':date', $date, PDO::PARAM_STR);
			$stmt->bindParam(':id', $hensyunum, PDO::PARAM_INT);
			$stmt->execute();
			}
		}
	}else{
	//INSERT INTO テーブル名（カラム名） VALUES(データ名）
	$sql = $pdo -> prepare("INSERT INTO test (name, comment, date, pass) VALUES (:name, :comment, :date, :pass)");
	//bindparam(パラメータ、データ、データ型）
	$sql -> bindParam(':name', $name, PDO::PARAM_STR);
	$sql -> bindParam(':comment', $comment, PDO::PARAM_STR);
	$sql -> bindParam(':date', $date, PDO::PARAM_STR);
	$sql -> bindParam(':pass', $pass, PDO::PARAM_STR);
	$sql -> execute();	
	}
}

if(!empty($_POST["削除対象番号"])){
$delete = $_POST["削除対象番号"];
$sql = 'SELECT * FROM test';
$stmt = $pdo->query($sql);
//fetchAllで配列に
$results = $stmt->fetchAll();
	foreach ($results as $row){
		if($delete == $row['id'] and $_POST["パスワード2"]==$row['pass']){
		//delete from テーブル名 where 
		$sql = 'delete from test where id=:id';
		$stmt = $pdo->prepare($sql);
		$stmt->bindParam(':id', $delete, PDO::PARAM_INT);
		$stmt->execute();
		} 
	}
}

//編集対象番号が入っているか
if(!empty($_POST["編集対象番号"])){
$hensyu = $_POST["編集対象番号"];
$sql = 'SELECT * FROM test';
$stmt = $pdo->query($sql);
//fetchAllで配列に
$results = $stmt->fetchAll();
	foreach ($results as $row){
		if($hensyu == $row['id'] and $_POST["パスワード3"]==$row['pass']){
		$namehen = $row['name'];
		$commenthen =$row['comment'];
		$hennumber = $row['id'];
		}
	}	
}

?>

<html>
<body>
<form action="" method="post">
<br>
  <input type="text" name="名前"  placeholder = "名前" value = "<?php if(!empty($namehen)){echo $namehen;}else{echo "";} ?>" ><br>
  <input type="text" name="コメント"  placeholder="コメント" value = "<?php if(!empty($commenthen)){echo $commenthen;}else{echo "";} ?>"><br>
  <input type="text" name="パスワード" placeholder="パスワード">
  <input type="submit" value="送信"><br>
  <input type="hidden" name="編集番号"  value = "<?php if(!empty($hennumber)){echo $hennumber;}else{echo "";} ?>"><br>

  削除対象番号 <input type="text" name="削除対象番号" size="3"><br>
  <input type="text" name="パスワード2" placeholder="パスワード">
  <input type="submit" value="削除"><br>
  <br>
  編集対象番号 <input type="text" name="編集対象番号" size="3"><br>
  <input type="text" name="パスワード3" placeholder="パスワード">
  <input type="submit" value="編集"><br>

</form>
</body>
</html>

<?php

//ファイルの読み込み・表示
$sql = 'SELECT * FROM test';
$stmt = $pdo->query($sql);
//fetchAllで配列に
$results = $stmt->fetchAll();
foreach ($results as $row){
	//$rowの中にはテーブルのカラム名が入る
	echo $row['id'].' ';
	echo $row['name'].' ';
	echo $row['comment'].' ';
	echo $row['date'].'<br>';
	echo "<hr>";
}
?>