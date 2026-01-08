<?php
// UI/settings.php
// NOTE: The UI area uses browser localStorage to track login (see UI/footer.php).
// Do NOT enforce PHP session-based login here, otherwise Settings will always
// redirect to signin.php even when the user is logged in.

require_once 'config.php';

$deleteError = '';

// Handle delete request
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['confirm_delete'])) {
    $currentUsername = trim($_POST['username'] ?? '');

    if ($currentUsername === '') {
        $deleteError = 'Missing username. Please refresh and try again.';
    } else {
        // Delete from signup table (all user_types)
        $stmt = $db->prepare("DELETE FROM signup WHERE username = ?");
        $stmt->bind_param("s", $currentUsername);

        if ($stmt->execute()) {
            $stmt->close();

            // TODO (optional): also delete related rows from other tables (user_answers, rewards, etc.)

            // Clear browser localStorage + redirect out of the members area
            echo "<script>
                try {
                    localStorage.removeItem('passwordVerified');
                    localStorage.removeItem('username');
                    localStorage.removeItem('userType');
                } catch (e) {}
                alert('Your account has been deleted successfully.');
                window.location.href = '../index.php';
            </script>";
            exit;
        } else {
            $deleteError = 'Failed to delete your account. Please try again.';
            $stmt->close();
        }
    }
}

// Show the page
include('header.php');
?>

<div>
  <div class="container py-5">
      <h3 class="text-center fw-bold" style="color: #ff9900;">Settings</h3>
    <div class="row justify-content-center mt-5">
    <div class="col-md-10 shadow rounded overflow-hidden" style="background-color: #111; border: 1px solid #333;">
      <div class="card-data text-center">
      <p class="text-dark small mb-4">
        If you no longer wish to use Opinion Elite, you can request to delete your account below.
        <br><strong>This action is permanent and cannot be undone.</strong>
      </p>

      <?php if (!empty($deleteError)): ?>
        <div class="alert alert-danger" role="alert">
          <?php echo htmlspecialchars($deleteError); ?>
        </div>
      <?php endif; ?>

      <form method="post" id="deleteAccountForm">
        <input type="hidden" name="username" id="usernameField" value="">
        <div class="mb-4 text-start">
          <div class="form-check">
            <input
              class="form-check-input"
              type="radio"
              name="delete_choice"
              id="deleteChoice"
              value="yes"
            >
            <label class="form-check-label" for="deleteChoice">
              Would you like to delete your account?
            </label>
          </div>
        </div>

        <button
          type="submit"
          name="confirm_delete"
          id="deleteSubmit"
          class="btn btn-orange w-100"
          disabled
        >
          Submit
        </button>
      </form>
        </div>
    </div>
    </div>
  </div>
</div>

<script>
  document.addEventListener('DOMContentLoaded', function () {
    // Require localStorage login (same as other UI pages)
    try {
      const passwordVerified = localStorage.getItem('passwordVerified');
      const username = localStorage.getItem('username');
      if (passwordVerified !== 'true' || !username) {
        window.location.href = '/signin.php';
        return;
      }

      const usernameField = document.getElementById('usernameField');
      if (usernameField) {
        usernameField.value = username;
      }
    } catch (e) {
      window.location.href = '/signin.php';
      return;
    }

    const deleteChoice = document.getElementById('deleteChoice');
    const deleteSubmit = document.getElementById('deleteSubmit');

    if (deleteChoice && deleteSubmit) {
      deleteChoice.addEventListener('change', function () {
        deleteSubmit.disabled = !deleteChoice.checked;
      });
    }
  });
</script>

<?php
include('footer.php');
?>
