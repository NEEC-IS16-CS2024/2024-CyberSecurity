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

// フォームが送信されたとき（削除実行時）の処理
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // 該当メモをデータベースから削除
    $stmt = $pdo->prepare("DELETE FROM posts WHERE post_id = ?");
    $stmt->execute([$id]);

    // ホーム画面にリダイレクト
    header("Location: home.php");
    exit;
}
?>

<!-- 削除確認フォーム -->
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <title>削除確認</title>
</head>

<body>
    <p>このメモを削除してもよろしいですか？</p>
    <form method="post">
        <button type="submit">OK</button>
        <a href="memo_detail.php?id=<?= htmlspecialchars($id, ENT_QUOTES, 'UTF-8') ?>">キャンセル</a>
    </form>
</body>

</html>