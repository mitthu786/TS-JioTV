<?php
// * Copyright 2021-2025 SnehTV, Inc.
// * Licensed under MIT (https://github.com/mitthu786/TS-JioTV/blob/main/LICENSE)
// * Created By : TechieSneh

error_reporting(0);
include "functions.php";

$data = null;
if (isset($_GET['data'])) {
  $hex = $_GET['data'];
} else {
  $parts = explode('_', $_SERVER['REQUEST_URI']);
  if (isset($parts[1])) {
    $hex = $parts[1];
  } else {
    $hex = '';
  }
}

if (isApache()) {
  $url_host = "/play_";
  $cp_url_host = "/catchup/cp_";
} else {
  $url_host = "/play.php?data=";
  $cp_url_host = "/catchup/cp.php?data=";
}

if (!empty($hex)) {
  $decoded = hex2bin($hex);
  $data = explode('=?=', $decoded);
}

$cid = $data[0];
$id = $data[1];
$name = strtoupper(str_replace('_', ' ', $cid));
$image = 'https://jiotv.catchup.cdn.jio.com/dare_images/images/' . $cid . '.png';
$c = $data[2];

$protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https://' : 'http://';
$local_ip = getHostByName(php_uname('n'));
$host_jio = ($_SERVER['SERVER_ADDR'] !== "127.0.0.1" && $_SERVER['SERVER_ADDR'] !== 'localhost')
  ? $_SERVER['HTTP_HOST']
  : $local_ip . ($_SERVER['SERVER_PORT'] ? ':' . $_SERVER['SERVER_PORT'] : '');

$jio_path = $protocol . $host_jio . str_replace(basename($_SERVER['PHP_SELF']), '', $_SERVER['PHP_SELF']);
$jio_path = rtrim($jio_path, '/');

$file_path = 'assets/data/credskey.jtv';
$file_exists = file_exists($file_path);

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= $name ?> | JioTV+ ReBorn</title>
  <link rel="icon" href="https://ik.imagekit.io/techiesneh/tv_logo/jtv-plus_TMaGGk6N0.png" type="image/png">
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

    .date-card {
      background: rgba(31, 41, 55, 0.6);
      transition: all 0.3s ease;
    }

    .date-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 10px 20px rgba(0, 0, 0, 0.3);
    }
  </style>
</head>

