<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Opinion Elite</title>

  <!-- ✅ Bootstrap 5 CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
      
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
  <link rel="stylesheet" href="css/style.css">

  <style>
    body {
      background-color: #000;
      color: #fff;
      font-family: Arial, sans-serif;
      line-height: 1.6;
      margin: 0;
      padding: 40px 0;
    }

    .bold {
      font-weight: bold;
    }

    .container, .modal-content {
      color: #fff;
    }

    .border {
      border: 1px solid #444;
    }

    .form-check-label {
      color: #ddd;
    }

    .bg-info {
      background-color: #333 !important;
    }

    .text-dark {
      color: #fff !important;
    }

    .btn-dark {
      background-color: #444;
      border-color: #555;
    }

    .btn-dark:hover {
      background-color: #666;
      border-color: #777;
    }

    .modal-header, .modal-body {
      background-color: #111;
    }

    .form-check-input:checked {
      background-color: #555;
      border-color: #888;
    }

    .profile-card {
      background-color: #111;
      border-radius: 16px;
      padding: 20px;
      text-align: center;
      transition: transform 0.3s ease;
      box-shadow: 0 0 10px rgba(255, 140, 0, 0.2);
    }

    .profile-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 0 20px rgba(255, 140, 0, 0.4);
    }

    .profile-card img {
      width: 60px;
      height: 60px;
      margin-bottom: 15px;
    }

    .profile-card h5 {
      color: #ff9900;
      font-weight: bold;
      font-size: 18px;
    }

    .profile-card p {
      color: #ccc;
      font-size: 14px;
    }

    .card-container {
      display: flex;
      flex-wrap: wrap;
      gap: 20px;
    }
    
    /* Modal customization to match the dark theme */
    .modal-content {
      background-color: #111 !important;
      color: #ff9900 !important;
      border: 1px solid #444;
      border-radius: 12px;
    }

    .modal-header {
      background-color: #111 !important;
      border-bottom: 1px solid #333;
      color: #ff9900 !important;
    }

    .modal-title {
      color: #ff9900 !important;
    }

    .modal-body {
      background-color: #111 !important;
    }

    .modal-footer {
      background-color: #111 !important;
      border-top: 1px solid #333;
    }

    /* Input fields */
    .modal-body input[type="text"],
    .modal-body textarea {
      background-color: #222;
      color: #fff;
      border: 1px solid #555;
    }

    .modal-body input[type="text"]::placeholder,
    .modal-body textarea::placeholder {
      color: #999;
    }

    /* Buttons */
    .btn-primary {
      background-color: #ff9900;
      border-color: #ff9900;
      color: #000;
    }

    .btn-primary:hover {
      background-color: #e68a00;
      border-color: #e68a00;
      color: #fff;
    }

    .swiper {
      width: 75%;
      margin: auto;
      padding: 20px 0;
    }

    .swiper-slide {
      display: flex;
      justify-content: center;
    }

    .card {
      background: linear-gradient(to bottom, #111, #000);
      border-radius: 20px;
      box-shadow: 0 0 15px #ff9900;
      width: 300px;
      padding: 30px 20px;
      text-align: center;
      color: white;
      margin: 0 5px;
      transition: transform 0.3s ease;
    }

    .card:hover {
      transform: scale(1.05);
    }

    .card .icon {
      width: 200px;
      margin-bottom: 15px;
      height:150px;
      margin:auto;
    }

    .card h5 {
      font-size: 16px;
      letter-spacing: 1px;
      color: #ccc;
      margin-bottom: 10px;
    }

    .card h2 {
      font-size: 32px;
      color: #fff;
      margin: 10px 0;
    }

    .card p {
      color: #ff9900;
      font-weight: bold;
      margin: 5px 0 0;
    }

    .swiper-pagination-bullet {
      background: #fff;
      opacity: 0.3;
    }

    .swiper-pagination-bullet-active {
      background: #ff9900;
      opacity: 1;
    }

    .swiper-button-prev,
    .swiper-button-next {
      color: #ff9900;
    }

    @media (max-width: 767px) {
      .card {
        width: 90%;
      }
    }

  </style>
</head>
<body>

<!-- ✅ Navbar -->
<nav class="navbar navbar-expand-lg navbar-light bg-light">
  <div class="container-fluid">
    <a class="navbar-brand" href="#">
      <img src="images/logo.png" alt="Logo" style="width: 260px; height: 70px;">
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown"
      aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarNavDropdown">
      <!-- Left side links -->
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item ms-3">
          <a class="nav-link active" aria-current="page" href="index.php">Home</a>
        </li>
        <li class="nav-item ms-3">
          <a class="nav-link" href="surveys.php">Surveys</a>
        </li>
        <li class="nav-item ms-3">
          <a class="nav-link" href="rewards.php">Rewards</a>
        </li>
      </ul>

      <!-- Profile dropdown on right -->
      <ul class="navbar-nav">
        <li class="nav-item dropdown ms-3">
          <a class="nav-link dropdown-toggle" href="#" id="profileDropdown" role="button"
            data-bs-toggle="dropdown" aria-expanded="false">
            <i class="fas fa-user"></i>
          </a>
          <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="profileDropdown">
            <li><a class="dropdown-item" href="my_account.php">My Account</a></li>
            <!-- 🔐 Only for direct users (hidden via JS for others) -->
            <li id="changePasswordMenuItem">
              <a class="dropdown-item" href="change_password.php">Change Password</a>
            </li>
            <li><a class="dropdown-item" href="#">Settings</a></li>
            <li><a class="dropdown-item" href="logout.php">Logout</a></li>
          </ul>
        </li>
      </ul>
    </div>
  </div>
</nav>

<!-- JS to hide "Change Password" for non-direct users -->
<script>
  (function () {
    try {
      const userType  = localStorage.getItem('userType');
      const username  = localStorage.getItem('username');
      const changeLi  = document.getElementById('changePasswordMenuItem');

      // If not logged in at all, hide the Change Password item
      if (!username && changeLi) {
        changeLi.style.display = 'none';
        return;
      }

      // Hide for any non-direct userType (linkedin, facebook, etc.)
      if (changeLi && userType && userType !== 'direct') {
        changeLi.style.display = 'none';
      }
    } catch (e) {
      // Fail silently: worst case, item is still visible, but
      // backend will block non-direct users.
    }
  })();
</script>
