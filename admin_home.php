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
<title>Admin Panel</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://kit.fontawesome.com/a2d9d5b258.js" crossorigin="anonymous"></script>
<style>
body {
    background: linear-gradient(135deg, #6c5ce7, #00b894);
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    font-family: 'Poppins', sans-serif;
}

.card {
    padding: 40px;
    border-radius: 20px;
    box-shadow: 0 10px 25px rgba(0,0,0,0.2);
    background-color: #fff;
    text-align: center;
    width: 100%;
    max-width: 500px;
}

.card h2 {
    margin-bottom: 30px;
    color: #2d3436;
    font-weight: 700;
}

.btn-custom {
    width: 100%;
    padding: 15px;
    margin: 10px 0;
    font-size: 18px;
    border-radius: 10px;
    transition: all 0.3s ease;
}

.btn-nbfc {
    background-color: #0984e3;
    color: #fff;
}

.btn-nbfc:hover {
    background-color: #74b9ff;
    color: #fff;
}

.btn-add {
    background-color: #00b894;
    color: #fff;
}

.btn-add:hover {
    background-color: #55efc4;
    color: #fff;
}

.btn-view {
    background-color: #fdcb6e;
    color: #fff;
}

.btn-view:hover {
    background-color: #ffeaa7;
    color: #fff;
}

.logout-btn {
    position: absolute;
    top: 20px;
    right: 20px;
}
</style>
</head>
<body>

<form method="POST" action="admin_logout.php">
    <button type="submit" class="btn btn-danger logout-btn">
        Logout <i class="fa-solid fa-right-from-bracket"></i>
    </button>
</form>

<div class="card">
    <h2>Welcome, <?php echo $_SESSION['admin_name']; ?></h2>

    <!-- NBFC Student Data Button -->
    <a href="admin_dashboard.php" class="btn btn-custom btn-nbfc">
        <i class="fa-solid fa-database"></i> NBFC Student Data
    </a>

    <!-- Add Student / EMI Button -->
    <a href="add_student_emi.php" class="btn btn-custom btn-add">
        <i class="fa-solid fa-user-plus"></i> Add Student / EMI
    </a>

    <!-- View Students Button -->
    <a href="view_students.php" class="btn btn-custom btn-view">
        <i class="fa-solid fa-users"></i> View Students
    </a>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
