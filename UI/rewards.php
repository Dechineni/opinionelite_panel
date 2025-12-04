<?php
include('header.php');
include('config.php');
$sql="select * from reward";
?>
<div>

<div class="container my-4">
  <h5>Total Earnings: <span class="text-main" id="reward-count">$0.00</span> 
  Total Redemptions: <span class="text-main">$0.00</span> 
  Account Balance: <span class="text-main" id="account-balance">$0.00</span></h5>

  <h4 class="mt-4 mb-3">Available Rewards</h4>
  <div class="card-rewards p-3">
    <h6 class="mb-3">Retail Gift Card</h6>
    <div class="row g-3">
      <!-- Card 1 -->
      <div class="col-12 col-sm-6 col-md-4 col-lg-3 ">
        <div class="reward-card text-center bg-white">
          <img src="images/Tremendous.png" class="reward-img" alt="1-800-Flowers">
          <p class="fw-bold">1.Tremendous.in</p>
          <div>
            <button class="btn btn-outline-dark amount-btn">$5</button>
            <button class="btn btn-outline-dark amount-btn">$20</button>
            <button class="btn btn-outline-dark amount-btn">$50</button>
          </div>
        </div>
      </div>

      <!-- Repeat the card structure for other rewards -->
      <!-- Card 2 Example: Amazon -->
      <div class="col-12 col-sm-6 col-md-4 col-lg-3">
        <div class="reward-card text-center bg-white">
          <img src="images/2.png" class="reward-img" alt="Amazon">
          <p class="fw-bold">2. Amazon.in</p>
          <div>
            <button class="btn btn-outline-dark amount-btn">$25</button>
            <button class="btn btn-outline-dark amount-btn">$50</button>
            <button class="btn btn-outline-dark amount-btn">$100</button>
          </div>
        </div>
      </div>
      <div class="col-12 col-sm-6 col-md-4 col-lg-3">
        <div class="reward-card text-center bg-white">
          <img src="images/2.png" class="reward-img" alt="Amazon">
          <p class="fw-bold">2. Amazon.in</p>
          <div>
            <button class="btn btn-outline-dark amount-btn">$25</button>
            <button class="btn btn-outline-dark amount-btn">$50</button>
            <button class="btn btn-outline-dark amount-btn">$100</button>
          </div>
        </div>
      </div>
      <div class="col-12 col-sm-6 col-md-4 col-lg-3">
        <div class="reward-card text-center bg-white">
          <img src="images/2.png" class="reward-img" alt="Amazon">
          <p class="fw-bold">2. Amazon.in</p>
          <div>
            <button class="btn btn-outline-dark amount-btn">$25</button>
            <button class="btn btn-outline-dark amount-btn">$50</button>
            <button class="btn btn-outline-dark amount-btn">$100</button>
          </div>
        </div>
      </div>
      <div class="col-12 col-sm-6 col-md-4 col-lg-3">
        <div class="reward-card text-center bg-white">
          <img src="images/2.png" class="reward-img" alt="Amazon">
          <p class="fw-bold">2. Amazon.in</p>
          <div>
            <button class="btn btn-outline-dark amount-btn">$25</button>
            <button class="btn btn-outline-dark amount-btn">$50</button>
            <button class="btn btn-outline-dark amount-btn">$100</button>
          </div>
        </div>
      </div>
      <div class="col-12 col-sm-6 col-md-4 col-lg-3">
        <div class="reward-card text-center bg-white">
          <img src="images/2.png" class="reward-img" alt="Amazon">
          <p class="fw-bold">2. Amazon.in</p>
          <div>
            <button class="btn btn-outline-dark amount-btn">$25</button>
            <button class="btn btn-outline-dark amount-btn">$50</button>
            <button class="btn btn-outline-dark amount-btn">$100</button>
          </div>
        </div>
      </div><div class="col-12 col-sm-6 col-md-4 col-lg-3">
        <div class="reward-card text-center bg-white">
          <img src="images/2.png" class="reward-img" alt="Amazon">
          <p class="fw-bold">2. Amazon.in</p>
          <div>
            <button class="btn btn-outline-dark amount-btn">$25</button>
            <button class="btn btn-outline-dark amount-btn">$50</button>
            <button class="btn btn-outline-dark amount-btn">$100</button>
          </div>
        </div>
      </div>
      <div class="col-12 col-sm-6 col-md-4 col-lg-3">
        <div class="reward-card text-center bg-white">
          <img src="images/2.png" class="reward-img" alt="Amazon">
          <p class="fw-bold">2. Amazon.in</p>
          <div>
            <button class="btn btn-outline-dark amount-btn">$25</button>
            <button class="btn btn-outline-dark amount-btn">$50</button>
            <button class="btn btn-outline-dark amount-btn">$100</button>
          </div>
        </div>
      </div>
      <div class="col-12 col-sm-6 col-md-4 col-lg-3">
        <div class="reward-card text-center bg-white">
          <img src="images/2.png" class="reward-img" alt="Amazon">
          <p class="fw-bold">2. Amazon.in</p>
          <div>
            <button class="btn btn-outline-dark amount-btn">$25</button>
            <button class="btn btn-outline-dark amount-btn">$50</button>
            <button class="btn btn-outline-dark amount-btn">$100</button>
          </div>
        </div>
      </div>

      <!-- Add more cards similarly... -->

    </div>
  </div>
</div>
</div>
<script>
  const username = localStorage.getItem('username');
  if (username) {
    // Send username to PHP via fetch
    fetch('get_rewards.php', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/x-www-form-urlencoded'
      },
      body: 'username=' + encodeURIComponent(username)
    })
    .then(res => res.json())
    .then(data => {
      // Show total reward
      document.getElementById('reward-count').textContent = `$${data.total_reward}`;
      document.getElementById('account-balance').textContent=`$${data.total_reward}`;
    })
    .catch(error => {
      console.error('Error fetching reward:', error);
    });
  } else {
    console.warn('No username found in localStorage');
  }
</script>
<?php
include('footer.php');
?>

