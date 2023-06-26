<?php
// DB credentials (using a free hosting service for MySQL)
$host = "sql12.freemysqlhosting.net";
$user = "sql12627958";
$db = "sql12627958";
$password = "pTnr7VcuJS";


$conn = new mysqli($host, $user, $password, $db); //mySQL php extension user manual: https://www.php.net/manual/en/book.mysqli.php

// error test on connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$shortUrl = '';

if (isset($_POST['submit'])) {

    $originalUrl = $_POST['url'];     // originalUrl turns into the input
    $generatedShortURL = generateShortUrl();     // short URL generation
    addToMap($generatedShortURL, $originalUrl);     // adds both to database
    $shortUrl = getShortUrl($generatedShortURL);    // Create the shortened URL
}

// retrieve all shortened URLs and their original URLs
$urlMap = getAllUrls();
?>

<!DOCTYPE html>
<html>
    <head>
        <title>URL Shortener</title>
        <style>
            body {
                font-family: Arial, sans-serif;
                background-color: #f4f4f4;
                margin: 0;
                padding: 0;
            }

            .main {
                max-width: 600px;
                margin: 0 auto;
                padding: 20px;
                background-color: #fff;
                border-radius: 5px;
                box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            }

            h1 {
                margin-top: 0;
            }

            .urlSection {
                margin-bottom: 15px;
            }

            label {
                display: block;
                font-weight: bold;
                margin-bottom: 5px;
            }

            input[type="text"] {
                width: 100%;
                padding: 10px;
                font-size: 16px;
                border: 1px solid #ccc;
                border-radius: 4px;
            }

            .button {
                display: block;
                width: 100%;
                padding: 10px;
                font-size: 16px;
                background-color: #4caf50;
                color: #fff;
                border: none;
                border-radius: 4px;
                cursor: pointer;
            }

            .shortened-url {
                margin-top: 20px;
            }

            .shortened-url p {
                margin-bottom: 5px;
                font-weight: bold;
            }

            .shortened-url a {
                color: #0645ad;
                text-decoration: none;
            }

            table {
                width: 100%;
                border-collapse: collapse;
            }

            th, td {
                padding: 8px;
                text-align: left;
                border-bottom: 1px solid #ddd;
            }

            th {
                background-color: #f2f2f2;
            }
        </style>
    </head>
    <body>
        <div class="main">
            <h1>URL Shortener</h1>
            <form method="post" action="">
                <div class="urlSection">
                    <label for="url">Enter a URL:</label>
                    <input type="text" id="url" name="url" placeholder="Enter a URL" required>
                </div>
                <input type="submit" name="submit" value="Shorten" class="button">
            </form>

            <?php if (!empty($shortUrl)): ?>
                <div class="shortened-url">
                    <p>Shortened URL:</p>
                    <a href="<?php echo $shortUrl; ?>" target="_blank"><?php echo $shortUrl; ?></a>
                </div>
            <?php endif; ?>

            <h2>All Shortened URLs:</h2>
            <table>
                <thead>
                    <tr>
                        <th>Shortened URL</th>
                        <th>Original URL</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($urlMap as $url): ?>
                        <tr>
                            <td><a href="<?php echo $url['original_url']; ?>"><?php echo $url['short_url']; ?></a></td>
                            <td><?php echo $url['original_url']; ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </body>
</html>

<?php

// creates the 6 character short URL
function generateShortUrl() {
    $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'; //to add more possible combinations, can add special characters and numbers (compatible with urls)
    $shortURL = '';

    for ($i = 0; $i < 6; $i++) {
        $randomIndex = rand(0, strlen($chars) - 1);
        $shortURL .= $chars[$randomIndex];
    }

    return $shortURL;
}

// adds both the short URL and original URL to database 
function addToMap($shortURL, $originalUrl) {
    global $conn;

    $shortURL = $conn->real_escape_string($shortURL);
    $originalUrl = $conn->real_escape_string($originalUrl);
    //adds https:// to URLs missing it
    if (!preg_match("~^(?:f|ht)tps?://~i", $originalUrl)) {
        $originalUrl = "https://" . $originalUrl; 
    }


    $sql = "INSERT INTO url_map (short_url, original_url) VALUES ('$shortURL', '$originalUrl')";
    $conn->query($sql);
}

// retrieves URL based on shortURL
function getShortUrl($shortURL) {
    $baseUrl = 'http://localhost/urlTest1/';
    return $baseUrl . 'redirect.php?sU=' . $shortURL; //sU = shortURL
}

// returns URLs
function getAllUrls() {
    global $conn;

    $sql = "SELECT short_url, original_url FROM url_map";
    $result = $conn->query($sql);

    $urlMap = [];

    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $urlMap[] = $row;
        }
    }

    return $urlMap;
}
