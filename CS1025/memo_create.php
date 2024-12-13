<?php
    // データベース接続設定ファイルを読み込み
    require 'config.php';

    // セッションを開始
    session_start();

    if(isset($_SESSION['user_id'])){
        // フォームが送信されたときの処理
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $title = trim($_POST['title']); // 入力されたタイトルを取得し、前後の空白を除去
            $content = trim($_POST['content']); // 入力された内容を取得し、前後の空白を除去
    
            // タイトルと内容が空白でないかをチェック
            if (empty($title) || empty($content)) {
                // エラーメッセージをセット
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
    } else {
        echo "ログインしていません<br />";
        echo '<a href="../CS1025/index.php">ログイン画面へ</a>';
    }
?>

<!-- メモ新規作成フォーム -->
<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="UTF-8">
        <title>メモ一覧</title>
    </head>
    <body>
        <?php if(isset($_SESSION['user_id'])) : ?>
            <h2>新規メモ作成</h2>
            <form method="post">
                <label>タイトル: <input type="text" name="title" required></label><br>
                <label>内容: <textarea name="content" required></textarea></label><br>
                <button type="submit">作成</button>
                <?php if (isset($error)) echo "<p>$error</p>"; ?>
            </form>

            <p><a href="home.php">ホームへ戻る</a></p>
        <?php endif ?>
    </body>
</html>
