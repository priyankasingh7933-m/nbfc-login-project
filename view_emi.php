<?php
session_start();
include 'connection.php';

if(!isset($_SESSION['admin_id'])){
    header("Location: admin_login.php");
    exit();
}

// Delete EMI record
if(isset($_GET['delete'])){
    $id = intval($_GET['delete']);
    // Delete file
    $file_res = $conn->query("SELECT proof_image FROM emi_records WHERE id=$id");
    $row = $file_res->fetch_assoc();
    if($row['proof_image'] && file_exists($row['proof_image'])) unlink($row['proof_image']);
    
    $conn->query("DELETE FROM emi_records WHERE id=$id");
    header("Location: view_emi.php?msg=deleted");
    exit();
}

// Fetch all EMI records
$records = $conn->query("SELECT * FROM emi_records ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>View EMI Records</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://kit.fontawesome.com/a2d9d5b258.js" crossorigin="anonymous"></script>
<style>
body { font-family: 'Poppins', sans-serif; background: #f0f2f5; }
.container { margin-top: 50px; }
.card { border-radius: 15px; padding: 20px; box-shadow: 0 5px 20px rgba(0,0,0,0.1);}
.table th { background: #0984e3; color: #fff; }
.table td img { width:50px; height:auto; border-radius:5px; }
.btn-custom { margin:2px; }
</style>
</head>
<body>
<div class="container">
    <h2 class="mb-3">EMI Dashboard</h2>
    <a href="add_student_emi.php" class="btn btn-success mb-3"><i class="fa-solid fa-plus"></i> Add New EMI</a>

    <?php if(isset($_GET['msg'])): ?>
        <div class="alert alert-success"><?php echo htmlspecialchars($_GET['msg']); ?></div>
    <?php endif; ?>

    <div class="card table-responsive">
    <table class="table table-bordered table-striped align-middle">
        <thead>
            <tr>
                <th>ID</th>
                <th>Student Name</th>
                <th>Counsellor</th>
                <th>EMI Amount</th>
                <th>Reset Amount</th>
                <th>Paid Amount</th>
                <th>Date & Time</th>
                <th>Proof</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
        <?php while($row = $records->fetch_assoc()): ?>
            <tr>
                <td><?php echo $row['id'];?></td>
                <td><?php echo htmlspecialchars($row['student_name']);?></td>
                <td><?php echo htmlspecialchars($row['counsellor_name']);?></td>
                <td><?php echo $row['emi_amount'];?></td>
                <td><?php echo $row['reset_amount'];?></td>
                <td><?php echo $row['paid_amount'];?></td>
                <td><?php echo $row['date_time'];?></td>
                <td>
                    <?php if($row['proof_image'] && file_exists($row['proof_image'])): ?>
                        <a href="<?php echo $row['proof_image'];?>" target="_blank">
                            <img src="<?php echo $row['proof_image'];?>" alt="proof">
                        </a>
                    <?php else: ?>
                        N/A
                    <?php endif; ?>
                </td>
                <td>
                    <a href="edit_emi.php?id=<?php echo $row['id'];?>" class="btn btn-warning btn-sm btn-custom"><i class="fa-solid fa-pen-to-square"></i> Edit</a>
                    <a href="?delete=<?php echo $row['id'];?>" class="btn btn-danger btn-sm btn-custom" onclick="return confirm('Are you sure?')"><i class="fa-solid fa-trash"></i> Delete</a>
                </td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
    </div>
</div>
</body>
</html>
