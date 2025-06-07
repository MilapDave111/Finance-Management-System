<?php
include("config.php");

if (isset($_POST['user_id'], $_POST['column'], $_POST['value'])) {
    $user_id = $_POST['user_id'];  
    $column = $_POST['column'];
    $value = $_POST['value'];

    // Prevent SQL Injection
    $user_id = mysqli_real_escape_string($conn, $user_id);
    $column = mysqli_real_escape_string($conn, $column);
    $value = mysqli_real_escape_string($conn, $value);

    // Define which columns belong to the `user` table
    $user_columns = ['name', 'amount', 'date_of_participation', 'mis', 'number_of_months'];

    if (in_array($column, $user_columns)) {
        // Update `user` table
        $query = "UPDATE user SET `$column` = '$value' WHERE id = '$user_id'";
    } else {
        // Update `finance` table
        $query = "UPDATE finance SET `$column` = '$value' WHERE user_id = '$user_id'";
    }

    if (mysqli_query($conn, $query)) {
        echo "Success";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>
