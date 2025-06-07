<?php
include("config.php");

if (isset($_POST['delete'])) {
    $user_id = $_POST['user_id'];
    $current_month = $_POST['current_month'];

    // Delete only the finance record for the given user and current_month
    $deleteFinance = "DELETE FROM finance WHERE user_id = '$user_id' AND current_month = '$current_month'";
    mysqli_query($conn, $deleteFinance);

    echo "<script>alert('Finance record deleted successfully.'); window.location.href = 'data_entry.php';</script>";
    exit;
}

// Fetch user list for dropdown
$user_query = mysqli_query($conn, "SELECT id, name, amount, number_of_months, mis FROM user");
$users = [];
while ($row = mysqli_fetch_assoc($user_query)) {
    $users[] = $row;
}
// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['filter_month'])) {
        $filter_month = $_POST['filter_month']; // Format: YYYY-MM
    } else {
        $user_id = $_POST['user_id'];
        $current_month = $_POST['current_month']; // Format: YYYY-MM
        $amount_received = $_POST['amount_received'];
        $date = $_POST['date']; // Current date from the finance table

        // Fetch user details including 'mis'
        $user_data = mysqli_fetch_assoc(mysqli_query($conn, "SELECT amount, number_of_months, mis FROM user WHERE id = '$user_id'"));
        $user_amount = $user_data['amount'];
        $number_of_months = $user_data['number_of_months']; 
        $mis = $user_data['mis'];

        // AmountToBeRecieve Calculation
        $AmountToBeRecieve = ($mis < $user_amount || $mis == 0) ?  "+".abs($mis - $user_amount) : "-".abs($mis - $user_amount);

        // Calculate Remaining Amount
        $remaining_amount = ($AmountToBeRecieve <= 0 && $amount_received == 0 ) ? 0 : abs($AmountToBeRecieve - $amount_received);

        // Extract numeric month
        $month_number = date('n', strtotime($current_month));

        // Assign cycle
        $cycle = $month_number;

        // Determine previous cycle
        $prev_cycle = ($cycle == 1) ? 12 : $cycle - 1;
        $prev_year = ($cycle == 1) ? date('Y', strtotime($current_month)) - 1 : date('Y', strtotime($current_month));

        // Fetch last_pending from previous cycle
        $prevPendingQuery = "SELECT remaining_amount FROM finance WHERE user_id = '$user_id' AND cycle = '$prev_cycle' ORDER BY id DESC LIMIT 1";
        $prevPendingResult = mysqli_query($conn, $prevPendingQuery);
        $prevPendingRow = mysqli_fetch_assoc($prevPendingResult);
        $last_pending = ($prevPendingRow) ? $prevPendingRow['remaining_amount'] : 0;

        // Calculate upcoming date based on 'date' column from finance and 'number_of_months' from user
        $upcomming_date = date('Y-m-d', strtotime("+$number_of_months months", strtotime($date)));

        // Insert data
        $insert_query = "INSERT INTO finance (user_id, current_month, amount_recieved, last_pending, date, upcomming_date, cycle, AmountToBeRecieve, remaining_amount) 
                         VALUES ('$user_id', '$current_month', '$amount_received', '$last_pending', '$date', '$upcomming_date', '$month_number', '$AmountToBeRecieve', '$remaining_amount')";
        mysqli_query($conn, $insert_query) or die(mysqli_error($conn));

        echo "Data inserted successfully!";
    }
} else {
    $filter_month = "";
}

// Fetch data with filter
$query = "SELECT 
    u.name, 
    u.amount, 
    u.date_of_participation, 
    u.mis, 
    u.number_of_months, 
    f.user_id,
    f.current_month, 
    f.amount_recieved, 
    f.AmountToBeRecieve, 
    f.remaining_amount, 
    f.last_pending, 
    f.date, 
    f.upcomming_date 
FROM finance f 
JOIN user u ON f.user_id = u.id";

// Apply filter condition if a month is selected
if (!empty($filter_month)) {
    $query .= " WHERE f.current_month = '$filter_month'";
}

