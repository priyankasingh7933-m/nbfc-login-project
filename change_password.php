<?php
session_start();
include 'connection.php';

if(!isset($_SESSION['admin_id'])){
    header("Location: admin_login.php");
    exit();
}

if(isset($_POST['change'])){
    $old = trim($_POST['old']);
    $new = trim($_POST['new']);

    $id = $_SESSION['admin_id'];
    $stmt = $conn->prepare("SELECT * FROM admin WHERE id=?");
    $stmt->bind_param("i",$id);
    $stmt->execute();
    $res = $stmt->get_result()->fetch_assoc();

    if($res && $old === $res['password']){
        $update = $conn->prepare("UPDATE admin SET password=? WHERE id=?");
        $update->bind_param("si",$new,$id);
        if($update->execute()){
            $msg = "✅ Password changed successfully!";
        }
    } else {
        $error = "❌ Old password incorrect!";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Change Password</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
body { display:flex; justify-content:center; align-items:center; height:100vh; background:#f0f2f5; font-family:Poppins,sans-serif; }
.box { background:#fff; padding:30px; border-radius:10px; box-shadow:0 0 15px rgba(0,0,0,0.1); width:100%; max-width:400px; }
h3 { text-align:center; color:#007bff; margin-bottom:20px; }
.error { color:red; text-align:center; margin-bottom:10px; }
.success { color:green; text-align:center; margin-bottom:10px; }
</style>
</head>
<body>
<div class="box">
    <h3>Change Password</h3>
    <?php if(isset($error)) echo "<p class='error'>$error</p>"; ?>
    <?php if(isset($msg)) echo "<p class='success'>$msg</p>"; ?>
    <form method="POST">
        <div class="mb-3">
            <label>Old Password</label>
            <input type="password" name="old" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>New Password</label>
            <input type="password" name="new" class="form-control" required>
        </div>
        <button type="submit" name="change" class="btn btn-primary w-100">Change Password</button>
    </form>
    <div class="text-center mt-3">
        <a href="admin_home.php">Back to Dashboard</a>
    </div>
</div>
</body>
</html>
