<?php
// * Copyright 2021-2025 SnehTV, Inc.
// * Licensed under MIT (https://github.com/mitthu786/TS-JioTV/blob/main/LICENSE)
// * Created By : TechieSneh

error_reporting(0);
include "cpfunctions.php";

// Get protocol and host information
$protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https://' : 'http://';
$local_ip = getHostByName(php_uname('n'));
$host_jio = ($_SERVER['SERVER_ADDR'] !== '127.0.0.1' && $_SERVER['SERVER_ADDR'] !== 'localhost')
  ? $_SERVER['HTTP_HOST']
  : $local_ip;

// Handle port information
if (strpos($host_jio, $_SERVER['SERVER_PORT']) === false) {
  $host_jio .= ':' . $_SERVER['SERVER_PORT'];
}

// Construct base path
$jio_path = $protocol . $host_jio . str_replace(basename($_SERVER['PHP_SELF']), '', $_SERVER['PHP_SELF']);
$jio_path = rtrim($jio_path, '/');

// Process request data
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
  $cp_url_host = "/cplay_";
} else {
  $cp_url_host = "/cplay.php?data=";
}


if (!empty($hex)) {
  $decoded = hex2bin($hex);
  $data = explode('=?=', $decoded);
}

$id = $data[1];
$cid = str_replace("_", " ", $data[0]);
$pg = $data[2];

// Channel image URL
$image = 'https://jiotvimages.cdn.jio.com/dare_images/images/' . $data[0] . '.png';

// Check credentials
$file_path = '../assets/data/credskey.jtv';
$file_exists = file_exists($file_path);

// Fetch EPG data
$catchupDataArr = getEPGData($id, $pg);

// Function to format epoch time
function getFormattedTime($startEpoch)
{
  $dateTime = new DateTime('@' . ($startEpoch / 1000));
  return $dateTime->format('Ymd\THis');
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>JioTV+ ReBorn : <?= htmlspecialchars($cid) ?> Catchup</title>
  <link rel="icon" href="https://ik.imagekit.io/techiesneh/tv_logo/jtv-plus_TMaGGk6N0.png" type="image/png">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
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

    .episode-card {
      background: rgba(31, 41, 55, 0.6);
      transition: all 0.3s ease;
    }

    .episode-card:hover {
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
        <img src="<?= htmlspecialchars($image) ?>" alt="<?= $cid ?>"
          class="w-24 h-24 md:w-32 md:h-32 mx-auto rounded-xl mb-4 md:mb-6 shadow-lg">

        <h2 class="text-2xl md:text-3xl font-bold gradient-text mb-2 md:mb-4">
          <?= htmlspecialchars($cid) ?> Catchup
        </h2>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 md:gap-4 justify-items-center">
          <div class="flex items-center space-x-2 text-sm md:text-base">
            <span class="iconify" data-icon="mdi:calendar"></span>
            <span><?= substr($catchupDataArr['serverDate'], 0, 10); ?></span>
          </div>
          <div class="flex items-center space-x-2 text-sm md:text-base">
            <span class="iconify" data-icon="mdi:tv"></span>
            <span><?= $catchupDataArr['channel_category_name']; ?></span>
          </div>
        </div>
      </div>
    </div>

    <!-- Episodes Grid -->
    <?php if ($catchupDataArr && isset($catchupDataArr['epg'])): ?>
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <?php foreach ($catchupDataArr['epg'] as $index => $episode):
          $episodePoster = "https://jiotvimages.cdn.jio.com/dare_images/shows/" . $episode['episodePoster'];
          $result1 = substr($episode['episodePoster'], 0, 10);
          $showTime = date("g:i A", strtotime($episode['showtime']));
          $endTime = date("g:i A", strtotime($episode['endtime']));
          $name = str_replace(["/", "  "], " ", $episode['showname']);
          $begin = getFormattedTime($episode['startEpoch']);
          $end = getFormattedTime($episode['endEpoch']);

          $data = implode('=?=', [
            $name,
            $id,
            str_replace(":", "", $episode['showtime']),
            $episode['srno'],
            $begin,
            $end
          ]);
          $link = $jio_path . $cp_url_host . bin2hex($data);
        ?>
          <div class="episode-card rounded-xl p-6" data-aos="fade-up" data-aos-delay="<?= $index * 50 ?>">
            <img src="<?= htmlspecialchars($episodePoster) ?>"
              alt="<?= htmlspecialchars($name) ?>"
              class="w-full h-48 object-cover rounded-lg mb-4">

            <div class="space-y-4">
              <h3 class="text-xl font-bold gradient-text"><?= htmlspecialchars($name) ?></h3>

              <div class="text-gray-400 space-y-2">
                <div class="flex items-center space-x-2">
                  <span class="iconify" data-icon="mdi:calendar"></span>
                  <span><?= htmlspecialchars($result1) ?></span>
                </div>
                <div class="flex items-center space-x-2">
                  <span class="iconify" data-icon="mdi:clock-time-four"></span>
                  <span><?= $showTime ?> - <?= $endTime ?></span>
                </div>
              </div>

              <p class="text-gray-300 text-sm line-clamp-3">
                <?= htmlspecialchars($episode['description']) ?>
              </p>

              <a href="<?= htmlspecialchars($link) ?>"
                class="inline-block w-full px-4 py-2 bg-gradient-to-r from-purple-600 to-pink-600 hover:from-purple-700 hover:to-pink-700 rounded-lg text-center transition-all">
                Watch Now
              </a>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    <?php else: ?>
      <div class="glass-effect rounded-2xl p-8 text-center" data-aos="fade-up">
        <div class="text-2xl gradient-text font-bold mb-4">No Episodes Available</div>
        <p class="text-gray-400">Could not fetch catchup data for this channel</p>
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
          <a href="../login"
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
          onclick="performLogout('cp')">
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
          onclick="performRefresh('cp')">
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
  <script src="../assets/js/button.js"></script>
  <script>
    // Initialize animations
    AOS.init({
      duration: 800,
      once: false,
      easing: 'ease-in-out-quad'
    });

    // Update the logout button event listener
    document.getElementById("logoutButton").addEventListener("click", showLogoutModal);

    // Update the refresh button event listener
    document.getElementById("refreshButton").addEventListener("click", showRefreshModal);
  </script>
</body>

</html>