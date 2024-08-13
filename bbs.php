<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>mission_5-1</title>
</head>
<body>
    
    <?php
        //初期化
        $name_edit = "";
        $comment_edit = "";
        $num_edit = "";
        $password_edit = "";
    ?>
    
    
    <?php
        // DB接続設定
        $dsn = 'mysql:dbname=tb250606db;host=localhost';
        $user = 'tb-250606';
        $db_password = 'Pdux8aDhPb';
        $pdo = new PDO($dsn, $user, $db_password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
        //DB内にテーブルを作成
        $sql = "CREATE TABLE IF NOT EXISTS mission_5"
        . "("
        . "id INT AUTO_INCREMENT PRIMARY KEY, "
        . "name CHAR(32), "
        . "comment TEXT, "
        . "date TEXT, "
        . "password CHAR(32)"
        . ");";
        $stmt = $pdo -> query($sql);


        //全てのフォームが入力された場合は何もしない．
        if (!empty($_POST["str_name"]) && !empty($_POST["str_comment"]) && !empty($_POST["num_del"]) && !empty($_POST["num_edit"])) {
            echo "送信フォームと削除フォーム及び編集フォームを同時に埋めないでください";
        } 
        //送信フォームと削除フォームを同時に埋められた場合は何もしない．
        elseif (!empty($_POST["str_name"]) && !empty($_POST["str_comment"]) && !empty($_POST["num_del"])) {
            echo "送信フォームと削除フォームを同時に埋めないでください";
        }
        //投稿機能
        //名前とコメント，パスワードが入力され，2つのPOSTが空ではない場合
        elseif (!empty($_POST["str_name"]) && !empty($_POST["str_comment"]) && !empty($_POST["str_password"]) && empty($_POST["num_edited"])) {
            //データの受け取り
            $name = $_POST["str_name"];
            $comment = $_POST["str_comment"];
            $date = date("Y/m/d H:i:s");//date関数
            $password = $_POST["str_password"];
            //データレコードの挿入
            $sql = "INSERT INTO mission_5 (name, comment, date, password) VALUES (:name, :comment, :date, :password)";
            $stmt = $pdo -> prepare($sql);
            //変数をプレースホルダーにバインドする．
            $stmt -> bindParam(':name', $name, PDO::PARAM_STR);
            $stmt -> bindParam(':comment', $comment, PDO::PARAM_STR);
            $stmt -> bindParam(':date', $date, PDO::PARAM_STR);
            $stmt -> bindParam(':password', $password, PDO::PARAM_STR);
            //SQLステートメントの実行
            $stmt -> execute();
        }
        //削除機能
        //削除対象番号とパスワードが入力された場合
        elseif (!empty($_POST["num_del"]) && !empty($_POST["str_password_del"])) {
            //データの受け取り
            $num_del = $_POST["num_del"];
            $str_password_del = $_POST["str_password_del"];
            /*
            //受け取り確認テスト
            echo "<hr>";
            echo "test:" . $num_del . "/" . $str_password_del;
            echo "<hr>";
            */
            //passwordを照合するため，ループ処理を行う．
            $sql = 'SELECT * FROM mission_5';
            $stmt = $pdo -> query($sql);
            $results = $stmt -> fetchAll();
            foreach ($results as $row){
                //$rowの中にはテーブルのカラム名が入る
                //条件分岐
                //idとpasswordが正しい場合
                if ($row['id'] == $num_del && $row['password'] == $str_password_del){
                    //特定のidとpasswordを持つデータレコードを削除
                    $sql = 'DELETE FROM mission_5 WHERE id = :id AND password = :password';
                    $stmt = $pdo -> prepare($sql);
                    //変数をプレースホルダーにバインドする
                    $stmt -> bindParam(':id', $num_del, PDO::PARAM_INT);
                    $stmt -> bindParam(':password', $str_password_del, PDO::PARAM_STR);
                    //SQLステートメントの実行
                    $stmt -> execute();
                    //削除
                    echo "削除しました";
                }
                //パスワードが誤りの場合
                elseif ($row['id'] == $num_del && $row['password'] != $str_password_del){
                    echo "パスワードが誤りです";
                }
            }
        } 
        //パスワードが入力されていない場合
        elseif (!empty($_POST["num_del"]) && empty($_POST["str_password_del"])) {
            echo "パスワードを入力してください";
        }
        //編集機能
        //編集対象番号とパスワードが入力された場合
        elseif (!empty($_POST["num_edit"]) && !empty($_POST["str_password_edit"])) {
            //データの受け取り
            $num_edit = $_POST["num_edit"];
            $str_password_edit = $_POST["str_password_edit"];
            //投稿フォームに表示させるため，抽出する
            $sql = 'SELECT * FROM mission_5';
            $stmt = $pdo -> query($sql);
            $results = $stmt -> fetchAll();
            foreach ($results as $row){
                //$rowの中にはテーブルのカラム名が入る
                //条件分岐
                //idとpasswordが正しい場合
                if ($row['id'] == $num_edit && $row['password'] == $str_password_edit){
                    //投稿フォームに表示する変数
                    $name_edit = $row['name'];
                    $comment_edit = $row['comment'];
                    $password_edit = $row['password'];
                }
                //パスワードが誤りの場合
                elseif ($row['id'] == $num_edit && $row['password'] != $str_password_edit){
                    echo "パスワードが誤りです";
                }
            }
        }
        //編集機能の続き
        elseif (!empty($_POST["num_edited"])){
            //データの受け取り
            $num_edited = $_POST["num_edited"];
            $name = $_POST["str_name"];
            $comment = $_POST["str_comment"];
            $date = date("Y/m/d H:i:s");//date関数
            $password = $_POST["str_password"];
            $sql = 'UPDATE mission_5 SET name=:name, comment=:comment, date=:date, password=:password WHERE id=:id';
            $stmt = $pdo -> prepare($sql);
            $stmt -> bindParam(':name', $name, PDO::PARAM_STR);
            $stmt -> bindParam(':comment', $comment, PDO::PARAM_STR);
            $stmt -> bindParam(':date', $date, PDO::PARAM_STR);
            $stmt -> bindParam(':password', $password, PDO::PARAM_STR);
            $stmt -> bindParam(':id', $num_edited, PDO::PARAM_INT);
            $stmt -> execute();
        }
        //パスワードが入力されていない場合
        elseif (!empty($_POST["num_edit"]) && empty($_POST["str_password_edit"])){
            echo "パスワードを入力してください";
        }
    ?>
    
    <!--入力フォーム-->
    <form action="" method="post">
        投稿<br>
        <input type="text" name="str_name" value="<?php echo htmlspecialchars($name_edit, ENT_QUOTES, 'UTF-8'); ?>" placeholder="名前">
        <input type="text" name="str_comment" value="<?php echo htmlspecialchars($comment_edit, ENT_QUOTES, 'UTF-8'); ?>" placeholder="コメント">
        <input type="text" name="str_password" value="<?php echo htmlspecialchars($password_edit, ENT_QUOTES, 'UTF-8'); ?>" placeholder="パスワード">
        <input type="submit" value="送信">
        <br>
        削除<br>
        <input type="num" name="num_del" placeholder="削除対象番号">
        <input type="text" name="str_password_del" placeholder="パスワード">
        <input type="submit" value="削除">
        <br>
        編集<br>
        <input type="num" name="num_edit" placeholder="編集対象番号">
        <input type="text" name="str_password_edit" placeholder="パスワード">
        <input type="submit" value="編集">
        <br>
        <input type="hidden" name="num_edited" value="<?php echo htmlspecialchars($num_edit, ENT_QUOTES, 'UTF-8'); ?>">
    </form>

    <h1>投稿</h1>
    <?php
        /*
        //テーブル一覧を表示
        $sql ='SHOW TABLES';
        $result = $pdo -> query($sql);
        foreach ($result as $row){
            echo $row[0];
            echo '<br>';
        }
        echo "<hr>";
        */

        /*
        //テーブルの構成詳細を確認
        $sql = 'SHOW CREATE TABLE mission_5';
        $result = $pdo -> query($sql);
        foreach ($result as $row){
            echo $row[1];
        }
        echo "<hr>";
        */
        
        //既存の投稿を表示させる．
        $sql = 'SELECT * FROM mission_5';
        $stmt = $pdo -> query($sql);
        $results = $stmt -> fetchAll();
        foreach ($results as $row){
            //$rowの中にはテーブルのカラム名が入る
            echo "投稿番号: " . $row['id']. '<br>';
            echo "投稿者: " . $row['name']. '<br>';
            echo "コメント: " . $row['comment']. '<br>';
            echo "投稿日時: " . $row['date']. '<br>';
            echo "パスワード: " . $row['password']. '<br>';
            echo "<hr>";
        }
        
    ?>
    
    <!--トップへ戻るボタン -->
    <a href="#" class="top-link">トップヘ戻る</a>

</body>
</html>