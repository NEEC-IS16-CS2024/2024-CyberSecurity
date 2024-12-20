<?php
// データベース接続設定ファイルを読み込み
require 'config.php';

// セッションを開始
session_start();

// ログインしていない場合はログイン画面にリダイレクト
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}


// ログアウト処理
if (isset($_POST['logout'])) {
    session_destroy(); // セッションを破棄
    header("Location: index.php"); // ログイン画面にリダイレクト
    exit;
}

// ログインユーザーIDに基づいてデータベースからメモを取得
$user_id = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT * FROM posts WHERE user_id = ?");
$stmt->execute([$user_id]);
$memos = $stmt->fetchAll(); // ユーザーのメモ一覧を取得
?>

<!-- メモ一覧画面 -->
<form method="post">
    <button type="submit" name="logout">ログアウト</button>
</form>
<a href="memo_create.php">新規メモ作成</a>
<h2>メモ一覧</h2>
<ul>
    <!-- メモタイトルをリンクとして表示 -->
    <?php foreach ($memos as $memo): ?>
        <li>
            <a href="memo_detail.php?id=<?= $memo['post_id'] ?>"><?= htmlspecialchars($memo['post_title']) ?></a>
        </li>
    <?php endforeach; ?>
</ul>