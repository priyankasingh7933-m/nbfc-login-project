<?php
session_start();
include 'connection.php';

if(isset($_POST['send_otp'])){
    $username = trim($_POST['username']);

    // Check if username exists
    $stmt = $conn->prepare("SELECT * FROM admin WHERE username=?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if($result->num_rows > 0){
        // Generate 6-digit OTP
        $otp = rand(100000,999999);
        $_SESSION['otp'] = $otp;
        $_SESSION['reset_username'] = $username;

        // In real application, send OTP via email/SMS
        // For demo, we show OTP directly
        $message = "✅ OTP generated: $otp";

        header("Location: verify_otp.php?msg=".urlencode($message));
        exit();
    } else {
        $error = "❌ Username not found!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Forgot Password</title>
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
    <h3>Forgot Password</h3>
    <?php if(isset($error)) echo "<p class='error'>$error</p>"; ?>
    <form method="POST">
        <div class="mb-3">
            <label>Enter your username</label>
            <input type="text" name="username" class="form-control" required>
        </div>
        <button type="submit" name="send_otp" class="btn btn-primary">Send OTP</button>
        <a href="admin_login.php" class="btn btn-secondary mt-2">Back to Login</a>
    </form>
</div>
</body>
</html>
