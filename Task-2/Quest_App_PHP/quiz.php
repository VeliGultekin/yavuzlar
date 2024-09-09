<?php
session_start();
require 'functions/db.php';

try {
    $stmt = $pdo->query("SELECT id, question, options, correct_answer FROM Questions");
    $questions = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Veritabanı hatası: " . $e->getMessage();
    exit;
}

$total_questions = count($questions);

if (!isset($_SESSION['current_question'])) {
    $_SESSION['current_question'] = 0;
    $_SESSION['answers'] = [];  // Store user answers
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['answer'])) {

    $_SESSION['answers'][$_SESSION['current_question']] = $_POST['answer'];

    $_SESSION['current_question']++;

    if ($_SESSION['current_question'] >= $total_questions) {
        header("Location: submitQuiz.php");
        exit;
    }
}

$current_question = $questions[$_SESSION['current_question']];
$options = json_decode($current_question['options'], true);
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quiz</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h1>Quiz</h1>
        <form method="post">
            <h3><?php echo "Question " . ($_SESSION['current_question'] + 1) . ": " . htmlspecialchars($current_question['question']); ?></h3>
            <?php foreach ($options as $index => $option): ?>
                <div>
                    <input type="radio" name="answer" value="<?php echo $index; ?>" required>
                    <label><?php echo htmlspecialchars($option); ?></label>
                </div>
            <?php endforeach; ?>

            <?php if ($_SESSION['current_question'] < $total_questions - 1): ?>
                <button type="submit">Next</button>
            <?php else: ?>
                <button type="submit">Finish</button>
            <?php endif; ?>
        </form>
    </div>
</body>
</html>
