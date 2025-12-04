<?php
include('header.php');
?>
<div>
<div class="container py-5">
    <div class="card-data text-center">
    <h5 class="fw-bold">Change your password</h5>
    <p class="text-dark small mb-4">Enter a new password below to change your password</p>
    
    <form>
      <div class="mb-3 text-start">
        <label for="newPassword" class="form-label">New password</label>
        <input type="password" class="form-control" id="newPassword" placeholder="New password">
      </div>
      <div class="mb-4 text-start">
        <label for="confirmPassword" class="form-label">Confirm password</label>
        <input type="password" class="form-control" id="confirmPassword" placeholder="Confirm your password">
      </div>
      <button type="submit" class="btn btn-orange w-100">CHANGE PASSWORD</button>
    </form>
  </div>
</div>
</div>
<?php
include('footer.php');
?>