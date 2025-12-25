<?php
session_start();
session_unset();
session_destroy();
header("Location: student_dashboard.php"); // Wapas student dashboard
exit();
?>
