<!--
* Copyright 2021-2024 SnehTV, Inc.
* Licensed under MIT (https://github.com/mitthu786/TS-JioTV/blob/main/LICENSE)
* Created By : TechieSneh
-->

<?php
$file_path = 'app/assets/data/credskey.jtv';
$file_exists = file_exists($file_path);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>JIOTV+</title>
    <meta charset="utf-8">
    <meta name="description" content="ENJOY FREE LIVE JIOTV">
    <meta name="keywords" content="JIOTV, LIVETV, SPORTS, MOVIES, MUSIC">
    <meta name="author" content="Techie Sneh">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <link rel="shortcut icon" type="image/x-icon" href="https://i.ibb.co/BcjC6R8/jiotv.png">
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
    <header>
        <div id="jtvh1">
            <img src="https://ik.imagekit.io/techiesneh/tv_logo/jtv-plus_TMaGGk6N0.png" alt="JIOTV+">
        </div>
        <div id="userButtons">
            <button class="Btn" id="loginButton">Login</button>
            <button class="Btn" id="refreshButton">Refresh</button>
            <button class="Btn" id="logoutButton">Logout</button>
            <button class="Btn" id="PlayListButton">PlayList</button>
        </div>
    </header></br>
    <div id="searchWrapper">
        <input type="text" name="searchBar" id="searchBar" placeholder="Search ..." />
    </div>
    <div id="content">
        <div class="container">
            <div class="filters">
                <label for="catchupFilter"></label>
                <select id="catchupFilter">
                    <option value="">-- CATCHUP --</option>
                    <option value="y">True</option>
                    <option value="n">False</option>
                </select>

                <label for="genreFilter"></label>
                <select id="genreFilter">
                    <option value="">-- GENRE --</option>
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

                <label for="langFilter"></label>
                <select id="langFilter">
                    <option value="">-- LANG --</option>
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

    <?php if (!$file_exists) : ?>
        <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="LoginModal" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="LoginModal">🔐 TS-JioTV : Login Portal</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        Log in to enjoy seamless, uninterrupted access to all our live TV channels and premium content.
                    </div>
                    <div class="modal-footer">
                        <a href="app/login" class="btn btn-primary">Login</a>
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <script src="app/assets/js/search.js"></script>

    <?php if (!$file_exists) : ?>
        <script>
            $(document).ready(function() {
                $('#myModal').modal('show');
            });
        </script>
    <?php endif; ?>
</body>

</html>