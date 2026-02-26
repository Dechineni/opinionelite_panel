<?php
include('config.php');
include('header.php');
?>

<style>
.complete-wrap {
  max-width: 900px;
  margin: 0 auto;
  padding: 20px;
}

.complete-card {
  background: #111;
  border: 1px solid #444;
  border-radius: 14px;
  padding: 28px 22px;
  box-shadow: 0 0 18px rgba(255, 153, 0, 0.15);
  text-align: center;
}

.complete-title {
  color: #ff9900;
  font-weight: 700;
  font-size: 22px;
  margin-bottom: 10px;
}

.complete-msg {
  color: #ddd;
  font-size: 15px;
  margin: 0;
}

.complete-sub {
  color: #aaa;
  font-size: 13px;
  margin-top: 10px;
}

.btn-orange {
  display: inline-block;
  margin-top: 18px;
  padding: 10px 18px;
  background: #ff9900;
  color: #000;
  border-radius: 8px;
  text-decoration: none;
  font-weight: 700;
}
.btn-orange:hover { background: #e68a00; color: #000; }

.ref {
  margin-top: 12px;
  color: #666;
  font-size: 12px;
}
</style>

<div class="complete-wrap">
  <div class="complete-card">
    <div class="complete-title">Survey Completed</div>
    <p class="complete-msg">Thank you for completing the survey, your reward will be added to your account in 7 days.</p>

    <?php
      $pid = isset($_GET['pid']) ? trim($_GET['pid']) : '';
      if ($pid !== '') {
        echo '<div class="ref">Ref: ' . htmlspecialchars($pid) . '</div>';
      }
    ?>

    <a class="btn-orange" href="surveys.php">Go to Surveys</a>
  </div>
</div>

<?php include('footer.php'); ?>