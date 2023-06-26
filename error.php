<!DOCTYPE html>
<html>
<head>
    <title>Error</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .main {
            text-align: center;
        }

        h1 {
            margin-top: 0;
        }

        .invText {
            font-size: 24px;
            margin-bottom: 20px;
        }

        .button {
            display: inline-block;
            padding: 10px 20px;
            font-size: 16px;
            background-color: #4caf50;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="main">
        <h1>Error</h1>
        <div class="invText">
            <p>Invalid URL</p>
            <p>The entered URL does not exist within the database.</p>
        </div>
        <a href="index.php" class="button">Return</a>
    </div>
</body>
</html>