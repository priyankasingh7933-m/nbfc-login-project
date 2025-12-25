<?php
session_start();
include 'connection.php';

// Agar admin login nahi hai to redirect karo
if(!isset($_SESSION['admin_id'])){
    header("Location: admin_login.php");
    exit();
}

// Agar URL me 'id' aayi hai
if(isset($_GET['id'])) {
    $id = intval($_GET['id']); // sanitize ID

    // Pehle proof image ka path nikalte hain
    $stmt = $conn->prepare("SELECT proof_image FROM emi_records WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->bind_result($proof_image);
    $stmt->fetch();
    $stmt->close();

    // Agar image file exist karti hai to delete kar do
    if(!empty($proof_image) && file_exists($proof_image)){
        unlink($proof_image);
    }

    // EMI record delete karo
    $stmt = $conn->prepare("DELETE FROM emi_records WHERE id = ?");
    $stmt->bind_param("i", $id);
    if($stmt->execute()){
        echo "<script>alert('Record deleted successfully!'); window.location='view_students.php';</script>";
    } else {
        echo "<script>alert('Error deleting record!'); window.location='view_students.php';</script>";
    }
    $stmt->close();
} else {
    echo "<script>alert('Invalid request!'); window.location='view_students.php';</script>";
}
?>
