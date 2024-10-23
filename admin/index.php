<?php
session_start();
$admins_file = 'admins.json';

function load_admins($admins_file) {
    if (file_exists($admins_file)) {
        $admins_json = file_get_contents($admins_file);
        return json_decode($admins_json, true);
    } else {
        return [];
    }
}
function save_admins($admins_file, $admins) {
    $admins_json = json_encode($admins, JSON_PRETTY_PRINT);
    file_put_contents($admins_file, $admins_json);
}
$admins = load_admins($admins_file);
if (isset($_POST['login'])) {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    $captcha = $_POST['captcha'] ?? '';

    if ($captcha !== $_SESSION['captcha']) {
        $_SESSION['login_error'] = "Incorrect CAPTCHA.";
    } elseif (isset($admins[$username]) && $admins[$username] === $password) {
        $_SESSION['logged_in'] = true;
        $_SESSION['username'] = $username;
        header('Location: ' . $_SERVER['PHP_SELF']);
        exit;
    } else {
        $_SESSION['login_error'] = "Invalid username or password.";
    }
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit;
}

if (isset($_GET['logout'])) {
    session_destroy();
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit;
}


if (!isset($_SESSION['error_display_count'])) {
    $_SESSION['error_display_count'] = 0;
}

$error_message = '';
if (isset($_SESSION['admin_error']) && $_SESSION['error_display_count'] < 2) {
    $error_message = $_SESSION['admin_error'];
    $_SESSION['error_display_count']++;
    unset($_SESSION['admin_error']);
} else {
    if ($_SESSION['error_display_count'] >= 2) {
        $_SESSION['error_display_count'] = 0;
    }
}

if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="icon" type="image/png" href="https://yourdomain.com/logo1.png" />
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style1.css">
</head>
<body>
    <div>
        <p class="admin01">ADMIN LOGIN</p>
        <div class="login-container">
            <a style="color: #000; font-size: 2em; font-weight: bold; text-decoration: none;" href="/">YOUR WEBSITE NAME</a>
            <?php if ($error_message) : ?>
                <p id="error-message" class="error"><?php echo $error_message; ?></p>
            <?php endif; ?>
            <?php if (isset($_SESSION['login_error'])) : ?>
                <p class="error"><?php echo $_SESSION['login_error']; ?></p>
                <?php unset($_SESSION['login_error']); ?>
            <?php endif; ?>
            <form method="post">
                <input type="text" name="username" placeholder="Username" required>
                <input type="password" name="password" placeholder="Password" required>
                <img src="captcha.php" alt="CAPTCHA Image"><br>
                <input type="text" name="captcha" placeholder="Enter CAPTCHA" required>
                <button type="submit" name="login">Login</button>
            </form>
        </div>
    </div>
