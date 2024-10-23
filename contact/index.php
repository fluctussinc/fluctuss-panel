<?php include('admin/track.php'); ?>
<?php
session_start();
function generateCsrfToken() {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function isBot() {
    return !empty($_POST['honeypot']);
}

$csrf_token = generateCsrfToken();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
     <link rel="icon" type="image/png" href="logo1.webp" />
    <meta name="viewport" 
      content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0"/>
    <title>Contact Us - TrendsIgnite</title>
    <link href="https://fonts.googleapis.com/css2?family=Rubik:wght@400;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900&display=swap" rel="stylesheet">
    <style>
        body {
            margin: 0;
            font-family: 'Rubik', sans-serif;
            background-color: #000;
            color: #fff;
            overflow-x: hidden;
        }

        }
        .container { 
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
            background-color: transparent;
            border-radius: 8px;
            position: relative;
            z-index: 1;
            
        }
        h2 {
            text-align: center;
            color: #fff;
            font-family: "Poppins", sans-serif;
            margin-top: 100px;
        }
        form {
            display: flex;
            flex-direction: column;
            font-family: "Poppins", sans-serif;
            margin-right: 20px;
        }
        label {
            margin: 10px 0 5px;
            color: #ddd;
        }
        input, textarea {
            padding: 10px;
            margin: 5px 0 20px;
            border: 1px solid #444;
            border-radius: 5px;
            background-color: #f7f7df;
            color: #000;
            width: 100%;
        }
        .button {
            padding: 15px;
            border: none;
            border-radius: 5px;
            margin-left: 20px;
            margin-top: 20px;
            background-color: #5cb85c;
            color: #fff;
            font-size: 16px;
            cursor: pointer;
            font-weight: bold;
            transition: 0.5s;
        }
        .button:hover {
            background-color: #45fff3;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2 style="font-size: 3em; font-weight: bold; margin: -1.5em; margin-bottom: 1em;">GET IN TOUCH!</h2>
        <form action="https://trendsignite.com/xa550-serverside0000-55880sa51f000000000sa98ds41009as89d41w06694981a89fd89g8r4h8rt4hj9rt4h89erg1fa0dsf1gqer1yjr5819ey4j1156a0d0000/server.php" method="POST" onsubmit="return validateForm()">
    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrf_token, ENT_QUOTES, 'UTF-8'); ?>">
    
    <div style="display:none;">
        <label for="honeypot">If you are a human, leave this field blank</label>
        <input type="text" id="honeypot" name="honeypot">
    </div>

    <p><b>Contact</b><br>Inquiries@yourdomain.com</p>

    <label for="name"><b>Name</b></label>
    <input type="text" id="name" name="name" required maxlength="50">

    <label for="email"><b>Email</b></label>
    <input type="email" id="email" name="email" required maxlength="50">

    <label for="subject"><b>Subject</b></label>
    <input type="text" id="subject" name="subject" required maxlength="50">

    <label for="message"><b>Message</b></label>
    <textarea id="message" name="message" class="msg1" required maxlength="500"></textarea>

    <label for="captcha"><b>Enter the code shown:</b></label>
    <img src="../admin/captcha.php" alt="CAPTCHA Image" style="display:block; margin-bottom:10px; width: 120px;">
    <input type="text" id="captcha" name="captcha" required maxlength="5" style="width: 100px;">

    <button type="submit" class="button">Send</button>
</form>
<script>
function validateForm() {
    const message = document.getElementById("message").value.trim();
    const maxWords = 500;
    const wordCount = message.split(/\s+/).length;

    if (wordCount > maxWords) {
        alert("Message exceeds the word limit of " + maxWords + " words.");
        return false;
    }

    return true;
}
</script>
</body>
</html>
