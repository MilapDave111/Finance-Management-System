<?php
include("config.php");

header('Content-Type: application/sql');
header('Content-Disposition: attachment; filename="backup.sql"');

$sqlData = "-- User and Finance Data Backup\n\n";

// Backup User Table
$sqlData .= "-- Backup User Table\n";
$userQuery = "SELECT * FROM user";
$userResult = mysqli_query($conn, $userQuery);

while ($row = mysqli_fetch_assoc($userResult)) {
    $values = array_map(function ($value) {
        return "'" . addslashes($value) . "'";
    }, array_values($row));

    $sqlData .= "INSERT INTO user (" . implode(", ", array_keys($row)) . ") VALUES (" . implode(", ", $values) . ");\n";
}

$sqlData .= "\n\n";

// Backup Finance Table
$sqlData .= "-- Backup Finance Table\n";
$financeQuery = "SELECT * FROM finance";
$financeResult = mysqli_query($conn, $financeQuery);

while ($row = mysqli_fetch_assoc($financeResult)) {
    $values = array_map(function ($value) {
        return "'" . addslashes($value) . "'";
    }, array_values($row));

    $sqlData .= "INSERT INTO finance (" . implode(", ", array_keys($row)) . ") VALUES (" . implode(", ", $values) . ");\n";
}

// Output SQL data for download
echo $sqlData;

mysqli_close($conn);
?>
