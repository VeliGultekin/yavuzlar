<?php

function login($nickname,$passwd){

    session_start();
    include "db.php";
    $query = "SELECT id, passwd,role FROM users WHERE nickname = :username";
    $statement = $pdo->prepare($query);
    $statement->bindParam(':username', $nickname);
    $statement->execute();
    $user = $statement->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        if ($passwd === $user['passwd']) {
            $_SESSION['loggedin'] = true;
            $_SESSION['username'] = $nickname; 
            $_SESSION['user_id'] = $user['id']; 
            $_SESSION['user_role'] = $user['role'];
            

            header("location: index.php");
        } else {

           echo' <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Document</title>
        </head>
        <body>
            <script>alert("Kullanıcı Adı Şifre Yanlış!")
        window.location.href = "login.php";</script>
            </swindow.location.href>
        </body>
        </html>';
        }
    } else {

        echo '<!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Document</title>
        </head>
        <body>
            <script>alert("Kullanıcı Bulunamadı!")
            window.location.href = "login.php";
            </script>
        </body>
        </html>';
        
    }

}



?>