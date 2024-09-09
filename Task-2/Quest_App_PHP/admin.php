<?php
require 'functions/db.php'; 

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $question = $_POST['question'];
    $options = json_encode($_POST['options']); 
    $correctAnswer = $_POST['correct_answer'];

    try {
        $stmt = $pdo->prepare("INSERT INTO Questions (question, options, correct_answer) VALUES (:question, :options, :correct_answer)");
        $stmt->execute([
            ':question' => $question,
            ':options' => $options,
            ':correct_answer' => $correctAnswer
        ]);

        echo '<script>alert("Soru Eklendi"); window.location.href = "admin.php";</script>';
    } catch (Exception $e) {
        echo "Soru eklenirken hata oluştu: " . $e->getMessage();
    }
} else {
    echo '<link rel="stylesheet" href="styles.css">
    <button onclick="anasayfa()">Ana Sayfa</button>
    <div class="container">
    <form method="POST" action="admin.php">
        Soru: <input type="text" name="question" required><br>
        A seçeneği: <input type="text" name="options[]" required><br>
        B seçeneği: <input type="text" name="options[]" required><br>
        C seçeneği: <input type="text" name="options[]" required><br>
        D seçeneği: <input type="text" name="options[]" required><br>
        Doğru Cevap (0-3 arasında bir sayı girin): <input type="number" name="correct_answer" min="0" max="3" required><br>
        <button type="submit">Soru Ekle</button>
    </form>
    <script src="script.js"></script>
    </div>';
}
?>