</body>
</html>
<?php
} else {
    ?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta content="width=device-width,initial-scale=1,user-scalable=0" name=viewport>
  <link
    href="https://fonts.googleapis.com/css2?family=Lato:ital,wght@0,100;0,300;0,400;0,700;0,900;1,100;1,300;1,400;1,700;1,900&display=swap"
    rel="stylesheet">
   <link rel="icon" type="image/x-icon" href="logo1.webp">
  <title>Admin Panel</title>
</head>
<style>
  body {
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 0;
    color: white;
    background: #34e89e;
    background: linear-gradient(to right, #000000, #000000);
    transition: background 1s ease;
  }

  h1 {
    font-family: "Lato", sans-serif;
    margin-left: 20px;
  }

  .lato {
    font-family: "Lato", sans-serif;
  }

  .button-container {
    display: flex;
    background-color: transparent;
    width: 250px;
    height: 40px;
    align-items: center;
    justify-content: space-around;
    border-radius: 10px;

  }

  .button {
    outline: 0 !important;
    border: 0 !important;
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background-color: transparent;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #fff;
    transition: all ease-in-out 0.3s;
    cursor: pointer;
  }

  .button:hover {
    transform: translateY(-3px);
  }

  .button:hover:nth-child(6) {
    transform: rotate(80deg) translateX(-3px);
  }


  .icon {
    font-size: 20px;
  }

  .container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 20px;
  }

  header {
    background: rgba(237, 237, 237, 0.1);
    backdrop-filter: blur(5px);
    color: #ffffff;
    border-radius: 50px;
    padding: 20px;
  }

  nav ul {
    list-style: none;
    padding: 0;
  }

  nav ul li {
    display: inline;
    margin-right: 20px;
  }

  nav ul li a {
    color: #ffffff;
    text-decoration: none;
  }

  .stats {
    display: flex;
    justify-content: space-around;
    margin: 20px 0;
  }

  .stat {
    background: rgba(237, 237, 237, 0.1);
    padding: 20px;
    margin: 10px;
    border-radius: 50px;
    width: 30%;
    cursor: default;
    text-align: center;
    transition: ease-out 0.2s;
  }

  .stat:hover {
    background: rgba(237, 237, 237, 0.2);
    transform: scale(0.95);
    color: #e03ed8;
  }

  .table-section {
    background: rgba(237, 237, 237, 0.1);
    padding: 20px;
    border-radius: 50px;
    transition: ease 0.3s;
  }

  .table-section:hover {
    background: rgba(237, 237, 237, 0.09);
    transform: scale(0.99);
    z-index: -1;
  }

  footer {
    text-align: center;
    margin-top: 20px;
  }

  #userChart {
    max-width: auto;
    max-height: 500px;
    margin: 100px;
    margin-bottom: 20px;
    color: white;
    margin-top: 20px;
    display: block;
  }

  .center {
    margin-left: auto;
    margin-right: auto;
    left: 0;
    text-align: center;
    right: 0;
  }

  .switch {
    background: #333;
    padding: 15px;
    border-radius: 15px;
    cursor: pointer;
    background: #000;
    border: none;
    outline: none;
    color: white;
    transition: ease-out 0.3s;
  }

  .switch:hover {
    transform: scale(0.95);
    color: #e03ed8;
  }

  .Btn {
    display: flex;
    align-items: center;
    justify-content: flex-start;
    width: 50px;
    height: 50px;
    border: none;
    background: transparent;
    border-radius: 50%;
    cursor: pointer;
    position: absolute;
    right: 0;
    margin-right: 30px;

    overflow: hidden;
    transition-duration: 0.4s;
  }

  .sign {
    width: 100%;
    transition-duration: 0.4s;
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
    color: #ecf0f1;
    font-size: 1.2em;
    font-weight: 600;
    transition-duration: 0.4s;
  }

  .Btn:hover {
    width: 150px;
    background: linear-gradient(to right, #3498db, #e74c3c);
    border-radius: 20px;
    transition-duration: 0.4s;
  }

  .Btn:hover .sign {
    width: 30%;
    transition-duration: 0.4s;
    padding-left: 12px;
  }

  .Btn:hover .text {
    opacity: 1;
    width: 70%;
    transition-duration: 0.4s;
    padding-right: 10px;
  }

  .Btn:active {
    transform: translate(2px, 2px);
    box-shadow: 0 0 0 rgba(0, 0, 0, 0.2);
  }
  .blur {
      display: table;
      padding: 10px;
      max-width: 300px;
      text-align: center;
      border-radius: 25px;
      margin: 20px auto;
      margin-bottom: 80px;
      position: relative;
      filter: blur(7px);
      transition: ease 0.3s;
      background: rgba(237, 237, 237, 0.3);
      z-index: 1;
      cursor: pointer;
  }
  .blur:hover {
      backdrop-filter: blur(5px);
      filter: blur(0px);
  }
  .reveal {
      text-align: center;
      color: white;
      font-family: "Lato", sans-serif;
  }
  .copy {
    padding: 5px 10px;
    background-color: transparent;
    color: white;
    border: 1px solid #ffffff80;
    cursor: pointer;
    border-radius: 10px;
    transition: background-color 0.3s ease;
}

.copy:hover {
    background-color: #ffffff90;
}
.form-container {
    background: rgba(237, 237, 237, 0.1);
    padding: 20px;
    border-radius: 20px;
    max-width: 400px;
    margin: 20px auto;
    border: 1px solid #ffffff80;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    transition: ease 0.3s;
}
.form-container:hover {
    transform: scale(1.01);
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.5);
}

.form-heading {
    color: #ffffff;
    text-align: center;
    margin-bottom: 15px;
    font-size: 1.5em;
}

.form-label {
    display: block;
    font-size: 1em;
    margin-bottom: 10px;
    color: #ffffff;
}

