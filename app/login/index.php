<?php
// * Copyright 2021-2025 SnehTV, Inc.
// * Licensed under MIT (https://github.com/mitthu786/TS-JioTV/blob/main/LICENSE)
// * Created By : TechieSneh

error_reporting(0);
ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);

include "functions.php";

function handleLogin()
{
  $msg = '';
  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user = $_POST['username'];
    $otp_data = send_jio_otp($user);

    if ($otp_data["status"] == "error") {
      $msg = $otp_data["message"];
      header("Location: index.php?OtpError&msg=" . urlencode($msg));
      exit();
    } else {
      header("Location: otpVerify.php?user=" . $user);
      exit();
    }
  }

  renderLoginForm($msg);
}

function renderLoginForm($msg)
{
?>

  <!DOCTYPE html>
  <html lang="en">

  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>JioTV Login</title>
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
    </style>
  </head>

  <body class="bg-gray-900 min-h-screen flex items-center justify-center p-4">

    <div class="glass-effect rounded-2xl shadow-2xl w-full max-w-md p-8" data-aos="zoom-in">
      <div class="text-center mb-8">
        <img src="https://i.ibb.co/BcjC6R8/jiotv.png"
          alt="JioTV Logo"
          class="w-24 h-24 mx-auto mb-4 filter brightness-125"
          data-aos="fade-down">
        <h1 class="text-3xl font-bold gradient-text mb-2">JioTV Login</h1>
        <p class="text-gray-400">Secure access to premium content</p>
      </div>

      <div id="alert" class="hidden mb-6 p-4 rounded-lg text-sm"></div>

      <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" class="space-y-6">
        <div>
          <label class="block text-gray-300 mb-2">Mobile Number</label>
          <div class="relative">
            <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400">
              <span class="iconify" data-icon="mdi:cellphone"></span>
            </span>
            <input id="username"
              name="username"
              type="text"
              minlength="10"
              maxlength="10"
              required
              class="w-full pl-10 pr-4 py-3 bg-gray-800 border border-gray-700 rounded-lg focus:ring-2 focus:ring-purple-600 focus:border-transparent text-gray-100 placeholder-gray-500"
              placeholder="Enter 10-digit mobile number">
          </div>
        </div>

        <button type="submit"
          class="w-full py-3 bg-gradient-to-r from-purple-600 to-pink-600 hover:from-purple-700 hover:to-pink-700 rounded-lg font-medium transition-all transform hover:scale-[1.02] flex items-center justify-center">
          <span class="iconify mr-2" data-icon="mdi:login"></span>
          Send OTP
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

      document.addEventListener("DOMContentLoaded", function() {
        const params = new URLSearchParams(window.location.search);
        const alertEl = document.getElementById('alert');

        const alertConfig = {
          success: {
            message: "Login successful! Redirecting...",
            color: "bg-green-900 text-green-300"
          },
          error: {
            message: params.get("msg"),
            color: "bg-red-900 text-red-300"
          },
          OtpError: {
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

handleLogin();
?>