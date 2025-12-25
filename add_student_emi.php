<?php
session_start();
if(!isset($_SESSION['admin_id'])){
    header("Location: admin_login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Add Student / EMI</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://kit.fontawesome.com/a2d9d5b258.js" crossorigin="anonymous"></script>
<style>
body {
    background: linear-gradient(135deg, #232526, #414345);
    color: #2d3436;
    font-family: 'Poppins', sans-serif;
}

.container {
    margin-top: 50px;
}

.form-container {
    background: #ffffff; /* White background */
    padding: 30px;
    border-radius: 15px;
    box-shadow: 0 0 15px rgba(0,0,0,0.2);
    color: #2d3436; /* Dark text for contrast */
}

label {
    font-weight: 500;
    margin-top: 10px;
}

.btn-submit {
    background-color: #00c6ff;
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
    background-color: #0072ff;
    transform: scale(1.05);
}

.logout-btn {
    float:right;
    margin-bottom: 20px;
}
</style>
</head>
<body>

<div class="container">
    <h2>Welcome, <?php echo $_SESSION['admin_name']; ?></h2>
    <form method="POST" action="admin_logout.php">
        <button type="submit" class="btn btn-danger logout-btn">
            Logout <i class="fa-solid fa-right-from-bracket"></i>
        </button>
    </form>

    <div class="col-md-8 offset-md-2 form-container">
        <h3 class="text-center mb-4">Add Student / EMI Details</h3>
        <form action="insert_emi.php" method="POST" enctype="multipart/form-data">
            <div class="row">
                <div class="col-md-6">
                    <label>Student Name</label>
                    <input type="text" name="student_name" class="form-control" required>
                </div>
                <div class="col-md-6">
                    <label>Course Counsellor Name</label>
                    <input type="text" name="counsellor_name" class="form-control" required>
                </div>
                <div class="col-md-6">
                    <label>EMI Amount (₹)</label>
                    <input type="number" name="emi_amount" class="form-control" required>
                </div>
                <div class="col-md-6">
                    <label>Reset Amount (₹)</label>
                    <input type="number" name="reset_amount" class="form-control" required>
                </div>
                <div class="col-md-6">
                    <label>Paid EMI Amount (₹)</label>
                    <input type="number" name="paid_amount" class="form-control" required>
                </div>
                <div class="col-md-6">
                    <label>Date & Time</label>
                    <input type="datetime-local" name="date_time" class="form-control" required>
                </div>
                <div class="col-md-12">
                    <label>Upload Image (Payment Proof)</label>
                    <input type="file" name="proof_image" class="form-control" accept="image/*" required>
                </div>
            </div>
            <button type="submit" class="btn-submit">Submit EMI Details</button>
        </form>
    </div>
</div>

</body>
</html>