<body class="bg-gray-900 text-gray-100">
  <header class="bg-gray-800 shadow-xl">
    <div class="container mx-auto flex justify-between items-center p-4">
      <div data-aos="fade-right">
        <img src="https://ik.imagekit.io/techiesneh/tv_logo/jtv-plus_TMaGGk6N0.png" alt="JIOTV+" class="h-12">
      </div>
      <!-- In Header Section -->
      <div id="userButtons" class="flex gap-2" data-aos="fade-left">
        <button onclick="window.location.href='../'" class="p-3 bg-gradient-to-r from-purple-600 to-pink-600 hover:from-purple-700 hover:to-pink-700 rounded-lg transition-all group relative" id="loginButton">
          <span class="iconify text-xl" data-icon="mdi:home"></span>
          <span class="sr-only">Home</span>
        </button>

        <button class="p-3 bg-gradient-to-r from-purple-600 to-pink-600 hover:from-purple-700 hover:to-pink-700 rounded-lg transition-all group relative" id="refreshButton">
          <span class="iconify text-xl" data-icon="mdi:reload"></span>
          <span class="sr-only">Refresh</span>
        </button>

        <button class="p-3 bg-gradient-to-r from-purple-600 to-pink-600 hover:from-purple-700 hover:to-pink-700 rounded-lg transition-all group relative" id="logoutButton">
          <span class="iconify text-xl" data-icon="mdi:logout"></span>
          <span class="sr-only">Logout</span>
        </button>
      </div>
    </div>
  </header>


  <main class="container mx-auto pt-24 pb-12 px-4">
    <div class="glass-effect rounded-2xl p-4 md:p-8 mb-6 md:mb-8 mx-2 md:mx-0" data-aos="fade-up">
      <div class="text-center space-y-4 md:space-y-6">
        <img src="<?= $image ?>" alt="<?= $name ?>"
          class="w-24 h-24 md:w-32 md:h-32 mx-auto rounded-xl mb-4 md:mb-6 shadow-lg">

        <h2 class="text-2xl md:text-3xl font-bold gradient-text mb-2 md:mb-4">
          <?= $name ?>
        </h2>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 md:gap-4 justify-items-center">

          <?php
          date_default_timezone_set('Asia/Kolkata');

          $currentDate = date('d-M-Y');
          $currentTime = date('h:i A');
          ?>

          <div class="flex items-center space-x-2 text-sm md:text-base">
            <span class="iconify" data-icon="mdi:calendar"></span>
            <span><?= $currentDate ?></span>
          </div>
          <div class="flex items-center space-x-2 text-sm md:text-base">
            <span class="iconify" data-icon="mdi:clock-time-four"></span>
            <span id="live-clock"><?= $currentTime ?></span>
          </div>
        </div>

        <!-- <p class="text-gray-300 text-xs md:text-sm line-clamp-3 md:line-clamp-4 px-2 md:px-0">
          Description
        </p> -->

        <a href="<?= $jio_path .  $url_host . bin2hex($cid . '=?=' . $id) ?>"
          class="inline-block w-full sm:w-auto px-4 py-2 md:px-8 md:py-3 
                  bg-gradient-to-r from-purple-600 to-pink-600 
                  hover:from-purple-700 hover:to-pink-700 
                  rounded-lg font-medium text-sm md:text-base 
                  transition-all transform hover:scale-105"
          data-aos="zoom-in">
          Watch Live
        </a>
      </div>
    </div>

    <!-- Catchup Section -->
    <?php if ($c == true): ?>
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6" data-aos="fade-up">
        <?php
        $daysToShow = 7;
        $timezone = new DateTimeZone('Asia/Kolkata');
        $currentDate = new DateTime('now', $timezone);

        for ($i = 0; $i >= -$daysToShow; $i--):
          $date = clone $currentDate;
          $date->modify("$i day");

          $formats = [
            'day' => 'd',
            'month' => 'F',
            'year' => 'Y',
            'weekday' => 'l'
          ];

          $dateComponents = [];
          foreach ($formats as $key => $format) {
            $dateComponents[$key] = $date->format($format);
          }

          $dataToEncode = implode('=?=', [$cid, $id, $i]);
          $cp_link = bin2hex($dataToEncode);
        ?>
          <div class="date-card rounded-xl p-6 text-center hover:scale-[1.02] transition-transform">
            <div class="mb-4">
              <div class="text-4xl font-bold text-purple-400"><?= htmlspecialchars($dateComponents['day']) ?></div>
              <div class="text-gray-400 text-sm mt-2">
                <div><?= htmlspecialchars($dateComponents['weekday']) ?></div>
                <div><?= htmlspecialchars($dateComponents['month']) ?> <?= htmlspecialchars($dateComponents['year']) ?></div>
              </div>
            </div>
            <a href="<?= htmlspecialchars($jio_path) . $cp_url_host . htmlspecialchars($cp_link) ?>"
              class="inline-block w-full sm:w-auto px-4 py-2 bg-purple-800 hover:bg-purple-700 rounded-lg transition-colors">
              Watch Catchup
            </a>
          </div>
        <?php endfor; ?>
      </div>
    <?php else: ?>
      <div class="glass-effect rounded-2xl p-8 text-center" data-aos="fade-up">
        <div class="text-2xl gradient-text font-bold mb-4">Catchup Not Available</div>
        <p class="text-gray-400">This channel doesn't support catchup viewing</p>
      </div>
    <?php endif; ?>
  </main>

  <!-- Login Modal -->
  <?php if (!$file_exists): ?>
    <div id="loginModal" class="fixed inset-0 bg-black bg-opacity-75 backdrop-blur-sm flex items-center justify-center">
      <div class="glass-effect rounded-xl p-6 max-w-md w-full transform transition-all" data-aos="zoom-in">
        <h2 class="text-2xl font-bold gradient-text mb-4">🔐 Login Required</h2>
        <p class="text-gray-400 mb-6">Sign in to access premium content</p>
        <div class="flex justify-end gap-2">
          <a href="login"
            class="px-6 py-2 bg-gradient-to-r from-purple-600 to-pink-600 rounded-lg hover:opacity-90 transition-opacity">
            Continue to Login
          </a>
        </div>
      </div>
    </div>
  <?php endif; ?>

  <!-- LogOut Modal -->
  <div id="logoutModal" class="hidden fixed inset-0 bg-black bg-opacity-75 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-gray-800 rounded-xl p-6 max-w-md w-full border border-gray-700 transform transition-all" data-aos="zoom-in">
      <h2 class="text-2xl font-bold mb-4 gradient-text">🚪 Confirm Logout</h2>
      <p class="text-gray-400 mb-6">
        Are you sure you want to logout? You'll need to login again to access premium content.
      </p>
      <div class="flex justify-end gap-2">
        <button class="px-6 py-2 bg-gray-700 hover:bg-gray-600 rounded-lg transition-colors"
          onclick="closeLogoutModal()">
          Cancel
        </button>
        <button class="px-6 py-2 bg-red-600 hover:bg-red-700 rounded-lg transition-colors"
          onclick="performLogout('dt')">
          Logout
        </button>
      </div>
    </div>
  </div>

  <!-- Refresh Modal -->
  <div id="refreshModal" class="hidden fixed inset-0 bg-black bg-opacity-75 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-gray-800 rounded-xl p-6 max-w-md w-full border border-gray-700 transform transition-all" data-aos="zoom-in">
      <h2 class="text-2xl font-bold mb-4 gradient-text">🔄 Refresh Authentication</h2>
      <p class="text-gray-400 mb-6">
        Are you sure you want to refresh your authentication? This will update your session credentials.
      </p>
      <div class="flex justify-end gap-2">
        <button class="px-6 py-2 bg-gray-700 hover:bg-gray-600 rounded-lg transition-colors"
          onclick="closeRefreshModal()">
          Cancel
        </button>
        <button class="px-6 py-2 bg-blue-600 hover:bg-blue-700 rounded-lg transition-colors"
          onclick="performRefresh('dt')">
          Refresh
        </button>
      </div>
    </div>
  </div>

  <footer class="bg-gray-800 mt-12 py-4">
    <div class="container mx-auto text-center text-gray-400">
      <p>&copy; 2021-<?= date('Y') ?> SnehTV, Inc. All rights reserved.</p>
    </div>
  </footer>

  <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
  <script src="assets/js/button.js"></script>
  <script>
    // Initialize animations
    AOS.init({
      duration: 800,
      once: false,
      easing: 'ease-in-out-quad'
    });

    // Real-time clock update
    function updateClock() {
      const timeElement = document.getElementById('live-clock');
      if (timeElement) {
        timeElement.textContent = new Date().toLocaleTimeString();
      }
    }
    setInterval(updateClock, 1000);

    // Update the logout button event listener
    document.getElementById("logoutButton").addEventListener("click", showLogoutModal);

    // Update the refresh button event listener
    document.getElementById("refreshButton").addEventListener("click", showRefreshModal);
  </script>
</body>

</html>