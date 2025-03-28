<?php
// * Copyright 2021-2025 SnehTV, Inc.
// * Licensed under MIT (https://github.com/mitthu786/TS-JioTV/blob/main/LICENSE)
// * Created By : TechieSneh

error_reporting(0);
ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);

include "functions.php";

$user = $_GET['user'] ?? '';

function handleOTPVerification()
{
  global $user;
  $msg = '';

  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $otp = implode('', $_POST['otp']);
    $verification_data = verify_jio_otp($user, $otp);

    if ($verification_data["status"] == "success") {
      header("Location: index.php?success&msg=Login+Successful");
      exit();
    } else {
      $msg = $verification_data["message"];
      header("Location: otpVerify.php?user=" . $user . "&error&msg=" . urlencode($msg));
      exit();
    }
  }

  renderOTPForm($msg, $user);
}

function renderOTPForm($msg, $user)
{
?>
  <!DOCTYPE html>
  <html lang="en">

  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify OTP</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <script src="https://code.iconify.design/2/2.1.2/iconify.min.js"></script>
    <style>
      .glass-effect {
        background: rgba(17, 24, 39, 0.8);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.1);
      }

      .gradient-text {
        background: linear-gradient(45deg, #8B5CF6, #EC4899);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
      }

      .otp-input {
        width: 3.5rem;
        height: 3.5rem;
        font-size: 1.5rem;
        text-align: center;
      }
    </style>
  </head>

  <body class="bg-gray-900 min-h-screen flex items-center justify-center p-4">

    <div class="glass-effect rounded-2xl shadow-2xl w-full max-w-md p-8" data-aos="zoom-in">
      <div class="text-center mb-8">
        <img src="https://i.ibb.co/BcjC6R8/jiotv.png"
          alt="JioTV Logo"
          class="w-24 h-24 mx-auto mb-4 filter brightness-125"
          data-aos="fade-down">
        <h1 class="text-3xl font-bold gradient-text mb-2">Verify OTP</h1>
        <p class="text-gray-400">Enter code sent to <?php echo htmlspecialchars($user); ?></p>
      </div>

      <div id="alert" class="hidden mb-6 p-4 rounded-lg text-sm"></div>

      <form action="<?php echo $_SERVER['PHP_SELF']; ?>?user=<?php echo $user; ?>" method="POST" class="space-y-6">
        <div class="flex justify-center gap-4">
          <?php for ($i = 0; $i < 6; $i++): ?>
            <input type="number"
              name="otp[]"
              class="otp-input bg-gray-800 border border-gray-700 rounded-lg focus:ring-2 focus:ring-purple-600 text-gray-100"
              maxlength="1"
              min="0"
              max="9"
              required
              oninput="this.value=this.value.slice(0,1); focusNext(this)">
          <?php endfor; ?>
        </div>

        <div class="text-center text-gray-400 text-sm">
          Didn't receive code?
          <span id="resend" class="gradient-text cursor-pointer hover:opacity-80" onclick="resendOTP()">
            Resend OTP (<span id="countdown">30</span>s)
          </span>
        </div>

        <button type="submit"
          class="w-full py-3 bg-gradient-to-r from-purple-600 to-pink-600 hover:from-purple-700 hover:to-pink-700 rounded-lg font-medium transition-all transform hover:scale-[1.02] flex items-center justify-center">
          <span class="iconify mr-2" data-icon="mdi:shield-check"></span>
          Verify OTP
        </button>
      </form>

      <div class="mt-6 text-center">
        <p class="text-gray-400 text-sm">
          Powered by <span class="gradient-text font-medium">SNEH-TV</span>
        </p>
      </div>
    </div>

    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
      AOS.init({
        duration: 800,
        once: false,
        easing: 'ease-in-out-quad'
      });

      // OTP Input Handling
      function focusNext(input) {
        if (input.value.length === 1) {
          const next = input.nextElementSibling;
          if (next) next.focus();
        }
      }

      // Resend OTP Countdown
      let timer = 30;
      const countdownEl = document.getElementById('countdown');
      const resendBtn = document.getElementById('resend');

      function updateCountdown() {
        countdownEl.textContent = timer;
        if (timer-- <= 0) {
          resendBtn.classList.remove('gradient-text');
          resendBtn.innerHTML = 'Resend OTP';
          resendBtn.onclick = () => {
            window.location.href = 'index.php?user=<?php echo $user; ?>';
          };
          clearInterval(countdownInterval);
        }
      }

      let countdownInterval = setInterval(updateCountdown, 1000);

      // Alert Handling
      document.addEventListener("DOMContentLoaded", function() {
        const params = new URLSearchParams(window.location.search);
        const alertEl = document.getElementById('alert');

        const alertConfig = {
          success: {
            message: "Verification successful! Redirecting...",
            color: "bg-green-900 text-green-300"
          },
          error: {
            message: params.get("msg"),
            color: "bg-red-900 text-red-300"
          }
        };

        for (const [key, config] of Object.entries(alertConfig)) {
          if (params.has(key)) {
            alertEl.innerHTML = `
                        <div class="${config.color} p-3 rounded-lg flex justify-between items-center">
                            <span>${config.message}</span>
                            <button onclick="this.parentElement.remove()" 
                                    class="text-gray-400 hover:text-white">
                                <span class="iconify" data-icon="mdi:close"></span>
                            </button>
                        </div>
                    `;
            alertEl.classList.remove('hidden');

            if (key === 'success') {
              setTimeout(() => {
                window.location.href = '../index.php';
              }, 1500);
            }
          }
        }
      });
    </script>
  </body>

  </html>
<?php
}

handleOTPVerification();
?>