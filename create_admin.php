<?php
// create_admin.php — run once, then delete for security.

include 'connection.php';

$username = 'admin2';
$plain_password = 'admin123';

// Hash password securely
$hashed = password_hash($plain_password, PASSWORD_DEFAULT);

// Optional: check if username exists
$stmt = $conn->prepare("SELECT id FROM admin WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$res = $stmt->get_result();

if($res && $res->num_rows > 0){
    echo "User '$username' already exists. Aborting.\n";
    exit;
}

// Insert new admin
$ins = $conn->prepare("INSERT INTO admin (username, password, name) VALUES (?, ?, ?)");
$name = 'Admin User'; // adjust if your table has a 'name' column
$ins->bind_param("sss", $username, $hashed, $name);

if($ins->execute()){
    echo "Admin created successfully. Username: $username  Password: $plain_password\n";
} else {
    echo "Insert failed: " . $conn->error . "\n";
}
?>
