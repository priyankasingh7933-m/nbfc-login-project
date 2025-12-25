<?php
session_start();
include 'connection.php';

if(!isset($_SESSION['admin_id'])){
    header("Location: admin_login.php");
    exit();
}

// Logout
if(isset($_POST['logout'])){
    session_destroy();
    header("location: admin_login.php");
    exit();
}

// -----------------------
// Search & Date Filter Setup
// -----------------------
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$from_date = isset($_GET['from_date']) ? $_GET['from_date'] : '';
$to_date = isset($_GET['to_date']) ? $_GET['to_date'] : '';

// Pagination setup
$records_per_page = isset($_GET['limit']) ? (int)$_GET['limit'] : 10;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $records_per_page;

// Base query
$baseQuery = "FROM emi_records WHERE 1";

// Search filter
if($search != ''){
    $baseQuery .= " AND (student_name LIKE '%$search%' OR counsellor_name LIKE '%$search%')";
}

// Date filter
if(!empty($from_date) && !empty($to_date)){
    $baseQuery .= " AND DATE(date_time) BETWEEN '$from_date' AND '$to_date'";
} elseif(!empty($from_date)){
    $baseQuery .= " AND DATE(date_time) >= '$from_date'";
} elseif(!empty($to_date)){
    $baseQuery .= " AND DATE(date_time) <= '$to_date'";
}

// Total rows for pagination
$countQuery = "SELECT COUNT(*) as total ".$baseQuery;
$countResult = $conn->query($countQuery);
$total_rows = $countResult ? $countResult->fetch_assoc()['total'] : 0;
$total_pages = ($total_rows > 0) ? ceil($total_rows / $records_per_page) : 1;

// Fetch students
$query = "SELECT * ".$baseQuery." ORDER BY id DESC LIMIT $records_per_page OFFSET $offset";
$students = $conn->query($query);

