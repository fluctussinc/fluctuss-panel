<?php
session_start();

$stateFile = 'slider_state.txt';

if (file_exists($stateFile)) {
    $state = file_get_contents($stateFile);
    $_SESSION['renamed'] = ($state === 'enabled'); 
} else {
    $_SESSION['renamed'] = false; 
}

if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
    die("
    <html>
    <head><title>Access Denied</title></head>
    <body>
        <h2>You must be logged in to continue.</h2>
    </body>
    </html>
    ");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $dir = ''; 

    $file1 = $dir . '../index1.php';
    $file2 = $dir . '../index.php';

    if (file_exists($file1) && file_exists($file2)) {
        if ($_POST['action'] === 'rename' && !$_SESSION['renamed']) {

            rename($file2, $dir . 'temp.php');
            rename($file1, $file2);
            rename($dir . 'temp.php', $file1);
            $_SESSION['renamed'] = true; 
            file_put_contents($stateFile, 'enabled'); 
            echo "Effect enabled successfully!";
        } elseif ($_POST['action'] === 'revert' && $_SESSION['renamed']) {

            rename($file2, $dir . 'temp.php');
            rename($file1, $file2);
            rename($dir . 'temp.php', $file1);
            $_SESSION['renamed'] = false; 
            file_put_contents($stateFile, 'disabled'); 
            echo "Effect disabled successfully";
        } else {
            echo "No changes made.";
        }
    } else {
        echo "One or both of the files do not exist.";
    }
    exit; 
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Advanced settings</title>
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background-color: #1e1e1e;
            color: #ffffff;
        }

        .center {
            display: flex;
            justify-content: center;
            align-items: center;
            margin-left: auto;
            margin-right: auto;
        }

        .content {
            display: block;
            padding: 20px;
            padding-right: 70px;
            max-width: 600px;
            margin-top: 30px;
            background-color: #2c2c2c;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.5);
        }

        h1 {
            font-size: 24px;
            margin-bottom: 20px;
        }

        #message {
            margin: 10px 0;
            font-size: 16px;
        }

        .switch {
            position: relative;
            display: inline-block;
            width: 60px;
            height: 34px;
        }

        .switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }

        .slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #ccc;
            transition: .4s;
            border-radius: 34px;
        }

        .slider:before {
            position: absolute;
            content: "";
            height: 26px;
            width: 26px;
            left: 4px;
            bottom: 4px;
            background-color: white;
            transition: .4s;
            border-radius: 50%;
        }

        input:checked+.slider {
            background-color: #2196F3;
        }

        input:checked+.slider:before {
            transform: translateX(26px);
        }

        .Btn {
            display: flex;
            align-items: center;
            justify-content: flex-start;
            width: 45px;
            height: 45px;
            border: none;
            border-radius: 50%;
            cursor: pointer;
            position: absolute;
            overflow: hidden;
            transition-duration: .3s;
            box-shadow: 2px 2px 10px rgba(0, 0, 0, 0.199);
            background-color: rgb(255, 65, 65);
            margin: 30px;
            bottom: 0;
        }

        .sign {
            width: 100%;
            transition-duration: .3s;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .sign svg {
            width: 17px;
        }

        .sign svg path {
            fill: white;
        }

        .text {
            position: absolute;
            right: 0%;
            width: 0%;
            opacity: 0;
            color: white;
            font-size: 1.2em;
            font-weight: 600;
            transition-duration: .3s;
        }

        .Btn:hover {
            width: 125px;
            border-radius: 40px;
            transition-duration: .3s;
        }

        .Btn:hover .sign {
            width: 30%;
            transition-duration: .3s;
            padding-left: 20px;
        }

        .Btn:hover .text {
            opacity: 1;
            width: 70%;
            transition-duration: .3s;
            padding-right: 10px;
        }

        .Btn:active {
            transform: translate(2px, 2px);
        }
    </style>
</head>

<body>
    <div class="center">
        <div class="content">

            <h1>Enable Parallax Scrolling</h1>
            <div id="message">Toggle the switch to enable or disable parallax scrolling.</div>

            <label class="switch">
                <input type="checkbox" id="renameToggle" <?php if ($_SESSION['renamed']) echo 'checked' ; ?>>
                <span class="slider"></span>
            </label>
        </div>
    </div>
    <button class="Btn" onclick="location.href='/admin'">
        <div class="sign"><svg viewBox="0 0 512 512">
                <path
                    d="M377.9 105.9L500.7 228.7c7.2 7.2 11.3 17.1 11.3 27.3s-4.1 20.1-11.3 27.3L377.9 406.1c-6.4 6.4-15 9.9-24 9.9c-18.7 0-33.9-15.2-33.9-33.9l0-62.1-128 0c-17.7 0-32-14.3-32-32l0-64c0-17.7 14.3-32 32-32l128 0 0-62.1c0-18.7 15.2-33.9 33.9-33.9c9 0 17.6 3.6 24 9.9zM160 96L96 96c-17.7 0-32 14.3-32 32l0 256c0 17.7 14.3 32 32 32l64 0c17.7 0 32 14.3 32 32s-14.3 32-32 32l-64 0c-53 0-96-43-96-96L0 128C0 75 43 32 96 32l64 0c17.7 0 32 14.3 32 32s-14.3 32-32 32z">
                </path>
            </svg></div>

        <div class="text">Back</div>
    </button>

    <script>
        document.getElementById('renameToggle').addEventListener('change', function () {
            const xhr = new XMLHttpRequest();
            const action = this.checked ? 'rename' : 'revert';

            xhr.open('POST', window.location.href, true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.onreadystatechange = function () {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    document.getElementById('message').innerText = xhr.responseText;
                }
            };
            xhr.send('action=' + action);
        });
    </script>

</body>

</html>