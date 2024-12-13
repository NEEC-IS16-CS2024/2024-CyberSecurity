<?php
    // データベース接続設定ファイルを読み込み
    require 'config.php';

    // セッションを開始
    session_start(); 

    // ログイン認証
    if(isset($_SESSION['id'])){

        // メモIDをGETパラメータから取得
        $id = $_GET['id'];
    
        // データベースからメモ情報を取得
        $stmt = $pdo->prepare("SELECT * FROM posts WHERE post_id = ?");
        $stmt->execute([$id]);
        $memo = $stmt->fetch();

    }else{
        echo "ログインしていません<br />";
        echo '<a href="../CS1025/index.php">ログイン画面へ</a>';
    }
?>

<!-- メモ詳細表示 -->
<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="UTF-8">
        <title>メモ一覧</title>
    </head>
    <body>
        <?php if(isset($_SESSION['id'])) : ?>
            <h2><?= htmlspecialchars($memo['post_title']) ?></h2>
            <p>作成日: <?= htmlspecialchars($memo['created_at']) ?></p>
            <p><?= nl2br(htmlspecialchars($memo['post_text'])) ?></p>
            <a href="memo_edit.php?id=<?= $memo['post_id'] ?>">編集</a>
            <a href="memo_delete_confirm.php?id=<?= $memo['post_id'] ?>">削除</a>
            <a href="home.php">ホームへ戻る</a>
        <?php endif ?>
    </body>
</html>
