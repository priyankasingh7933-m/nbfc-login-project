<?php
include 'connection.php';

if($_SERVER['REQUEST_METHOD'] === 'POST'){

    // Collect form data
    $studentName   = $_POST['studentName'] ?? '';
    $courseName    = $_POST['courseName'] ?? '';
    $batch         = $_POST['batch'] ?? '';
    $studentEmail  = $_POST['studentEmail'] ?? '';
    $studentNumber = $_POST['studentNumber'] ?? '';
    $courseFee     = $_POST['courseFee'] ?? 0;
    $advanceFee    = $_POST['advanceFee'] ?? 0;
    $emiStart      = $_POST['emiStart'] ?? '';
    $emiTenure     = $_POST['emiTenure'] ?? '';
    $parentName    = $_POST['parentName'] ?? '';
    $parentMobile  = $_POST['parentMobile'] ?? '';
    $aadhar        = $_POST['aadhar'] ?? '';
    $pan           = $_POST['pan'] ?? '';
    $account       = $_POST['account'] ?? '';
    $ifsc          = $_POST['ifsc'] ?? '';

    // File upload
    if(isset($_FILES['document']) && $_FILES['document']['error']==0){
        $fileName = $_FILES['document']['name'];
        $fileTmp  = $_FILES['document']['tmp_name'];
        $targetDir = "uploads/";
        if(!is_dir($targetDir)) mkdir($targetDir,0777,true);
        $filePath = $targetDir . basename($fileName);
        move_uploaded_file($fileTmp,$filePath);
    }else{
        $filePath = NULL;
    }

    // Prepare SQL statement
    $sql = "INSERT INTO students 
    (studentName, courseName, batch, studentEmail, studentNumber, courseFee, advanceFee, emiStart, emiTenure, parentName, parentMobile, aadhar, pan, account, ifsc, document_path) 
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    if(!$stmt){
        die("Prepare failed: ".$conn->error);
    }

    $stmt->bind_param(
        "ssssdidsssssssss",
        $studentName, $courseName, $batch, $studentEmail, $studentNumber,
        $courseFee, $advanceFee, $emiStart, $emiTenure,
        $parentName, $parentMobile, $aadhar, $pan, $account, $ifsc, $filePath
    );

    if($stmt->execute()){
        // ✅ Redirect to Student Login after successful save
        echo "<script>
            alert('✅ Student data saved successfully!');
            window.location.href='student_login.php';
        </script>";
    }else{
        echo "❌ Execute failed: ".$stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>
