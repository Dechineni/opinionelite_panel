<?php
// logout.php
session_start();
session_unset();
session_destroy();
?>

<script>
  localStorage.removeItem('passwordVerified');
  localStorage.removeItem('username');

  window.location.href = "../"; 
</script>