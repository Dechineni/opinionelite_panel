<!doctype html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Opinion Elite</title>
    <link href="output.css" rel="stylesheet" />
    <script src="scripts/main.js"></script>
    <link rel="icon" sizes="64x64" href="favicon.png" type="image/png" />

    <!-- FORCE AUTH LAYOUT (works even if Tailwind classes aren't present in output.css) -->
    <style>
      .oe-auth { width: 100%; max-width: 720px; margin-top: 28px; }
      .oe-auth-row {
        display: flex;
        flex-direction: column;
        gap: 16px;
        align-items: flex-start;
        justify-content: flex-start;
      }
      @media (min-width: 768px) {
        .oe-auth-row { flex-direction: row; flex-wrap: nowrap; }
      }
      .oe-pill {
        width: 100%;
        max-width: 520px;
        height: 80px;
        border-radius: 999px;
        overflow: hidden;
        display: flex;
        align-items: center;
        justify-content: flex-start;
        position: relative;
        text-decoration: none;
      }
      @media (min-width: 768px) {
        .oe-pill { width: 330px; max-width: none; }
      }
      .oe-pill-bg { position: absolute; inset: 0; width: 100%; height: 100%; object-fit: cover; }
      .oe-pill-content {
        position: relative;
        z-index: 10;
        display: flex;
        align-items: center;
        gap: 12px;
        padding-left: 16px;
        font-size: 16px;
        font-weight: 800;
        letter-spacing: 0.03em;
        text-transform: uppercase;
        line-height: 1;
        color: #000;
        white-space: nowrap;
      }
      .oe-pill-icon { width: 40px; height: 40px; object-fit: contain; flex: 0 0 auto; }
      .oe-divider { display: flex; align-items: center; gap: 14px; margin: 22px 0; width: 100%; }
      .oe-divider::before, .oe-divider::after {
        content: "";
        flex: 1;
        height: 1px;
        background: rgba(255,255,255,0.25);
      }
      .oe-divider span {
        color: rgba(255,255,255,0.75);
        font-size: 12px;
        letter-spacing: 0.14em;
        text-transform: uppercase;
        white-space: nowrap;
      }
      @media (max-width: 1279px) {
        .oe-auth-row { align-items: center; justify-content: center; }
      }
    </style>
  </head>

  <body class="flex flex-col items-center justify-start bg-gradient-to-br from-[#171717] to-[#000] font-gilroy">
    <div id="content">
      <!-- Top nav: logo centered -->
      <nav class="nav w-full mb-12">
        <div class="wrapper mx-auto flex items-center justify-center w-full max-w-[1440px] p-8">
          <a href="/" class="logo w-[111px] h-[31px] sm:w-[222px] sm:h-[62px] md:w-[332px] md:h-[93px] block">
            <img src="imgs/logo.png" alt="Logo" />
          </a>
        </div>
      </nav>

      <main class="main flex flex-col w-full">
        <section class="intro w-full relative">
          <div class="wrapper gap-8 mx-auto max-w-[1440px] px-8 flex flex-col xl:flex-row items-center">
            <!-- LEFT SIDE: headline + sign up / sign in buttons -->
            <div class="text flex flex-col items-center xl:items-start w-full max-w-[700px] lg:max-w-auto lg:w-3/5">
              <h1 class="text-[50px] md:text-[65px] text-white font-semibold leading-none tracking-[-0.03em] mb-8">
                Be Heard Be Elite
              </h1>
              <p class="text-white xl:text-left text-center text-opacity-[0.85] text-[20px] md:text-[23px] font-semibold max-w-[540px]">
                Jump into Opinion Elite and unlock exclusive cash rewards reserved just for you.
              </p>

              <!-- AUTH BUTTONS -->
              <div class="oe-auth">
                <!-- SIGN UP -->
                <div class="oe-auth-row">
                  <a href="linked_in.php?flow=signup" class="oe-pill">
                    <img src="imgs/join-button.png" alt="Sign up via LinkedIn" class="oe-pill-bg" />
                    <span class="oe-pill-content">
                      <img src="imgs/form-li-button.png" alt="LinkedIn" class="oe-pill-icon" />
                      SIGN UP VIA LINKEDIN
                    </span>
                  </a>

                  <a href="facebook_login.php?flow=signup" class="oe-pill">
                    <img src="imgs/join-button.png" alt="Sign up via Facebook" class="oe-pill-bg" />
                    <span class="oe-pill-content">
                      <img src="imgs/form-fb-button.png" alt="Facebook" class="oe-pill-icon" />
                      SIGN UP VIA FACEBOOK
                    </span>
                  </a>
                </div>

                <div class="oe-divider">
                  <span>Already have an account</span>
                </div>

                <!-- SIGN IN -->
                <div class="oe-auth-row">
                  <a href="linked_in.php?flow=signin" class="oe-pill">
                    <img src="imgs/join-button.png" alt="Sign in via LinkedIn" class="oe-pill-bg" />
                    <span class="oe-pill-content">
                      <img src="imgs/form-li-button.png" alt="LinkedIn" class="oe-pill-icon" />
                      SIGN IN VIA LINKEDIN
                    </span>
                  </a>

                  <a href="facebook_login.php?flow=signin" class="oe-pill">
                    <img src="imgs/join-button.png" alt="Sign in via Facebook" class="oe-pill-bg" />
                    <span class="oe-pill-content">
                      <img src="imgs/form-fb-button.png" alt="Facebook" class="oe-pill-icon" />
                      SIGN IN VIA FACEBOOK
                    </span>
                  </a>
                </div>
              </div>

              <p class="mt-6 text-[13px] text-white/60 text-center xl:text-left max-w-[460px]">
                By continuing, you agree to the
                <a href="" class="underline font-bold">Privacy Policy</a>
                and
                <a href="" class="underline font-bold">Terms of Service</a>.
              </p>
            </div>

            <!-- RIGHT SIDE: phone mockup / video -->
            <div class="hidden xl:flex video w-2/5 relative flex justify-end">
              <img class="w-[194px] h-[700px] block mr-[200px] xl:mr-[330px] object-contain" src="imgs/video.png" alt="Video" />
              <div class="rounded-[25px] border-2 border-gray-800 overflow-hidden block w-[330px] h-[740px] absolute top-[-2%]">
                <video class="block w-full h-full object-cover" autoplay loop playsinline muted>
                  <source src="video/app-video.mp4" type="video/mp4" />
                </video>
              </div>
            </div>
          </div>
        </section>

        <footer class="footer py-12">
          <div class="wrapper max-w-[1440px] mx-auto p-8 flex md:flex-row flex-col items-center justify-start md:justify-between">
            <div class="footerLinks order-2 md:order-1 flex items-center gap-4 text-[12px] lg:text-[16px] font-medium text-white text-opacity-[0.65] uppercase">
              <a class="" href="">Privacy Policy</a>
              <a class="" href="">Terms of Service</a>
            </div>
            <img src="imgs/footer-logo.png" class="order-1 mb-4 md:mb-0 md:order-2 w-[221px] h-[62px] lg:w-[332px] lg:h-[93px] block" alt="Footer logo" />
            <p class="text-[12px] order-3 lg:text-[16px] font-medium text-white text-opacity-[0.65]">
              Copyright &copy; 2025 QuantifyAi
            </p>
          </div>
        </footer>
      </main>

      <!-- Legal modal + preloader -->
      <div id="legal-modal" class="fixed inset-0 bg-black/50 z-[999] hidden items-center justify-center backdrop-blur-sm p-4">
        <div class="bg-[#151515] border border-[#fd9f15] rounded-xl w-full h-full lg:w-[80%] lg:h-[80vh] mx-auto relative flex flex-col">
          <button id="close-modal" class="absolute top-4 right-4 text-white/50 hover:text-white">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <line x1="18" y1="6" x2="6" y2="18"></line>
              <line x1="6" y1="6" x2="18" y2="18"></line>
            </svg>
          </button>
          <h2 id="modal-title" class="text-2xl font-bold text-white p-8 border-b border-[#fd9f15]"></h2>
          <div id="modal-content" class="text-white/50 text-lg leading-relaxed p-8 overflow-y-auto flex-1 custom-scrollbar"></div>
        </div>
      </div>

      <div id="preloader" class="fixed inset-0 z-50 flex items-center justify-center bg-[#111] z-[999]">
        <div class="animate-pulse">
          <div class="w-32 h-32 rounded-full border-4 border-t-[#fd9f15] border-r-[#fd9f15] border-b-transparent border-l-transparent animate-spin"></div>
        </div>
      </div>
    </div>
  </body>
</html>
