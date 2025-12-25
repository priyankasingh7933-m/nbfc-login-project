<?php
session_start();
include 'connection.php';

if(!isset($_SESSION['admin_id'])){
    header("Location: admin_login.php");
    exit();
}

if(!isset($_GET['id'])){
    echo "<script>alert('Invalid request!'); window.location='view_students.php';</script>";
    exit();
}

$id = intval($_GET['id']);
$stmt = $conn->prepare("SELECT * FROM emi_records WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if($result->num_rows == 0){
    echo "<script>alert('Record not found!'); window.location='view_students.php';</script>";
    exit();
}

$row = $result->fetch_assoc();
$stmt->close();

// Update record when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
    $student_name = $_POST['student_name'];
    $counsellor_name = $_POST['counsellor_name'];
    $emi_amount = $_POST['emi_amount'];
    $reset_amount = $_POST['reset_amount'];
    $paid_amount = $_POST['paid_amount'];
    $date_time = $_POST['date_time'];
    $proof_image = $row['proof_image']; // Default old image

    // Handle new image upload
    if(isset($_FILES['proof_image']) && $_FILES['proof_image']['error'] == 0){
        $target_dir = "uploads/";
        if(!is_dir($target_dir)) mkdir($target_dir, 0777, true);

        $file_name = time() . "_" . basename($_FILES['proof_image']['name']);
        $target_file = $target_dir . $file_name;

        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        $allowed = ['jpg','jpeg','png','gif'];

        if(in_array($imageFileType, $allowed)){
            if(move_uploaded_file($_FILES['proof_image']['tmp_name'], $target_file)){
                // delete old image if exists
                if(!empty($proof_image) && file_exists($proof_image)){
                    unlink($proof_image);
                }
                $proof_image = $target_file;
            }
        }
    }

    // Update query
    $update = $conn->prepare("UPDATE emi_records SET student_name=?, counsellor_name=?, emi_amount=?, reset_amount=?, paid_amount=?, date_time=?, proof_image=? WHERE id=?");
    $update->bind_param("ssddsssi", $student_name, $counsellor_name, $emi_amount, $reset_amount, $paid_amount, $date_time, $proof_image, $id);

    if($update->execute()){
        echo "<script>alert('Record Updated Successfully!'); window.location='view_students.php';</script>";
    } else {
        echo "<script>alert('Error updating record!');</script>";
    }

    $update->close();
    $conn->close();
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Edit EMI Record</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://kit.fontawesome.com/a2d9d5b258.js" crossorigin="anonymous"></script>
<style>
body {
    background: linear-gradient(135deg, #2c3e50, #3498db);
    color: #2d3436;
    font-family: 'Poppins', sans-serif;
}
.container {
    margin-top: 50px;
}
.form-container {
    background: #fff;
    padding: 30px;
    border-radius: 15px;
    box-shadow: 0 0 15px rgba(0,0,0,0.2);
}
label {
    font-weight: 500;
    margin-top: 10px;
}
.btn-submit {
    background-color: #00b894;
    border: none;
    width: 100%;
    padding: 12px;
    color: white;
    font-weight: bold;
    border-radius: 10px;
    margin-top: 20px;
    transition: 0.3s;
}
.btn-submit:hover {
    background-color: #01936b;
    transform: scale(1.05);
}
.logout-btn { float:right; }
</style>
</head>
<body>

<div class="container">
    <div class="col-md-8 offset-md-2 form-container">
        <h3 class="text-center mb-4"><i class="fa-solid fa-pen-to-square"></i> Edit Student EMI</h3>
        <form method="POST" enctype="multipart/form-data">
            <div class="row">
                <div class="col-md-6">
                    <label>Student Name</label>
                    <input type="text" name="student_name" class="form-control" value="<?php echo $row['student_name']; ?>" required>
                </div>
                <div class="col-md-6">
                    <label>Course Counsellor</label>
                    <input type="text" name="counsellor_name" class="form-control" value="<?php echo $row['counsellor_name']; ?>" required>
                </div>
                <div class="col-md-6">
                    <label>EMI Amount (₹)</label>
                    <input type="number" name="emi_amount" class="form-control" value="<?php echo $row['emi_amount']; ?>" required>
                </div>
                <div class="col-md-6">
                    <label>Reset Amount (₹)</label>
                    <input type="number" name="reset_amount" class="form-control" value="<?php echo $row['reset_amount']; ?>" required>
                </div>
                <div class="col-md-6">
                    <label>Paid EMI Amount (₹)</label>
                    <input type="number" name="paid_amount" class="form-control" value="<?php echo $row['paid_amount']; ?>" required>
                </div>
                <div class="col-md-6">
                    <label>Date & Time</label>
                    <input type="datetime-local" name="date_time" class="form-control" value="<?php echo date('Y-m-d\TH:i', strtotime($row['date_time'])); ?>" required>
                </div>
                <div class="col-md-12">
                    <label>Current Proof Image</label><br>
                    <?php if(!empty($row['proof_image']) && file_exists($row['proof_image'])): ?>
                        <img src="<?php echo $row['proof_image']; ?>" width="80" height="80" style="border-radius:10px;">
                    <?php else: ?>
                        <p>No image uploaded</p>
                    <?php endif; ?>
                    <br><label>Upload New Proof (optional)</label>
                    <input type="file" name="proof_image" class="form-control" accept="image/*">
                </div>
            </div>
            <button type="submit" class="btn-submit">Update EMI Details</button>
        </form>
    </div>
</div>

</body>
</html>
