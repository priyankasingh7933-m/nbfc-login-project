<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include 'connection.php';

// Check if admin is logged in
if(!isset($_SESSION['admin_id'])){
    header("Location: admin_login.php");
    exit();
}

// Check if form submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
    $student_name   = $_POST['student_name'];
    $counsellor_name = $_POST['counsellor_name'];
    $emi_amount     = $_POST['emi_amount'];
    $reset_amount   = $_POST['reset_amount'];
    $paid_amount    = $_POST['paid_amount'];
    $date_time      = $_POST['date_time'];

    // 🩵 Upload directory
    $target_dir = "uploads/";

    // Create uploads folder if not exists
    if(!is_dir($target_dir)){
        mkdir($target_dir, 0777, true);
    }

    // 🩵 Handle image upload
    $proof_image = "";
    if(isset($_FILES["proof_image"]) && $_FILES["proof_image"]["error"] == 0){
        $file_name = time() . "_" . basename($_FILES["proof_image"]["name"]);
        $target_file = $target_dir . $file_name;

        // Validate image type
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        $allowed = array("jpg", "jpeg", "png", "gif");

        if(in_array($imageFileType, $allowed)){
            if(move_uploaded_file($_FILES["proof_image"]["tmp_name"], $target_file)){
                $proof_image = $target_file; // ✅ Save correct relative path
            } else {
                echo "<script>alert('Error uploading image.'); window.history.back();</script>";
                exit();
            }
        } else {
            echo "<script>alert('Invalid image format. Only JPG, PNG, GIF allowed.'); window.history.back();</script>";
            exit();
        }
    }

    // 🩵 Insert data into table
    $stmt = $conn->prepare("INSERT INTO emi_records (student_name, counsellor_name, emi_amount, reset_amount, paid_amount, date_time, proof_image) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssddsss", $student_name, $counsellor_name, $emi_amount, $reset_amount, $paid_amount, $date_time, $proof_image);

    if($stmt->execute()){
        echo "<script>alert('EMI Record Added Successfully!'); window.location='view_students.php';</script>";
    } else {
        echo "<script>alert('Error saving record.'); window.history.back();</script>";
    }

    $stmt->close();
    $conn->close();
}
?>