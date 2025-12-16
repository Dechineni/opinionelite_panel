<?php
include('header.php');
include('config.php');

$successMessage = '';
$errorMessage   = '';

// Handle POST (change password)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['change_password'])) {

    // Username from hidden field (filled from localStorage on the client)
    $username        = isset($_POST['username']) ? trim($_POST['username']) : '';
    $newPassword     = $_POST['newPassword']      ?? '';
    $confirmPassword = $_POST['confirmPassword']  ?? '';

    if ($username === '') {
        $errorMessage = 'Unable to identify user. Please sign in again.';
    } else {
        // Extra safety: check user_type in DB, allow only "direct"
        $stmt = $db->prepare("SELECT user_type FROM signup WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->bind_result($userTypeFromDb);
        $stmt->fetch();
        $stmt->close();

        if ($userTypeFromDb !== 'direct') {
            $errorMessage = 'Password change is only available for users who signed up directly with Opinion Elite.';
        } elseif ($newPassword === '' || $confirmPassword === '') {
            $errorMessage = 'Please enter and confirm your new password.';
        } elseif ($newPassword !== $confirmPassword) {
            $errorMessage = 'New password and confirm password do not match.';
        } elseif (strlen($newPassword) < 8) {
            $errorMessage = 'Password must be at least 8 characters long.';
        } else {
            // All good -> hash and update
            $hashedPassword = password_hash($newPassword, PASSWORD_BCRYPT);

            $update = $db->prepare("UPDATE signup SET password = ? WHERE username = ?");
            $update->bind_param("ss", $hashedPassword, $username);

            if ($update->execute() && $update->affected_rows > 0) {
                $successMessage = 'Your password has been changed successfully.';
            } else {
                $errorMessage = 'Failed to update password. Please try again.';
            }
            $update->close();
        }
    }
}
?>

<div>
  <div class="container py-5">
    <div class="card-data text-center">

      <!-- Success / Error banners -->
      <?php if (!empty($successMessage)): ?>
        <div
          class="mb-4"
          style="
            max-width: 480px;
            margin: 0 auto 24px auto;
            padding: 10px 16px;
            border-radius: 8px;
            background: rgba(241,170,63,0.12);
            border: 1px solid #fd8e19;
            color: #ffe7bf;
            font-size: 14px;
            text-align: left;
          "
        >
          <?php echo htmlspecialchars($successMessage); ?>
        </div>
      <?php endif; ?>

      <?php if (!empty($errorMessage)): ?>
        <div
          class="mb-4"
          style="
            max-width: 480px;
            margin: 0 auto 24px auto;
            padding: 10px 16px;
            border-radius: 8px;
            background: rgba(248,113,113,0.12);
            border: 1px solid #f97373;
            color: #fecaca;
            font-size: 14px;
            text-align: left;
          "
        >
          <?php echo htmlspecialchars($errorMessage); ?>
        </div>
      <?php endif; ?>

      <h5 class="fw-bold">Change your password</h5>
      <p class="text-dark small mb-4">Enter a new password below to change your password</p>

      <form method="post" id="changePasswordForm">
        <!-- hidden username filled from localStorage -->
        <input type="hidden" name="username" id="usernameHidden" value="">

        <div class="mb-3 text-start">
          <label for="newPassword" class="form-label">New password</label>
          <input
            type="password"
            class="form-control"
            id="newPassword"
            name="newPassword"
            placeholder="New password"
            required
          >
        </div>

        <div class="mb-4 text-start">
          <label for="confirmPassword" class="form-label">Confirm password</label>
          <input
            type="password"
            class="form-control"
            id="confirmPassword"
            name="confirmPassword"
            placeholder="Confirm your password"
            required
          >
        </div>

        <button type="submit" name="change_password" class="btn btn-orange w-100">
          CHANGE PASSWORD
        </button>
      </form>
    </div>
  </div>
</div>

<script>
  (function () {
    // Read username + userType from localStorage
    const storedUsername = localStorage.getItem('username');
    const userType       = localStorage.getItem('userType');

    // If not logged in at all, send back to login
    if (!storedUsername) {
      window.location.href = '../login.php';
      return;
    }

    // Fill hidden username for server-side update
    const hiddenUsernameInput = document.getElementById('usernameHidden');
    if (hiddenUsernameInput) {
      hiddenUsernameInput.value = storedUsername;
    }

    // If NOT a direct user, redirect away from this page
    if (userType && userType !== 'direct') {
      // Option 1: Simple redirect to dashboard
      window.location.href = 'index.php';
    }
  })();
</script>

<?php
include('footer.php');
?>
