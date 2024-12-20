<?php
// データベース接続設定ファイルを読み込み
require 'config.php';

// セッションを開始
session_start();

// フォームが送信された場合
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // 入力されたニックネームとパスワードを取得
    $nickname = trim($_POST['nickname']);
    $password = trim($_POST['password']);

    // 入力内容の長さチェック
    if (strlen($nickname) > 20 || strlen($password) > 20) {
        $error = "ユーザー名またはパスワードは20文字以下である必要があります。";
    } else {
        // データベースから入力されたニックネームのユーザー情報を取得
        $stmt = $pdo->prepare("SELECT * FROM users WHERE nickname = ?");
        $stmt->execute([$nickname]);
        $user = $stmt->fetch();

        // ユーザーが存在し、パスワードが一致する場合
        if ($user && password_verify($password, $user['password'])) {
            // セッションにユーザーIDを保存
            $_SESSION['user_id'] = $user['user_id'];

            // ホーム画面にリダイレクト
            header("Location: home.php");
            exit;
        } else {
            // 認証失敗時のエラーメッセージ
            $error = "ユーザー名またはパスワードが間違っています。";
        }
    }
}
?>

<!-- ログインフォーム -->
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <title>ログイン</title>
</head>

<body>
    <h2>ログイン</h2>
    <form method="post">
        <label>ユーザー名: <input type="text" name="nickname" required maxlength="20"></label><br>
        <label>パスワード: <input type="password" name="password" required maxlength="20"></label><br>
        <button type="submit">ログイン</button>
    </form>
    <p><a href="register.php">新規登録はこちら</a></p>

    <!-- エラーメッセージが存在する場合に表示 -->
    <?php if (isset($error)): ?>
        <p style="color: red;"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></p>
    <?php endif; ?>
</body>

</html>