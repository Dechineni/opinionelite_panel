<!-- ✅ jQuery (only if needed for your custom AJAX) -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- ✅ Bootstrap 5.3.2 Bundle (includes Popper) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  


<script>
  // Welcome message from localStorage
  document.addEventListener("DOMContentLoaded", function () {
    const passwordVerified = localStorage.getItem('passwordVerified');
    const username = localStorage.getItem('username');

    if (passwordVerified !== 'true' || !username) {
      window.location.href = '/signin.php';
    } else {
      document.getElementById('welcome-msg').innerText = `Welcome, ${username}!`;
    }
  });

  // Profile card click to open modal
 </script>
<script>
  $(document).ready(function () {
    $('.profile-card').on('click', function () {
      const profileType = $(this).data('profile'); // Read data-profile from card
      const username = localStorage.getItem('username');

      if (!profileType || !username) {
        alert("Missing profile type or username.");
        return;
      }

      // Update modal title with selected profile
      $('#profile-type-title').text(profileType);

      // Fetch questions via AJAX
      $.ajax({
        url: 'fetch_questions.php',
        method: 'POST',
        data: {
          type: profileType,
          username: username
        },
        success: function (response) {
          $('#modal-question-content').html(response);
          $('#questionModal').modal('show');
        },
        error: function () {
          alert("Failed to load questions.");
        }
      });
    });
  });
</script>

<script>

 $(document).ready(function () {
    // Modal form submit
    $(document).on('submit', '#answerForm', function (e) {
      e.preventDefault();
      $.ajax({
        url: 'submit_answers.php',
        method: 'POST',
        data: $(this).serialize(),
        success: function (response) {
          alert(response);
          $('#questionModal').modal('hide');
        },
        error: function () {
          alert("Error saving your answers.");
        }
      });
    });
  });
</script>
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

<script>
  const swiper = new Swiper('.mySwiper', {
    slidesPerView: 3,
    spaceBetween: 20,
    centeredSlides: true,
    loop: true,
    autoplay: {
      delay: 2500,
      disableOnInteraction: false,
    },
    pagination: {
      el: ".swiper-pagination",
      clickable: true,
    },
    navigation: {
      nextEl: ".swiper-button-next",
      prevEl: ".swiper-button-prev",
    },
    breakpoints: {
      0: {
        slidesPerView: 1,
        centeredSlides: false,
      },
      768: {
        slidesPerView: 2,
        centeredSlides: false,
      },
      1024: {
        slidesPerView: 3,
        centeredSlides: true,
      }
    }
  });
</script>

