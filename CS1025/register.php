<?php
    // データベース接続設定ファイルを読み込み
    require 'config.php';

    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        // 入力された情報を取得
        $nickname = $_POST['nickname'];
        $password = $_POST['password'];

        // ユーザー名がすでに存在するかを確認
        $stmt = $pdo->prepare("SELECT * FROM users WHERE nickname = ?");
        $stmt->execute([$nickname]);
        $existingUser = $stmt->fetch();

        if ($existingUser) {
            $error = "このユーザー名は既に使用されています。";
        } else {
            // 新しいユーザーをデータベースに追加
            $stmt = $pdo->prepare("INSERT INTO users (nickname, password) VALUES (?, ?)");
            $stmt->execute([$nickname, $password]);

            // 登録完了後にログイン画面にリダイレクト
            header("Location: index.php");
            exit;
        }
    }
?>

<!-- 新規ユーザー登録フォーム -->
<h2>新規ユーザー登録</h2>
<form method="post">
    <label>ユーザー名（姓）: <input type="text" name="nickname" required></label><br>
    <label>パスワード　　　: <input type="password" name="password" required></label><br>
    <button type="submit">登録</button>

    <?php if (isset($error)) echo "<p>$error</p>"; ?>
</form>

<p><a href="index.php">ログイン画面に戻る</a></p>