$query .= " ORDER BY f.id ASC"; // Sorting latest first
$result = mysqli_query($conn, $query);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Finance Form</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>

    <style>
        body {
            background-color: #f8f9fa;
        }
        .container {
            max-width: 900px;
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
        table {
            margin-top: 30px;
        }
        
    </style>
</head>
<body>
    <div class="container">
        <div class="row">
            <h2>Finance Form</h2>
            <div class="text-end mb-3">
                 <a href="user.php" class="btn btn-success">Add New User</a>
                </div>
            <div class="col-lg-7">
        
            
            <!-- Data Form -->
            <form method="POST" class="row g-3"> 
                <div class="col-md-6">
                    <label class="form-label">User:</label>
                    <select name="user_id" id="user-select" class="form-select" required>
                        <option value="">Select a user</option>
                        <?php foreach ($users as $user) { ?>
                            <option value="<?= $user['id'] ?>"><?= $user['name'] ?></option>
                        <?php } ?>
                    </select>
                </div>
            
            <div class="col-md-6">
                <label class="form-label">Current Month:</label>
                <input type="month" name="current_month" class="form-control" required>
            </div>

            <div class="col-md-6">
                <label class="form-label">Amount Received:</label>
                <input type="number" name="amount_received" class="form-control" required>
            </div>

            <div class="col-md-6">
                <label class="form-label">Date:</label>
                <input type="date" name="date" class="form-control" required>
            </div>

            <div class="col-12 text-center mb-5">
                <button type="submit" class="btn btn-primary form-control">Submit</button>
            </div>
        </form>

        <!-- Filter Form -->
        <form method="POST" class="row g-3 mb-4">
            <div class="col-md-6">
                <label class="form-label">Filter by Month:</label>
                <input type="month" name="filter_month" class="form-control" value="<?= $filter_month ?>">
            </div>
            <div class="col-md-6 d-flex align-items-end">
                <button type="submit" class="btn btn-primary">Filter</button>
                <a href="backup.php" class="btn btn-secondary mx-2">Backup</a>
                <a href="restore.php" class="btn btn-danger">Restore</a>


            </div>
        </form>
        </div>
         <!-- Notification Column -->
         <div class="col-lg-5 upcomming">
                <div class="card">
                <div class="card-header bg-warning text-dark fw-bold">
                    Upcoming Payments
                </div>
                <div class="card-body" id="notification-list">
                    <!-- Notifications will appear here -->
                </div>
            </div>
        </div>
    </div>
</div>


        <!-- Data Table -->
        <table class="table table-striped table-bordered mt-4 text-center">
            <thead class="table-dark">
                <tr>
                    <th>SR</th>
                    <th>Name</th>
                    <th>Amount</th>
                    <th>MIS</th>
                    <th>Amount To Be Received</th>
                    <th>Amount Received</th>
                    <th>Remaining Amount</th>
                    <th>Last Pending</th>
                    <th>Current Month</th>
                    <th>Date</th>
                    <th>No. of Months</th>
                    <th>Upcoming Date</th>
                    <th>Date of Participation</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $count = 1;
                while ($row = mysqli_fetch_assoc($result)) { 
                    // Determine the color dynamically
                    $color = (strpos($row['AmountToBeRecieve'], '+') !== false) ? 'green' : 'darkred';
                ?> 
                <tr data-user-id="<?= $row['user_id'] ?>">
                    <td><?= $count++ ?></td>
                    <td contenteditable="true" data-column="name"><?= $row['name'] ?></td>
                    <td contenteditable="true" data-column="amount"><?= $row['amount'] ?></td>
                    <td contenteditable="true" data-column="mis"><?= $row['mis'] ?></td>
                    <td contenteditable="true" data-column="AmountToBeRecieve">
                        <span style="color: <?= $color ?>; font-weight: bold;"><?= $row['AmountToBeRecieve'] ?></span>
                    </td>                    
                    <td contenteditable="true" data-column="amount_recieved"><?= $row['amount_recieved'] ?></td>
                    <td contenteditable="true" data-column="remaining_amount"><?= $row['remaining_amount'] ?></td>
                    <td contenteditable="true" data-column="last_pending"><?= $row['last_pending'] ?></td>
                    <td contenteditable="true" data-column="current_month"><?= $row['current_month'] ?></td>
                    <td contenteditable="true" data-column="date"><?= $row['date'] ?></td>
                    <td contenteditable="true" data-column="number_of_months"><?= $row['number_of_months'] ?></td>
                    <td contenteditable="true" data-column="upcomming_date" style="color:red"><?= $row['upcomming_date'] ?></td>
                    <td contenteditable="true" data-column="date_of_participation"><?= $row['date_of_participation'] ?></td>


                        <td>
                           <form method="POST">
                           <input type="hidden" name="user_id" value="<?php echo $row['user_id']; ?>">
                            <input type="hidden" name="current_month" value="<?php echo $row['current_month']; ?>">
                            <button type="submit" name="delete" class="btn btn-sm btn-danger px-3">Delete</button>
                           </form>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
</body>
<script>
document.addEventListener("DOMContentLoaded", function () {
    document.querySelectorAll("td[contenteditable=true]").forEach(cell => {
        cell.addEventListener("blur", function () {
            let row = this.closest("tr");
            let userId = row.getAttribute("data-user-id");  // Get correct user_id
            let column = this.getAttribute("data-column");
            let value = this.innerText.trim();

            if (userId && column) {
                let formData = new FormData();
                formData.append("user_id", userId);
                formData.append("column", column);
                formData.append("value", value);

                fetch("update.php", {
                    method: "POST",
                    body: formData
                })
                .then(response => response.text())
                .then(data => {
                    console.log("Server response:", data);
                    if (data.trim() !== "Success") {
                        alert("Error updating: " + data);
                    }
                })
                .catch(error => console.error("Fetch error:", error));
            }
        });
    });
});
</script>

<script>
    $(document).ready(function() {
        $('#user-select').select2({
            placeholder: "Search for a user",
            allowClear: true
        });
    });
</script>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        const notifications = [];

        <?php
        mysqli_data_seek($result, 0); // Reset pointer to re-read data
        while ($row = mysqli_fetch_assoc($result)) {
            $upcomingDate = date_create($row['upcomming_date']);
            $today = date_create(date('Y-m-d'));
            $interval = date_diff($today, $upcomingDate);
            $daysLeft = (int)$interval->format("%r%a"); // Difference in days

            if ($daysLeft >= 0 && $daysLeft <= 7) {
                $msg = "⚠ Payment due for <strong>" . $row['name'] . "</strong> on <strong>" . $row['upcomming_date'] . "</strong>";
                echo "notifications.push(" . json_encode($msg) . ");\n";
            }
        }
        ?>

        const container = document.getElementById("notification-list");
        if (notifications.length === 0) {
            container.innerHTML = "<p class='text-muted'>No upcoming payments.</p>";
        } else {
            notifications.forEach(msg => {
                const p = document.createElement("p");
                p.className = "mb-2";
                p.innerHTML = msg;
                container.appendChild(p);
            });
        }
    });
</script>

</html>
