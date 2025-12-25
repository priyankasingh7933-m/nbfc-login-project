<?php
include 'connection.php';

if(isset($_POST['student_id']) && isset($_POST['nbfc_remark'])){
    $id = intval($_POST['student_id']);
    $remark = trim($_POST['nbfc_remark']);

    $stmt = $conn->prepare("UPDATE students SET nbfc_remark=? WHERE id=?");
    $stmt->bind_param("si", $remark, $id);

    if($stmt->execute()){
        echo "Remark updated successfully";
    } else {
        echo "Error updating remark";
    }
} else {
    echo "Invalid request.";
}
?>
