<?php
session_start();
include 'connection.php';
if(!isset($_SESSION['admin_id'])){ header("Location: admin_login.php"); exit; }
if($_SERVER['REQUEST_METHOD'] !== 'POST') header("Location: admin_dashboard.php");

$id = intval($_POST['id']);
$studentName = $_POST['studentName'] ?? '';
$studentEmail = $_POST['studentEmail'] ?? '';
$courseName = $_POST['courseName'] ?? '';
$batch = $_POST['batch'] ?? '';
$studentNumber = $_POST['studentNumber'] ?? '';
$courseFee = $_POST['courseFee'] ?? 0;
$emiStart = $_POST['emiStart'] ?? null;
$emiTenure = $_POST['emiTenure'] ?? null;

// handle optional file
$filePath = null;
if(isset($_FILES['document']) && $_FILES['document']['error'] === 0){
    $targetDir = 'uploads/';
    if(!is_dir($targetDir)) mkdir($targetDir,0777,true);
    $fileName = basename($_FILES['document']['name']);
    $filePath = $targetDir . $fileName;
    move_uploaded_file($_FILES['document']['tmp_name'], $filePath);
    // update with new path
    $sql = "UPDATE students SET studentName=?, studentEmail=?, courseName=?, batch=?, studentNumber=?, courseFee=?, emiStart=?, emiTenure=?, document_path=? WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssdsssi", $studentName, $studentEmail, $courseName, $batch, $studentNumber, $courseFee, $emiStart, $emiTenure, $filePath, $id);
} else {
    // keep existing document_path
    $sql = "UPDATE students SET studentName=?, studentEmail=?, courseName=?, batch=?, studentNumber=?, courseFee=?, emiStart=?, emiTenure=? WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssdssi", $studentName, $studentEmail, $courseName, $batch, $studentNumber, $courseFee, $emiStart, $emiTenure, $id);
}

if($stmt->execute()){
    header("Location: admin_dashboard.php");
    exit();
} else {
    echo "Error: " . $stmt->error;
}
