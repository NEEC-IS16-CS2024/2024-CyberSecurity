<?php
// データベース接続設定ファイルを読み込み
require 'config.php';

// セッションを開始
session_start();

// エラーメッセージを初期化
$error = "";

// ログインしていない場合はリダイレクト
if (!isset($_SESSION['user_id'])) {
    echo "ログインしていません<br />";
    echo '<a href="../CS1025/index.php">ログイン画面へ</a>';
    exit;
}

// フォームが送信されたときの処理
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = trim($_POST['title']); // 入力されたタイトルを取得し、前後の空白を除去
    $content = trim($_POST['content']); // 入力された内容を取得し、前後の空白を除去

    // 入力内容の検証
    if (strlen($title) > 1000 || strlen($content) > 1000) {
        $error = "タイトルまたは内容は1000文字以下である必要があります。";
    } elseif (empty($title) || empty($content)) {
        $error = "タイトルと内容の両方を入力してください。";
    } else {
        // メモをデータベースに挿入
        $stmt = $pdo->prepare("INSERT INTO posts (user_id, post_title, post_text, created_at) VALUES (?, ?, ?, NOW())");
        $stmt->execute([$_SESSION['user_id'], $title, $content]);

        // 登録後にホーム画面にリダイレクト
        header("Location: home.php");
        exit;
    }
}
?>

<!-- メモ新規作成フォーム -->
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <title>メモ新規作成</title>
</head>

<body>
    <h2>新規メモ作成</h2>
    <form method="post">
        <label>タイトル: <input type="text" name="title" required maxlength="1000"></label><br>
        <label>内容: <textarea name="content" required maxlength="1000"></textarea></label><br>
        <button type="submit">作成</button>
        <?php if (!empty($error))
            echo "<p style='color:red;'>$error</p>"; ?> <!-- エラーメッセージの表示 -->
    </form>

    <p><a href="home.php">ホームへ戻る</a></p>
</body>

</html>