<?php
include('header.php');
include('config.php');

// Tremendous helper (in project root)
require_once __DIR__ . '/../tremendous_helper.php';

// Optional: existing SQL (not used directly here but kept)
$sql = "SELECT * FROM rewards";

$rewardMessage = '';
$rewardError   = '';

// Handle reward confirmation (from modal)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['confirm_reward'])) {

    $amount        = isset($_POST['reward_amount']) ? (float) $_POST['reward_amount'] : 0.0;
    $emailChoice   = $_POST['email_choice'] ?? 'registered';
    $recipientName = 'Opinion Elite Member'; // you can later build from firstname/lastname if you want

    if ($emailChoice === 'registered') {
        // Email that came from DB via AJAX and hidden input
        $recipientEmail = isset($_POST['registered_email']) ? trim($_POST['registered_email']) : '';
    } else {
        // New email typed by user
        $recipientEmail = isset($_POST['new_email']) ? trim($_POST['new_email']) : '';
    }

    if (!filter_var($recipientEmail, FILTER_VALIDATE_EMAIL)) {
        $rewardError = 'Please provide a valid email address.';
    } elseif ($amount <= 0) {
        $rewardError = 'Invalid reward amount.';
    } else {
        try {
            // Call Tremendous API (sandbox)
            $response = send_tremendous_reward($recipientEmail, $recipientName, $amount);

            $rewardId = $response['order']['rewards'][0]['id'] ?? '';

            $rewardMessage =
                'Reward sent successfully! Amount: $' . htmlspecialchars(number_format($amount, 2))
                . (!empty($rewardId) ? (' | Reward ID: ' . htmlspecialchars($rewardId)) : '');

        } catch (Exception $e) {
            $rewardError = 'Failed to send reward: ' . htmlspecialchars($e->getMessage());
        }
    }
}
?>

<div>
  <div class="container my-4">

    <!-- Success / error messages from PHP -->
    <?php if (!empty($rewardMessage)): ?>
      <div class="alert alert-success" role="alert">
        <?php echo $rewardMessage; ?>
      </div>
    <?php endif; ?>

    <?php if (!empty($rewardError)): ?>
      <div class="alert alert-danger" role="alert">
        <?php echo $rewardError; ?>
      </div>
    <?php endif; ?>

    <!-- JS-controlled low balance banner -->
    <div id="balanceErrorAlert"
         class="alert alert-danger"
         role="alert"
         style="display:none;">
      Available balance is low
    </div>

    <h5>
      Total Earnings:
      <span class="text-main" id="reward-count">$0.00</span>
      &nbsp; Total Redemptions:
      <span class="text-main">$0.00</span>
      &nbsp; Account Balance:
      <span class="text-main" id="account-balance">$0.00</span>
    </h5>

    <h4 class="mt-4 mb-3">Available Rewards</h4>

    <div class="card-rewards p-3">
      <h6 class="mb-3">Retail Gift Card</h6>
      <div class="row g-3">

        <!-- Tremendous card -->
        <div class="col-12 col-sm-6 col-md-4 col-lg-3">
          <div class="reward-card text-center bg-white">
            <img src="images/Tremendous.png" class="reward-img" alt="Tremendous">
            <p class="fw-bold">1. Tremendous.in</p>
            <div>
              <!-- CLICKABLE AMOUNTS -->
              <!-- <button class="btn btn-outline-dark amount-btn" data-amount="5">$5</button> -->
              <button class="btn btn-outline-dark amount-btn" data-amount="20">$20</button>
              <button class="btn btn-outline-dark amount-btn" data-amount="50">$50</button>
              <button class="btn btn-outline-dark amount-btn" data-amount="100">$100</button>
            </div>
          </div>
        </div>

        <!-- Future: other reward cards here -->

      </div>
    </div>
  </div>
</div>

<!-- Simple modal styles -->
<style>
.reward-modal-backdrop {
  position: fixed;
  inset: 0;
  background: rgba(0,0,0,0.5);
  display: none;
  align-items: center;
  justify-content: center;
  z-index: 9999;
}
.reward-modal-backdrop.show {
  display: flex;
}
.reward-modal-content {
  background: #fff;
  border-radius: 8px;
  padding: 20px;
  max-width: 500px;
  width: 100%;
  color: #000;
}
.reward-modal-content p {
  margin-bottom: 1rem;
  color: #111;
}
.reward-modal-content .form-check-label {
  color: #222;
}
.reward-modal-content .btn {
  color: #fff;
}
.reward-modal-content .btn.btn-secondary {
  background-color: #6c757d;
}
.reward-modal-content .btn.btn-primary {
  background-color: #f7941d; /* OE orange */
  border-color: #f7941d;
}
</style>

