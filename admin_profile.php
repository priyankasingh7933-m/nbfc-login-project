<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include 'connection.php';

if(!isset($_SESSION['admin_id'])){
    header("Location: admin_login.php");
    exit();
}

$admin_id = $_SESSION['admin_id'];

// Fetch latest admin data
function fetchAdmin($conn, $admin_id){
    $stmt = $conn->prepare("SELECT * FROM admin WHERE id = ?");
    $stmt->bind_param("i", $admin_id);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_assoc();
}

$admin = fetchAdmin($conn, $admin_id);

if(!$admin){
    die("Admin not found.");
}

// Handle update
if(isset($_POST['update'])){
    $new_username = trim($_POST['username'] ?? '');
    $new_password = trim($_POST['password'] ?? '');

    if($new_username === '' || $new_password === ''){
        $error = "Username and password cannot be empty.";
    } else {
        $update = $conn->prepare("UPDATE admin SET username=?, password=? WHERE id=?");
        $update->bind_param("ssi", $new_username, $new_password, $admin_id);
        if($update->execute()){
            $success = "Credentials updated successfully!";
            $admin = fetchAdmin($conn, $admin_id); // fetch updated data
        } else {
            $error = "Failed to update credentials.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Admin Profile</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://kit.fontawesome.com/a2d9d5b258.js" crossorigin="anonymous"></script>
<style>
body { background:#f8f9fb; font-family:'Poppins',sans-serif; }
.container { margin-top:50px; }
.card { border-radius:12px; box-shadow:0 6px 18px rgba(0,0,0,0.08); padding:25px; }
h3 { color:#2c7be5; font-weight:700; }
.input-group .eye-btn { border:none; background:transparent; color:#495057; }
.btn-primary-gradient { background:linear-gradient(45deg,#4e54c8,#8f94fb); color:#fff; border:none; }
.btn-secondary { background:#6c757d; color:#fff; border:none; }
.alert { margin-top:15px; }
</style>
</head>
<body>
<div class="container">
    <div class="card col-md-6 mx-auto">
        <h3><i class="fa-solid fa-user-shield"></i> Admin Profile</h3>
        <hr>

        <?php if(isset($success)): ?>
            <div class="alert alert-success" id="successMsg"><?php echo htmlspecialchars($success); ?></div>
            <script>
                alert("<?php echo htmlspecialchars($success); ?>");
                setTimeout(()=>document.getElementById('successMsg').style.display='none',3000);
            </script>
        <?php elseif(isset($error)): ?>
            <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <form method="POST" autocomplete="off">
            <div class="mb-3">
                <label class="form-label">Username</label>
                <input id="username" name="username" type="text" class="form-control" value="<?php echo htmlspecialchars($admin['username']); ?>" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Password</label>
                <div class="input-group">
                    <input id="password" name="password" type="password" class="form-control" value="<?php echo htmlspecialchars($admin['password']); ?>" required>
                    <button type="button" class="eye-btn" onclick="toggleVisibility('password')">
                        <i id="icon_password" class="fa-solid fa-eye"></i>
                    </button>
                </div>
            </div>

            <button type="submit" name="update" class="btn btn-primary-gradient w-100 mb-2"><i class="fa-solid fa-rotate"></i> Update</button>
            <a href="admin_dashboard.php" class="btn btn-secondary w-100"><i class="fa-solid fa-arrow-left"></i> Back to Dashboard</a>
        </form>
    </div>
</div>

<script>
function toggleVisibility(fieldId){
    var f = document.getElementById(fieldId);
    var icon = document.getElementById('icon_password');
    if(f.type==="password"){ f.type="text"; icon.className="fa-solid fa-eye-slash"; }
    else { f.type="password"; icon.className="fa-solid fa-eye"; }
}
</script>
</body>
</html>