// -----------------------
// Bulk CSV Download
// -----------------------
if(isset($_GET['bulk_download'])){
    $bulkQuery = "SELECT * ".$baseQuery." ORDER BY id DESC";
    $bulkStudents = $conn->query($bulkQuery);

    if($bulkStudents && $bulkStudents->num_rows > 0){
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="students_bulk_records.csv"');
        $output = fopen('php://output', 'w');
        $headerWritten = false;
        while($row = $bulkStudents->fetch_assoc()){
            if(!$headerWritten){
                fputcsv($output, array_keys($row));
                $headerWritten = true;
            }
            fputcsv($output, $row);
        }
        fclose($output);
        exit();
    } else {
        echo "<script>alert('No records found to download.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Student EMI Dashboard</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://kit.fontawesome.com/a2d9d5b258.js" crossorigin="anonymous"></script>
<style>
body { background: #f0f2f5; font-family: 'Poppins', sans-serif; }
.container { margin-top: 50px; }
.card { padding: 20px; border-radius: 12px; box-shadow: 0 0 15px rgba(0,0,0,0.2); background: #fff; }
.table th { background-color: #0984e3; color: #fff; }
.btn-action { margin: 2px; }
.top-buttons { display: flex; justify-content: flex-end; gap: 10px; margin-bottom: 20px; flex-wrap: wrap; }
.pagination { margin: 0; }
</style>
</head>
<body>

<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>Student EMI Dashboard</h2>
        <div class="top-buttons">
            <a href="add_student_emi.php" class="btn btn-success">
                <i class="fa-solid fa-user-plus"></i> Add Student
            </a>
            <!-- ✅ Updated NBFC Button Redirect -->
            <a href="admin_dashboard.php" class="btn btn-info text-white">
                <i class="fa-solid fa-database"></i> NBFC Data
            </a>
            <form method="POST" class="d-inline">
                <button name="logout" class="btn btn-danger">
                    Logout <i class="fa-solid fa-right-from-bracket"></i>
                </button>
            </form>
        </div>
    </div>

    <!-- ✅ Search + Date Filter + Bulk CSV + Reset -->
    <form method="GET" class="row g-2 mb-3 align-items-end">
        <div class="col-md-3">
            <label>Search</label>
            <input type="text" name="search" class="form-control" placeholder="Student/Counsellor" value="<?php echo htmlspecialchars($search); ?>">
        </div>
        <div class="col-md-2">
            <label>From Date</label>
            <input type="date" name="from_date" class="form-control" value="<?php echo $from_date; ?>">
        </div>
        <div class="col-md-2">
            <label>To Date</label>
            <input type="date" name="to_date" class="form-control" value="<?php echo $to_date; ?>">
        </div>
        <div class="col-md-5 d-flex gap-2">
            <button type="submit" class="btn btn-primary mt-2">Search / Filter</button>
            <a href="view_student.php" class="btn btn-secondary mt-2">Reset</a>
            <button type="submit" name="bulk_download" value="1" class="btn btn-success mt-2">
                <i class="fa-solid fa-download"></i> Bulk CSV
            </button>
        </div>
    </form>

    <!-- Pagination and Limit Dropdown -->
    <div class="d-flex justify-content-between align-items-center mb-2 flex-wrap">
        <div>
            <form method="GET" class="d-flex align-items-center">
                <label class="me-2">Show</label>
                <select name="limit" class="form-select w-auto" onchange="this.form.submit()">
                    <option value="10" <?php if($records_per_page==10) echo 'selected'; ?>>10</option>
                    <option value="15" <?php if($records_per_page==15) echo 'selected'; ?>>15</option>
                    <option value="20" <?php if($records_per_page==20) echo 'selected'; ?>>20</option>
                    <option value="25" <?php if($records_per_page==25) echo 'selected'; ?>>25</option>
                </select>
            </form>
        </div>
        <nav>
            <ul class="pagination mb-0">
                <?php if($page > 1): ?>
                    <li class="page-item">
                        <a class="page-link" href="?page=<?php echo ($page-1); ?>&limit=<?php echo $records_per_page; ?>">Previous</a>
                    </li>
                <?php endif; ?>
                <?php for($i=1; $i <= $total_pages; $i++): ?>
                    <li class="page-item <?php if($i==$page) echo 'active'; ?>">
                        <a class="page-link" href="?page=<?php echo $i; ?>&limit=<?php echo $records_per_page; ?>">
                            <?php echo $i; ?>
                        </a>
                    </li>
                <?php endfor; ?>
                <?php if($page < $total_pages): ?>
                    <li class="page-item">
                        <a class="page-link" href="?page=<?php echo ($page+1); ?>&limit=<?php echo $records_per_page; ?>">Next</a>
                    </li>
                <?php endif; ?>
            </ul>
        </nav>
    </div>

    <div class="card mt-2">
        <table class="table table-bordered table-striped align-middle">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Student Name</th>
                    <th>Course Counsellor</th>
                    <th>EMI Amount</th>
                    <th>Reset Amount</th>
                    <th>Paid Amount</th>
                    <th>Date & Time</th>
                    <th>Payment Proof</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
            <?php if($students && $students->num_rows > 0): ?>
                <?php while($row = $students->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row['id'];?></td>
                        <td><?php echo $row['student_name'];?></td>
                        <td><?php echo $row['counsellor_name'];?></td>
                        <td>₹<?php echo $row['emi_amount'];?></td>
                        <td>₹<?php echo $row['reset_amount'];?></td>
                        <td>₹<?php echo $row['paid_amount'];?></td>
                        <td><?php echo $row['date_time'];?></td>
                        <td>
                            <?php if(!empty($row['proof_image']) && file_exists($row['proof_image'])): ?>
                                <a href="<?php echo $row['proof_image'];?>" target="_blank">
                                    <img src="<?php echo $row['proof_image'];?>" width="50" height="50" style="border-radius:5px;">
                                </a>
                            <?php else: ?>N/A<?php endif; ?>
                        </td>
                        <td>
                            <a href="edit_emi.php?id=<?php echo $row['id'];?>" class="btn btn-sm btn-warning btn-action">
                                <i class="fa-solid fa-pen-to-square"></i> Edit
                            </a>
                            <a href="delete_emi.php?id=<?php echo $row['id'];?>" class="btn btn-sm btn-danger btn-action" onclick="return confirm('Are you sure?')">
                                <i class="fa-solid fa-trash"></i> Delete
                            </a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr><td colspan="9" class="text-center">No records found.</td></tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