<!-- Confirmation Modal -->
<div class="reward-modal-backdrop" id="rewardModal">
  <div class="reward-modal-content">
    <h5 class="mb-3">Confirm Reward</h5>
    <p id="rewardModalMessage"></p>

    <form method="post" id="rewardModalForm">
      <!-- Hidden amount -->
      <input type="hidden" name="reward_amount" id="rewardAmountInput" value="0">
      <!-- Hidden registered email (filled from AJAX) -->
      <input type="hidden" name="registered_email" id="registeredEmailInput" value="">

      <div class="form-check">
        <input
          class="form-check-input"
          type="radio"
          name="email_choice"
          id="emailChoiceRegistered"
          value="registered"
          checked
        >
        <label class="form-check-label" for="emailChoiceRegistered">
          Continue with registered email
          (<strong><span id="registeredEmailLabel">loading...</span></strong>)
        </label>
      </div>

      <div class="form-check mt-2">
        <input
          class="form-check-input"
          type="radio"
          name="email_choice"
          id="emailChoiceNew"
          value="new"
        >
        <label class="form-check-label" for="emailChoiceNew">
          Enter new email
        </label>
      </div>

      <div class="mt-3" id="newEmailContainer" style="display:none;">
        <label class="form-label" for="newEmailInput">Enter new email</label>
        <input type="email" class="form-control" name="new_email" id="newEmailInput" placeholder="you@example.com">
      </div>

      <div class="mt-4 d-flex justify-content-end gap-2">
        <button type="button" class="btn btn-secondary" id="rewardModalCancel">Cancel</button>
        <button type="submit" name="confirm_reward" class="btn btn-primary">Submit</button>
      </div>
    </form>
  </div>
</div>

<script>
  let registeredEmail = '';   // filled via AJAX
  let accountBalance = 0;     // numeric balance

  const username = localStorage.getItem('username');

  // --- Fetch reward totals (incl. balance) ---
  if (username) {
    fetch('get_rewards.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
      body: 'username=' + encodeURIComponent(username)
    })
      .then(res => res.json())
      .then(data => {
        const totalReward = parseFloat(data.total_reward || 0);
        accountBalance = isNaN(totalReward) ? 0 : totalReward;

        document.getElementById('reward-count').textContent   = `$${accountBalance}`;
        document.getElementById('account-balance').textContent = `$${accountBalance}`;
      })
      .catch(err => console.error('Error fetching reward:', err));

    // Fetch registered email from DB
    fetch('get_registered_email.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
      body: 'username=' + encodeURIComponent(username)
    })
      .then(res => res.json())
      .then(data => {
        registeredEmail = data.email || '';
        document.getElementById('registeredEmailInput').value = registeredEmail;

        const labelSpan = document.getElementById('registeredEmailLabel');
        if (registeredEmail) {
          labelSpan.textContent = registeredEmail;
        } else {
          labelSpan.textContent = 'No registered email found';
        }
      })
      .catch(err => {
        console.error('Error fetching registered email:', err);
        document.getElementById('registeredEmailLabel').textContent = 'Error loading email';
      });

  } else {
    console.warn('No username found in localStorage');
  }

  // ---- Modal JS ----
  const modalBackdrop         = document.getElementById('rewardModal');
  const modalMessage          = document.getElementById('rewardModalMessage');
  const rewardAmountInput     = document.getElementById('rewardAmountInput');
  const emailChoiceRegistered = document.getElementById('emailChoiceRegistered');
  const emailChoiceNew        = document.getElementById('emailChoiceNew');
  const newEmailContainer     = document.getElementById('newEmailContainer');
  const rewardModalCancel     = document.getElementById('rewardModalCancel');
  const balanceErrorAlert     = document.getElementById('balanceErrorAlert');

  function updateEmailFieldVisibility() {
    if (emailChoiceNew.checked) {
      newEmailContainer.style.display = 'block';
    } else {
      newEmailContainer.style.display = 'none';
    }
  }

  emailChoiceRegistered.addEventListener('change', updateEmailFieldVisibility);
  emailChoiceNew.addEventListener('change', updateEmailFieldVisibility);

  rewardModalCancel.addEventListener('click', () => {
    modalBackdrop.classList.remove('show');
  });

  // When user clicks any amount button
  document.querySelectorAll('.reward-card .amount-btn').forEach(btn => {
    btn.addEventListener('click', () => {
      const amountStr   = btn.getAttribute('data-amount');
      const amountValue = parseFloat(amountStr);

      if (isNaN(amountValue)) {
        return;
      }

      // 🔴 Balance check BEFORE opening modal
      if (amountValue > accountBalance) {
        if (balanceErrorAlert) {
          balanceErrorAlert.style.display = 'block';
          balanceErrorAlert.textContent = 'Account balance is low';
        }
        return; // do NOT show dialog
      } else {
        // Hide banner if previously shown
        if (balanceErrorAlert) {
          balanceErrorAlert.style.display = 'none';
        }
      }

      // If enough balance, proceed with existing modal logic
      rewardAmountInput.value = amountValue;

      const amountText = `$${amountValue}`;
      const emailText = registeredEmail
        ? `this registered email: ${registeredEmail}`
        : `a new email (no registered email found)`;

      modalMessage.textContent =
        `Are you sure to send this reward of ${amountText} to ${emailText} ` +
        `or you want the reward to be forwarded to any new email?`;

      // Reset radio + email field
      emailChoiceRegistered.checked = true;
      emailChoiceNew.checked = false;
      updateEmailFieldVisibility();

      modalBackdrop.classList.add('show');
    });
  });
</script>

<?php
include('footer.php');
?>