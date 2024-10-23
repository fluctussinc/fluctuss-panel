<?php
session_start();

if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
    die("
    <html>
    <h2>You must be logged in to continue.</h2>
    </html>
    ");
    exit;
}

$folderPath = "../xa550-serverside0000-55880sa51f000000000sa98ds41009as89d41w06694981a89fd89g8r4h8rt4hj9rt4h89erg1fa0dsf1gqer1yjr5819ey4j1156a0d0000";
$fileName = "submissions.txt";
$filePath = realpath($folderPath . DIRECTORY_SEPARATOR . $fileName);
$deletedFilePath = "../xa550-serverside0000-55880sa51f000000000sa98ds41009as89d41w06694981a89fd89g8r4h8rt4hj9rt4h89erg1fa0dsf1gqer1yjr5819ey4j1156a0d0000/deleted_submissions.txt";

if (!file_exists($deletedFilePath)) {
    file_put_contents($deletedFilePath, '');
}

if (strpos($filePath, realpath($folderPath)) !== 0 || !file_exists($filePath)) {
    die("<p class='error'>The file was not found or access is restricted.</p>");
}

if (isset($_POST['delete_submission'])) {
    $deleteIndex = $_POST['delete_index'] ?? '';
    if (is_numeric($deleteIndex) && $deleteIndex >= 0) {
        $fileContents = file_get_contents($filePath);
        $fileContents = str_replace("\r\n", "\n", $fileContents);
        $submissions = array_filter(preg_split('/\n(?=Name:)/', $fileContents));

        if (isset($submissions[$deleteIndex])) {
            $deletedSubmission = $submissions[$deleteIndex];
            unset($submissions[$deleteIndex]);

            file_put_contents($deletedFilePath, $deletedSubmission . "\n\n", FILE_APPEND);
            file_put_contents($filePath, implode("\n\n", $submissions));

            header('Location: ' . $_SERVER['PHP_SELF']);
            exit;
        } else {
            die("<p class='error'>Invalid submission index.</p>");
        }
    } else {
        die("<p class='error'>Invalid request.</p>");
    }
}

if (isset($_POST['delete_permanently'])) {
    $deleteIndex = $_POST['delete_permanently_index'] ?? '';
    if (is_numeric($deleteIndex) && $deleteIndex >= 0) {
        $deletedFileContents = file_get_contents($deletedFilePath);
        $deletedFileContents = str_replace("\r\n", "\n", $deletedFileContents);
        $deletedSubmissions = array_filter(preg_split('/\n(?=Name:)/', $deletedFileContents));

        if (isset($deletedSubmissions[$deleteIndex])) {
            unset($deletedSubmissions[$deleteIndex]);
            file_put_contents($deletedFilePath, implode("\n\n", $deletedSubmissions));

            header('Location: ' . $_SERVER['PHP_SELF']);
            exit;
        } else {
            die("<p class='error'>Invalid submission index.</p>");
        }
    } else {
        die("<p class='error'>Invalid request.</p>");
    }
}

