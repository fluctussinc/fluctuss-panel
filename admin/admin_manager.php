<?php
session_start();
header('Content-Type: application/json');  

$jsonFilePath = 'admins.json';

function getAdmins($jsonFilePath) {
    $jsonData = file_get_contents($jsonFilePath);
    return json_decode($jsonData, true);
}

function saveAdmins($admins, $jsonFilePath) {
    $jsonData = json_encode($admins, JSON_PRETTY_PRINT);
    file_put_contents($jsonFilePath, $jsonData);
}

$admins = getAdmins($jsonFilePath);

if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
    echo json_encode(['status' => 'error', 'message' => 'You must be logged in to continue.']);
    exit;
}

$currentUser = $_SESSION['username'];
if (!isset($admins[$currentUser])) {
    echo json_encode(['status' => 'error', 'message' => 'Access Denied: You do not have permission to manage admins.']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);  
    if (isset($data['action'])) {
        if ($data['action'] === 'add') {
            $newUsername = $data['username'];
            $newPassword = $data['password'];
            if (!empty($newUsername) && !isset($admins[$newUsername])) {
                $admins[$newUsername] = $newPassword;
                saveAdmins($admins, $jsonFilePath);
                echo json_encode(['status' => 'success', 'message' => 'Admin added successfully!']);
                exit;
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Error adding admin. Username might already exist or invalid input.']);
                exit;
            }
        } elseif ($data['action'] === 'remove') {
            $removeUsername = $data['username'];
            if (!empty($removeUsername) && isset($admins[$removeUsername])) {
                unset($admins[$removeUsername]);
                saveAdmins($admins, $jsonFilePath);
                echo json_encode(['status' => 'success', 'message' => 'Admin removed successfully!']);
                exit;
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Error removing admin. Username might not exist or invalid input.']);
                exit;
            }
        }
    }
}
?>
