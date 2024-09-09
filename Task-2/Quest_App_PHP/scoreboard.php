<?php
require 'functions/db.php';


$stmt = $pdo->query("
    SELECT u.nickname, SUM(s.is_correct) AS score
    FROM Users u
    JOIN Submissions s ON u.id = s.user_id
    GROUP BY u.id
    ORDER BY score DESC
");

$scores = $stmt->fetchAll();
echo'<link rel="stylesheet" href="styles.css">';
echo'<div class="container">';
echo'<button onclick="anasayfa()">Ana Sayfa</button>';
echo "<h1>Scoreboard</h1>";
echo "<table border='1'>";
echo "<tr><th>Kullanıcı</th><th>Score</th></tr>";
foreach ($scores as $score) {
    echo "<tr><td>{$score['nickname']}</td><td>{$score['score']}</td></tr>";

}

echo "</table>";
echo'</div>';

echo'<script src="script.js"></script>';

?>
