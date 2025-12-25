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
// Search & Filter setup (Date-wise)
// -----------------------
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$filter_course = isset($_GET['course']) ? trim($_GET['course']) : '';
$from_date = isset($_GET['from_date']) ? $_GET['from_date'] : '';
$to_date = isset($_GET['to_date']) ? $_GET['to_date'] : '';
$records_per_page = isset($_GET['limit']) ? (int)$_GET['limit'] : 10;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $records_per_page;

// -----------------------
// Base query
// -----------------------
$baseQuery = "FROM students WHERE 1";

// Search condition
if($search != ''){
    $baseQuery .= " AND (studentName LIKE '%$search%' 
                OR studentEmail LIKE '%$search%' 
                OR studentNumber LIKE '%$search%' 
                OR parentName LIKE '%$search%')";
}

// Filter by course
if($filter_course != ''){
    $baseQuery .= " AND courseName = '$filter_course'";
}

// Date filter
if(!empty($from_date) && !empty($to_date)){
    $baseQuery .= " AND DATE(created_at) BETWEEN '$from_date' AND '$to_date'";
} elseif(!empty($from_date)){
    $baseQuery .= " AND DATE(created_at) >= '$from_date'";
} elseif(!empty($to_date)){
    $baseQuery .= " AND DATE(created_at) <= '$to_date'";
}

// Total rows for pagination
$countQuery = "SELECT COUNT(*) as total ".$baseQuery;
$countResult = $conn->query($countQuery);
$total_rows = $countResult->fetch_assoc()['total'];
$total_pages = ceil($total_rows / $records_per_page);

// Final data fetch query
$query = "SELECT * ".$baseQuery." ORDER BY id DESC LIMIT $records_per_page OFFSET $offset";
$students = $conn->query($query);

// For course dropdown
$courses = $conn->query("SELECT DISTINCT courseName FROM students");

