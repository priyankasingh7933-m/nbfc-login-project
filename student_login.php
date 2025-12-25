<?php
session_start();
include 'connection.php';

$error = '';

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $studentName  = trim($_POST['studentName']);
    $studentEmail = trim($_POST['studentEmail']);

    // Check if student exists
    $stmt = $conn->prepare("SELECT * FROM students WHERE studentName = ? AND studentEmail = ?");
    $stmt->bind_param("ss", $studentName, $studentEmail);
    $stmt->execute();
    $result = $stmt->get_result();

    if($result->num_rows > 0){
        $student = $result->fetch_assoc();
        // Set session
        $_SESSION['student_id'] = $student['id'];
        $_SESSION['student_name'] = $student['studentName'];
        $_SESSION['student_email'] = $student['studentEmail'];
        // Redirect to student dashboard
        header("Location: student_dashboard.php");
        exit();
    } else {
        $error = "❌ Student not found! Check Name and Email.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Student Login</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
body {
    background: #f0f2f5;
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
    font-family: 'Poppins', sans-serif;
}
.login-box {
    background: #fff;
    padding: 40px;
    border-radius: 10px;
    box-shadow: 0 0 25px rgba(0,0,0,0.1);
    width: 100%;
    max-width: 400px;
}
h3 {
    text-align: center;
    color: #007bff;
    margin-bottom: 25px;
}
.error {
    color: red;
    text-align: center;
    margin-bottom: 10px;
}
.btn-primary {
    width: 100%;
}
</style>
</head>
<body>

<div class="login-box">
    <h3>Student Login</h3>
    <?php if($error) echo "<p class='error'>$error</p>"; ?>
    <form method="POST" action="">
        <div class="mb-3">
            <label>Name</label>
            <input type="text" name="studentName" class="form-control" placeholder="Enter your Name" required>
        </div>
        <div class="mb-3">
            <label>Email</label>
            <input type="email" name="studentEmail" class="form-control" placeholder="Enter your Email" required>
        </div>
        <button type="submit" class="btn btn-primary">Login</button>
    </form>
</div>

</body>
</html>
