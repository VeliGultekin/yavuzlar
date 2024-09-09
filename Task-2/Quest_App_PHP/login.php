<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Giriş Yap</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <form action="signQuery.php" method="post">
            <h2>Lütfen Giriş Yapınız.</h2>
        <input  class="loginInput" type="text" name="username"  placeholder="Kullanıcı Adı" style="width: 200px; height: 25px; " required><br><br>
        <input class="loginInput"  type="password" name="password" placeholder="Şifre" style="width: 215px; height: 43px;  " required><br><br>
        <button type="submit" style="width: 215px;" >Giriş Yap</button>
        </form>
    </div>
</body>
</html>