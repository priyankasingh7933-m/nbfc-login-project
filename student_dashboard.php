<?php
session_start();
include 'connection.php';

// Check if student is logged in
if(!isset($_SESSION['student_id'])){
    header("Location: student_login.php");
    exit();
}

$student_id = $_SESSION['student_id'];

// Fetch student data
$stmt = $conn->prepare("SELECT * FROM students WHERE id=?");
$stmt->bind_param("i", $student_id);
$stmt->execute();
$result = $stmt->get_result();
$student = $result->fetch_assoc();

// CSV Download
if(isset($_GET['download']) && $_GET['download'] == 'csv'){
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="student_record.csv"');

    $output = fopen('php://output', 'w');
    fputcsv($output, array_keys($student));
    fputcsv($output, $student);
    fclose($output);
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Student Dashboard</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://kit.fontawesome.com/a2d9d5b258.js" crossorigin="anonymous"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" integrity="sha512-VbL0W...YOUR_HASH..." crossorigin="anonymous" referrerpolicy="no-referrer" />
<style>
body {
    background: #f5f6fa;
    font-family: 'Poppins', sans-serif;
}
.container { margin-top: 60px; }
.card { border-radius: 12px; }
.table th { background-color: #007bff; color: #fff; }
.download-btn, .summary-btn {
    margin-top: 20px;
    margin-right: 10px;
}

/* Admin Icon */
.admin-icon {
    position: fixed;
    top: 20px;
    right: 20px;
    font-size: 32px;
    cursor: pointer;
    color: #007bff;
    background: #fff;
    border-radius: 50%;
    padding: 12px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.2);
    transition: all 0.3s ease;
    z-index: 999;
}
.admin-icon:hover {
    color: #fff;
    background: #007bff;
    transform: scale(1.1);
}
</style>
</head>
<body>

<!-- Admin Icon / Logout -->
<?php if(isset($_SESSION['admin_id'])): ?>
    <a href="admin_logout.php" class="admin-icon" title="Admin Logout">
        <i class="fa-solid fa-right-from-bracket"></i>
    </a>
<?php else: ?>
    <a href="admin_login.php" class="admin-icon" title="Admin Login">
        <i class="fa-solid fa-user-shield"></i>
    </a>
<?php endif; ?>

<div class="container">
    <h2 class="text-center mb-4">Welcome, <?php echo $_SESSION['student_name']; ?></h2>

    <div class="card p-4 shadow">
        <h4>Your Record</h4>
        <table class="table table-bordered mt-3">
    <tbody>
        <?php foreach($student as $key => $value): ?>
            <?php if($key == 'nbfc_remark') continue; // skip nbfc_remark in loop ?>
            <tr>
                <th><?php echo ucwords(str_replace('_',' ',$key)); ?></th>
                <td><?php echo $value; ?></td>
            </tr>
        <?php endforeach; ?>
        
        <!-- ✅ NBFC Remark / Description -->
        <tr>
            <th>NBFC Remark</th>
            <td><?php echo isset($student['nbfc_remark']) ? $student['nbfc_remark'] : '-'; ?></td>
        </tr>
</tbody>
</table>


        <!-- Buttons -->
        <a href="?download=csv" class="btn btn-success download-btn">
            <i class="fa-solid fa-file-csv me-1"></i> Download CSV
        </a>
        <a href="student_summary.php?id=<?php echo $student_id; ?>" class="btn btn-primary summary-btn">
            <i class="fa-solid fa-chart-pie me-1"></i> Summary
        </a>
    </div>
</div>

</body>
</html>
