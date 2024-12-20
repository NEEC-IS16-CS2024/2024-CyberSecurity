<?php
// データベース接続設定ファイルを読み込み
require 'config.php';

// セッションを開始
session_start();

// ログインチェック
if (!isset($_SESSION['user_id'])) {
    echo "ログインしていません<br />";
    echo '<a href="../CS1025/index.php">ログイン画面へ</a>';
    exit;
}

// メモIDをGETパラメータから取得
$id = $_GET['id'] ?? null;

// メモIDが指定されていない場合の処理
if (!$id) {
    echo "メモIDが指定されていません。<br />";
    echo '<a href="home.php">ホームへ戻る</a>';
    exit;
}

// データベースからメモ情報を取得
$stmt = $pdo->prepare("SELECT * FROM posts WHERE post_id = ?");
$stmt->execute([$id]);
$memo = $stmt->fetch();

// メモが見つからない場合の処理
if (!$memo) {
    echo "指定されたメモが見つかりません。<br />";
    echo '<a href="home.php">ホームへ戻る</a>';
    exit;
}
?>

<!-- メモ詳細表示 -->
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <title>メモ詳細</title>
</head>

<body>
    <h2><?= htmlspecialchars($memo['post_title'], ENT_QUOTES, 'UTF-8') ?></h2>
    <p>作成日: <?= htmlspecialchars($memo['created_at'], ENT_QUOTES, 'UTF-8') ?></p>
    <p><?= nl2br(htmlspecialchars($memo['post_text'], ENT_QUOTES, 'UTF-8')) ?></p>
    <a href="memo_edit.php?id=<?= urlencode($memo['post_id']) ?>">編集</a>
    <a href="memo_delete_confirm.php?id=<?= urlencode($memo['post_id']) ?>">削除</a>
    <a href="home.php">ホームへ戻る</a>
</body>

</html>