<?php
    // データベース接続設定ファイルを読み込み
    require 'config.php';

    // セッションを開始
    session_start();

    // フォームが送信されたときの処理
    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        //入力されたニックネーム、パスワードを取得
        $nickname = $_POST['nickname'];
        $password = $_POST['password'];

        // 入力されたニックネームに該当するユーザーをデータベースから取得
        $stmt = $pdo->prepare("SELECT * FROM users WHERE nickname = ?");
        $stmt->execute([$nickname]);
        $user = $stmt->fetch();

        // ユーザーが存在し、パスワードが一致する場合
        if ($user && $user['password'] === $password) {
            $_SESSION['user_id'] = $user['user_id']; // セッションにユーザーIDを保存
            header("Location: home.php"); // ホーム画面にリダイレクト
            exit;
        } else {
            // ログイン失敗時のエラーメッセージ
            $error = "ユーザー名またはパスワードが間違っています。";
        }
    }
?>

<!-- ログインフォーム -->
<form method="post">
    <label>ユーザー名: <input type="text" name="nickname"></label><br>
    <label>パスワード: <input type="password" name="password"></label><br>
    <button type="submit">ログイン</button><br>
    <p><a href="register.php">新規登録はこちら</a></p>

    <!-- エラーメッセージが存在する場合 -->
    <?php if (isset($error)) echo "<p>$error</p>"; ?> 
</form>
