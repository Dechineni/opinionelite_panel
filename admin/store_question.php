<?php
include('config.php');

if (isset($_POST['btn'])) {
    $qid = $_POST['q_id'];
    $qname = $_POST['q_name'];
    $ques = $_POST['question'];
    $answers = $_POST['answer'];
    $precodes = $_POST['precode'];
    $profile=$_POST['profile'];
    $answerArray = [];

    // Combine answers with precodes
    for ($i = 0; $i < count($answers); $i++) {
        $answerArray[] = [
            'answer' => $answers[$i],
            'precode' => $precodes[$i]
        ];
    }

    $encodedAnswers = json_encode($answerArray); // JSON encode answer-precode pairs

    $sql = "INSERT INTO question_answers (question_id, name, question, answers,profile) 
            VALUES ('$qid', '$qname', '$ques', '$encodedAnswers','$profile')";

    if (mysqli_query($db, $sql)) {
        echo "<script>
        alert('Question Added Successfully');
        window.location.href='view_questions.php';
        </script>";
    } else {
        echo "Error: " . mysqli_error($db);
    }
}
?>
