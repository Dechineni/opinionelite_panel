<?php
include_once 'config.php';

$type = $_POST['type'] ?? '';
$username = $_POST['username'] ?? '';

$checkQuery = "SELECT COUNT(*) as total FROM rewards WHERE user = '$username' AND profile = '$type'";
$checkResult = $db->query($checkQuery);
$row = $checkResult->fetch_assoc();

if ($row['total'] > 0) {
    echo "<div class='alert alert-info'>You have already submitted answers for the <strong>$type</strong> profile.</div>";
    exit;
}


$type = mysqli_real_escape_string($db, $type);
$username = mysqli_real_escape_string($db, $username);

$output = '<form id="answerForm">';
$output .= '<input type="hidden" name="username" id="hidden-username" value="' . htmlspecialchars($username) . '" />';
$output .= '<input type="hidden" name="profile" id="hidden-profile" value="' . htmlspecialchars($type) . '" />';

// Fetch questions for the selected profile
$query = "SELECT * FROM question_answers WHERE profile = '$type'";
$result = $db->query($query);

if ($result->num_rows > 0) {
    $i = 1;
    while ($row = $result->fetch_assoc()) {
        $qid = $row['question_id'];
        $question = htmlspecialchars($row['question']);
        $answers = json_decode($row['answers'], true);

        // Fetch saved answer for this question and user
        $savedAnswer = '';
        $stmt = $db->prepare("SELECT answer FROM user_answers WHERE user_id = ? AND question_id = ? AND profile = ?");
        $stmt->bind_param("sss", $username, $qid, $type);
        $stmt->execute();
        $stmt->bind_result($savedAnswerResult);
        if ($stmt->fetch()) {
            $savedAnswer = htmlspecialchars($savedAnswerResult);
        }
        $stmt->close();

        // Render question and answer input
        $output .= '<div class="mb-4">';
        $output .= "<label for='question_$qid'>$i. <strong>$question</strong></label><br>";
        $output .= "<input list='datalist_$qid' id='question_$qid' name='answers[$qid]' class='form-control' value='$savedAnswer' placeholder=''>";

        $output .= "<datalist id='datalist_$qid'>";
        if (!empty($answers)) {
            foreach ($answers as $ans) {
                $option = htmlspecialchars($ans['answer']);
                $output .= "<option value=\"$option\">";
            }
        }
        $output .= "</datalist>";
        $output .= '</div>';
        $i++;
    }

    $output .= '<button type="submit" class="btn btn-primary">Submit Answers</button>';
    $output .= '</form>';
} else {
    $output .= "<p>No questions found for this profile.</p>";
}

echo $output;
?>

<!-- Populate hidden username from localStorage -->
<script>
  const username = localStorage.getItem('username');
  if (username) {
    document.getElementById('hidden-username').value = username;
  }
</script>