if (isset($_POST['delete_all'])) {
    if (file_exists($deletedFilePath)) {
        file_put_contents($deletedFilePath, '');
        header('Location: ' . $_SERVER['PHP_SELF']);
        exit;
    } else {
        die("<p class='error'>The deleted submissions file was not found.</p>");
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Submissions panel</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
    <link rel="icon" type="image/png" href="logo1.webp" />
    <style>
        .advanced-details {
            display: none;
            margin-top: 10px;
            font-style: italic;
        }
        .show-details {
            cursor: pointer;
            color: blue;
            text-decoration: underline;
        }
        .advanced-details  {
            display: none;
            margin-top: 10px;
            font-style: italic;
        }
        .back-btn {
            background-color: rgb(237, 237, 237, 0.2);
            border: none;
            backdrop-filter: blur(20px);
            border-radius: 50px;
            color: white;
            border: none;
            padding: 15px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            margin-left: 20px;
            position: absolute;
            top: 60px;
            font-weight: bold;
            margin-top: 50px;
            transition: 0.5s;
            font-size: 14px;
        }
        .back-btn:hover {
            background-color: rgb(237, 237, 237, 0.3);
            color: #ccffcc;
        }
        @media only screen and (max-width: 600px) {
            .back-btn {
             position: relative;
    bottom: 0px;
    left: 50%;
    top: 0;
    transform: translateX(-50%);
    margin-left: 0px;
            }
    </style>
</head>
<body>

<?php
if (file_exists($filePath)) {
    $fileContents = file_get_contents($filePath);
    $fileContents = str_replace("\r\n", "\n", $fileContents);
    $submissions = array_reverse(array_filter(preg_split('/\n(?=Name:)/', $fileContents)));

    echo "<h1>Submissions</h1>";
    echo "<div class='container'>";

    foreach ($submissions as $index => $submission) {
        $lines = array_map('trim', explode("\n", $submission));

        $name = $email = $subject = $message = $date = $ip = 'N/A';

        foreach ($lines as $line) {
            if (strpos($line, 'Name:') === 0) {
                $name = htmlspecialchars(trim(substr($line, 5)), ENT_QUOTES, 'UTF-8');
            } elseif (strpos($line, 'Email:') === 0) {
                $email = htmlspecialchars(trim(substr($line, 6)), ENT_QUOTES, 'UTF-8');
            } elseif (strpos($line, 'Subject:') === 0) {
                $subject = htmlspecialchars(trim(substr($line, 8)), ENT_QUOTES, 'UTF-8');
            } elseif (strpos($line, 'Message:') === 0) {
                $message = htmlspecialchars(trim(substr($line, 8)), ENT_QUOTES, 'UTF-8');
            } elseif (strpos($line, 'Date:') === 0) {
                $date = htmlspecialchars(trim(substr($line, 5)), ENT_QUOTES, 'UTF-8');
            } elseif (strpos($line, 'IP Address:') === 0) {
                $ip = htmlspecialchars(trim(substr($line, 11)), ENT_QUOTES, 'UTF-8');
            }
        }

        echo "<div class='submission'>";
        echo "<h3>Submission " . ($index + 1) . "</h3>";
        echo "<p><strong>Name:</strong> $name</p>";
        echo "<p><strong>Email:</strong> $email</p>";
        echo "<p><strong>Subject:</strong> $subject</p>";
        echo "<p><strong>Message:</strong> $message</p>";
        echo "<p><span class='show-details' data-index='$index'>Advanced Details</span></p>";
        echo "<div class='advanced-details' id='details-$index'>";
        echo "<p><strong>Date:</strong> $date</p>";
        echo "<p><strong>IP Address:</strong> $ip</p>";
        echo "</div>";
        echo "<form method='post'>
                  <input type='hidden' name='delete_index' value='$index'>
                  <button type='submit' name='delete_submission' class='delete-btn'>Delete</button>
              </form>";
        echo "</div>";
    }

    echo "</div>";
} else {
    echo "<p class='error'>The file was not found.</p>";
}
?>

<div>
    <a href="index.php" class="back-btn">Return</a>
    <form action="download_submissions.php" method="post">
        <button type="submit" class="dscsv">Download Submissions CSV</button>
    </form>
    <button id="toggleDeletedBtn">Deleted Submissions</button>

    <div id="deletedSubmissions" class="hidden">
        <?php
        if (file_exists($deletedFilePath)) {
            $deletedFileContents = file_get_contents($deletedFilePath);
            $deletedFileContents = str_replace("\r\n", "\n", $deletedFileContents);
            $deletedSubmissions = array_filter(preg_split('/\n(?=Name:)/', $deletedFileContents));

            if (count($deletedSubmissions) > 0) {
                echo "<div class='container'>";

                foreach ($deletedSubmissions as $index => $submission) {
                    $lines = array_map('trim', explode("\n", $submission));

                    $name = $email = $subject = $message = $date = $ip = 'N/A';

                    foreach ($lines as $line) {
                        if (strpos($line, 'Name:') === 0) {
                            $name = htmlspecialchars(trim(substr($line, 5)), ENT_QUOTES, 'UTF-8');
                        } elseif (strpos($line, 'Email:') === 0) {
                            $email = htmlspecialchars(trim(substr($line, 6)), ENT_QUOTES, 'UTF-8');
                        } elseif (strpos($line, 'Subject:') === 0) {
                            $subject = htmlspecialchars(trim(substr($line, 8)), ENT_QUOTES, 'UTF-8');
                        } elseif (strpos($line, 'Message:') === 0) {
                            $message = htmlspecialchars(trim(substr($line, 8)), ENT_QUOTES, 'UTF-8');
                        } elseif (strpos($line, 'Date:') === 0) {
                            $date = htmlspecialchars(trim(substr($line, 5)), ENT_QUOTES, 'UTF-8');
                        } elseif (strpos($line, 'IP Address:') === 0) {
                            $ip = htmlspecialchars(trim(substr($line, 11)), ENT_QUOTES, 'UTF-8');
                        }
                    }

                    echo "<div class='submission'>";
                    echo "<h3>Deleted Submission " . ($index + 1) . "</h3>";
                    echo "<p><strong>Name:</strong> $name</p>";
                    echo "<p><strong>Email:</strong> $email</p>";
                    echo "<p><strong>Subject:</strong> $subject</p>";
                    echo "<p><strong>Message:</strong> $message</p>";
                    echo "<p><span class='show-details' data-index='$index'>Advanced Details</span></p>";
                    echo "<div class='advanced-details' id='deleted-details-$index'>";
                    echo "<p><strong>Date:</strong> $date</p>";
                    echo "<p><strong>IP Address:</strong> $ip</p>";
                    echo "</div>";
                    echo "<form method='post'>
                              <input type='hidden' name='delete_permanently_index' value='$index'>
                              <button type='submit' name='delete_permanently' class='delete-btn'>Permanently Delete</button>
                          </form>";
                    echo "</div>";
                }

                echo "</div>";
            } else {
                echo "<p class='error'>No deleted submissions found.</p>";
            }
        } else {
            echo "<p class='error'>The deleted submissions file was not found.</p>";
        }
        ?>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var toggleDeletedBtn = document.getElementById('toggleDeletedBtn');
    var deletedSection = document.getElementById('deletedSubmissions');
    var showDeleted = localStorage.getItem('showDeleted');

    if (showDeleted === 'true') {
        deletedSection.classList.remove('hidden');
        toggleDeletedBtn.textContent = 'Hide Deleted Submissions';
    } else {
        deletedSection.classList.add('hidden');
        toggleDeletedBtn.textContent = 'Deleted Submissions';
    }

    toggleDeletedBtn.addEventListener('click', function() {
        if (deletedSection.classList.contains('hidden')) {
            deletedSection.classList.remove('hidden');
            this.textContent = 'Hide Deleted Submissions';
            localStorage.setItem('showDeleted', 'true');
            scrollToBottom();  // Smooth scroll to the bottom when section is visible
        } else {
            deletedSection.classList.add('hidden');
            this.textContent = 'Deleted Submissions';
            localStorage.setItem('showDeleted', 'false');
        }
    });

    function toggleDetails(event) {
        var index = this.getAttribute('data-index');
        var details = document.getElementById('details-' + index);
        if (details.style.display === 'none' || details.style.display === '') {
            details.style.display = 'block';
        } else {
            details.style.display = 'none';
        }
    }

    function toggleDeletedDetails(event) {
        var index = this.getAttribute('data-index');
        var details = document.getElementById('deleted-details-' + index);
        if (details.style.display === 'none' || details.style.display === '') {
            details.style.display = 'block';
        } else {
            details.style.display = 'none';
        }
    }

    var detailsLinks = document.querySelectorAll('.show-details');
    detailsLinks.forEach(function(link) {
        link.addEventListener('click', toggleDetails);
    });

    var deletedDetailsLinks = document.querySelectorAll('#deletedSubmissions .show-details');
    deletedDetailsLinks.forEach(function(link) {
        link.addEventListener('click', toggleDeletedDetails);
    });

    function scrollToBottom() {
        window.scrollTo({
            top: document.body.scrollHeight,
            behavior: 'smooth' 
        });
    }

    window.addEventListener('beforeunload', function() {
        localStorage.setItem('scrollPosition', window.scrollY);
    });

    var scrollPosition = localStorage.getItem('scrollPosition');
    if (scrollPosition !== null) {
        window.scrollTo(0, scrollPosition);
    }
});
</script>

</body>
</html>

