<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>mission_5-01</title>
</head>
<body>
    <?php
    //データベースへの接続開始
        $dsn = 'データベース名';
        $user = 'ユーザー名';
        $password = 'パスワード';
        $pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
    //テーブルの作成開始
        $sql = "CREATE TABLE IF NOT EXISTS mission5"
        ."("
        ."id INT AUTO_INCREMENT PRIMARY KEY,"
        ."name char(32),"
        ."comment TEXT,"
        ."date TEXT,"
        ."pass char(16)"
        .");";
        $stmt = $pdo->query($sql);
    //テーブルの作成終了
    
    //データを入力（データレコードの挿入）開始
        if(!empty($_POST["name"]) && !empty($_POST["comment"]) && empty($_POST["return_num"])){//もし編集番号が空で名前とコメントが空っぽじゃなかったら
            if($_POST["pass_nc"]==""){//パスワードが入力されていないとき
                    $emptypass = "パスワードを入力してください";
            }else{
                $name = $_POST["name"];
                $comment = $_POST["comment"]; 
                $date = date('Y/m/d H:i');
                $pass = $_POST["pass_nc"];
                $sql = $pdo -> prepare("INSERT INTO mission5 (name, comment, date, pass) VALUES (:name, :comment, :date, :pass)");
                $sql->bindParam(':name', $name, PDO::PARAM_STR);
                $sql->bindParam(':comment', $comment, PDO::PARAM_STR);
                $sql->bindParam(':date', $date, PDO::PARAM_STR);
                $sql->bindParam(':pass', $pass, PDO::PARAM_STR);
                $sql->execute();
            }
        }
    //データを入力（データレコードの挿入）終了
    
    //削除機能開始
        elseif(!empty($_POST["delete"])&&empty($_POST["name"])&&empty($_POST["comment"])&&empty($_POST["edit"])&&empty($_POST["return_num"])){
            if($_POST["pass_delete"]==""){//パスワードが書き込まれていないとき
                    $emptypass = "パスワードを入力してください";
            }else{//パスワードが空でない時
                $sql = 'SELECT * FROM mission5';
                $stmt = $pdo->query($sql);
                $results = $stmt->fetchAll();
                foreach ($results as $row){
                    if($row["id"] == $_POST["delete"] && $row["pass"] == $_POST["pass_delete"]){
                        $id = $_POST["delete"];
                        $sql = 'delete from mission5 where id=:id';
                        $stmt = $pdo->prepare($sql);
                        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
                        $stmt->execute();
                    }elseif($row["id"] == $_POST["delete"] && $row["pass"] != $_POST["pass_delete"]){//パスワードが違うとき
                        $notpass = "パスワードが間違っています";
                    }
                }
            }
        }
    //削除機能終了
    
    //編集機能開始
        elseif(!empty($_POST["edit"])&&empty($_POST["name"])&&empty($_POST["comment"])&&empty($_POST["delete"])&&empty($_POST["return_num"])){//編集番号だけが空でないとき
            if($_POST["pass_edit"]==""){//パスワードが空のとき
                    $emptypass = "パスワードを入力してください";
            }else{//パスワードが書き込まれているとき
                $sql = 'SELECT * FROM mission5';
                $stmt = $pdo->query($sql);
                $results = $stmt->fetchAll();
                foreach ($results as $row){
                    if($row["id"] == $_POST["edit"] && $row["pass"] == $_POST["pass_edit"]){//編集番号とパスワードが一致したとき
                        $return_num = $row["id"];//編集番号
                        $re_name = $row["name"];//フォームに表示する名前
                        $re_comment = $row["comment"];//フォームの表示するコメント
                    }elseif($row["id"] == $_POST["edit"] && $row["pass"] != $_POST["pass_edit"]){//パスワードが違うとき
                        $return_num = "";
                        $re_name = "";
                        $re_comment = "";
                        $notpass = "パスワードが間違っています";
                    }
                }
            }
        }
    //投稿を編集開始
        elseif(!empty($_POST["return_num"])){
            $id = $_POST["return_num"]; //変更する投稿番号
            $name = $_POST["name"];
            $comment = $_POST["comment"]; //変更したい名前、変更したいコメントは自分で決めること
            $date = date('Y/m/d H:i');
            $pass = $_POST["pass_edit"];
            $sql = 'UPDATE mission5 SET name=:name,comment=:comment,date=:date,pass=:pass WHERE id=:id';
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->bindParam(':name', $name, PDO::PARAM_STR);
            $stmt->bindParam(':comment', $comment, PDO::PARAM_STR);
            $stmt-> bindParam(':date', $date, PDO::PARAM_STR);
            $stmt-> bindParam(':pass', $pass, PDO::PARAM_STR);
            $stmt->execute();
        }
    //投稿を編集終了
    //編集機能終了    
    ?>
    <form action="" method="post">
        <input type="text" name="name" placeholder="名前"
                value="<?php if(!empty($return_num)){echo $re_name;}?>"><br>
        <input type="text" name="comment" placeholder="コメント"
                value="<?php if(!empty($return_num)){echo $re_comment;}?>"><br>
        <input type="text" name="pass_nc" placeholder="パスワード">
        <input type="submit" name="submit" value="送信"><br><br>
        
        <input type="number" name="delete" placeholder="削除番号"><br>
        <input type="text" name="pass_delete" placeholder="パスワード">
        <input type="submit" name="submit" value="削除"><br><br>
        
        <input type="number" name="edit" placeholder="編集番号"><br>
        <input type="hidden" name="return_num" 
                value="<?php if(!empty($return_num)){echo $return_num;}?>">
        <input type="text" name="pass_edit" placeholder="パスワード">
        <input type="submit" name="submit" value="編集"><br><br>
    </form>
    <hr width="30%" align="left">
    <?php
    //ブラウザ表示機能開始
        if(!empty($emptypass)){
            echo $emptypass;//パスワードを入力してください
        }elseif(!empty($notpass)){
            echo $notpass;//パスワードが間違っています
        }
    ?>
    <hr width="30%" align="left">
    <?php
    //投稿表示
        $sql = 'SELECT * FROM mission5';
        $stmt = $pdo->query($sql);
        $results = $stmt->fetchAll();
        foreach ($results as $row){
            //$rowの中にはテーブルのカラム名が入る
            echo $row['id'].'. ';
            echo $row['name'].'　「';
            echo $row['comment'].'」　';
            echo $row['date'].'<br>';
        echo "<hr>";
        }
    //ブラウザ表示終了   
    
    ?>
    
</body>