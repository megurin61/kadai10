<?php
// MySQLデータベースへの接続
$servername = "mysql3101.db.sakura.ne.jp";
$username = "fabulousjapanese";  // phpMyAdminで使っているユーザー名
$password = "";  // phpMyAdminで設定したパスワード
$dbname = "fabulousjapanese_gs_kadai07";

$conn = new mysqli($servername, $username, $password, $dbname);

// 接続エラー確認
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// ブックマークの追加処理
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $conn->real_escape_string($_POST['title']);
    $url = $conn->real_escape_string($_POST['url']);
    $rating = (int)$_POST['rating']; // 評価を取得

    if (!empty($title) && !empty($url) && $rating >= 1 && $rating <= 5) {
        $sql = "INSERT INTO bookmarks (title, url, rating) VALUES ('$title', '$url', $rating)";
        if ($conn->query($sql) === TRUE) {
            echo "新しいブックマークが追加されました！";
        } else {
            echo "エラー: " . $sql . "<br>" . $conn->error;
        }
    } else {
        echo "タイトル、URL、評価を正しく入力してください。";
    }
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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ブックマークアプリ</title>
    <link rel="stylesheet" href="style.css"> <!-- CSSを外部から読み込み -->
</head>
<body>
    <div class="container">
        <h1 class="title">ブックマークアプリ</h1>

        <!-- ブックマーク追加フォーム -->
        <form method="post" action="">
            <input type="text" name="title" placeholder="タイトル" required>
            <input type="url" name="url" placeholder="URL" required>
            <label for="rating">評価:</label>
            <select name="rating" id="rating" required>
                <option value="1">★1</option>
                <option value="2">★2</option>
                <option value="3">★3</option>
                <option value="4">★4</option>
                <option value="5">★5</option>
            </select>
            <button type="submit">追加</button>
        </form>

        <!-- ブックマーク一覧表示 -->
        <ul class="bookmark-list">
            <?php if ($result->num_rows > 0): ?>
                <?php while($row = $result->fetch_assoc()): ?>
                    <li>
                        <a href="<?php echo $row['url']; ?>" target="_blank"><?php echo $row['title']; ?></a>
                        <span>評価: <?php echo str_repeat('★', $row['rating']); ?></span> <!-- 評価を表示 -->
                        <a href="?delete=<?php echo $row['id']; ?>" class="delete-btn">削除</a>
                    </li>
                <?php endwhile; ?>
            <?php else: ?>
                <li>まだブックマークがありません。</li>
            <?php endif; ?>
        </ul>
    </div>
</body>
</html>

<?php
$conn->close();
?>
