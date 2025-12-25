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

// Prepare chart data
$paid = $student['advanceFee'];
$total = $student['courseFee'];
$remaining = $total - $paid;

?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Student Summary</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://kit.fontawesome.com/a2d9d5b258.js" crossorigin="anonymous"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" integrity="sha512-VbL0W...YOUR_HASH..." crossorigin="anonymous" referrerpolicy="no-referrer" />
<style>
body { background: #f5f6fa; font-family: 'Poppins', sans-serif; }
.container { margin-top: 60px; }
.card { border-radius: 12px; }
.back-btn, .admin-logout {
    margin-top: 20px;
    margin-right: 10px;
}
.admin-logout {
    position: fixed;
    top: 20px;
    right: 20px;
    font-size: 28px;
    cursor: pointer;
    color: #fff;
    background: #dc3545;
    border-radius: 50%;
    padding: 10px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.2);
    transition: 0.3s;
}
.admin-logout:hover {
    background: #c82333;
    transform: scale(1.1);
}
</style>
</head>
<body>

<!-- Admin Logout Icon -->
<a href="admin_logout.php" class="admin-logout" title="Admin Logout">
    <i class="fa-solid fa-right-from-bracket"></i>
</a>

<div class="container">
    <h2 class="text-center mb-4">Summary for <?php echo $_SESSION['student_name']; ?></h2>

    <div class="card p-4 shadow mb-4">
        <h4>Financial Overview</h4>
        <canvas id="summaryChart" style="max-height: 300px;"></canvas>
    </div>

    <div class="card p-4 shadow">
        <h4>Key Details</h4>
        <table class="table table-bordered mt-3">
            <tbody>
                <tr><th>Course Fee</th><td>₹ <?php echo $student['courseFee']; ?></td></tr>
                <tr><th>Advance Paid</th><td>₹ <?php echo $student['advanceFee']; ?></td></tr>
                <tr><th>Remaining EMI</th><td>₹ <?php echo $remaining; ?></td></tr>
                <tr><th>EMI Duration</th><td><?php echo $student['emiTenure']; ?></td></tr>
                <tr><th>Batch</th><td><?php echo $student['batch']; ?></td></tr>
            </tbody>
        </table>

        <a href="student_dashboard.php" class="btn btn-primary back-btn">
            <i class="fa-solid fa-arrow-left me-1"></i> Back to Dashboard
        </a>
    </div>
</div>

<script>
const ctx = document.getElementById('summaryChart').getContext('2d');
const summaryChart = new Chart(ctx, {
    type: 'doughnut',
    data: {
        labels: ['Advance Paid', 'Remaining EMI'],
        datasets: [{
            label: '₹',
            data: [<?php echo $paid; ?>, <?php echo $remaining; ?>],
            backgroundColor: ['#28a745', '#ffc107'],
            borderColor: ['#28a745', '#ffc107'],
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: { position: 'bottom' }
        }
    }
});
</script>

</body>
</html>
