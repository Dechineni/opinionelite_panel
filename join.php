<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';
include('UI/config.php');

session_start();

/**
 * Messages for in-page banners
 */
$signupSuccessMessage = '';
$signupErrorMessage   = '';

// Are we coming from LinkedIn or Facebook for the first time?
$isLinkedinNewUser = isset($_SESSION['linkedin_new_user']) && $_SESSION['linkedin_new_user'] === true;
$isFacebookNewUser = isset($_SESSION['facebook_new_user']) && $_SESSION['facebook_new_user'] === true;

// Prefill values from social login if present (LinkedIn first, then Facebook)
$prefillFirstName = $_SESSION['linkedin_first_name']
    ?? $_SESSION['facebook_first_name']
    ?? '';

$prefillLastName  = $_SESSION['linkedin_last_name']
    ?? $_SESSION['facebook_last_name']
    ?? '';

$prefillEmail     = $_SESSION['linkedin_email']
    ?? $_SESSION['facebook_email']
    ?? '';

if (isset($_GET['signup_success']) && $_GET['signup_success'] === '1') {
    $signupSuccessMessage = 'Signup successfully completed and it will take 48hrs to activate your account.';
}

if (isset($_GET['signup_error']) && $_GET['signup_error'] === 'email_exists') {
    $signupErrorMessage = 'This Email Id is already registered. Please sign in or use a different email address.';
}

