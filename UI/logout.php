<?php
// logout.php
session_start();
session_unset();
session_destroy();
?>

<script>
  localStorage.removeItem('username');
  localStorage.removeItem('passwordVerified');
  window.location.href = '../index.php'; 
</script>