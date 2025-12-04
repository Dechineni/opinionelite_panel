<?php include('header.php'); ?>
<div>
  <div class="container py-5">
    <h3 class="text-center fw-bold" style="color: #ff9900;">My Account</h3>
    <div class="row justify-content-center mt-5">
      <div class="col-md-10 shadow rounded overflow-hidden" style="background-color: #111; border: 1px solid #333;">
        <div class="row g-0">
          <div class="col-md-7 p-4 m-auto">
            <h4 class="fw-bold" id="welcome-msg" style="color: #fff;"></h4>
            <p style="color: #ccc;">
              You will begin to realise why this exercise is called the Dickens Pattern (with reference to the ghost showing Scrooge some different futures)
            </p>

            <!-- Contact Info -->
            <ul class="list-unstyled mt-4">
              <li class="mb-2" style="color: #ff9900;">
                <i class="fa-solid fa-calendar-days me-2"></i>
                <span id="user-dob" style="color: #fff;">Loading...</span>
              </li>
              <li class="mb-2" style="color: #ff9900;">
                <i class="fa-solid fa-envelope me-2"></i>
                <span id="user-email" style="color: #fff;">Loading...</span>
              </li>
            </ul>

            <!-- Social Icons -->
            <div class="mt-3">
              <a href="#" class="me-3" style="color: #ccc;"><i class="fab fa-facebook-f"></i></a>
              <a href="#" class="me-3" style="color: #ccc;"><i class="fab fa-twitter"></i></a>
              <a href="#" style="color: #ccc;"><i class="fab fa-linkedin-in"></i></a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
  document.addEventListener('DOMContentLoaded', () => {
    const username = localStorage.getItem('username');
    if (username) {
      fetch(`get_user_data.php?username=${encodeURIComponent(username)}`)
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            const user = data.user;
            document.getElementById('welcome-msg').textContent = `Welcome, ${user.firstname} ${user.lastname}`;
            document.getElementById('user-email').textContent = user.email;
            document.getElementById('user-dob').textContent = user.birthday;
          } else {
            document.getElementById('welcome-msg').textContent = `User not found`;
          }
        })
        .catch(error => {
          console.error('Error fetching user:', error);
        });
    }
  });
</script>

<?php include('footer.php'); ?>
