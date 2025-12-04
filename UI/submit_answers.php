<?php
include_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['answers']) && isset($_POST['username']) && isset($_POST['profile'])) {
    $answers = $_POST['answers'];
    $username = $_POST['username'];
    $profile = $_POST['profile']; // varchar

    foreach ($answers as $question_id => $answer) {
        if (!empty($answer)) {
            // Check if answer already exists for this user + question + profile
            $checkStmt = $db->prepare("SELECT id FROM user_answers WHERE user_id = ? AND question_id = ? AND profile = ?");
            $checkStmt->bind_param("sis", $username, $question_id, $profile);
            $checkStmt->execute();
            $checkStmt->store_result();

            if ($checkStmt->num_rows > 0) {
                // Update existing answer (if needed)
                $updateStmt = $db->prepare("UPDATE user_answers SET answer = ?, submitted_at = NOW() WHERE user_id = ? AND question_id = ? AND profile = ?");
                $updateStmt->bind_param("ssis", $answer, $username, $question_id, $profile);
                $updateStmt->execute();
                $updateStmt->close();
            } else {
                // Insert new answer
                $insertStmt = $db->prepare("INSERT INTO user_answers (user_id, question_id, answer, profile, submitted_at) VALUES (?, ?, ?, ?, NOW())");
                $insertStmt->bind_param("siss", $username, $question_id, $answer, $profile);
                $insertStmt->execute();
                $insertStmt->close();
            }

            $checkStmt->close();
        }
    }

    // Count total questions in this profile
    $totalQQuery = $db->prepare("SELECT COUNT(*) FROM question_answers WHERE profile = ?");
    $totalQQuery->bind_param("s", $profile);
    $totalQQuery->execute();
    $totalQQuery->bind_result($totalQuestions);
    $totalQQuery->fetch();
    $totalQQuery->close();

    // Count answered questions by user for this profile
    $answeredQQuery = $db->prepare("SELECT COUNT(*) FROM user_answers WHERE user_id = ? AND profile = ?");
    $answeredQQuery->bind_param("ss", $username, $profile);
    $answeredQQuery->execute();
    $answeredQQuery->bind_result($answeredCount);
    $answeredQQuery->fetch();
    $answeredQQuery->close();

    if ($answeredCount == $totalQuestions) {
        // Check if reward already given
        $checkReward = $db->prepare("SELECT id FROM rewards WHERE user = ? AND profile = ?");
        $checkReward->bind_param("ss", $username, $profile);
        $checkReward->execute();
        $checkReward->store_result();

        if ($checkReward->num_rows === 0) {
            $reward = 2;
            $rewardStmt = $db->prepare("INSERT INTO rewards (user, reward, profile) VALUES (?, ?, ?)");
            $rewardStmt->bind_param("sis", $username, $reward, $profile);
            $rewardStmt->execute();
            $rewardStmt->close();

            echo "Answers saved! 🎉 You earned $2 for completing the $profile profile.";
        } else {
            echo "Answers saved! (Reward already given for $profile profile)";
        }

        $checkReward->close();
    } else {
        echo "Answers saved! Complete all questions to earn $2 for this profile.";
    }
} else {
    echo "No answers submitted.";
}
?>