.input-field {
    width: calc(100% - 20px);
    padding: 10px;
    margin-bottom: 20px;
    border: 1px solid #00bfa5;
    border-radius: 5px;
    background-color: rgba(255, 255, 255, 0.5);
    color: #ffffff;
    font-size: 1em;
}

.input-field:focus {
    outline: none;
    border-color: #00bfa5;
    box-shadow: 0 0 5px rgba(0, 191, 165, 0.5);
}

.submit-button {
    width: 100%;
    padding: 10px;
    font-size: 1em;
    background-color: #00bfa5;
    color: white;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

.submit-button:hover {
    background-color: #FA5F55;
}

.input-field::placeholder {
    color: #b2dfdb;
}
  .success-message {
        color: #AFE1AF;
        background-color: rgba(255, 255, 255, 0.5);
        padding: 10px;
        border-radius: 5px;
        font-weight: bold;
    }
    .error-message {
        color: #FA5F55;
        background-color: rgba(255, 255, 255, 0.5);
        padding: 10px;
        border-radius: 5px;
        font-weight: bold;
    }
    .version {
        position: fixed;
        right: 0;
        color: black;
        z-index: -400;
        margin: 10px;
        bottom: 0;
    }
.menu {
  opacity: 0;
  display: none;
  position: fixed;
  margin-top: 550px;
  left: 50%;
  transform: translate(-50%, -50%) scale(0.9);
  background-color: rgba(0, 0, 0, 0.5);
  width: 100%;
  max-width: 450px;
  padding: 50px;
  border-radius: 10px;
  text-align: center;
  z-index: 10000;
  color: white;
  transition: opacity 0.5s ease, transform 0.5s ease;
}

.menu.show {
  opacity: 1;
  position: fixed;
  transform: translate(-50%, -50%) scale(1);
}
.circle-container {
  display: flex;
  justify-content: space-around;
  z-index: 10001;
  gap: 2em;
  margin-top: 20px;
}

.circle {
  width: 60px;
  height: 60px;
  border-radius: 50%;
  transition: ease 0.3s;
}
.circle:hover {
    transform: translateY(-5px);
    cursor: pointer;
}

.gradient1 {
       background: linear-gradient(to right, #283c86, #45a247);
}

.gradient2 {
   background: linear-gradient(to bottom right, #000000, #0f3443, #34e89e);
}

.gradient3 {
   background: linear-gradient(to right, #0f0c29, #302b63, #24243e);
}
.gradient4 {
   background: linear-gradient(to right, #000000, #434343);
}
.gradient5 {
   background: linear-gradient(to right, #000000, #000000);
}


.close-btn {
  position: fixed;
  top: 10px;
  right: 10px;
  background: none;
  border: none;
  padding: 10px;
  font-weight: bold;
  font-size: 20px;
  cursor: pointer;
}


  @media only screen and (max-width: 600px) {
    .container {
      background: transparent;
      box-shadow: none;
    }

    #userChart {
      max-width: auto;
      max-height: 300px;
      color: white;
      margin: 0px;
      margin-top: 20px;
      display: block;
      color: white;
      border-radius: 30px;
    }
  }
</style>

<body>
    <p class="version">3.0.8</p>
  <div class="container">
    <header>
      <h1>ADMIN PANEL</h1>
      <nav>
        <div class="button-container">
          <button class="button">
            <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 1024 1024" stroke-width="0"
              fill="currentColor" stroke="currentColor" class="icon">
              <path
                d="M946.5 505L560.1 118.8l-25.9-25.9a31.5 31.5 0 0 0-44.4 0L77.5 505a63.9 63.9 0 0 0-18.8 46c.4 35.2 29.7 63.3 64.9 63.3h42.5V940h691.8V614.3h43.4c17.1 0 33.2-6.7 45.3-18.8a63.6 63.6 0 0 0 18.7-45.3c0-17-6.7-33.1-18.8-45.2zM568 868H456V664h112v204zm217.9-325.7V868H632V640c0-22.1-17.9-40-40-40H432c-22.1 0-40 17.9-40 40v228H238.1V542.3h-96l370-369.7 23.1 23.1L882 542.3h-96.1z">
              </path>
            </svg>
          </button>
          <button class="button"  onclick="window.location='submissions.php'">
          <svg fill="#ffffff" version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 477.297 477.297" xml:space="preserve" width="20px" height="20px" stroke="#ffffff"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <g> <g> <g> <path d="M42.85,358.075c0-24.138,0-306.758,0-330.917c23.9,0,278.867,0,302.767,0c0,8.542,0,49.44,0,99.722 c5.846-1.079,11.842-1.812,17.99-1.812c3.149,0,6.126,0.647,9.232,0.928V0H15.649v385.233h224.638v-27.158 C158.534,358.075,57.475,358.075,42.85,358.075z"></path> <path d="M81.527,206.842h184.495c1.812-10.16,5.393-19.608,10.095-28.452H81.527V206.842z"></path> <rect x="81.527" y="89.432" width="225.372" height="28.452"></rect> <path d="M81.527,295.822h191.268c5.112-3.106,10.57-5.63,16.415-7.183c-5.544-6.45-10.095-13.697-13.978-21.269H81.527V295.822z"></path> <path d="M363.629,298.669c41.071,0,74.16-33.197,74.16-74.139c0-40.984-33.09-74.16-74.16-74.16 c-40.898,0-74.009,33.176-74.009,74.16C289.62,265.472,322.731,298.669,363.629,298.669z"></path> <path d="M423.143,310.706H304.288c-21.226,0-38.612,19.457-38.612,43.422v119.33c0,1.316,0.604,2.481,0.69,3.84h194.59 c0.086-1.337,0.69-2.524,0.69-3.84v-119.33C461.733,330.227,444.39,310.706,423.143,310.706z"></path> </g> </g> <g> </g> <g> </g> <g> </g> <g> </g> <g> </g> <g> </g> <g> </g> <g> </g> <g> </g> <g> </g> <g> </g> <g> </g> <g> </g> <g> </g> <g> </g> </g> </g></svg>
          </button>
          <button class="button" id="scrollButton">
            <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24" stroke-width="0"
              fill="red" stroke="currentColor" class="icon" id="manageUsersBtn">
              <path
                d="M12 2.5a5.5 5.5 0 0 1 3.096 10.047 9.005 9.005 0 0 1 5.9 8.181.75.75 0 1 1-1.499.044 7.5 7.5 0 0 0-14.993 0 .75.75 0 0 1-1.5-.045 9.005 9.005 0 0 1 5.9-8.18A5.5 5.5 0 0 1 12 2.5ZM8 8a4 4 0 1 0 8 0 4 4 0 0 0-8 0Z">
              </path>
            </svg>
          </button>
          <!-- themes !-->
                      <button class="button" onclick="toggleMenu()">
<svg fill="#ffffff" width="20px" height="20px" viewBox="0 0 512 512" id="icons" xmlns="http://www.w3.org/2000/svg" stroke="#00"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"><path d="M208,512a24.84,24.84,0,0,1-23.34-16l-39.84-103.6a16.06,16.06,0,0,0-9.19-9.19L32,343.34a25,25,0,0,1,0-46.68l103.6-39.84a16.06,16.06,0,0,0,9.19-9.19L184.66,144a25,25,0,0,1,46.68,0l39.84,103.6a16.06,16.06,0,0,0,9.19,9.19l103,39.63A25.49,25.49,0,0,1,400,320.52a24.82,24.82,0,0,1-16,22.82l-103.6,39.84a16.06,16.06,0,0,0-9.19,9.19L231.34,496A24.84,24.84,0,0,1,208,512Zm66.85-254.84h0Z"></path><path d="M88,176a14.67,14.67,0,0,1-13.69-9.4L57.45,122.76a7.28,7.28,0,0,0-4.21-4.21L9.4,101.69a14.67,14.67,0,0,1,0-27.38L53.24,57.45a7.31,7.31,0,0,0,4.21-4.21L74.16,9.79A15,15,0,0,1,86.23.11,14.67,14.67,0,0,1,101.69,9.4l16.86,43.84a7.31,7.31,0,0,0,4.21,4.21L166.6,74.31a14.67,14.67,0,0,1,0,27.38l-43.84,16.86a7.28,7.28,0,0,0-4.21,4.21L101.69,166.6A14.67,14.67,0,0,1,88,176Z"></path><path d="M400,256a16,16,0,0,1-14.93-10.26l-22.84-59.37a8,8,0,0,0-4.6-4.6l-59.37-22.84a16,16,0,0,1,0-29.86l59.37-22.84a8,8,0,0,0,4.6-4.6L384.9,42.68a16.45,16.45,0,0,1,13.17-10.57,16,16,0,0,1,16.86,10.15l22.84,59.37a8,8,0,0,0,4.6,4.6l59.37,22.84a16,16,0,0,1,0,29.86l-59.37,22.84a8,8,0,0,0-4.6,4.6l-22.84,59.37A16,16,0,0,1,400,256Z"></path></g></svg>
          </button>
<div id="menu" class="menu">
  <button class="close-btn" onclick="toggleMenu()"> <svg fill="#000000" width="32px" height="32px" viewBox="0 0 24 24" id="cross-circle" data-name="Flat Color" xmlns="http://www.w3.org/2000/svg" class="icon flat-color"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"><circle id="primary" cx="12" cy="12" r="10" style="fill: #000000;"></circle><path id="secondary" d="M13.41,12l2.3-2.29a1,1,0,0,0-1.42-1.42L12,10.59,9.71,8.29A1,1,0,0,0,8.29,9.71L10.59,12l-2.3,2.29a1,1,0,0,0,0,1.42,1,1,0,0,0,1.42,0L12,13.41l2.29,2.3a1,1,0,0,0,1.42,0,1,1,0,0,0,0-1.42Z" style="fill: <svg fill=" #fff"="" height="200px" width="200px" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 490 490" xml:space="preserve" stroke="#fff"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <polygon points="456.851,0 245,212.564 33.149,0 0.708,32.337 212.669,245.004 0.708,457.678 33.149,490 245,277.443 456.851,490 489.292,457.678 277.331,245.004 489.292,32.337 "></polygon> </g>;"&gt;</path></g></svg> </button>
  <h1 style="margin-right: 15px">THEMES</h1>
  <br><br>
    <div class="circle-container">
      <div class="circle gradient1" onclick="changeBackground('gradient1')"></div>
      <div class="circle gradient2" onclick="changeBackground('gradient2')"></div>
      <div class="circle gradient3" onclick="changeBackground('gradient3')"></div>
            <div class="circle gradient4" onclick="changeBackground('gradient4')"></div>
               <div class="circle gradient5" onclick="changeBackground('gradient5')"> 
            </div> </div></div>

          <button class="button" onclick="window.location='adv_settings.php'">
            <svg xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" width="20" height="20" viewBox="0,0,256,256">
              <g fill="#ffffff" fill-rule="nonzero" stroke="none" stroke-width="1" stroke-linecap="butt"
                stroke-linejoin="miter" stroke-miterlimit="10" stroke-dasharray="" stroke-dashoffset="0"
                font-family="none" font-weight="none" font-size="none" text-anchor="none"
                style="mix-blend-mode: normal">
                <g transform="scale(8,8)">
                  <path
                    d="M13.1875,3l-0.15625,0.8125l-0.59375,2.96875c-0.95312,0.375 -1.8125,0.90234 -2.59375,1.53125l-2.90625,-1l-0.78125,-0.25l-0.40625,0.71875l-2,3.4375l-0.40625,0.71875l0.59375,0.53125l2.25,1.96875c-0.08203,0.51172 -0.1875,1.02344 -0.1875,1.5625c0,0.53906 0.10547,1.05078 0.1875,1.5625l-2.25,1.96875l-0.59375,0.53125l0.40625,0.71875l2,3.4375l0.40625,0.71875l0.78125,-0.25l2.90625,-1c0.78125,0.62891 1.64063,1.15625 2.59375,1.53125l0.59375,2.96875l0.15625,0.8125h5.625l0.15625,-0.8125l0.59375,-2.96875c0.95313,-0.375 1.8125,-0.90234 2.59375,-1.53125l2.90625,1l0.78125,0.25l0.40625,-0.71875l2,-3.4375l0.40625,-0.71875l-0.59375,-0.53125l-2.25,-1.96875c0.08203,-0.51172 0.1875,-1.02344 0.1875,-1.5625c0,-0.53906 -0.10547,-1.05078 -0.1875,-1.5625l2.25,-1.96875l0.59375,-0.53125l-0.40625,-0.71875l-2,-3.4375l-0.40625,-0.71875l-0.78125,0.25l-2.90625,1c-0.78125,-0.62891 -1.64062,-1.15625 -2.59375,-1.53125l-0.59375,-2.96875l-0.15625,-0.8125zM14.8125,5h2.375l0.5,2.59375l0.125,0.59375l0.5625,0.1875c1.13672,0.35547 2.16797,0.95703 3.03125,1.75l0.4375,0.40625l0.5625,-0.1875l2.53125,-0.875l1.1875,2.03125l-2,1.78125l-0.46875,0.375l0.15625,0.59375c0.12891,0.57031 0.1875,1.15234 0.1875,1.75c0,0.59766 -0.05859,1.17969 -0.1875,1.75l-0.125,0.59375l0.4375,0.375l2,1.78125l-1.1875,2.03125l-2.53125,-0.875l-0.5625,-0.1875l-0.4375,0.40625c-0.86328,0.79297 -1.89453,1.39453 -3.03125,1.75l-0.5625,0.1875l-0.125,0.59375l-0.5,2.59375h-2.375l-0.5,-2.59375l-0.125,-0.59375l-0.5625,-0.1875c-1.13672,-0.35547 -2.16797,-0.95703 -3.03125,-1.75l-0.4375,-0.40625l-0.5625,0.1875l-2.53125,0.875l-1.1875,-2.03125l2,-1.78125l0.46875,-0.375l-0.15625,-0.59375c-0.12891,-0.57031 -0.1875,-1.15234 -0.1875,-1.75c0,-0.59766 0.05859,-1.17969 0.1875,-1.75l0.15625,-0.59375l-0.46875,-0.375l-2,-1.78125l1.1875,-2.03125l2.53125,0.875l0.5625,0.1875l0.4375,-0.40625c0.86328,-0.79297 1.89453,-1.39453 3.03125,-1.75l0.5625,-0.1875l0.125,-0.59375zM16,11c-2.75,0 -5,2.25 -5,5c0,2.75 2.25,5 5,5c2.75,0 5,-2.25 5,-5c0,-2.75 -2.25,-5 -5,-5zM16,13c1.66797,0 3,1.33203 3,3c0,1.66797 -1.33203,3 -3,3c-1.66797,0 -3,-1.33203 -3,-3c0,-1.66797 1.33203,-3 3,-3z">
                  </path>
                </g>
              </g>
            </svg>
          </button>


          <button class="Btn" onclick="window.location='?logout'">
            <div class="sign">
              <svg viewBox="0 0 512 512">
                <path
                  d="M377.9 105.9L500.7 228.7c7.2 7.2 11.3 17.1 11.3 27.3s-4.1 20.1-11.3 27.3L377.9 406.1c-6.4 6.4-15 9.9-24 9.9c-18.7 0-33.9-15.2-33.9-33.9l0-62.1-128 0c-17.7 0-32-14.3-32-32l0-64c0-17.7 14.3-32 32-32l128 0 0-62.1c0-18.7 15.2-33.9 33.9-33.9c9 0 17.6 3.6 24 9.9zM160 96L96 96c-17.7 0-32 14.3-32 32l0 256c0 17.7 14.3 32 32 32l64 0c17.7 0 32 14.3 32 32s-14.3 32-32 32l-64 0c-53 0-96-43-96-96L0 128C0 75 43 32 96 32l64 0c17.7 0 32 14.3 32 32s-14.3 32-32 32z">
                </path>
              </svg>
            </div>

            <div class="text">Logout</div>
          </button>

        </div>
      </nav>
    </header>
    
      <!-- bork in progress !-->
    <section class="stats">
      <div class="stat" style="cursor: pointer" id="scrollDiv">
        <h2>Admin Users</h2>
        <p>2</p>
      </div>
      <div class="stat">
    <h2>Total visits</h2>
    <p id="total-visits">Loading...</p>
      </div>
      
      <div class="stat" style="cursor: pointer" onclick="window.location='submissions.php';">
    <h2>Submissions</h2>
    <p>
        <?php
        $filePath = '../xa550-serverside0000-55880sa51f000000000sa98ds41009as89d41w06694981a89fd89g8r4h8rt4hj9rt4h89erg1fa0dsf1gqer1yjr5819ey4j1156a0d0000/submissions.txt';

        function countSubmissions($filePath) {
            $fileContents = file_get_contents($filePath);
            $submissions = explode('Name:', $fileContents);
            return count($submissions) - 1;
        }
        echo countSubmissions($filePath);
        ?>
    </p>
</div>

    </section>
    <section class="table-section">
      <h2 class="lato" style="text-align: center">ANALYTICS</h2>
      <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
      <canvas id="userChart"></canvas>
      <div class="center">
        <button id="switchChartType" class="switch">Switch Chart Type</button>
      </div>
      </section>
      
            <!-- add admin thing !-->
            
      <section class="table-section" style="margin-top: 50px; transform: scale(1) !important;" id="1">
          <h1>USER MANAGEMENT</h1>
          <p class="reveal">Hover to reveal usernames</p>
          
      <div style="text-align: center; margin-top: 30px; margin-left: 5px">
          <div class="blur">
    <h3 style="text-align: left; color: pink">Current Admins:</h3>
    <ul style="text-align: left"> 
<?php
$admins = json_decode(file_get_contents('admins.json'), true);
foreach ($admins as $username => $password) {
    echo "<li>$username <button class='copy' onclick=\"copyToClipboard(this, '$username')\">Copy</button></li> <br>";
}
?>
    </ul>
</div>
</div>
<div class="form-container">
    <h3 class="form-heading">Add a New Admin</h3>
        <div id="message-container"></div>
    <form id="addAdminForm">
        <label for="username" class="form-label">Username:</label>
        <input type="text" id="addUsername" name="username" class="input-field" required><br>
        <label for="password" class="form-label">Password:</label>
        <input type="password" id="addPassword" name="password" class="input-field" required><br>
        <button type="submit" class="submit-button">Add Admin</button>
    </form>

    <h3 class="form-heading">Remove an Admin</h3>
    <form id="removeAdminForm">
        <label for="removeUsername" class="form-label">Username:</label>
        <input type="text" id="removeUsername" name="username" class="input-field" required><br>
        <button type="submit" class="submit-button">Remove Admin</button>
    </form>
</div>
    </section>
   
    <section style="margin-top: 50px">
         <div class="table-section" style="width: 75%; margin: 0 auto;">
             <h1>Miscellaneous</h1>
       <canvas id="myChart" width="400" height="200"></canvas>
    </div>
    <footer>
      <p>&copy; 2024 Fluctuss Panel. All rights reserved.</p>
    </footer>
  </div>

  
  <!-- script !-->
<script>
    // JavaScript to handle the scroll
    function scrollToSection() {
        const section = document.getElementById('1');
        section.scrollIntoView({ behavior: 'smooth' });
    }

    document.getElementById('scrollButton').addEventListener('click', scrollToSection);
    document.getElementById('scrollDiv').addEventListener('click', scrollToSection);
</script>
<script>
async function fetchLogs() {
    const response = await fetch('../trace/http_requests_log.txt');
    const logText = await response.text();
    const getMatch = logText.match(/GET=(\d+)/);
    const postMatch = logText.match(/POST=(\d+)/);

    const getCount = getMatch ? parseInt(getMatch[1], 10) : 0;
    const postCount = postMatch ? parseInt(postMatch[1], 10) : 0;

    const labels = ["Total Requests"];
    const getData = [getCount]; 
    const postData = [postCount]; 

    const ctx = document.getElementById('myChart').getContext('2d');
    const chart = new Chart(ctx, {
        type: 'bar', 
        data: {
            labels: labels,
            datasets: [
                {
                    label: 'GET Requests',
                    data: getData,
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 1
                },
                {
                    label: 'POST Requests',
                    data: postData,
                    backgroundColor: 'rgba(255, 99, 132, 0.2)',
                    borderColor: 'rgba(255, 99, 132, 1)',
                    borderWidth: 1
                }
            ]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Number of Requests'
                    }
                },
                x: {
                    title: {
                        display: true,
                        text: 'Request Types'
                    }
                }
            }
        }
    });
}

fetchLogs();
</script>
  <script>
    var ctx = document.getElementById('userChart').getContext('2d');
    var chartTypes = ['bar', 'line', 'pie', 'doughnut', 'radar', 'polarArea'];
    var currentIndex = 0;
    var userChart;
    function updateChartData() {
      fetch('/visitors.json')
        .then(response => response.json())
        .then(data => {
          if (userChart) {
            userChart.destroy();
          }
          userChart = new Chart(ctx, {
            type: chartTypes[currentIndex],
            data: {
              labels: data.labels,
              datasets: data.datasets
            },
            options: {
              scales: {
                y: {
                  beginAtZero: true
                }
              },
              responsive: true,
              maintainAspectRatio: false
            }
          });
        })
        .catch(error => console.error('Error fetching the data:', error));
    }
    updateChartData();
    document.getElementById('switchChartType').addEventListener('click', function () {
      currentIndex = (currentIndex + 1) % chartTypes.length;
      updateChartData();
    });
  </script>
<script>
function copyToClipboard(button, username) {
    const tempInput = document.createElement('input');
    tempInput.value = username;
    document.body.appendChild(tempInput);
    tempInput.select();
    document.execCommand('copy');
    document.body.removeChild(tempInput);
    button.innerHTML = 'Copied!';
    
    setTimeout(() => {
        button.innerHTML = 'Copy';
    }, 2000);
}
</script>
<script>
    const addAdminForm = document.getElementById('addAdminForm');
    const removeAdminForm = document.getElementById('removeAdminForm');
    const messageContainer = document.getElementById('message-container');

    function showMessage(message, isSuccess) {
        messageContainer.innerHTML = `<p class="${isSuccess ? 'success-message' : 'error-message'}">${message}</p>`;
    }
    async function sendRequest(data) {
        const response = await fetch('admin_manager.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(data),
        });
        return response.json();
    }
    addAdminForm.addEventListener('submit', async (e) => {
        e.preventDefault();  
        const username = document.getElementById('addUsername').value;
        const password = document.getElementById('addPassword').value;

        const data = {
            action: 'add',
            username: username,
            password: password,
        };

        const result = await sendRequest(data);

        // Display result
        showMessage(result.message, result.status === 'success');
    });
    removeAdminForm.addEventListener('submit', async (e) => {
        e.preventDefault();  // Prevent form from submitting the traditional way

        const username = document.getElementById('removeUsername').value;

        const data = {
            action: 'remove',
            username: username,
        };
        const result = await sendRequest(data);
        showMessage(result.message, result.status === 'success');
    });
</script>

    <script>
        fetch('../visitors.json')
            .then(response => response.json())
            .then(data => {
                const visitData = data.datasets[0].data;
                const totalVisits = visitData.reduce((total, num) => total + num, 0); 

                document.getElementById('total-visits').textContent = totalVisits;
            })
            .catch(error => {
                console.error('Error fetching the visitors data:', error);
                document.getElementById('total-visits').textContent = 'Error loading data';
            });
    </script>
    <script>
function toggleMenu() {
  const menu = document.getElementById("menu");
  
  if (menu.style.display === "none" || menu.style.display === "") {
    menu.style.display = "block";
    setTimeout(() => {
      menu.classList.add("show");
    }, 10); 
  } else {
    menu.classList.remove("show");
    setTimeout(() => {
      menu.style.display = "none";
    }, 500);
  }
}

    </script>
<script>
function changeBackground(gradientClass) {
  const gradientDiv = document.querySelector(`.${gradientClass}`);
  const computedStyle = window.getComputedStyle(gradientDiv);
  const newBackground = computedStyle.backgroundImage;
  localStorage.setItem('selectedBackground', newBackground);

  let opacity = 1;
  const fadeOutInterval = setInterval(() => {
    opacity -= 0.05; 
    document.body.style.opacity = opacity;

    if (opacity <= 0) {
      clearInterval(fadeOutInterval);
      document.body.style.backgroundImage = newBackground;

      let fadeInOpacity = 0;
      const fadeInInterval = setInterval(() => {
        fadeInOpacity += 0.05;
        document.body.style.opacity = fadeInOpacity;

        if (fadeInOpacity >= 1) {
          clearInterval(fadeInInterval);
        }
      }, 20); 
    }
  }, 20); 
}
function applySavedBackground() {
  const savedBackground = localStorage.getItem('selectedBackground');
  if (savedBackground) {
    document.body.style.backgroundImage = savedBackground;
  }
}
window.onload = applySavedBackground;
</script>


</body>
</html>
    <?php
    }
    ?>
