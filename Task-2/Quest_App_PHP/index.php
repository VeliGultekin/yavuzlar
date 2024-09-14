<?php session_start();


if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {

    header('Location: login.php');
    exit;
}
$user_role = $_SESSION['user_role'];
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Anasayfa</title>
    <link rel="stylesheet" href="styles.css">
</head>

<body>
    <div class="container">


        <?php if ($user_role === 'admin'): ?>
            <button onclick="admin()">Admin Paneli</button>
            <br>
            <button onclick="soru()">Quiz'e Başla</button>
            <br>

            <button onclick="score()">Scoreboard</button>
            <br>
            <button onclick="logout()">Çıkış Yap</button>
            <br> <?php endif; ?>

        <?php if ($user_role === 'user'): ?>
            <button onclick="soru()">Quiz'e Başla</button>
            <br>

            <button onclick="score()">Scoreboard</button>
            <br>
            <button onclick="logout()">Çıkış Yap</button>
            <br>
        <?php endif; ?>
    </div>
    <script src="script.js"></script>
</body>

</html>
