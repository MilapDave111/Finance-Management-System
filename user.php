<?php
include ("config.php");
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST["name"];
    $amount = $_POST["amount"];
    $number_of_months = $_POST["number_of_months"];
    $date_of_participation = $_POST["date_of_participation"];
    $mis = $_POST["mis"];
    
    $sql = "INSERT INTO user (name, amount, number_of_months, date_of_participation, mis) 
                VALUES ('$name', '$amount', '$number_of_months', '$date_of_participation', '$mis')";

        if ($conn->query($sql) === TRUE) {
            echo "<script>alert('Data inserted successfully!'); window.location.href = 'data_entry.php';</script>";
        exit; // Stop further script execution
        } else {
            echo "<script>alert('Error: " . $conn->error . "');</script>";
        }
       
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New User</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .container {
            max-width: 600px;
            margin-top: 50px;
            background: #ffffff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h2 {
            text-align: center;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Add New User</h2>
        <div class="text-end mb-3">
            <a href="data_entry.php" class="btn btn-success">Data Entry</a>
        </div>
        <form method="POST" class="row g-3">
            <div class="col-12">
                <label for="name" class="form-label">Name:</label>
                <input type="text" id="name" name="name" class="form-control" required>
            </div>
            
            <div class="col-12">
                <label for="amount" class="form-label">Total Amount:</label>
                <input type="number" id="amount" name="amount" step="0.01" class="form-control" required>
            </div>

            <div class="col-12">
            <label for="date_of_participation" class="form-label">MIS:</label>
            <input type="number" id="mis" name="mis" class="form-control"  required>
            </div>

            <div class="col-12">
                <label for="number_of_months" class="form-label">Number of Months:</label>
                <input type="number" id="number_of_months" name="number_of_months" class="form-control" required>
            </div>

            <div class="col-12">
                <label for="date_of_participation" class="form-label">Date of Participation:</label>
                <input type="date" id="date_of_participation" name="date_of_participation" class="form-control" required>
            </div>

            <div class="col-12 text-center">
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

