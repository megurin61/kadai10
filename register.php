<?php
$servername = "mysql3101.db.sakura.ne.jp";
$username = "fabulousjapanese";
$password = "";
$dbname = "fabulousjapanese_gs_kadai07";

$conn = new mysqli($servername, $username, $password, $dbname);

// 接続エラー確認
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $conn->real_escape_string($_POST['username']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $sql = "INSERT INTO users (username, password) VALUES ('$username', '$password')";
    if ($conn->query($sql) === TRUE) {
        echo "新しいユーザーが作成されました！";
    } else {
        echo "エラー: " . $sql . "<br>" . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>ユーザー登録</title>
    <link rel="stylesheet" href="style.css">

</head>
<body>
    <h2>ユーザー登録</h2>
    <form method="POST" action="">
        <label for="username">ユーザー名:</label>
        <input type="text" name="username" required>
        <br>
        <label for="password">パスワード:</label>
        <input type="password" name="password" required>
        <br>
        <button type="submit">登録</button>
    </form>
</body>
</html>
