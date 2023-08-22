<!--
* Copyright 2021-2023 SnehTV, Inc.
* Licensed under MIT (https://github.com/mitthu786/TS-JioTV/blob/main/LICENSE)
* Created By : TechieSneh
-->

<!DOCTYPE html>
<html lang="en">

<head>
    <title>JIOTV+</title>
    <meta charset="utf-8">
    <meta name="description" content="ENJOY FREE LIVE JIOTV">
    <meta name="keywords" content="JIOTV, LIVETV, SPORTS, MOVIES, MUSIC">
    <meta name="author" content="Techie Sneh">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <link rel="shortcut icon" type="image/x-icon" href="https://i.ibb.co/37fVLxB/f4027915ec9335046755d489a14472f2.png">
    <link rel="stylesheet" href="app/assets/css/techiesneh.min.css">
    <link rel="stylesheet" href="app/assets/css/search.css">
    <script src="https://cdn.jsdelivr.net/npm/lazysizes@5.3.2/lazysizes.min.js"></script>
    <script src="https://code.iconify.design/2/2.1.2/iconify.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/lazysizes/4.1.5/lazysizes.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
</head>

<body>
    <div id="jtvh1">
        <h1>JIOTV+</h1>
    </div>
    <div id="userButtons">
        <button id="loginButton">Login</button>
        <button id="logoutButton">Logout</button>
        <button id="PlayListButton">PlayList</button>
    </div><br>
    <div id="searchWrapper">
        <input type="text" name="searchBar" id="searchBar" placeholder="Search ..." />
    </div>
    <div id="content">
        <div class="container">
            <div class="filters">
                <label for="genreFilter">Genre:</label>
                <select id="genreFilter">
                    <option value="">All</option>
                    <option value="Entertainment">Entertainment</option>
                    <option value="Movies">Movies</option>
                    <option value="Kids">Kids</option>
                    <option value="Sports">Sports</option>
                    <option value="Lifestyle">Lifestyle</option>
                    <option value="Infotainment">Infotainment</option>
                    <option value="News">News</option>
                    <option value="Music">Music</option>
                    <option value="Devotional">Devotional</option>
                    <option value="Business">Business</option>
                    <option value="Educational">Educational</option>
                    <option value="Shopping">Shopping</option>
                    <option value="JioDarshan">JioDarshan</option>
                </select>

                <label for="langFilter">Language:</label>
                <select id="langFilter">
                    <option value="">All</option>
                    <option value="Hindi">Hindi</option>
                    <option value="English">English</option>
                    <option value="Marathi">Marathi</option>
                    <option value="Punjabi">Punjabi</option>
                    <option value="Urdu">Urdu</option>
                    <option value="Bengali">Bengali</option>
                    <option value="Malayalam">Malayalam</option>
                    <option value="Tamil">Tamil</option>
                    <option value="Gujarati">Gujarati</option>
                    <option value="Odia">Odia</option>
                    <option value="Telugu">Telugu</option>
                    <option value="Bhojpuri">Bhojpuri</option>
                    <option value="Kannada">Kannada</option>
                    <option value="Assamese">Assamese</option>
                    <option value="Nepali">Nepali</option>
                    <option value="French">French</option>
                </select>
            </div>
            <div id="charactersList" class="row">
            </div>
        </div>
    </div>
    <script src="app/assets/js/search.js"></script>
</body>
<script>
    document.getElementById("loginButton").addEventListener("click", function() {
        window.location.href = "app/login.php";
    });

    document.getElementById("logoutButton").addEventListener("click", function() {
        var xhr = new XMLHttpRequest();
        xhr.open("POST", "app/logout.php", true);
        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4 && xhr.status === 200) {
                alert(xhr.responseText);
            }
        };
        xhr.send();
    });

    const PlayListButton = document.getElementById('PlayListButton');
    PlayListButton.addEventListener('click', () => {

        var protocol = window.location.protocol;
        var localIP = window.location.hostname;
        var port = window.location.port;

        if (window.location.hostname !== "127.0.0.1" && window.location.hostname !== "localhost") {
            var hostJio = window.location.host;
        } else {
            var hostJio = localIP + (port ? ':' + port : '');
        }

        var jioPath = protocol + '//' + hostJio + window.location.pathname.replace(/\/[^/]*$/, '');
        var jioPath = jioPath + '/app/playlist.php';

        navigator.clipboard.writeText(jioPath)
            .then(() => {
                alert('PlayList URL copied to Clipboard!');
            })
            .catch((error) => {
                console.error('Error copying URL:', error);
            });
    });
</script>

</html>