if (isset($_POST['signup'])) {
    $fname        = $_POST['firstName'];
    $lname        = $_POST['lastName'];
    $email        = $_POST['email'];
    $country      = $_POST['country'];
    $zipcode      = $_POST['zipCode'];
    $gender       = $_POST['gender'];
    $birthday     = $_POST['birthday'];
    $education    = $_POST['education'];
    $income       = $_POST['income'];
    $job_industry = $_POST['job_industry'];
    $role         = $_POST['role'];
    $job_title    = $_POST['job_title'];
    $username     = $_POST['username'];

    // Mark user_type depending on the flow
    if ($isLinkedinNewUser) {
        $userType = 'linkedin';
    } elseif ($isFacebookNewUser) {
        $userType = 'facebook';
    } else {
        $userType = 'direct';
    }

    $sql  = "INSERT INTO signup(
                firstname,
                lastname,
                email,
                country,
                zipcode,
                gender,
                birthday,
                education,
                income,
                job_industry,
                role,
                job_title,
                username,
                user_type
             ) VALUES (
                '$fname',
                '$lname',
                '$email',
                '$country',
                '$zipcode',
                '$gender',
                '$birthday',
                '$education',
                '$income',
                '$job_industry',
                '$role',
                '$job_title',
                '$username',
                '$userType'
             )";

    $sql1 = "SELECT COUNT(email) FROM signup WHERE email='$email'";
    $rs   = mysqli_query($db, $sql1);
    $rw   = mysqli_fetch_row($rs);

    if ($rw[0] == 0) {
        if (mysqli_query($db, $sql)) {
            $mail = new PHPMailer(true);

            try {
                // Server settings
                $mail->isSMTP();
                $mail->Host       = 'smtp.office365.com';
                $mail->SMTPAuth   = true;
                $mail->Username   = 'noreply@opinionelite.com';  // Microsoft 365 email
                $mail->Password   = 'kkzzxtbjxmhpkjvm';          // Real password or App Password
                $mail->SMTPSecure = 'tls';
                $mail->Port       = 587;

                $mail->setFrom('noreply@opinionelite.com', 'Opinion Elite');
                $mail->addAddress($email, $fname);
                $mail->isHTML(true);
                $mail->Subject = 'Welcome to Opinion Elite!';
                $mail->Body = '
                <div style="max-width: 600px; margin: auto; padding: 20px; font-family: Arial, sans-serif; background-color: #f4f4f4%;">
                  <div style="background-color: #ffffff; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); padding: 30px;">
                    <h2 style="color: #333333; text-align: center;">Welcome to <span style="color: #f1aa3f;">Opinion Elite</span>!</h2>
                    <p style="font-size: 16px; color: #555555;">
                      Hi ' . htmlspecialchars($fname) . '
                    </p>
                    <p style="font-size: 16px; color: #555555; line-height: 1.6;">
                      Signup successfully completed and it will take <strong>48 hours</strong> to activate your account.
                    </p>
                    <p style="font-size: 14px; color: #777777; line-height: 1.6%;">
                      Once your account is active, you will be able to log in and start participating in our exclusive surveys and earn rewards.
                    </p>
                    <div style="text-align: center; margin: 30px 0;">
                      <a href="https://opinionelite.com" style="background-color: #f1aa3f; color: #ffffff; padding: 12px 24px; font-size: 16px; text-decoration: none; border-radius: 5px; display: inline-block;">
                        Visit Opinion Elite
                      </a>
                    </div>
                  </div>
                </div>';

                $mail->send();

                if ($isLinkedinNewUser || $isFacebookNewUser) {
                    // Clear social onboarding session data
                    unset(
                        $_SESSION['linkedin_new_user'],
                        $_SESSION['linkedin_email'],
                        $_SESSION['linkedin_first_name'],
                        $_SESSION['linkedin_last_name'],
                        $_SESSION['facebook_new_user'],
                        $_SESSION['facebook_email'],
                        $_SESSION['facebook_first_name'],
                        $_SESSION['facebook_last_name'],
                        $_SESSION['facebook_id']
                    );

                    // Auto-login social user and go to home
                    echo "<script>
                        localStorage.setItem('passwordVerified', 'true');
                        localStorage.setItem('username', " . json_encode($username) . ");
                        window.location.href = 'UI/index.php';
                    </script>";
                    exit;
                } else {
                    // Redirect with success flag for orange banner (direct signup)
                    header('Location: index.php?signup_success=1');
                    exit;
                }
            } catch (Exception $e) {
                echo "❌ Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
            }
        } else {
            echo mysqli_error($db);
        }
    } else {
        // Redirect with error flag for duplicate email banner
        header('Location: index.php?signup_error=email_exists');
        exit;
    }
}
?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Opinion Elite</title>
    <link href="output.css" rel="stylesheet" />
    <script src="scripts/main.js?v=20251216"></script>
    <link rel="icon" sizes="64x64" href="favicon.png" type="image/png" />

    <script>
      document.addEventListener('DOMContentLoaded', function () {
        const countrySelect   = document.getElementById('country');
        const educationSelect = document.getElementById('education');
        const incomeSelect    = document.getElementById('income');

        if (!countrySelect || !educationSelect || !incomeSelect) return;

        function resetSelect(selectElement, placeholderText) {
          selectElement.innerHTML = '';
          const placeholder = document.createElement('option');
          placeholder.value = '';
          placeholder.textContent = placeholderText;
          placeholder.disabled = true;
          placeholder.selected = true;
          placeholder.hidden = true;
          selectElement.appendChild(placeholder);
        }

        // Initially disable education+income until a country is chosen
        resetSelect(educationSelect, 'Education');
        resetSelect(incomeSelect, 'Household Income (Pre-Tax)');
        educationSelect.disabled = true;
        incomeSelect.disabled = true;

        // Load country data from JSON generated from Excel
        fetch('countries.json', { cache: 'no-cache' })
          .then(function (res) {
            if (!res.ok) throw new Error('Failed to load countries.json');
            return res.json();
          })
          .then(function (COUNTRY_DATA) {
            // Populate Country dropdown (sorted alphabetically)
            const countries = Object.keys(COUNTRY_DATA).sort(function (a, b) {
              return a.localeCompare(b);
            });

            countries.forEach(function (country) {
              const opt = document.createElement('option');
              opt.value = country;
              opt.textContent = country;
              countrySelect.appendChild(opt);
            });

            // On country change → update Education and Income for that country
            countrySelect.addEventListener('change', function () {
              const selectedCountry = this.value;
              const data = COUNTRY_DATA[selectedCountry];

              resetSelect(educationSelect, 'Education');
              resetSelect(incomeSelect, 'Household Income (Pre-Tax)');

              if (!data) {
                educationSelect.disabled = true;
                incomeSelect.disabled = true;
                return;
              }

              // Fill Education dropdown
              (data.educations || []).forEach(function (edu) {
                const opt = document.createElement('option');
                opt.value = edu;
                opt.textContent = edu;
                educationSelect.appendChild(opt);
              });

              // Fill Income dropdown (already in correct currency + brackets)
              (data.incomes || []).forEach(function (inc) {
                const opt = document.createElement('option');
                opt.value = inc;
                opt.textContent = inc;
                incomeSelect.appendChild(opt);
              });

              educationSelect.disabled = false;
              incomeSelect.disabled = false;
            });
          })
          .catch(function (err) {
            console.error('Error loading countries.json:', err);
            // Optional: you could show a small message or fallback here if needed
          });
      });
    </script>
  </head>

  <body
    class="flex flex-col items-center justify-start bg-gradient-to-br from-[#171717] to-[#000] font-gilroy"
  >
    <!-- Main Content -->
    <div id="content">
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
            <div
              class="text flex flex-col items-center w-full max-w-[700px] lg:max-w-auto lg:w-3/5"
            >
              <h1
                class="text-[50px] md:text-[70px] text-white font-semibold leading-none tracking-[-0.03em] mb-12"
              >
                Join the Elite
              </h1>
              <p
                class="text-white xl:text-left text-center text-opacity-[0.85] text-[20px] md:text-[23px] font-semibold"
              >
                Join our elite group of members in Opinion Elite and unlock
                exclusive cash rewards reserved just for you!
              </p>

              <!-- 🔶 ORANGE SUCCESS BANNER -->
              <?php if (!empty($signupSuccessMessage)): ?>
                <div
                  style="
                    width:100%;
                    max-width:640px;
                    margin:16px auto 12px auto;
                    padding:12px 16px;
                    border-radius:8px;
                    background:rgba(241,170,63,0.12);
                    border:1px solid #fd8e19;
                    color:#ffe7bf;
                    font-size:14px;
                    line-height:1.5;
                    text-align:left;
                  "
                >
                  <?php echo htmlspecialchars($signupSuccessMessage); ?>
                </div>
              <?php endif; ?>

              <!-- 🔴 ERROR BANNER (duplicate email) -->
              <?php if (!empty($signupErrorMessage)): ?>
                <div
                  style="
                    width:100%;
                    max-width:640px;
                    margin:16px auto 12px auto;
                    padding:12px 16px;
                    border-radius:8px;
                    background:rgba(248,113,113,0.12);
                    border:1px solid #f97373;
                    color:#fecaca;
                    font-size:14px;
                    line-height:1.5;
                    text-align:left;
                  "
                >
                  <?php echo htmlspecialchars($signupErrorMessage); ?>
                </div>
              <?php endif; ?>

              <form method="post">
                <div class="form grid grid-cols-1 sm:grid-cols-2 gap-8 py-12">
                  <!-- First Name -->
                  <div class="pseudoInput relative">
                    <input
                      class="peer w-full text-white font-semibold text-[19px] placeholder:text-[#494940] border-2 border-[#262629] bg-gradient-to-b from-[#111112] to-[#151517] rounded-md p-4 pt-6 hover:border-[#363639] focus:border-[#f1aa3f] focus:from-[#1a1918] focus:to-[#272219] active:border-[#f1aa3f] ring-0 focus:ring-0 outline-none"
                      type="text"
                      id="firstName"
                      name="firstName"
                      placeholder="eg. John"
                      value="<?php echo htmlspecialchars($prefillFirstName); ?>"
                      required
                    />
                    <label
                      class="absolute top-3 left-[calc(1rem+2px)] text-[#45454D] peer-focus:text-[#f1aa3f] font-semibold uppercase text-[12px]"
                      >FIRST NAME</label
                    >
                  </div>

                  <!-- Last Name -->
                  <div class="pseudoInput relative">
                    <input
                      class="peer w-full text-white font-semibold text-[19px] placeholder:text-[#494940] border-2 border-[#262629] bg-gradient-to-b from-[#111112] to-[#151517] rounded-md p-4 pt-6 hover:border-[#363639] focus:border-[#f1aa3f] focus:from-[#1a1918] focus:to-[#272219] active:border-[#f1aa3f] ring-0 focus:ring-0 outline-none"
                      type="text"
                      id="lastName"
                      name="lastName"
                      placeholder="eg. Doe"
                      value="<?php echo htmlspecialchars($prefillLastName); ?>"
                      required
                    />
                    <label
                      class="absolute top-3 left-[calc(1rem+2px)] text-[#45454D] peer-focus:text-[#f1aa3f] font-semibold uppercase text-[12px]"
                      >LAST NAME</label
                    >
                  </div>

                  <!-- Email -->
                  <div class="pseudoInput relative">
                    <input
                      class="peer w-full text-white font-semibold text-[19px] placeholder:text-[#494940] border-2 border-[#262629] bg-gradient-to-b from-[#111112] to-[#151517] rounded-md p-4 pt-6 hover:border-[#363639] focus:border-[#f1aa3f] focus:from-[#1a1918] focus:to-[#272219] active:border-[#f1aa3f] ring-0 focus:ring-0 outline-none"
                      type="email"
                      id="email"
                      name="email"
                      placeholder="eg. john.doe@example.com"
                      value="<?php echo htmlspecialchars($prefillEmail); ?>"
                      <?php echo ($isLinkedinNewUser || $isFacebookNewUser) ? 'readonly' : ''; ?>
                      required
                    />
                    <label
                      class="absolute top-3 left-[calc(1rem+2px)] text-[#45454D] peer-focus:text-[#f1aa3f] font-semibold uppercase text-[12px]"
                      >EMAIL</label
                    >
                  </div>

                  <!-- Country -->
                  <div class="pseudoInput relative">
                    <select
                      class="peer w-full font-semibold text-[19px] border-2 border-[#262629] bg-gradient-to-b from-[#111112] to-[#151517] rounded-md p-4 hover:border-[#363639] focus:border-[#f1aa3f] focus:from-[#1a1918] focus:to-[#272219] ring-0 focus:ring-0 outline-none appearance-none"
                      id="country"
                      name="country"
                      required
                    >
                      <option value="" disabled selected hidden>Country</option>
                    </select>
                  </div>

                  <!-- Zip Code -->
                  <div class="pseudoInput relative">
                    <input
                      class="peer w-full text-white font-semibold text-[19px] placeholder:text-[#494940] border-2 border-[#262629] bg-gradient-to-b from-[#111112] to-[#151517] rounded-md p-4 pt-6 hover:border-[#363639] focus:border-[#f1aa3f] focus:from-[#1a1918] focus:to-[#272219] active:border-[#f1aa3f] ring-0 focus:ring-0 outline-none appearance-none"
                      type="number"
                      id="zipCode"
                      name="zipCode"
                      placeholder="eg. 12345"
                      required
                      min="10000"
                      max="99999"
                    />
                    <label
                      class="absolute top-3 left-[calc(1rem+2px)] text-[#45454D] peer-focus:text-[#f1aa3f] font-semibold uppercase text-[12px]"
                      >ZIP CODE</label
                    >
                  </div>

                  <!-- Gender -->
                  <div class="pseudoInput relative">
                    <select
                      class="peer w-full font-semibold text-[19px] border-2 border-[#262629] bg-gradient-to-b from-[#111112] to-[#151517] rounded-md p-4 hover:border-[#363639] focus:border-[#f1aa3f] focus:from-[#1a1918] focus:to-[#272219] ring-0 focus:ring-0 outline-none appearance-none"
                      id="gender"
                      name="gender"
                      required
                    >
                      <option value="" disabled selected hidden>
                        Gender closest identify with
                      </option>
                      <option value="Female">Female</option>
                      <option value="Male">Male</option>
                      <option value="Other">Other</option>
                    </select>
                  </div>

                  <!-- Date of birth -->
                  <div class="pseudoInput relative">
                    <input
                      class="peer w-full font-semibold text-[19px] placeholder:text-[#494940] border-2 border-[#262629] bg-gradient-to-b from-[#111112] to-[#151517] rounded-md p-4 pt-6 hover:border-[#363639] focus:border-[#f1aa3f] focus:from-[#1a1918] focus:to-[#272219] active:border-[#f1aa3f] ring-0 focus:ring-0 outline-none text-[#494940]"
                      type="date"
                      id="birthday"
                      name="birthday"
                      required
                    />
                  </div>

                  <!-- Education -->
                  <div class="pseudoInput relative">
                    <select
                      class="peer w-full font-semibold text-[19px] border-2 border-[#262629] bg-gradient-to-b from-[#111112] to-[#151517] rounded-md p-4 hover:border-[#363639] focus:border-[#f1aa3f] focus:from-[#1a1918] focus:to-[#272219] ring-0 focus:ring-0 outline-none appearance-none"
                      id="education"
                      name="education"
                      required
                    >
                      <!-- Options filled via JS -->
                    </select>
                  </div>

                  <!-- Household Income -->
                  <div class="pseudoInput relative">
                    <select
                      class="peer w-full font-semibold text-[19px] border-2 border-[#262629] bg-gradient-to-b from-[#111112] to-[#151517] rounded-md p-4 hover:border-[#363639] focus:border-[#f1aa3f] focus:from-[#1a1918] focus:to-[#272219] ring-0 focus:ring-0 outline-none appearance-none"
                      id="income"
                      name="income"
                      required
                    >
                      <!-- Options filled via JS -->
                    </select>
                  </div>

                  <!-- Job Industry -->
                  <div class="pseudoInput relative">
                    <select
                      class="peer w-full font-semibold text-[19px] border-2 border-[#262629] bg-gradient-to-b from-[#111112] to-[#151517] rounded-md p-4 hover:border-[#363639] focus:border-[#f1aa3f] focus:from-[#1a1918] focus:to-[#272219] ring-0 focus:ring-0 outline-none appearance-none"
                      id="job_industry"
                      name="job_industry"
                      required
                    >
                      <option value="" disabled selected hidden>
                        Job Industry
                      </option>
                      <option value="Accounting">Accounting</option>
                      <option value="Automotive">Automotive</option>
                      <option value="Aviation/Transportation">
                        Aviation/Transportation
                      </option>
                      <option value="Banking/Financial">Banking/Financial</option>
                      <option value="Communications/Information">
                        Communications/Information
                      </option>
                      <option value="Computer Hardware/Software">
                        Computer Hardware/Software
                      </option>
                      <option value="Construction">Construction</option>
                      <option value="Education">Education</option>
                      <option value="Energy/Utilities/Oil and Gas">
                        Energy/Utilities/Oil and Gas
                      </option>
                      <option value="Environmental Services">
                        Environmental Services
                      </option>
                      <option value="Fashion/Apparel">Fashion/Apparel</option>
                      <option value="Food and Consumer Products">
                        Food and Consumer Products
                      </option>
                      <option value="Government/Public Sector">
                        Government/Public Sector
                      </option>
                      <option value="Health Care and Social assistance">
                        Health Care and Social assistance
                      </option>
                      <option value="Hospitality/Tourism">
                        Hospitality/Tourism
                      </option>
                      <option value="Information/Technology/IT">
                        Information/Technology/IT
                      </option>
                      <option value="Insurance/Legal/Law">
                        Insurance/Legal/Law
                      </option>
                      <option value="Manufacturing">Manufacturing</option>
                      <option value="Marketing">Marketing</option>
                      <option value="Media/Entertainment">
                        Media/Entertainment
                      </option>
                      <option value="Military">Military</option>
                      <option value="Non Profit/Social services">
                        Non Profit/Social services
                      </option>
                      <option value="Real Estate/Property">
                        Real Estate/Property
                      </option>
                      <option value="Sales">Sales</option>
                      <option value="Security">Security</option>
                      <option value="Shipping/Distribution">
                        Shipping/Distribution
                      </option>
                      <option value="Does not apply">Does not apply</option>
                    </select>
                  </div>

                  <!-- Job Decisions Making Role -->
                  <div class="pseudoInput relative">
                    <select
                      class="peer w-full font-semibold text-[19px] border-2 border-[#262629] bg-gradient-to-b from-[#111112] to-[#151517] rounded-md p-4 hover:border-[#363639] focus:border-[#f1aa3f] focus:from-[#1a1918] focus:to-[#272219] ring-0 focus:ring-0 outline-none appearance-none"
                      id="role"
                      name="role"
                      required
                    >
                      <option value="" disabled selected hidden>
                        Job Decisions Making Role
                      </option>
                      <option value="Executive Level Company Maker">
                        Executive Level Company Maker
                      </option>
                      <option value="Department Level Decision Maker">
                        Department Level Decision Maker
                      </option>
                      <option value="Manager Level Decision Maker">
                        Manager Level Decision Maker
                      </option>
                      <option value="Task Level Decision Maker">
                        Task Level Decision Maker
                      </option>
                    </select>
                  </div>

                  <!-- Job Title -->
                  <div class="pseudoInput relative">
                    <select
                      class="peer w-full font-semibold text-[19px] border-2 border-[#262629] bg-gradient-to-b from-[#111112] to-[#151517] rounded-md p-4 hover:border-[#363639] focus:border-[#f1aa3f] focus:from-[#1a1918] focus:to-[#272219] ring-0 focus:ring-0 outline-none appearance-none"
                      id="job_title"
                      name="job_title"
                      required
                    >
                      <option value="" disabled selected hidden>Job Title</option>
                      <option value="Administrative (Clerical or Support Staff)">
                        Administrative (Clerical or Support Staff)
                      </option>
                      <option value="Analyst">Analyst</option>
                      <option value="Assistant or associate">
                        Assistant or associate
                      </option>
                      <option
                        value="C-Level (e.g. CEO, CFO), Owner, Partner, President"
                      >
                        C-Level (e.g. CEO, CFO), Owner, Partner, President
                      </option>
                      <option value="Consultant">Consultant</option>
                      <option
                        value="Director (Group Director, Sr. Director, Director)"
                      >
                        Director (Group Director, Sr. Director, Director)
                      </option>
                      <option value="Intern">Intern</option>
                      <option
                        value="Manager (Group Manager, Sr. Manager, Manager, Program Manager)"
                      >
                        Manager (Group Manager, Sr. Manager, Manager,
                        Program Manager)
                      </option>
                      <option value="Vice President (EVP, SVP, AVP, VP)">
                        Vice President (EVP, SVP, AVP, VP)
                      </option>
                      <option value="Volunteer">Volunteer</option>
                      <option value="Other / None of the above">
                        Other / None of the above
                      </option>
                      <option value="Does not apply">Does not apply</option>
                    </select>
                  </div>

                  <!-- Username -->
                  <div class="pseudoInput relative">
                    <input
                      class="peer w-full text-white font-semibold text-[19px] placeholder:text-[#494940] border-2 border-[#262629] bg-gradient-to-b from-[#111112] to-[#151517] rounded-md p-4 pt-6 hover:border-[#363639] focus:border-[#f1aa3f] focus:from-[#1a1918] focus:to-[#272219] active:border-[#f1aa3f] ring-0 focus:ring-0 outline-none appearance-none"
                      type="text"
                      id="username"
                      name="username"
                      placeholder="eg.John"
                      required
                    />
                    <label
                      class="absolute top-3 left-[calc(1rem+2px)] text-[#45454D] peer-focus:text-[#f1aa3f] font-semibold uppercase text-[12px]"
                      >Username</label
                    >
                  </div>

                  <!-- Checkbox and Description -->
                  <div
                    class="col-span-1 sm:col-span-2 flex items-start gap-4 mt-4"
                  >
                    <label
                      class="relative flex items-center cursor-pointer mt-[2px]"
                    >
                      <input
                        type="checkbox"
                        id="terms"
                        name="terms"
                        class="peer absolute opacity-0 h-8 w-8 cursor-pointer"
                        required
                      />
                      <span
                        class="h-8 w-8 rounded border-2 border-black bg-transparent shadow-[0_0_1px_1px_#494940] peer-checked:shadow-[0_0_1px_1px_#f1aa3f] flex items-center justify-center peer-checked:bg-[#f1aa3f]"
                      >
                        ✔
                      </span>
                    </label>
                    <p
                      class="text-white text-opacity-75 font-medium text-[17px] md:text-[20px]"
                    >
                      I agree to the <a class="underline font-bold" href="">Terms of Use</a> and to receive marketing email
                      messages from Opinion Elite, and I accept the <a class="underline font-bold" href="">Privacy Policy</a>.
                    </p>
                  </div>
                </div>
                <div
                  class="flex flex-wrap items-center justify-center md:justify-start gap-6"
                >
                  <button
                    class="w-[300px] h-[80px] relative my-12"
                    type="submit"
                    name="signup"
                  >
                    <span
                      class="relative z-[10] text-[16px] font-bold tracking-[0.03em] leading-none uppercase"
                      >Join Now</span
                    >
                    <img
                      class="absolute inset-0 z-0"
                      src="imgs/join-button.png"
                      alt="Signup"
                    />
                  </button>
              </form>
                  <div
                    class="flex items-center justify-center gap-6 mx-auto md:mx-0"
                  >
                    <!-- FB button currently disabled -->
                  </div>
                </div>
              </div>

            <div class="hidden xl:flex video w-2/5 relative flex justify-end">
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
            />
            <p
              class="text-[12px] order-3 lg:text-[16px] font-medium text-white text-opacity-[0.65]"
            >
              Copyright &copy; 2025 QuantifyAi
            </p>
          </div>
        </footer>
      </main>

      <!-- Legal modal + preloader (unchanged) -->
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
          </div>
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
