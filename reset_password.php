<?php
session_start();
include 'connection.php';

if(!isset($_SESSION['otp_verified']) || $_SESSION['otp_verified'] !== true){
    header("Location: forgot_password.php");
    exit();
}

$username = $_SESSION['reset_username'];

if(isset($_POST['reset_password'])){
    $new_password = trim($_POST['new_password']);
    $confirm_password = trim($_POST['confirm_password']);

    if($new_password === $confirm_password){
        // Update password
        $stmt = $conn->prepare("UPDATE admin SET password=? WHERE username=?");
        $stmt->bind_param("ss", $new_password, $username);
        if($stmt->execute()){
            // Cleanup session
            unset($_SESSION['otp']);
            unset($_SESSION['reset_username']);
            unset($_SESSION['otp_verified']);
            $success = "✅ Password reset successfully! <a href='admin_login.php'>Login now</a>";
        } else {
            $error = "❌ Error updating password!";
        }
    } else {
        $error = "❌ Passwords do not match!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Reset Password</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
body { display:flex; justify-content:center; align-items:center; height:100vh; background:#f0f2f5; font-family:Poppins,sans-serif; }
.login-box { background:#fff; padding:30px; border-radius:10px; box-shadow:0 0 15px rgba(0,0,0,0.1); width:100%; max-width:400px; }
h3 { text-align:center; margin-bottom:25px; color:#007bff; }
.btn-primary { width:100%; }
.error { color:red; text-align:center; margin-bottom:10px; }
.success { color:green; text-align:center; margin-bottom:10px; }
</style>
</head>
<body>
<div class="login-box">
    <h3>Reset Password</h3>
    <?php if(isset($error)) echo "<p class='error'>$error</p>"; ?>
    <?php if(isset($success)) echo "<p class='success'>$success</p>"; ?>
    <form method="POST">
        <div class="mb-3">
            <label>New Password</label>
            <input type="password" name="new_password" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Confirm Password</label>
            <input type="password" name="confirm_password" class="form-control" required>
        </div>
        <button type="submit" name="reset_password" class="btn btn-primary">Reset Password</button>
    </form>
</div>
</body>
</html>
