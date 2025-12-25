<?php
session_start();
include 'connection.php';

if(!isset($_SESSION['otp']) || !isset($_SESSION['reset_username'])){
    header("Location: forgot_password.php");
    exit();
}

if(isset($_POST['verify_otp'])){
    $entered_otp = trim($_POST['otp']);
    if($entered_otp == $_SESSION['otp']){
        $_SESSION['otp_verified'] = true;
        header("Location: reset_password.php");
        exit();
    } else {
        $error = "❌ Invalid OTP!";
    }
}
$msg = isset($_GET['msg']) ? $_GET['msg'] : '';
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Verify OTP</title>
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
    <h3>Verify OTP</h3>
    <?php if($msg) echo "<p class='success'>$msg</p>"; ?>
    <?php if(isset($error)) echo "<p class='error'>$error</p>"; ?>
    <form method="POST">
        <div class="mb-3">
            <label>Enter OTP</label>
            <input type="number" name="otp" class="form-control" required>
        </div>
        <button type="submit" name="verify_otp" class="btn btn-primary">Verify OTP</button>
        <a href="forgot_password.php" class="btn btn-secondary mt-2">Back</a>
    </form>
</div>
</body>
</html>
