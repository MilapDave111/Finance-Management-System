<?php
include("config.php");

if (isset($_POST['restore'])) {
    $file = $_FILES['sql_file']['tmp_name'];

    if (!file_exists($file)) {
        die("No file uploaded.");
    }

    $sql = file_get_contents($file);
    $queries = explode(";", $sql);

    foreach ($queries as $query) {
        $query = trim($query);
        if (!empty($query)) {
            mysqli_query($conn, $query) or die("Error: " . mysqli_error($conn));
        }
    }

    echo "<script>alert('Database restored successfully!'); window.location.href = 'data_entry.php';</script>";
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restore Database</title>
</head>
<body>
    <h2>Restore Database</h2>
    <form method="POST" enctype="multipart/form-data">
        <input type="file" name="sql_file" accept=".sql" required>
        <button type="submit" name="restore">Restore</button>
    </form>
</body>
</html>
