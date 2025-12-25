<?php
session_start();
include 'connection.php';
if(!isset($_SESSION['admin_id'])){ header("Location: admin_login.php"); exit; }

if(!isset($_GET['id'])) header("Location: admin_dashboard.php");
$id = intval($_GET['id']);

// if you want to delete file too, fetch and unlink
$stmtF = $conn->prepare("SELECT document_path FROM students WHERE id=?");
$stmtF->bind_param("i",$id);
$stmtF->execute();
$resF = $stmtF->get_result();
$r = $resF->fetch_assoc();
if($r && !empty($r['document_path']) && file_exists($r['document_path'])){
    @unlink($r['document_path']);
}

$stmt = $conn->prepare("DELETE FROM students WHERE id=?");
$stmt->bind_param("i",$id);
$stmt->execute();

header("Location: admin_dashboard.php");
exit;
