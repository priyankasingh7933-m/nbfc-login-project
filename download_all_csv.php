<?php
include 'connection.php';
header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="students_all.csv"');

$out = fopen('php://output','w');
$first = true;
$res = $conn->query("SELECT * FROM students");
while($row = $res->fetch_assoc()){
    if($first){
        fputcsv($out, array_keys($row));
        $first = false;
    }
    fputcsv($out, $row);
}
fclose($out);
exit;
