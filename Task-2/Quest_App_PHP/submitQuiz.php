<?php
require 'functions/db.php';
session_start();

$user_id = $_SESSION['user_id'];
$score = 0;
$answers = $_SESSION['answers'];

$stmt = $pdo->query("SELECT id, correct_answer FROM Questions");
$questions = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach ($questions as $index => $question) {
    $question_id = $question['id'];
    $correct_answer = $question['correct_answer'];

    $user_answer = isset($answers[$index]) ? $answers[$index] : -1; 

   
    if ($user_answer == $correct_answer) {
        $is_correct = 1;
        $score++;
    } else {
        $is_correct = 0;
    }

    $stmt = $pdo->prepare("INSERT INTO Submissions (user_id, question_id, is_correct) VALUES (:user_id, :question_id, :is_correct)");
    $stmt->execute([
        ':user_id' => $user_id,
        ':question_id' => $question_id,
        ':is_correct' => $is_correct
    ]);
}

echo "Quiz Result: $score / " . count($questions);

unset($_SESSION['current_question']);
unset($_SESSION['answers']);
?>

<script>
alert("Quiz Result: <?php echo $score; ?> / <?php echo count($questions); ?>");
window.location.href = "scoreboard.php";
</script>
