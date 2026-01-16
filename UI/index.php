<?php
include('header.php');

?>
<div>
<div class="container mt-5">
  <div class="row g-3">
    
    <!-- Left Column -->
     <p><span class="bold" id="welcome-msg"></span></p>

  <p>
    Welcome to your member home page! We'll email you when new surveys are available but we also recommend that you 
    check in often so you can see what's new and how much money you're making from surveys and friends you referred. 
    To get you started, here's a quick overview of the site and how you can maximize your income:
  </p>
</div>
</div>
  <div class="container mt-5">
  <div class="swiper mySwiper">
  <div class="swiper-wrapper">
    <div class="swiper-slide">
      <div class="card profile-card"  data-profile="Technology Profile" style="cursor:pointer;">
        <img src="images/tech.png" alt="Icon 1" class="icon">
        <h5>Technology Profile</h5>
        <h2>$2.00</h2>
        <!-- <p>5 MIN</p> -->
      </div>
    </div>

    <div class="swiper-slide">
      <div class="card profile-card" data-profile="Health Profile" style="cursor:pointer;">
        <img src="images/health.png" alt="Icon 2" class="icon">
        <h5>Healthcare Profile</h5>
        <h2>$2.00</h2>
        <!-- <p>10 MIN</p> -->
      </div>
    </div>

    <div class="swiper-slide">
      <div class="card profile-card" data-profile="Work profile" style="cursor:pointer;">
        <img src="images/busin.png" alt="Icon 3" class="icon">
        <h5>Business & Employment Profile</h5>
        <h2>$2.00</h2>
        <!-- <p>7 MIN</p> -->
      </div>
    </div>

    <div class="swiper-slide">
      <div class="card profile-card" data-profile="Personal Profile" style="cursor:pointer;">
        <img src="images/enter.png" alt="Icon 4" class="icon">
        <h5>Entertainment (TV/Film) Profile</h5>
        <h2>$2.00</h2>
        <!-- <p>8 MIN</p> -->
      </div>
    </div>

    <div class="swiper-slide">
      <div class="card profile-card" data-profile="" style="cursor:pointer;">
        <img src="images/traval.png" alt="Icon 5" class="icon">
        <h5>Travel Profile</h5>
        <h2>$2.00</h2>
        <!-- <p>6 MIN</p> -->
      </div>
    </div>
    
    <div class="swiper-slide">
      <div class="card profile-card" data-profile="Auto Profile" style="cursor:pointer;">
        <img src="images/auto.png" alt="Icon 5" class="icon">
        <h5>Automotive Profile</h5>
        <h2>$2.00</h2>
        <!-- <p>6 MIN</p> -->
      </div>
    </div>

    <div class="swiper-slide">
      <div class="card profile-card" data-profile="" style="cursor:pointer;">
        <img src="images/game.png" alt="Icon 5" class="icon">
        <h5>Gaming Profile</h5>
        <h2>$2.00</h2>
        <!-- <p>6 MIN</p> -->
      </div>
    </div>
  
  <div class="swiper-slide">
      <div class="card profile-card" data-profile="Hobbies & Intrests" style="cursor:pointer;">
        <img src="images/hobbi.png" alt="Icon 5" class="icon">
        <h5>Hobbies & Interests</h5>
        <h2>$2.00</h2>
        <!-- <p>6 MIN</p> -->
      </div>
    </div>
  </div>

  <!-- Navigation -->
  <!-- <div class="swiper-button-prev"></div>
  <div class="swiper-button-next"></div> -->

  <!-- Pagination -->
  <div class="swiper-pagination mt-5"></div>
</div>
</div>



    <!-- <div class="col-md-6">
      <div class="border p-3">
        <div class="bg-info text-white font-weight-bold p-2">PROFILES</div>
        <div class="mt-2">
          <div class="form-check">
            <input class="form-check-input" type="checkbox" id="personal" value="Personal Profile">
            <label class="form-check-label font-weight-bold text-dark" for="personal">Personal Profile <span class="m-3">$2</span></label>
          </div>
          <div class="form-check">
            <input class="form-check-input" type="checkbox" id="work" value="Work profile">
            <label class="form-check-label font-weight-bold text-dark" for="work">Work Profile <span class="ml-lg-5">$2</span></label>
            
          </div>
          <div class="form-check">
            <input class="form-check-input" type="checkbox" id="auto" value="Auto Profile">
            <label class="form-check-label font-weight-bold text-dark" for="auto">Auto Profile <span class="ml-lg-5">$2</span></label>
          </div>
          <div class="form-check">
            <input class="form-check-input" type="checkbox" id="medical" value="Health Profile">
            <label class="form-check-label font-weight-bold text-dark" for="medical">Health Profile <span class="ml-5">$2</span></label>
          </div>
          <div class="form-check">
            <input class="form-check-input" type="checkbox" id="travel" value="Hobbies & Intrests">
            <label class="form-check-label font-weight-bold text-dark" for="travel">Hobbies & Intrests <span class="ml-2">$2</span></label>
          </div>
          <div class="form-check">
            <input class="form-check-input" type="checkbox" id="travel" value="Technology Profile">
            <label class="form-check-label font-weight-bold text-dark" for="travel">Technology Profile <span class="ml-2">$2</span></label>
          </div>
        </div>
      </div>
    </div> -->

    <!-- <div class="col-md-6">
    <div class="border p-4">
    <h5 class="font-weight-bold text-heading">
      Build Your Profile
      <i class="fa fa-bullhorn" aria-hidden="true"></i>

    </h5>
    <p class="text-dark mb-1">Your Downline currently consists of:</p>
    <ul class="text-dark pl-3 mb-2" style="list-style-type: none;">
      <li>0 Profile Complete</li>
     
    </ul>
    <p class="text-dark">You have earned $ 0.00 from your referrals so far.</p>

    <button class="btn btn-dark font-weight-bold  mt-2 text-white px-3 py-2">
      Build Your Complete Profile
    </button>
  </div>
</div> -->

  </div>
</div>

</div>


<!-- Modal -->
<div class="modal fade" id="questionModal" tabindex="-1" aria-labelledby="questionModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="questionModalLabel">Survey Questions for <span id="profile-type-title"></span></h5>

        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body" id="modal-question-content">
        <!-- AJAX-loaded questions will appear here -->
      </div>
    </div>
  </div>
</div>


<?php
include('footer.php');
?>

