<?php

// DB credentials (using a free hosting service for MySQL)
$host = "sql12.freemysqlhosting.net";
$user = "sql12627958";
$db = "sql12627958";
$password = "pTnr7VcuJS";

$conn = new mysqli($host, $user, $password, $db); //mySQL php extension user manual: https://www.php.net/manual/en/book.mysqli.php
// error testing
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_GET['sU'])) {
    $generatedShortURL = $_GET['sU'];

    // fetching original URL from database
    $originalUrl = retrieveURL($generatedShortURL);

    if ($originalUrl) {
        header("Location: " . htmlspecialchars_decode($originalUrl)); // sends to original URL
        exit();
    }
}

// if there is no short url, redirect to the given error page
header("Location: error.php");
exit();

// fetch original URL from short URL
function retrieveURL($shortUrl) {
    global $conn;

    $shortUrl = $conn->real_escape_string($shortUrl);

    $sql = "SELECT original_url FROM url_map WHERE short_url = '$shortUrl' LIMIT 1";
    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $originalUrl = $row['original_url'];

        //adds "https://"  to front of url if it is missing (should not happen as this gets done when the URL is entered in the database
//        if (!preg_match("~^(?:f|ht)tps?://~i", $originalUrl)) {
//            $originalUrl = "https://" . $originalUrl;
//        }

        return $originalUrl;
    }

    return null;
}
