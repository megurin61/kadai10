<?php
session_start();

// ユーザーがログインしていない場合、ログインページにリダイレクト
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// データベース接続
$servername = "mysql3101.db.sakura.ne.jp";
$username = "fabulousjapanese";
$password = "";
$dbname = "fabulousjapanese_gs_kadai07";

$conn = new mysqli($servername, $username, $password, $dbname);

// 接続エラー確認
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// ブックマークの削除処理
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $conn->query("DELETE FROM bookmarks WHERE id = $id");
}

// ブックマークの一覧取得
$sql = "SELECT * FROM bookmarks ORDER BY created_at DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>管理画面</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1 class="title">管理画面</h1>
        <p>ようこそ、<?php echo htmlspecialchars($_SESSION['username']); ?> さん！</p>
        <a class="logout" href="logout.php">ログアウト</a>

        <h2>ブックマーク一覧</h2>

        <table class="bookmark-table">
            <thead>
                <tr>
                    <th>本のタイトル</th>
                    <th>URL</th>
                    <th>評価</th>
                    <th>操作</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['title']); ?></td>
                        <td><a href="<?php echo htmlspecialchars($row['url']); ?>" target="_blank"><?php echo htmlspecialchars($row['url']); ?></a></td>
                        <td><?php echo htmlspecialchars($row['rating']); ?> / 5</td>
                        <td><a class="delete-btn" href="?delete=<?php echo $row['id']; ?>">削除</a></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
