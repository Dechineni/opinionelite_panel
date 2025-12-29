<!doctype html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Opinion Elite</title>
    <link href="output.css" rel="stylesheet" />
    <script src="scripts/main.js"></script>
    <link rel="icon" sizes="64x64" href="favicon.png" type="image/png" />
  </head>
  <body
    class="flex flex-col items-center justify-start bg-gradient-to-br from-[#171717] to-[#000] font-gilroy"
  >
    <div id="content">
      <!-- Top nav: logo centered -->
      <nav class="nav w-full mb-12">
        <div
          class="wrapper mx-auto flex items-center justify-center w-full max-w-[1440px] p-8"
        >
          <a
            href="/"
            class="logo w-[111px] h-[31px] sm:w-[222px] sm:h-[62px] md:w-[332px] md:h-[93px] block"
          >
            <img src="imgs/logo.png" alt="Logo" />
          </a>
        </div>
      </nav>

      <main class="main flex flex-col w-full">
        <section class="intro w-full relative">
          <div
            class="wrapper gap-8 mx-auto max-w-[1440px] px-8 flex flex-col xl:flex-row items-center"
          >
            <!-- LEFT SIDE: headline + sign-in buttons -->
            <div
              class="text flex flex-col items-center xl:items-start w-full max-w-[700px] lg:max-w-auto lg:w-3/5"
            >
              <h1
                class="text-[50px] md:text-[65px] text-white font-semibold leading-none tracking-[-0.03em] mb-8"
              >
                Be Heard Be Elite
              </h1>
              <p
                class="text-white xl:text-left text-center text-opacity-[0.85] text-[20px] md:text-[23px] font-semibold max-w-[540px]"
              >
                Sign in to Opinion Elite and unlock exclusive cash rewards
                reserved just for you.
              </p>

              <div
                class="flex flex-col items-center xl:items-start gap-4 mt-12"
              >
                <!-- Sign in via LinkedIn -->
                <a
                  href="linked_in.php"
                  class="relative w-[300px] h-[80px] flex items-center justify-start rounded-full overflow-hidden pl-4"
                >
                  <img
                    src="imgs/join-button.png"
                    alt="Sign in via LinkedIn"
                    class="absolute inset-0 w-full h-full object-cover"
                  />
                  <span
                    class="relative z-[10] flex items-center gap-3 text-[16px] font-bold tracking-[0.03em] leading-none uppercase"
                  >
                    <img
                      src="imgs/form-li-button.png"
                      alt="LinkedIn"
                      class="w-[40px] h-[40px] object-contain"
                    />
                    SIGN IN VIA LINKEDIN
                  </span>
                </a>

                <!-- Sign in via Facebook (UI only for now) -->
                <a
                  href="#"
                  class="relative w-[300px] h-[80px] flex items-center justify-start rounded-full overflow-hidden pl-4 opacity-70 cursor-not-allowed"
                  title="Facebook sign-in coming soon"
                >
                  <img
                    src="imgs/join-button.png"
                    alt="Sign in via Facebook"
                    class="absolute inset-0 w-full h-full object-cover"
                  />
                  <span
                    class="relative z-[10] flex items-center gap-3 text-[16px] font-bold tracking-[0.03em] leading-none uppercase"
                  >
                    <img
                      src="imgs/form-fb-button.png"
                      alt="Facebook"
                      class="w-[40px] h-[40px] object-contain"
                    />
                    SIGN IN VIA FACEBOOK
                  </span>
                </a>
              </div>

              <p
                class="mt-6 text-[13px] text-white/60 text-center xl:text-left max-w-[460px]"
              >
                By continuing, you agree to the
                <a href="" class="underline font-bold">Privacy Policy</a>
                and
                <a href="" class="underline font-bold">Terms of Service</a>.
              </p>
            </div>

            <!-- RIGHT SIDE: phone mockup / video -->
            <div
              class="hidden xl:flex video w-2/5 relative flex justify-end"
            >
              <img
                class="w-[194px] h-[700px] block mr-[200px] xl:mr-[330px] object-contain"
                src="imgs/video.png"
                alt="Video"
              />
              <div
                class="rounded-[25px] border-2 border-gray-800 overflow-hidden block w-[330px] h-[740px] absolute top-[-2%]"
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
              alt="Footer logo"
            />
            <p
              class="text-[12px] order-3 lg:text-[16px] font-medium text-white text-opacity-[0.65]"
            >
              Copyright &copy; 2025 QuantifyAi
            </p>
          </div>
        </footer>
      </main>

      <!-- Legal modal + preloader -->
      <div
        id="legal-modal"
        class="fixed inset-0 bg-black/50 z-[999] hidden items-center justify-center backdrop-blur-sm p-4"
      >
        <div
          class="bg-[#151515] border border-[#fd9f15] rounded-xl w-full h-full lg:w-[80%] lg:h-[80vh] mx-auto relative flex flex-col"
        >
          <button
            id="close-modal"
            class="absolute top-4 right-4 text-white/50 hover:text-white"
          >
            <!-- close icon -->
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
          ></div>
        </div>
      </div>

      <div
        id="preloader"
        class="fixed inset-0 z-50 flex items-center justify-center bg-[#111] z-[999]"
      >
        <div class="animate-pulse">
          <div
            class="w-32 h-32 rounded-full border-4 border-t-[#fd9f15] border-r-[#fd9f15] border-b-transparent border-l-transparent animate-spin"
          ></div>
        </div>
      </div>
    </div>
  </body>
</html>
