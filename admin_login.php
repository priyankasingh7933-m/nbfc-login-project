<?php
session_start();
include 'connection.php';

// If already logged in, redirect to dashboard
if(isset($_SESSION['admin_id'])){
    header("Location: admin_dashboard.php");
    exit();
}

$error = '';
if(isset($_POST['login'])){
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    $stmt = $conn->prepare("SELECT * FROM admin WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if($result->num_rows > 0){
        $row = $result->fetch_assoc();
        if($password === $row['password']){ // plain text match
            $_SESSION['admin_id'] = $row['id'];
            $_SESSION['admin_name'] = $row['username'];
            header("Location: admin_dashboard.php");
            exit();
        } else {
            $error = "❌ Invalid Password!";
        }
    } else {
        $error = "❌ No admin found!";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Admin Login</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://kit.fontawesome.com/a2d9d5b258.js" crossorigin="anonymous"></script>
<style>
html,body { height:100%; }
body {
    display: flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, #74b9ff, #0984e3);
    font-family: 'Poppins', sans-serif;
    margin:0;
    padding:20px;
}
.wrapper { width: 100%; max-width: 1000px; }
.card-login { background: #fff; padding: 30px; border-radius: 10px; box-shadow: 0 0 20px rgba(0,0,0,0.12); }
.left-col { border-right: 1px solid #f0f0f0; }
.right-col { display:flex; align-items:center; justify-content:center; background: linear-gradient(180deg,#ffffff,#f7fbff); }
.btn-edit { display:inline-flex; align-items:center; gap:10px; padding:12px 18px; background: linear-gradient(90deg,#6a11cb,#2575fc); color:#fff; border-radius:8px; text-decoration:none; box-shadow:0 6px 18px rgba(37,117,252,0.25); font-weight:600; transition: all 0.3s ease; }
.btn-edit:hover { transform: translateY(-2px) scale(1.03); }
.btn-edit i { font-size:18px; }
.small-note { font-size:0.9rem; color:#666; margin-top:10px; text-align:center; }
.error { color:red; text-align:center; margin-bottom:10px; }
@media (max-width: 767px) {
    .left-col { border-right: none; border-bottom: 1px solid #f0f0f0; }
    .right-col { padding-top:20px; padding-bottom:0; }
}
</style>
</head>
<body>
<div class="wrapper">
    <div class="card-login row g-0">
        <!-- LEFT: Login form -->
        <div class="col-md-7 p-4 left-col">
            <h3 class="mb-4" style="color:#007bff;">Admin Login</h3>
            <?php if($error) echo "<p class='error'>$error</p>"; ?>
            <form method="POST">
                <div class="mb-3">
                    <label class="form-label">Username</label>
                    <input type="text" name="username" class="form-control" required autocomplete="username">
                </div>
                <div class="mb-3">
                    <label class="form-label">Password</label>
                    <input type="password" name="password" class="form-control" required autocomplete="current-password">
                </div>
                <button type="submit" name="login" class="btn btn-primary w-100">Login</button>
            </form>
            <div class="small-note">If you need to change admin username/password, use the Edit Profile button on the right.</div>
        </div>

        <!-- RIGHT: Edit Profile button -->
        <div class="col-md-5 p-4 right-col">
            <div class="text-center">
                <p style="margin-bottom:12px; font-weight:700;">Need to update admin credentials?</p>
                <!-- Edit button (safe for now, redirect to profile page) -->
                <a href="admin_profile.php" class="btn-edit" title="Edit Profile">
                    <i class="fa-solid fa-user-pen"></i>
                    Edit Profile
                </a>
                <div class="small-note">Opens the admin profile page (you may need to login first).</div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
