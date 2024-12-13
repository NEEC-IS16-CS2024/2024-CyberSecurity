<?php
    // データベース接続設定ファイルを読み込み
    require 'config.php';

    // セッションを開始
    session_start(); 

    // ログイン認証
    if(isset($_SESSION['user_id'])){

        // メモIDをGETパラメータから取得
        $id = $_GET['id'];

        // 編集対象のメモを取得
        $stmt = $pdo->prepare("SELECT * FROM posts WHERE post_id = ?");
        $stmt->execute([$id]);
        $memo = $stmt->fetch();
    
        // フォームが送信されたときの処理
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
            // 更新内容を取得
            $title = $_POST['title'];
            $content = $_POST['content'];

            // 空白チェック
            if (empty($title) || empty($content)) {
                $error = "タイトルと内容の両方を入力してください。";
            } else {
                // データベース更新処理
                $stmt = $pdo->prepare("UPDATE posts SET post_title = ?, post_text = ?, created_at = NOW() WHERE post_id = ?");
                $stmt->execute([$title, $content, $id]);
                
                header("Location: memo_detail.php?id=$id"); // メモ詳細画面にリダイレクト
                exit;
            }
        }

    } else {
        echo "ログインしていません<br />";
        echo '<a href="../CS1025/index.php">ログイン画面へ</a>';
    }
?>

<!-- メモ編集フォーム -->
<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="UTF-8">
        <title>メモ一覧</title>
    </head>
    <body>
        <?php if(isset($_SESSION['id'])) : ?>
            <form method="post">
                <label>タイトル: <input type="text" name="title" value="<?= htmlspecialchars($memo['post_title']) ?>"></label><br>
                <label>内容: <textarea name="content"><?= htmlspecialchars($memo['post_text']) ?></textarea></label><br>
                <button type="submit">更新</button>
                <?php if (isset($error)) echo "<p>$error</p>"; ?>
            </form>
        <?php endif ?>
    </body>
</html>
