<?php
// データベース接続設定ファイルを読み込み
require 'config.php';

// セッションを開始
session_start();

// ログインチェック
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php"); // 未ログインならログイン画面にリダイレクト
    exit;
}

// ログアウト処理
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['logout'])) {
    session_destroy(); // セッションを破棄
    header("Location: index.php"); // ログイン画面にリダイレクト
    exit;
}

// ログインユーザーのIDを取得
$user_id = $_SESSION['user_id'];

// データベースからユーザーのメモを取得
$stmt = $pdo->prepare("SELECT * FROM posts WHERE user_id = ? ORDER BY created_at DESC");
$stmt->execute([$user_id]);
$memos = $stmt->fetchAll(); // メモ一覧を取得
?>

<!-- メモ一覧画面 -->
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <title>ホーム</title>
</head>

<body>
    <!-- ログアウトフォーム -->
    <form method="post">
        <button type="submit" name="logout">ログアウト</button>
    </form>

    <!-- 新規メモ作成リンク -->
    <a href="memo_create.php">新規メモ作成</a>

    <!-- メモ一覧表示 -->
    <h2>メモ一覧</h2>
    <?php if (count($memos) > 0): ?>
        <ul>
            <!-- メモタイトルをリンクとして表示 -->
            <?php foreach ($memos as $memo): ?>
                <li>
                    <a href="memo_detail.php?id=<?= htmlspecialchars($memo['post_id'], ENT_QUOTES, 'UTF-8') ?>">
                        <?= htmlspecialchars($memo['post_title'], ENT_QUOTES, 'UTF-8') ?>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p>メモがありません。</p>
    <?php endif; ?>
</body>

</html>