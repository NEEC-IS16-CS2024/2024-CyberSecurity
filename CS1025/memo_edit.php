<?php
    // データベース接続設定ファイルを読み込み
    require 'config.php';

    // セッションを開始
    session_start(); 

    // メモIDをGETパラメータから取得
    $id = $_GET['id'];

    // フォームが送信されたときの処理
    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        // 更新内容を取得
        $title = $_POST['title'];
        $content = $_POST['content'];
        
        // データベース更新処理
        $stmt = $pdo->prepare("UPDATE posts SET post_title = ?, post_text = ?, created_at = NOW() WHERE post_id = ?");
        $stmt->execute([$title, $content, $id]);
        
        header("Location: memo_detail.php?id=$id"); // メモ詳細画面にリダイレクト
        exit;
    } else {
        // 編集対象のメモを取得
        $stmt = $pdo->prepare("SELECT * FROM posts WHERE post_id = ?");
        $stmt->execute([$id]);
        $memo = $stmt->fetch();
    }
?>

<!-- メモ編集フォーム -->
<form method="post">
    <label>タイトル: <input type="text" name="title" value="<?= htmlspecialchars($memo['post_title']) ?>"></label><br>
    <label>内容: <textarea name="content"><?= htmlspecialchars($memo['post_text']) ?></textarea></label><br>
    <button type="submit">更新</button>
</form>
