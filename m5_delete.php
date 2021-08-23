<?php  
    $dsn = 'データベース名';
    $user = 'ユーザー名';
    $password = 'パスワード';
    $pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));

    //4-1で書いた「// DB接続設定」のコードの下に続けて記載する。
    // 【！この SQLは tbtest テーブルを削除します！】
        $sql = 'DROP TABLE mission';
        $stmt = $pdo->query($sql);
?>