<?php
session_start();
include 'connection.php';

if(!isset($_SESSION['admin_id'])){
    exit("Not authorized");
}

if($_SERVER['REQUEST_METHOD'] != 'POST'){
    header("Location: admin_profile.php");
    exit();
}

$admin_id = intval($_POST['admin_id']);
$username = trim($_POST['username']);
$password = $_POST['password'];
$confirm = $_POST['confirm_password'];

if($password != '' && $password != $confirm){
    header("Location: admin_profile.php?msg=" . urlencode("Passwords do not match."));
    exit();
}

// Update admin info
if($password == ''){
    $stmt = $conn->prepare("UPDATE admin SET username=? WHERE id=?");
    $stmt->bind_param("si", $username, $admin_id);
} else {
    $stmt = $conn->prepare("UPDATE admin SET username=?, password=? WHERE id=?");
    $stmt->bind_param("ssi", $username, $password, $admin_id);
}

if($stmt->execute()){
    $_SESSION['admin_name'] = $username;
    header("Location: admin_profile.php?msg=" . urlencode("Profile updated successfully."));
} else {
    header("Location: admin_profile.php?msg=" . urlencode("Update failed, try again."));
}
