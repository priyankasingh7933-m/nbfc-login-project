<?php
session_start();
include 'connection.php';
if(!isset($_SESSION['admin_id'])){ header("Location: admin_login.php"); exit; }

if(!isset($_GET['id'])){ header("Location: admin_dashboard.php"); exit; }
$id = intval($_GET['id']);
$stmt = $conn->prepare("SELECT * FROM students WHERE id = ?");
$stmt->bind_param("i",$id);
$stmt->execute();
$res = $stmt->get_result();
$student = $res->fetch_assoc();
if(!$student){ header("Location: admin_dashboard.php"); exit; }
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Edit Student</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-4">
  <a href="admin_dashboard.php" class="btn btn-secondary mb-3">&larr; Back</a>
  <div class="card p-3">
    <h5>Edit Student</h5>
    <form method="post" action="update_student.php" enctype="multipart/form-data">
      <input type="hidden" name="id" value="<?= $student['id'] ?>">
      <div class="row g-2">
        <div class="col-md-6">
          <label class="form-label">Student Name</label>
          <input name="studentName" class="form-control" value="<?= htmlspecialchars($student['studentName']) ?>" required>
        </div>
        <div class="col-md-6">
          <label class="form-label">Email</label>
          <input name="studentEmail" type="email" class="form-control" value="<?= htmlspecialchars($student['studentEmail']) ?>" required>
        </div>
        <div class="col-md-4">
          <label class="form-label">Course</label>
          <input name="courseName" class="form-control" value="<?= htmlspecialchars($student['courseName']) ?>">
        </div>
        <div class="col-md-4">
          <label class="form-label">Batch</label>
          <input name="batch" class="form-control" value="<?= htmlspecialchars($student['batch']) ?>">
        </div>
        <div class="col-md-4">
          <label class="form-label">Phone</label>
          <input name="studentNumber" class="form-control" value="<?= htmlspecialchars($student['studentNumber']) ?>">
        </div>
        <div class="col-md-4">
          <label class="form-label">Course Fee</label>
          <input name="courseFee" class="form-control" value="<?= htmlspecialchars($student['courseFee']) ?>">
        </div>
        <div class="col-md-4">
          <label class="form-label">EMI Start</label>
          <input name="emiStart" type="date" class="form-control" value="<?= htmlspecialchars($student['emiStart']) ?>">
        </div>
        <div class="col-md-4">
          <label class="form-label">EMI Tenure</label>
          <input name="emiTenure" class="form-control" value="<?= htmlspecialchars($student['emiTenure']) ?>">
        </div>

        <div class="col-12 mt-2">
          <label class="form-label">Document (leave blank to keep current)</label>
          <input type="file" name="document" class="form-control">
          <?php if(!empty($student['document_path'])): ?>
            <small class="text-muted">Current: <a href="<?=htmlspecialchars($student['document_path'])?>" target="_blank">View</a></small>
          <?php endif; ?>
        </div>
      </div>

      <div class="mt-3">
        <button class="btn btn-primary" type="submit">Save Changes</button>
        <a class="btn btn-secondary" href="admin_dashboard.php">Cancel</a>
      </div>
    </form>
  </div>
</div>
</body>
</html>
