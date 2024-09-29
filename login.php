<?php
session_start();
$servername = "";
$username = "";
$password = "";
$dbname = "";

$conn = new mysqli($servername, $username, $password, $dbname);

// 接続エラー確認
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $conn->real_escape_string($_POST['username']);
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE username='$username'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            // ログイン成功、セッションにユーザー情報を保存
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            header('Location: admin.php');
            exit();
        } else {
            $error = "パスワードが間違っています。";
        }
    } else {
        $error = "ユーザー名が存在しません。";
    }
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>ログイン</title>
    <link rel="stylesheet" href="style.css">

</head>
<body>
    <h2>ログイン</h2>
    <?php if (isset($error)) echo "<p>$error</p>"; ?>
    <form method="POST" action="">
        <label for="username">ユーザー名:</label>
        <input type="text" name="username" required>
        <br>
        <label for="password">パスワード:</label>
        <input type="password" name="password" required>
        <br>
        <button type="submit">ログイン</button>
    </form>
</body>
</html>
