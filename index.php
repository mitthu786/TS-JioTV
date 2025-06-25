<?php
// * Copyright 2021-2025 SnehTV, Inc.
// * Licensed under MIT (https://github.com/mitthu786/TS-JioTV/blob/main/LICENSE)
// * Created By : TechieSneh

error_reporting(0);
include "app/functions.php";

$file_path = __DIR__ . '/app/assets/data/credskey.jtv';
$file_exists = file_exists($file_path);
$isApache = isApache();

if ($file_exists) {
    $user_data = getUserData();
    $name = $user_data['name'];
    $mobile = $user_data['mobile'];
    $exp_date_time = $user_data['exp_date_time'];
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>JioTV+ ReBorn</title>
    <link rel="icon" href="https://ik.imagekit.io/techiesneh/tv_logo/jtv-plus_TMaGGk6N0.png" type="image/png">
    <meta name="description" content="ENJOY FREE LIVE JIOTV">
    <meta name="keywords" content="JIOTV, LIVETV, SPORTS, MOVIES, MUSIC">
    <meta name="author" content="Techie Sneh">
    <!-- Tailwind CSS -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <!-- AOS Animation -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <!-- Custom Styles -->
    <style>
        .gradient-text {
            background: linear-gradient(45deg, #8B5CF6, #EC4899);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .card-hover {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .card-hover:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.3);
        }

        ::-webkit-scrollbar {
            width: 8px;
        }

        ::-webkit-scrollbar-track {
            background: #1a202c;
        }

        ::-webkit-scrollbar-thumb {
            background: #4a5568;
            border-radius: 4px;
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
                <button class="p-3 bg-gradient-to-r from-purple-600 to-pink-600 hover:from-purple-700 hover:to-pink-700 rounded-lg transition-all group relative" id="loginButton">
                    <span class="iconify text-xl" data-icon="mdi:account"></span>
                    <span class="sr-only">Login</span>
                </button>

                <button class="p-3 bg-gradient-to-r from-purple-600 to-pink-600 hover:from-purple-700 hover:to-pink-700 rounded-lg transition-all group relative" id="refreshButton">
                    <span class="iconify text-xl" data-icon="mdi:reload"></span>
                    <span class="sr-only">Refresh</span>
                </button>

                <button class="p-3 bg-gradient-to-r from-purple-600 to-pink-600 hover:from-purple-700 hover:to-pink-700 rounded-lg transition-all group relative" id="logoutButton">
                    <span class="iconify text-xl" data-icon="mdi:logout"></span>
                    <span class="sr-only">Logout</span>
                </button>

                <button class="p-3 bg-gradient-to-r from-purple-600 to-pink-600 hover:from-purple-700 hover:to-pink-700 rounded-lg transition-all group relative" id="PlayListButton">
                    <span class="iconify text-xl" data-icon="mdi:playlist-music"></span>
                    <span class="sr-only">Playlist</span>
                </button>
            </div>
        </div>
    </header>

    <main class="container mx-auto p-4">
        <div class="mb-6 space-y-4" data-aos="fade-up">
            <input type="text" id="searchBar" placeholder="Search channels..." class="w-full p-3 bg-gray-800 border border-gray-700 rounded-lg focus:outline-none focus:ring-2 focus:ring-pink-500">
            <div class="flex gap-4 flex-wrap">
                <select id="catchupFilter" class="p-3 bg-gray-800 border border-gray-700 rounded-lg focus:outline-none focus:ring-2 focus:ring-pink-500 flex-1 min-w-[200px]">
                    <option value="">📺 CONTENT</option>
                    <option value="n">🔴 Live TV</option>
                    <option value="y">⏳ Catchup</option>
                </select>
                <select id="genreFilter" class="p-3 bg-gray-800 border border-gray-700 rounded-lg focus:outline-none focus:ring-2 focus:ring-pink-500 flex-1 min-w-[200px]">
                    <!-- Genre options -->
                </select>
                <select id="langFilter" class="p-3 bg-gray-800 border border-gray-700 rounded-lg focus:outline-none focus:ring-2 focus:ring-pink-500 flex-1 min-w-[200px]">
                    <!-- Language options -->
                </select>
            </div>
        </div>

        <div id="charactersList" class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-4"></div>
    </main>

    <!-- Login Modal -->
    <div id="loginModal" class="hidden fixed inset-0 bg-black bg-opacity-75 backdrop-blur-sm flex items-center justify-center p-4">
        <div class="bg-gray-800 rounded-xl p-6 max-w-md w-full border border-gray-700 transform transition-all" data-aos="zoom-in">
            <?php if ($file_exists): ?>
                <h2 class="text-2xl font-bold mb-4 gradient-text">👤 User Details</h2>
                <div class="mb-4">
                    <p class="text-gray-400">Logged in as:</p>
                    <p class="text-gray-100 font-semibold">👦🏻 <?php echo htmlspecialchars($name); ?></p>
                    <p class="text-gray-100 font-semibold">📱 <?php echo htmlspecialchars($mobile); ?></p><br />
                    <p class="text-gray-100 font-semibold">🎫 Token Expire at : <?php echo htmlspecialchars($exp_date_time); ?></p>
                </div>
                <div class="flex justify-end gap-2">
                    <button class="px-6 py-2 bg-gradient-to-r from-purple-600 to-pink-600 hover:from-purple-700 hover:to-pink-700 rounded-lg transition-colors"
                        onclick="performRefresh()">
                        <span class="iconify text-xl" data-icon="mdi:reload"></span>
                    </button>
                    <button class="px-6 py-2 bg-gray-700 hover:bg-gray-600 rounded-lg transition-colors" id="closeModal">Close</button>
                </div>
            <?php else: ?>
                <h2 class="text-2xl font-bold mb-4 gradient-text">🔐 Secure Login</h2>
                <p class="text-gray-400 mb-6">Access premium content with your credentials</p>
                <div class="flex justify-end gap-2">
                    <a href="app/login" class="px-6 py-2 bg-pink-600 hover:bg-pink-700 rounded-lg transition-colors">Continue</a>
                    <button class="px-6 py-2 bg-gray-700 hover:bg-gray-600 rounded-lg transition-colors" id="closeModal">Close</button>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- LogOut Modal -->
    <div id="logoutModal" class="hidden fixed inset-0 bg-black bg-opacity-75 backdrop-blur-sm flex items-center justify-center p-4">
        <div class="bg-gray-800 rounded-xl p-6 max-w-md w-full border border-gray-700 transform transition-all" data-aos="zoom-in">
            <h2 class="text-2xl font-bold mb-4 gradient-text">🗑️ Confirm Logout</h2>
            <p class="text-gray-400 mb-6">
                Are you sure you want to logout? You'll need to login again to access premium content.
            </p>
            <div class="flex justify-end gap-2">
                <button class="px-6 py-2 bg-gray-700 hover:bg-gray-600 rounded-lg transition-colors"
                    onclick="closeLogoutModal()">
                    Cancel
                </button>
                <button class="px-6 py-2 bg-red-600 hover:bg-red-700 rounded-lg transition-colors"
                    onclick="performLogout()">
                    Logout
                </button>
            </div>
        </div>
    </div>

    <!-- Refresh Modal -->
    <div id="refreshModal" class="hidden fixed inset-0 bg-black bg-opacity-75 backdrop-blur-sm flex items-center justify-center p-4">
        <div class="bg-gray-800 rounded-xl p-6 max-w-md w-full border border-gray-700 transform transition-all" data-aos="zoom-in">
            <h2 class="text-2xl font-bold mb-4 gradient-text">💫 Refresh Token</h2>
            <p class="text-gray-400 mb-6">
                Are you sure you want to refresh your authentication? This will update your session credentials.
            </p>
            <div class="flex justify-end gap-2">
                <button class="px-6 py-2 bg-gray-700 hover:bg-gray-600 rounded-lg transition-colors"
                    onclick="closeRefreshModal()">
                    Cancel
                </button>
                <button class="px-6 py-2 bg-gradient-to-r from-purple-600 to-pink-600 hover:from-purple-700 hover:to-pink-700 rounded-lg transition-colors"
                    onclick="performRefresh()">
                    Refresh
                </button>
            </div>
        </div>
    </div>

    <!-- PlayList Modal -->
    <div id="playlistModal" class="hidden fixed inset-0 bg-black bg-opacity-75 backdrop-blur-sm flex items-center justify-center p-4">
        <div class="bg-gray-800 rounded-xl p-6 max-w-md w-full border border-gray-700 transform transition-all" data-aos="zoom-in">
            <h2 class="text-2xl font-bold mb-4 gradient-text">📺 Playlist Info</h2>
            <p class="text-gray-400 mb-4">
                Copy the playlist URL to use in your favorite media player. This playlist contains all available channels.
            </p>
            <div class="relative">
                <input type="text" id="playlistUrl"
                    class="w-full p-3 pr-12 bg-gray-700 rounded-lg text-gray-100"
                    readonly>
                <button onclick="copyPlaylistUrl()"
                    class="absolute right-2 top-1/2 transform -translate-y-1/2 p-2 bg-gray-600 hover:bg-gray-500 rounded-lg">
                    <span class="iconify" data-icon="mdi:content-copy"></span>
                </button>
            </div>
            <div class="mt-6 flex justify-end gap-2">
                <button class="px-6 py-2 bg-gray-700 hover:bg-gray-600 rounded-lg transition-colors"
                    onclick="closePlaylistModal()">
                    Close
                </button>
            </div>
        </div>
    </div>

    <footer class="bg-gray-800 mt-12 py-4">
        <div class="container mx-auto text-center text-gray-400">
            <p>&copy; 2021-<?= date('Y') ?> SnehTV, Inc. All rights reserved.</p>
        </div>
    </footer>


    <!-- Iconify Library -->
    <script src="https://code.iconify.design/2/2.1.2/iconify.min.js"></script>
    <!-- LazySizes Library -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/lazysizes/5.3.2/lazysizes.min.js" async></script>
    <!-- AOS Animation Library -->
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <!-- Custom Scripts -->
    <script>
        const isApache = <?= $isApache ? 'true' : 'false' ?>;
        const url_host = isApache ? "app/details_" : "app/details.php?data=";
    </script>
    <script src="app/assets/js/search.js"></script>
    <script src="app/assets/js/button.js"></script>
    <script>
        // Initialize animations
        AOS.init({
            duration: 800,
            easing: 'ease-out-quad',
            once: false
        });
    </script>
</body>

</html>