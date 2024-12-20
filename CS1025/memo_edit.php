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

// 編集対象のメモを取得
$stmt = $pdo->prepare("SELECT * FROM posts WHERE post_id = ?");
$stmt->execute([$id]);
$memo = $stmt->fetch();

// メモが存在しない場合の処理
if (!$memo) {
    echo "指定されたメモが見つかりません。<br />";
    echo '<a href="home.php">ホームへ戻る</a>';
    exit;
}

// エラーメッセージを初期化
$error = "";

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
        // データベース更新処理
        $stmt = $pdo->prepare("UPDATE posts SET post_title = ?, post_text = ?, created_at = NOW() WHERE post_id = ?");
        $stmt->execute([$title, $content, $id]);

        // 更新後にメモ詳細画面にリダイレクト
        header("Location: memo_detail.php?id=$id");
        exit;
    }
}
?>

<!-- メモ編集フォーム -->
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <title>メモ編集</title>
</head>

<body>
    <h2>メモ編集</h2>
    <form method="post">
        <label>タイトル: <input type="text" name="title"
                value="<?= htmlspecialchars($memo['post_title'], ENT_QUOTES, 'UTF-8') ?>" maxlength="1000"
                required></label><br>
        <label>内容: <textarea name="content" maxlength="1000"
                required><?= htmlspecialchars($memo['post_text'], ENT_QUOTES, 'UTF-8') ?></textarea></label><br>
        <button type="submit">更新</button>
        <?php if (!empty($error))
            echo "<p style='color:red;'>$error</p>"; ?>
    </form>

    <p><a href="home.php">ホームへ戻る</a></p>
</body>

</html>