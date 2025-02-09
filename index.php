<?php
session_start(); // Start the session

// Display success message if it exists
if (isset($_SESSION['success_message'])) {
    echo "<div style='background-color: #d4edda; color: #155724; padding: 10px; margin-bottom: 10px;'>{$_SESSION['success_message']}</div>";
    // Unset the session variable to remove the message after displaying it
    unset($_SESSION['success_message']);
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hostel Details</title>

    <script>
        // Add event listeners to table rows
        document.addEventListener("DOMContentLoaded", function () {
            const rows = document.querySelectorAll("table tr");

            rows.forEach(row => {
                row.addEventListener("click", function () {
                    // Reset background color of all rows
                    rows.forEach(row => {
                        row.style.backgroundColor = '';
                    });

                    // Set background color of clicked row
                    this.style.backgroundColor = '#ADD8E6'; // Light blue color
                });
            });
        });
    </script>
    
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table, th, td {
            border: 1px solid #000000;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #BB9E84;
            cursor: pointer;
        }
        /* td {
            background-color: #FDEDEC;
        } */
        a {
            color: black;
            text-decoration: none;
        }
        button {
            border: 1px solid #BB9E84;
            background-color: #BB9E84;
            color: #ffffff;
            padding: 5px 10px;
            cursor: pointer;
        }
        
    </style>
</head>
<body>
<?php
    // Database connection parameters
    $host = "localhost";
    $username = "root";
    $password = "";
    $dbname = "hostel";


    $conn = new mysqli($host, $username, $password, $dbname);


    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $columns = array(
        'student_name' => 'Student Name',
        'permanent_address' => 'Permanent Address',
        'state' => 'State',
        'letter_submitted' => 'Letter Submitted',
        'room_no' => 'Room No',
        'hostel_wing' => 'Hostel Wing',
        'room_type' => 'Room Type',
        'occupancy' => 'Occupancy',
        'photos' => 'Photos',
        'id_proof' => 'ID Proof',
        'college_university' => 'College/University',
        'student_type' => 'Student Type',
        'course' => 'Course',
        'batch' => 'Batch',
        'student_contact_no' => 'Student Contact No',
        'email' => 'Email',
        'father_name' => 'Father Name',
        'father_contact_no' => 'Father Contact No',
        'father_email' => 'Father Email',
        'local_guardian_contact_no' => 'Local Guardian Contact No',
        'check_in_date' => 'Check In Date',
        'extended_days' => 'Extended Days',
        'checkout_date' => 'Checkout Date',
        'actual_checkout_date' => 'Actual Checkout Date',
        'occupancy_charge' => 'Occupancy Charge',
        '60per_occupancy_charge' => '60% Occupancy Charge',
        '40per_occupancy_charge' => '40% Occupancy Charge',
        'package_rate' => 'Package Rate',
        '60per_package' => '60% Package',
        '40per_package' => '40% Package',
        'oneTime_istallment' => 'One-time Installment',
        '60per_rcvd_amnt' => '60% Received Amount',
        '40per_rcvd_amnt' => '40% Received Amount',
        '60per_bal_amnt' => '60% Balance Amount',
        'total_rcvd_amnt' => 'Total Received Amount',
        'total_balance_amnt' => 'Total Balance Amount',
        'fine' => 'Fine',
        'total_bal_include_fine' => 'Total Balance (Include Fine)',
        '60perUTR_transc_id' => '60% UTR Transaction ID',
        '60perRCV_date' => '60% Received Date',
        '40perUTR_transc_id' => '40% UTR Transaction ID',
        '40perRCV_date' => '40% RCV Date',
        'rcpt_no_40per_60per' => 'Receipt No (40% - 60%)',
        'rcpt_date' => 'Receipt Date',
        'mode_of_payment' => 'Mode of Payment',
        'cr_dbt_card_no' => 'Credit/Debit Card No',
        'cheque_rtgs_neft_dd_acc_no' => 'Cheque/RTGS/NEFT/DD Account No',
        'note' => 'Note',
        'hostel_type'  => 'Hostel Type',
        'stream'  => 'Stream',
        'year'  => 'Year',
        'actual_package'  => 'Actual Package',
        'payment_remd_status'  => 'Payment Remd Status',
        'rcvd_40per_Payment'  => 'Rcvd 40% Payment',
        'balance_60per_payment'  => 'Balance 60% Payment',
        'balance_40per_payment'  => 'Balance 40% Payment',
        'security_deposit'  => 'Security Deposite'
    );
    
    // Determine the current sorting column and order
    $sortColumn = isset($_GET['sort']) ? $_GET['sort'] : 'student_name';
    $sortOrder = isset($_GET['order']) && strtolower($_GET['order']) === 'desc' ? 'DESC' : 'ASC';
    
    // SQL query to fetch student data with sorting
    $sql = "SELECT * FROM hostel_form ORDER BY $sortColumn $sortOrder";
    $result = $conn->query($sql);

    

    
    if ($result->num_rows > 0) {
        echo "<form class='update-form' method='post' action='update_data.php' enctype='multipart/form-data'>";
        echo "<table>";

        // Display table headers
        echo "<tr>";
        echo "<th>Serial Number</th>"; // New column for serial number
        foreach ($columns as $columnName => $displayName) {
            echo "<th><a href='?sort=$columnName&order=" . ($sortColumn == $columnName && $sortOrder == 'ASC' ? 'DESC' : 'ASC') . "'>$displayName</a></th>";
        }
        echo "<th>Action</th>";
        echo "</tr>";

        $serialNumber = 1; // Initialize serial number

       // Output data of each row
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>{$serialNumber}</td>"; // Display serial number
            foreach ($columns as $columnName => $displayName) {
                if ($columnName == 'photos') {
                    // Display the existing file name if it exists
                    echo "<td>";
                    if (!empty($row[$columnName])) {
                        $imagePath = "http://localhost/hostel/uploads/{$row['id_card']}/{$row[$columnName]}"; // Path to the image
                        echo "Existing File:<br>";
                        // Display the image if the file exists
                        echo "<img src='{$imagePath}' alt='{$row[$columnName]}' width='100'>";
                    }
                    // Add file input for the "Photos" field
                    echo "<input type='file' name='{$columnName}[{$row['id']}]'><br>";
                    echo "</td>";
                } else {
                    // Include hidden input for the other fields
                    echo "<td><input type='text' name='{$columnName}[{$row['id']}]' value='{$row[$columnName]}'></td>";
                }
            }
            echo "<td><button type='submit' name='update[]' value='{$row['id']}'>Update</button></td>";
            echo "</tr>";
            $serialNumber++; // Increment serial number for the next row
        }


        echo "</table>";
        echo "</form>";
    } else {
        echo "0 results";
    }

    // Close the database connection
    $conn->close();

    // palakchaudhary
    ?>
</body>
</html>