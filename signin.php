<!doctype html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Opinion Elite</title>
    <link href="output.css" rel="stylesheet" />
    <script src="scripts/main.js?v=20251216"></script>
    <link rel="icon" sizes="64x64" href="favicon.png" type="image/png" />
  </head>
  <body
    class="flex flex-col items-center justify-start bg-gradient-to-br from-[#171717] to-[#000] font-gilroy"
  >
    <!-- Password Modal -->
    <div
      id="password-modal"
      class="fixed font-inter inset-0 bg-black flex justify-center items-center"
    >
      <div class="modal-content">
        <img src="imgs/logo.png" alt="Logo" class="max-w-[200px] mb-6" />
        <div class="pseudoInput relative mb-4">
          <input
            autofocus
            type="text"
            id="username-input"
            name="username"
            placeholder=""
            class="peer w-full text-white font-medium text-[19px] placeholder:text-[#494940] border-2 border-[#262629] bg-gradient-to-b from-[#111112] to-[#151517] rounded-md p-4 pt-6 hover:border-[#363639] focus:border-[#f1aa3f] focus:from-[#1a1918] focus:to-[#272219] active:border-[#f1aa3f] ring-0 focus:ring-0 outline-none"
          />
          <label
            for="username-input"
            class="absolute top-3 left-[calc(1rem+2px)] text-gray-400 peer-focus:text-[#f1aa3f] font-semibold uppercase text-[12px]"
            >Username</label
          >
        </div>
        <div class="pseudoInput relative mb-4">
          <input
            type="password"
            id="password-input"
            name="password"
            placeholder=""
            class="peer w-full text-white font-medium text-[19px] placeholder:text-[#494940] border-2 border-[#262629] bg-gradient-to-b from-[#111112] to-[#151517] rounded-md p-4 pt-6 hover:border-[#363639] focus:border-[#f1aa3f] focus:from-[#1a1918] focus:to-[#272219] active:border-[#f1aa3f] ring-0 focus:ring-0 outline-none"
          />
          <label
            for="password-input"
            class="absolute top-3 left-[calc(1rem+2px)] text-gray-400 peer-focus:text-[#f1aa3f] font-semibold uppercase text-[12px]"
            >Password</label
          >
        </div>
        <div
          id="error-message"
          class="h-[20px] text-red-500 text-sm mb-4 invisible"
        ></div>

        <button onclick="checkPassword()" class="w-[300px] h-[80px] relative my-6">
          <span class="relative z-[10] text-[16px] font-bold tracking-[0.03em] leading-none uppercase">Login</span>
          <img class="absolute inset-0 z-[1]" src="imgs/join-button.png" alt="Signup" />
        </button>
        <button onclick="window.location.href='index.php'" class="w-[300px] h-[80px] relative my-2">
          <span class="relative z-[10] text-[16px] font-bold tracking-[0.03em] leading-none uppercase">Sign up here</span>
          <img class="absolute inset-0 z-[1]" src="imgs/join-button.png" alt="Signup" />
        </button>

      </div>
    </div>

    <!-- Main Content -->
    <div id="content">
      <nav class="nav w-full mb-12">
        <div
          class="wrapper mx-auto flex items-center justify-between w-full max-w-[1440px] p-8"
        >
          <a
            href="/"
            class="logo w-[111px] h-[31px] sm:w-[222px] sm:h-[62px] md:w-[332px] md:h-[93px] block"
          >
            <img src="imgs/logo.png" alt="Logo" />
          </a>
          <a
            href="signup.php"
            class="logo w-[80px] h-[24px] sm:w-[160px] sm:h-[48px] md:w-[240px] md:h-[72px] block"
          >
            <img src="imgs/signup.png" alt="Signup" />
          </a>
        </div>
      </nav>
      <main class="main flex flex-col w-full">
        <section class="intro w-full relative">
          <div class="hidden md:block md:inset-0 absolute z-[-1] py-[1%]">
            <img
              src="imgs/intro-bg.png"
              class="w-full h-[50vw] object-bottom object-cover opacity-[0.2]"
            />
          </div>
          <div
            class="wrapper mx-auto gap-8 xl:gap-0 max-w-[1440px] px-8 flex items-center md:flex-row flex-col"
          >
            <div
              class="text order-2 w-full md:w-3/5 flex flex-col items-start xl:order-1"
            >
              <div
                class="flex items-center justify-between gap-12 my-12 w-full"
              >
                <img
                  src="imgs/flame-icon.png"
                  class="w-[50px] h-[50px] md:w-[100px] md:h-[100px]"
                />
                <div class="line w-full h-[2px] bg-[#3E3E41] block"></div>
              </div>
              <h1
                class="text-[50px] pr-4 text-center md:text-left xl:text-[70px] text-white font-semibold leading-none tracking-[-0.03em] mb-12"
              >
                Your exclusive invite <br class="lg:block hidden" />to join
                Opinion Elite
              </h1>
              <p
                class="text-white text-center md:text-left text-opacity-[0.85] text-[17px] xl:text-[23px] font-semibold pr-4"
              >
                Welcome to Opinion Elite, where your voice matters. We're
                thrilled to introduce our cutting-edge survey platform, designed
                to revolutionize the way you share your opinions. Be part of
                something extraordinary!
              </p>
              <a
                href="signup.php"
                class="xl:w-[300px] w-[196px] mx-auto md:mx-0 flex items-center justify-center h-[53px] xl:h-[80px] relative my-12"
              >
                <span
                  class="relative z-[10] text-[11px] xl:text-[16px] font-bold tracking-[0.03em] leading-none uppercase"
                  >Join Opinion Elite</span
                >
                <img
                  class="absolute inset-0 z-0"
                  src="imgs/join-button.png"
                  alt="Signup"
                />
              </a>
            </div>
            <div
              class="video order-1 w-full md:w-2/5 relative flex justify-center md:justify-end xl:order-2"
            >
              <img
                class="w-[97px] md:w-[146px] xl:w-[194px] h-[350px] md:h-[525px] xl:h-[700px] block mr-[248px] xl:mr-[330px] object-contain"
                src="imgs/video.png"
                alt="Video"
              />
              <div
                class="rounded-[25px] border-2 border-gray-800 overflow-hidden block w-[165px] md:w-[248px] xl:w-[330px] h-[370px] md:h-[555px] xl:h-[740px] absolute top-[-2%]"
              >
                <video
                  class="block w-full h-full object-cover"
                  autoplay
                  loop
                  playsinline
                  muted
                >
                  <source src="video/app-video.mp4" type="video/mp4" />
                </video>
              </div>
            </div>
          </div>
        </section>

        <section class="reward-giftcard-options my-24">
          <div
            class="wrapper max-w-[1440px] mx-auto px-8 flex flex-col items-center"
          >
            <h3
              class="text-white text-[23px] font-semibold uppercase tracking-[-0.03em] mb-24"
            >
              Reward and Gift Card Options
            </h3>
            <div class="grid grid-cols-3 lg:grid-cols-5 gap-4 w-full">
              <div
                class="giftCard w-full h-[calc((100vw/6)-(4rem/5)-6px)] rounded-lg border-[2px] bg-gradient-to-b from-[#1c1c1d] to-[#111] bg-opacity-25 border-[#232325] flex items-center justify-center p-4"
              >
                <img
                  class="w-full h-full object-contain"
                  src="imgs/giftcard-amc.png"
                  alt="Gift Card 1"
                />
              </div>
              <div
                class="giftCard w-full h-[calc((100vw/6)-(4rem/5)-6px)] rounded-lg border-[2px] bg-gradient-to-b from-[#1c1c1d] to-[#111] bg-opacity-25 border-[#232325] flex items-center justify-center p-4"
              >
                <img
                  class="w-full h-full object-contain"
                  src="imgs/giftcard-sephora.png"
                  alt="Gift Card 1"
                />
              </div>
              <div
                class="giftCard w-full h-[calc((100vw/6)-(4rem/5)-6px)] rounded-lg border-[2px] bg-gradient-to-b from-[#1c1c1d] to-[#111] bg-opacity-25 border-[#232325] flex items-center justify-center p-4"
              >
                <img
                  class="w-full h-full object-contain mt-2 xl:mt-4"
                  src="imgs/giftcard-amazon.png"
                  alt="Gift Card 1"
                />
              </div>
              <div
                class="giftCard w-full h-[calc((100vw/6)-(4rem/5)-6px)] rounded-lg border-[2px] bg-gradient-to-b from-[#1c1c1d] to-[#111] bg-opacity-25 border-[#232325] flex items-center justify-center p-4"
              >
                <img
                  class="w-full h-full object-contain"
                  src="imgs/giftcard-michaelkors.png"
                  alt="Gift Card 1"
                />
              </div>
              <div
                class="giftCard w-full h-[calc((100vw/6)-(4rem/5)-6px)] rounded-lg border-[2px] bg-gradient-to-b from-[#1c1c1d] to-[#111] bg-opacity-25 border-[#232325] flex items-center justify-center p-4"
              >
                <img
                  class="w-full h-full object-contain"
                  src="imgs/giftcard-target.png"
                  alt="Gift Card 1"
                />
              </div>
            </div>
          </div>
        </section>

        <section class="three-features font-inter">
          <div
            class="wrapper max-w-[1440px] gap-8 lg:gap-0 mx-auto px-8 flex flex-col items-center"
          >
            <div
              class="feature flex flex-wrap md:flex-nowrap items-center gap-4 lg:gap-16 w-full xl:w-[calc(100%-4rem)] lg:py-16 lg:pr-24"
            >
              <span
                class="hidden md:block w-[6px] rounded-md h-[30px] bg-white bg-opacity-[0.3] mr-8"
              ></span>
              <div
                class="iconWrapper w-[50px] h-[50px] xl:w-[100px] xl:h-[100px] flex-shrink-0 flex items-center justify-center"
              >
                <img
                  class="block w-full h-full object-contain"
                  src="imgs/opinion-elite.png"
                  alt="Opinion Elite"
                />
              </div>
              <h2
                class="text-white flex-shrink-0 font-semibold text-[30px] xl:text-[45px] tracking-[-0.03em] leading-none w-[120px] xl:w-[300px] mr-7 xl:mr-12"
              >
                Opinion<br />Elite
              </h2>
              <p
                class="text-white w-full md:w-auto text-opacity-[0.65] text-[14px] xl:text-[17px] font-normal"
              >
                Opinion Elite is more than just a survey platform; it's your
                platform to voice your thoughts, shape opinions, and influence
                decisions. Our sleek interface and user-friendly design make
                sharing your feedback a breeze.
              </p>
            </div>
            <div
              class="feature bg-gradient-to-r from-[#333] to-[#000] rounded-lg xl:bg-none xl:rounded-none border border-[#EC990D] xl:border-none flex flex-wrap md:flex-nowrap p-8 md:p-0 md:py-16 md:pr-16 items-center gap-4 lg:gap-16 w-full xl:py-32 xl:pl-32 xl:pr-24 relative"
            >
              <img
                src="imgs/secure-your-spot-bg.png"
                class="absolute hidden xl:block inset-0 z-[-1] w-full h-auto xl:w-auto xl:h-full object-contain"
              />
              <span
                class="hidden md:block text-[12px] xl:hidden w-[6px] rounded-md h-[30px] text-[#fc9a14] mr-8"
                >▶</span
              >
              <div
                class="iconWrapper w-[50px] h-[50px] xl:w-[100px] xl:h-[100px] flex-shrink-0 flex items-center justify-center"
              >
                <img
                  class="w-[50px] h-[50px] xl:w-[100px] xl:h-[100px]"
                  src="imgs/secure-your-spot.png"
                  alt="Opinion Elite"
                />
              </div>
              <h2
                class="text-[#EC990D] flex-shrink-0 font-semibold text-[30px] xl:text-[45px] tracking-[-0.03em] leading-none w-[140px] xl:w-[300px] mr-0 xl:mr-12"
              >
                Secure<br />your spot
              </h2>
              <p class="text-white text-[14px] xl:text-[17px] font-normal">
                Signing up is quick and easy. Simply enter your email address,
                and you'll be on your way to unlocking a world of possibilities
                with Opinion Elite.
              </p>
            </div>
            <div
              class="feature flex flex-wrap md:flex-nowrap items-center gap-4 lg:gap-16 w-full xl:w-[calc(100%-4rem)] lg:py-16 lg:pr-24"
            >
              <span
                class="hidden md:block w-[6px] rounded-md h-[30px] bg-white bg-opacity-[0.3] mr-8"
              ></span>
              <div
                class="iconWrapper w-[50px] h-[50px] xl:w-[100px] xl:h-[100px] flex-shrink-0 flex items-center justify-center"
              >
                <img
                  class="block w-full h-full object-contain"
                  src="imgs/join-today.png"
                  alt="Opinion Elite"
                />
              </div>
              <h2
                class="text-white flex-shrink-0 font-semibold text-[30px] xl:text-[45px] tracking-[-0.03em] leading-none w-[120px] xl:w-[300px] mr-7 xl:mr-12"
              >
                Join<br />today
              </h2>
              <p
                class="text-white w-full md:w-auto text-opacity-[0.65] text-[14px] xl:text-[17px] font-normal"
              >
                By signing up, you're not just getting access - you're becoming
                a founding member of a movement that values your insights and
                empowers you to make a difference in brands you love.
              </p>
            </div>
          </div>
        </section>

        <section class="two-features mt-24 font-inter relative pb-2">
          <img
            src="imgs/survey-rewards-bg.png"
            class="absolute h-auto w-[150%] object-cover inset-0 top-[25%] z-[-1]"
          />
          <div
            class="wrapper gap-8 xl:gap-0 max-w-[1440px] mx-auto px-8 flex md:flex-row flex-col items-center"
          >
            <div
              class="feature w-full md:w-1/2 flex flex-col items-center gap-2"
            >
              <img
                class="w-full h-auto flex-shrink-0 xl:w-[754px] xl:h-[498px]"
                src="imgs/surveys.png"
                alt="Surveys"
              />
              <h2
                class="flex items-center justify-start gap-2 text-white font-semibold text-[40px] md:text-[50px]"
              >
                <img
                  src="imgs/surveys-icon.png"
                  class="w-[45px] h-[45px] md:w-[74px] md:h-[74px]"
                />
                Surveys
              </h2>
              <p
                class="text-white text-opacity-[0.65] text-[14px] md:text-[17px] font-normal tracking-[-0.03em] text-center max-w-[480px]"
              >
                Voice your opinions and shape real products with Opinion<br
                  class="hidden xl:block"
                />
                Elite's intuitive survey platform. Dive into thought-provoking
                questions spanning a wide range of topics, from global<br
                  class="hidden xl:block"
                />
                trends to everyday preferences.
              </p>
            </div>
            <div
              class="feature w-full md:w-1/2 flex flex-col items-center gap-2"
            >
              <img
                class="w-full h-auto flex-shrink-0 :w-[754px] xl:h-[498px]"
                src="imgs/rewards.png"
                alt="Surveys"
              />
              <h2
                class="flex items-center justify-start gap-2 text-white font-semibold text-[40px] md:text-[50px]"
              >
                <img
                  src="imgs/rewards-icon.png"
                  class="w-[45px] h-[45px] md:w-[74px] md:h-[74px]"
                />
                Rewards
              </h2>
              <p
                class="text-white text-opacity-[0.65] text-[14px] md:text-[17px] font-normal tracking-[-0.03em] text-center max-w-[480px]"
              >
                We take the time you invest and give it back through the<br
                  class="hidden xl:block"
                />
                highest paid cash reward than any other platform along with deep
                discounts on products and exclusive<br
                  class="hidden xl:block"
                />
                access to games.
              </p>
            </div>
          </div>
        </section>

        <section class="cta mt-24 lg:mt-48 font-inter">
          <a
            href="signup.php"
            class="w-full h-[90px] flex items-center justify-center relative text-center border-t border-b border-[#FFEC19] text-black bg-gradient-to-r from-[#FEC40A] to-[#FD7A1E] uppercase font-semibold mb-24 md:mb-48 text-[20px]"
          >
            <!-- <img
            src="imgs/banner-bg.png"
            class="absolute h-full w-auto object-cover inset-0 z-[-1]"
          /> -->
            Be heard, join now
            <span class="text-[20px] ml-2 leading-none">▸</span>
          </a>
          <div
            class="wrapper w-full max-w-[1440px] mx-auto px-8 flex flex-col items-center"
          >
            <div
              class="impactful-insight relative w-full flex flex-col items-center"
            >
              <img
                src="imgs/impactful-insight-bg.png"
                class="absolute w-full inset-0 z-0"
              />
              <img
                src="imgs/impactful-insight.png"
                class="w-full h-auto md:w-[672px] md:h-[634px] mt-[-15vw] md:mt-[-150px] z-[99]"
              />
              <h3
                class="text-white font-semibold text-[35px] md:text-[50px] tracking-[-0.03em] mt-[-40px] md:mt-[-80px]"
              >
                Impactful Insight
              </h3>
              <p
                class="text-white text-opacity-[0.65] text-[15px] md:text-[20px] font-normal tracking-[-0.03em] text-center max-w-[720px]"
              >
                Your opinions matter. With Opinion Elite, your feedback directly
                influences brands, products, and services. Shape the world
                around you by sharing your thoughts.
              </p>
            </div>
            <div
              class="miss-out rounded-[25px] xl:rounded-none mt-12 p-6 xl:p-0 md:mt-24 h-max border border-[#202020] bg-gradient-to-b from-[#161617] to-[#1c1e21] xl:bg-none xl:border-none xl:to-unset xl:from-unset xl:h-[546px] w-full flex flex-col items-center justify-center gap-2 relative"
            >
              <img
                src="imgs/miss-out-bg.png"
                class="xl:block hidden absolute inset-0 z-[-1]"
                alt="Miss Out"
              />
              <h3
                class="font-semibold mt-12 xl:mt-0 text-transparent text-[20px] sm:text-[25px] md:text-[30px] lg:text-[34px] tracking-[-0.04em] max-w-[1100px] text-center bg-gradient-to-b from-[#E1EBFA] to-[#A5ADB8] bg-clip-text"
              >
                Don't miss out on this opportunity to be part of something
                groundbreaking. Sign up now and be at the forefront of the
                Opinion Elite community. Together, let's revolutionize the way
                opinions are heard.
              </h3>
              <a
                href="signup.php"
                class="w-[200px] h-[52px] md:w-[300px] md:h-[80px] flex items-center justify-center relative my-12"
              >
                <span
                  class="relative z-[10] text-[12px] md:text-[16px] font-bold tracking-[0.03em] leading-none uppercase"
                  >Join Opinion Elite</span
                >
                <img
                  class="absolute inset-0 z-0"
                  src="imgs/join-button.png"
                  alt="Signup"
                />
              </a>
              <span class="block text-[#b4bdc9] text-[15px] xl:hidden">▲</span>
            </div>
          </div>
        </section>
        <footer class="footer py-12">
          <div
            class="wrapper max-w-[1440px] mx-auto p-8 flex md:flex-row flex-col items-center justify-start md:justify-between"
          >
            <div
              class="footerLinks order-2 md:order-1 flex items-center gap-4 text-[12px] lg:text-[16px] font-medium text-white text-opacity-[0.65] uppercase"
            >
              <a class="" href="">Privacy Policy</a>
              <a class="" href="">Terms of Service</a>
            </div>
            <img
              src="imgs/footer-logo.png"
              class="order-1 mb-4 md:mb-0 md:order-2 w-[221px] h-[62px] lg:w-[332px] lg:h-[93px] block"
            />
            <p
              class="text-[12px] order-3 lg:text-[16px] font-medium text-white text-opacity-[0.65]"
            >
              Copyright &copy; 2025 QuantifyAi
            </p>
          </div>
        </footer>
      </main>

      <div
        id="legal-modal"
        class="fixed inset-0 bg-black/50 z-[999] hidden items-center justify-center backdrop-blur-sm p-4"
      >
        <div
          class="bg-[#081226] border border-[#fd9f15] rounded-xl w-full h-full lg:w-[80%] lg:h-[80vh] mx-auto relative flex flex-col"
        >
          <button
            id="close-modal"
            class="absolute top-4 right-4 text-white/50 hover:text-white"
          >
            <svg
              xmlns="http://www.w3.org/2000/svg"
              width="24"
              height="24"
              viewBox="0 0 24 24"
              fill="none"
              stroke="currentColor"
              stroke-width="2"
              stroke-linecap="round"
              stroke-linejoin="round"
            >
              <line x1="18" y1="6" x2="6" y2="18"></line>
              <line x1="6" y1="6" x2="18" y2="18"></line>
            </svg>
          </button>
          <h2
            id="modal-title"
            class="text-2xl font-bold text-white p-8 border-b border-[#fd9f15]"
          ></h2>
          <div
            id="modal-content"
            class="text-white/50 text-lg leading-relaxed p-8 overflow-y-auto flex-1 custom-scrollbar"
          >
            <!-- Content will be inserted here -->
          </div>
        </div>
      </div>

      <div
        id="preloader"
        class="fixed inset-0 z-50 flex items-center justify-center bg-[#111] z-[999]"
      >
        <div class="animate-pulse">
          <!-- You could put your logo here or a simple loading animation -->
          <div
            class="w-32 h-32 rounded-full border-4 border-t-[#fd9f15] border-r-[#fd9f15] border-b-transparent border-l-transparent animate-spin"
          ></div>
        </div>
      </div>
    </div>

    <script>
      const correctUsernameHash =
        '216bfaef293aa2513c011c2643b253f28a0b0aac20a769cb2a15518fb1cf3e7f';

      const correctPasswordHash =
        'dd3b44a5cdb168769893b8096c28a09cdff2b7696e13dd7362231b0ad1b95b71';

      // Function to hash the user's input
      async function hashPassword(input) {
        const encoder = new TextEncoder();
        const data = encoder.encode(input);
        const hashBuffer = await crypto.subtle.digest('SHA-256', data);
        const hashArray = Array.from(new Uint8Array(hashBuffer));
        return hashArray.map((b) => b.toString(16).padStart(2, '0')).join('');
      }

      // Check username and password function
      async function checkPassword() {
        const userInput = document.getElementById('username-input').value.trim();
        const passInput = document.getElementById('password-input').value.trim();
        const errorMessage = document.getElementById('error-message');

        errorMessage.classList.add('invisible');
        errorMessage.textContent = '';

        if (!userInput || !passInput) {
          errorMessage.textContent = 'Please enter both username and password.';
          errorMessage.classList.remove('invisible');
          return;
        }

        const formData = new FormData();
        formData.append('username', userInput);
        formData.append('password', passInput);

        try {
          const response = await fetch('login.php', {
            method: 'POST',
            body: formData
          });
          const result = await response.json();

          if (result.success) {
            localStorage.setItem('passwordVerified', 'true');
            localStorage.setItem('username', result.username);

            // 🔹 NEW: store user_type for later use (header, change password, etc.)
            const userType = result.user_type || 'direct';
            localStorage.setItem('userType', userType);

            window.location.href = 'UI/index.php';
          } else {
            errorMessage.textContent = result.message;
            errorMessage.classList.remove('invisible');
          }
        } catch (error) {
          console.error('Error:', error);
          errorMessage.textContent = 'Server error. Try again later.';
          errorMessage.classList.remove('invisible');
        }
      }

      // Initialize and add Enter key listener after DOM is loaded
      document.addEventListener('DOMContentLoaded', () => {
        const content = document.getElementById('content');
        const modal = document.getElementById('password-modal');
        const usernameInput = document.getElementById('username-input');
        const passwordInput = document.getElementById('password-input');

        if (localStorage.getItem('passwordVerified') === 'true') {
          modal.classList.add('hidden');
          content.style.display = 'block';
        } else {
          content.style.display = 'none';
          modal.classList.remove('hidden');
          usernameInput.focus();
        }

        passwordInput.addEventListener('keydown', (e) => {
          if (e.key === 'Enter') {
            checkPassword();
          }
        });
      });
    </script>
  </body>
</html>
