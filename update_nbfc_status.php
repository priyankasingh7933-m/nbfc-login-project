<?php
include 'connection.php';

if(isset($_POST['student_id']) && isset($_POST['nbfc_status'])){
    $id = intval($_POST['student_id']);
    $status = trim($_POST['nbfc_status']);
    // Updated: modal me textarea ka name 'nbfc_remark' tha, isko properly handle kar rahe
    $remark = isset($_POST['nbfc_remark']) ? trim($_POST['nbfc_remark']) : '';

    // NBFC status + remark dono update
    $stmt = $conn->prepare("UPDATE students SET nbfc_status=?, nbfc_remark=? WHERE id=?");
    $stmt->bind_param("ssi", $status, $remark, $id);

    if($stmt->execute()){
        echo "NBFC Status and Remark updated successfully!";
    } else {
        echo "Error updating status: " . $stmt->error;
    }
} else {
    echo "Invalid request!";
}
?>