// -----------------------
// Individual CSV Download
// -----------------------
if(isset($_GET['download_id'])){
    $id = intval($_GET['download_id']);
    $stmt = $conn->prepare("SELECT * FROM students WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $student = $result->fetch_assoc();

    if($student){
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="student_'.$id.'_record.csv"');
        $output = fopen('php://output', 'w');
        fputcsv($output, array_keys($student));
        fputcsv($output, $student);
        fclose($output);
        exit();
    }
}

// -----------------------
// Bulk CSV Download
// -----------------------
if(isset($_GET['bulk_download'])){
    $bulkQuery = "SELECT * ".$baseQuery." ORDER BY id DESC";
    $bulkStudents = $conn->query($bulkQuery);

    if($bulkStudents->num_rows > 0){
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="students_bulk_records.csv"');
        $output = fopen('php://output', 'w');
        $headerWritten = false;
        while($student = $bulkStudents->fetch_assoc()){
            if(!$headerWritten){
                fputcsv($output, array_keys($student));
                $headerWritten = true;
            }
            fputcsv($output, $student);
        }
        fclose($output);
        exit();
    } else {
        echo "<script>alert('No records found for selected date range');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Admin Dashboard</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://kit.fontawesome.com/a2d9d5b258.js" crossorigin="anonymous"></script>
<style>
body { 
    background:#f0f2f5; 
    font-family:'Poppins',sans-serif; 
}
.container { 
    margin-top:50px; 
}
.card { 
    border-radius:15px; 
    padding:25px; 
    box-shadow:0 4px 15px rgba(0,0,0,0.1);
}
h2 { 
    color:#007bff; 
    font-weight:600;
}
.table th { 
    background: linear-gradient(135deg, #007bff, #00c6ff);
    color: #fff; 
    text-align:center;
}
.table td { 
    vertical-align:middle;
    text-align:center;
}
.table tr:hover { 
    background:#e6f7ff; 
    transition:0.3s;
}
.document-img { 
    width:50px; 
    height:auto; 
    border-radius:5px; 
}
.logout-btn { 
    float:right; 
}

/* Button gradients & hover */
.btn-gradient {
    color: #fff;
    font-weight:500;
    border:none;
    border-radius:8px;
    transition:0.3s;
}
.btn-primary-gradient {
    background: linear-gradient(45deg,#4e54c8,#8f94fb);
}
.btn-primary-gradient:hover {
    background: linear-gradient(45deg,#8f94fb,#4e54c8);
}
.btn-success-gradient {
    background: linear-gradient(45deg,#28a745,#7de26d);
}
.btn-success-gradient:hover {
    background: linear-gradient(45deg,#7de26d,#28a745);
}
.btn-info-gradient {
    background: linear-gradient(45deg,#17a2b8,#6dd5fa);
}
.btn-info-gradient:hover {
    background: linear-gradient(45deg,#6dd5fa,#17a2b8);
}
.btn-warning-gradient {
    background: linear-gradient(45deg,#ffc107,#ffdd57);
}
.btn-warning-gradient:hover {
    background: linear-gradient(45deg,#ffdd57,#ffc107);
}
.btn-danger-gradient {
    background: linear-gradient(45deg,#dc3545,#ff6b6b);
}
.btn-danger-gradient:hover {
    background: linear-gradient(45deg,#ff6b6b,#dc3545);
}

/* Button spacing fix */
.btn {
    margin-right: 6px;
    margin-bottom: 6px;
}
.btn:last-child {
    margin-right: 0;
}
form .btn {
    margin-top: 5px;
}

/* Pagination alignment */
.pagination .page-item {
    margin: 0 3px;
}

/* Limit dropdown alignment */
form.d-flex.align-items-center select.form-select {
    margin-right: 5px;
}

/* Responsive adjustments */
@media (max-width: 992px) {
    .table-responsive { 
        overflow-x:auto; 
    }
    .btn { 
        display:inline-block; 
        width: 100%; 
    }
    form .btn {
        width: 100%;
        margin-top: 5px;
    }
}
</style>
</head>
<body>

<div class="container">
   <h2>Welcome, <?php echo isset($_SESSION['admin_name']) ? htmlspecialchars($_SESSION['admin_name']) : 'Admin'; ?></h2>

    <!-- Profile + Logout Buttons -->
    <div class="mb-3 text-end">
    <a href="admin_profile.php" class="btn btn-info-gradient">
        <i class="fa-solid fa-user"></i> Profile
    </a>
    <form method="POST" style="display:inline-block;">
        <button name="logout" class="btn btn-danger-gradient">
            <i class="fa-solid fa-right-from-bracket"></i> Logout
        </button>
    </form>
</div>

    <div class="card mt-3">
        <h4>All Students</h4>

      <!-- Search + Filter + Date Range + Buttons -->
<form method="GET" class="row g-2 align-items-end mb-3">
    <div class="col-auto">
        <input type="text" name="search" class="form-control" placeholder="Search by name, email, number..." value="<?php echo htmlspecialchars($search); ?>">
    </div>
    <div class="col-auto">
        <select name="course" class="form-control">
            <option value="">All Courses</option>
            <?php while($c = $courses->fetch_assoc()): ?>
                <option value="<?php echo $c['courseName']; ?>" <?php if($filter_course == $c['courseName']) echo 'selected'; ?>>
                    <?php echo $c['courseName']; ?>
                </option>
            <?php endwhile; ?>
        </select>
    </div>
    <div class="col-auto">
        <input type="date" name="from_date" class="form-control" value="<?php echo $from_date; ?>">
    </div>
    <div class="col-auto">
        <input type="date" name="to_date" class="form-control" value="<?php echo $to_date; ?>">
    </div>
    <div class="col-auto d-flex flex-wrap gap-2">
        <button type="submit" class="btn btn-primary-gradient">
            <i class="fa-solid fa-magnifying-glass"></i> Search / Filter
        </button>
        <a href="admin_dashboard.php" class="btn btn-secondary btn-gradient">
            <i class="fa-solid fa-rotate-left"></i> Reset
        </a>
        <button type="submit" name="bulk_download" value="1" class="btn btn-success-gradient">
            <i class="fa-solid fa-download"></i> Bulk CSV
        </button>
        <a href="form.php" class="btn btn-info-gradient">
            <i class="fa-solid fa-database"></i> NBFC Data
        </a>
        <a href="view_students.php" class="btn btn-warning-gradient text-white">
            <i class="fa-solid fa-user-plus"></i> View Students
        </a>
    </div>
</form>

        <!-- Pagination + Limit Dropdown -->
        <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap">
            <div>
                <form method="GET" class="d-flex align-items-center">
                    <label class="me-2">Show</label>
                    <select name="limit" class="form-select w-auto" onchange="this.form.submit()">
                        <option value="10" <?php if($records_per_page==10) echo 'selected'; ?>>10</option>
                        <option value="15" <?php if($records_per_page==15) echo 'selected'; ?>>15</option>
                        <option value="20" <?php if($records_per_page==20) echo 'selected'; ?>>20</option>
                        <option value="25" <?php if($records_per_page==25) echo 'selected'; ?>>25</option>
                    </select>
                    <label class="ms-2">entries per page</label>
                    <input type="hidden" name="search" value="<?php echo htmlspecialchars($search); ?>">
                    <input type="hidden" name="course" value="<?php echo htmlspecialchars($filter_course); ?>">
                    <input type="hidden" name="from_date" value="<?php echo htmlspecialchars($from_date); ?>">
                    <input type="hidden" name="to_date" value="<?php echo htmlspecialchars($to_date); ?>">
                </form>
            </div>
            <nav>
                <ul class="pagination mb-0">
                    <?php if($page > 1): ?>
                        <li class="page-item"><a class="page-link" href="?page=<?php echo ($page-1); ?>&limit=<?php echo $records_per_page; ?>">Previous</a></li>
                    <?php endif; ?>
                    <?php for($i=1; $i <= $total_pages; $i++): ?>
                        <li class="page-item <?php if($i==$page) echo 'active'; ?>">
                            <a class="page-link" href="?page=<?php echo $i; ?>&limit=<?php echo $records_per_page; ?>"><?php echo $i; ?></a>
                        </li>
                    <?php endfor; ?>
                    <?php if($page < $total_pages): ?>
                        <li class="page-item"><a class="page-link" href="?page=<?php echo ($page+1); ?>&limit=<?php echo $records_per_page; ?>">Next</a></li>
                    <?php endif; ?>
                </ul>
            </nav>
        </div>

        <div class="table-responsive">
        <table class="table table-bordered table-striped align-middle">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Course</th>
                    <th>Batch</th>
                    <th>Number</th>
                    <th>Course Fee</th>
                    <th>Advance Fee</th>
                    <th>EMI Start</th>
                    <th>EMI Tenure</th>
                    <th>Parent Name</th>
                    <th>Parent Mobile</th>
                    <th>Aadhar</th>
                    <th>PAN</th>
                    <th>Account</th>
                    <th>IFSC</th>
                    <th>Document</th>
                    <th>Created At</th>
                    <th>Remark</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
            <?php while($row=$students->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $row['id'];?></td>
                    <td><?php echo $row['studentName'];?></td>
                    <td><?php echo $row['studentEmail'];?></td>
                    <td><?php echo $row['courseName'];?></td>
                    <td><?php echo $row['batch'];?></td>
                    <td><?php echo $row['studentNumber'];?></td>
                    <td><?php echo $row['courseFee'];?></td>
                    <td><?php echo $row['advanceFee'];?></td>
                    <td><?php echo $row['emiStart'];?></td>
                    <td><?php echo $row['emiTenure'];?></td>
                    <td><?php echo $row['parentName'];?></td>
                    <td><?php echo $row['parentMobile'];?></td>
                    <td><?php echo $row['aadhar'];?></td>
                    <td><?php echo $row['pan'];?></td>
                    <td><?php echo $row['account'];?></td>
                    <td><?php echo $row['ifsc'];?></td>
                    <td>
                        <?php if(!empty($row['document_path']) && file_exists($row['document_path'])): ?>
                            <?php $ext = strtolower(pathinfo($row['document_path'], PATHINFO_EXTENSION)); ?>
                            <?php if(in_array($ext, ['jpg','jpeg','png','gif'])): ?>
                                <a href="<?php echo $row['document_path']; ?>" target="_blank">
                                    <img src="<?php echo $row['document_path']; ?>" class="document-img" alt="Document"/>
                                </a>
                                <br>
                                <a href="<?php echo $row['document_path']; ?>" download class="btn btn-sm btn-info-gradient mt-1">
                                    <i class="fa-solid fa-download"></i> Download
                                </a>
                            <?php else: ?>
                                <a href="<?php echo $row['document_path']; ?>" download class="btn btn-sm btn-info-gradient">
                                    <i class="fa-solid fa-download"></i> Download
                                </a>
                            <?php endif; ?>
                        <?php else: ?>N/A<?php endif; ?>
                    </td>
                    <td><?php echo $row['created_at'];?></td>
                    <td>
                        <?php $remark = isset($row['nbfc_remark']) ? $row['nbfc_remark'] : ''; ?>
                        <span id="remark_text_<?php echo $row['id']; ?>">
                            <?php echo $remark != '' ? htmlspecialchars($remark) : '<em>No remark</em>'; ?>
                        </span>
                        <button class="btn btn-sm btn-primary-gradient edit-remark-btn" 
                                data-id="<?php echo $row['id']; ?>" 
                                data-remark="<?php echo htmlspecialchars($remark); ?>">
                            <i class="fa-solid fa-pen"></i>
                        </button>
                    </td>
                    <td>
                        <a href="edit_student.php?id=<?php echo $row['id'];?>" class="btn btn-sm btn-warning-gradient mb-1">
                            <i class="fa-solid fa-pen-to-square"></i> Edit
                        </a>
                        <a href="delete_student.php?id=<?php echo $row['id'];?>" class="btn btn-sm btn-danger-gradient mb-1" onclick="return confirm('Are you sure?')">
                            <i class="fa-solid fa-trash"></i> Delete
                        </a>
                        <a href="?download_id=<?php echo $row['id']; ?>" class="btn btn-sm btn-success-gradient mb-1">
                            <i class="fa-solid fa-file-csv"></i> CSV
                        </a>
                        <button class="btn btn-sm btn-info-gradient edit-status-btn mb-1"
                                data-id="<?php echo $row['id']; ?>" 
                                data-status="<?php echo htmlspecialchars($row['nbfc_status']); ?>"
                                data-remark="<?php echo htmlspecialchars($row['nbfc_remark']); ?>">
                            <i class="fa-solid fa-credit-card"></i> NBFC Status
                        </button>
                    </td>
                </tr>
            <?php endwhile; ?>
            </tbody>
        </table>
        </div>
    </div>
</div> 

<!-- Modals -->

<!-- Edit Remark Modal -->
<div class="modal fade" id="editRemarkModal" tabindex="-1" aria-labelledby="editRemarkModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form id="editRemarkForm">
        <div class="modal-header">
          <h5 class="modal-title" id="editRemarkModalLabel">Edit Remark</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <input type="hidden" name="student_id" id="remark_student_id">
            <textarea name="nbfc_remark" id="edit_remark_text" class="form-control" rows="4" placeholder="Enter new remark..."></textarea>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary-gradient">Save Remark</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Edit NBFC Status Modal -->
<div class="modal fade" id="editStatusModal" tabindex="-1" aria-labelledby="editStatusModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form id="editStatusForm">
        <div class="modal-header">
          <h5 class="modal-title" id="editStatusModalLabel">Edit NBFC Status</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <input type="hidden" name="student_id" id="status_student_id">
            <label>NBFC Status:</label>
            <select name="nbfc_status" id="edit_status_select" class="form-control mb-2">
                <option value="Pending">Pending</option>
                <option value="Approved">Approved</option>
                <option value="Rejected">Rejected</option>
            </select>
            <label>Remark:</label>
            <textarea name="nbfc_remark" id="edit_status_remark" class="form-control" rows="3" placeholder="Enter remark here..."></textarea>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary-gradient">Save Status & Remark</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Admin Profile Modal -->
<div class="modal fade" id="profileModal" tabindex="-1" aria-labelledby="profileModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form id="adminProfileForm">
        <div class="modal-header">
          <h5 class="modal-title" id="profileModalLabel">Admin Profile</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <label>Username:</label>
            <input type="text" name="username" class="form-control mb-2" value="<?php echo isset($_SESSION['admin_name']) ? htmlspecialchars($_SESSION['admin_name']) : ''; ?>" required>
            <label>New Password:</label>
            <input type="password" name="password" class="form-control mb-2" placeholder="Enter new password">
            <label>Confirm Password:</label>
            <input type="password" name="confirm_password" class="form-control mb-2" placeholder="Confirm password">
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary-gradient">Update</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
// Edit Remark Modal
$(document).on('click', '.edit-remark-btn', function(){
    var id = $(this).data('id');
    var remark = $(this).data('remark');
    $('#remark_student_id').val(id);
    $('#edit_remark_text').val(remark);
    $('#editRemarkModal').modal('show');
});

$('#editRemarkForm').on('submit', function(e){
    e.preventDefault();
    $.ajax({
        url: 'update_remark.php',
        method: 'POST',
        data: $(this).serialize(),
        success: function(response){
            alert(response);
            $('#editRemarkModal').modal('hide');
            location.reload();
        },
        error: function(){
            alert('Error updating remark.');
        }
    });
});

// Edit NBFC Status Modal
$(document).on('click', '.edit-status-btn', function(){
    var id = $(this).data('id');
    var status = $(this).data('status');
    var remark = $(this).data('remark');
    $('#status_student_id').val(id);
    $('#edit_status_select').val(status);
    $('#edit_status_remark').val(remark);
    $('#editStatusModal').modal('show');
});

$('#editStatusForm').on('submit', function(e){
    e.preventDefault();
    $.ajax({
        url: 'update_nbfc_status.php',
        method: 'POST',
        data: $(this).serialize(),
        success: function(response){
            alert(response);
            $('#editStatusModal').modal('hide');
            location.reload();
        },
        error: function(){
            alert('Error updating status.');
        }
    });
});

// Admin Profile Update
$('#adminProfileForm').on('submit', function(e){
    e.preventDefault();
    $.ajax({
        url: 'update_admin_profile.php',
        method: 'POST',
        data: $(this).serialize(),
        success: function(response){
            alert(response);
            $('#profileModal').modal('hide');
            location.reload();
        },
        error: function(){
            alert('Error updating profile.');
        }
    });
});
</script>
</body>
</html>
