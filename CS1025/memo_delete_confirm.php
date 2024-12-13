<?php
    // データベース接続設定ファイルを読み込み
    require 'config.php';

    // セッションを開始
    session_start();

    // メモIDをGETパラメータから取得
    $id = $_GET['id'];

    // フォームが送信されたとき（削除実行時）の処理
    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        //該当メモをデータベースから削除
        $stmt = $pdo->prepare("DELETE FROM posts WHERE post_id = ?");
        $stmt->execute([$id]);

        header("Location: home.php"); // ホーム画面にリダイレクト
        exit;
    }
?>

<!-- 削除確認フォーム -->
<p>このメモを削除してもよろしいですか？</p>
<form method="post">
    <button type="submit">OK</button>
    <a href="memo_detail.php?id=<?= $id ?>">キャンセル</a>
</form>
