<?php
require 'db.php'; // Include your database connection
include 'header.php';
include 'sidebar.php';

function fetchBookings($conn) {
    $stmt = $conn->query("SELECT * FROM bookings");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

$bookings = fetchBookings($conn);

// Get username from the session for welcome message
$username = isset($_SESSION['username']) ? htmlspecialchars($_SESSION['username']) : 'Admin';

// Path to your TMO logo image
$logoPath = './tmo.jpg'; 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Dashboard - TMO Shuttle Services</title>
    <link href="https://fonts.googleapis.com/css2?family=Segoe+UI:wght@400;600&display=swap" rel="stylesheet">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <style>
        /* General Reset and Body Styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fa; /* Light gray background */
            color: #333;
            display: flex;
            min-height: 100vh;
            overflow-x: hidden;
            flex-direction: column; /* Make body a column layout */
        }

        /* Main Content Styles */
        .main-content {
            margin-left: 280px; /* Leave space for the sidebar */
            padding: 2rem;
            flex-grow: 1;
            background-color: #e9ecef; /* Background color for main content */
            display: flex;
            flex-direction: column;
            align-items: center; /* Center items horizontally */
        }

        h1 {
            text-align: center; /* Align header to the center */
            margin-bottom: 20px;
            color: #007bff;
        }

        /* Date filter styles */
        .date-filter,
        .search-container {
            display: flex;
            justify-content: center; /* Center contents */
            align-items: center; /* Center align items vertically */
            margin-bottom: 10px;
            gap: 5px;
        }

        .date-filter input,
        .search-container input {
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        /* Button styles */
        button {
            padding: 10px 20px; 
            cursor: pointer; 
            background-color: #007BFF; 
            color: white; 
            border: none; 
            border-radius: 5px; 
            transition: background-color 0.3s; 
        }

        button:hover {
            background-color: #0056b3; 
        }

        /* Button container */
        .button-container {
            display: flex;
            justify-content: center; /* Center the buttons in the container */
            align-items: center; /* Align buttons vertically */
            gap: 10px;  /* Gap between buttons */
            margin-bottom: 20px; /* Space below the button container */
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }

        table, th, td {
            border: 1px solid #ccc;
            padding: 10px;
            text-align: left; /* Align table cells to the left */
        }

        th {
            background: #f2f2f2;
        }

        /* Total Price Display */
        .total-price {
            font-size: 1.2em;
            margin-top: 20px;
            color: #333;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            aside {
                width: 100%;
                position: relative; /* Position relative on small screens */
                height: auto; /* Auto height */
                padding-bottom: 1rem; /* Space at the bottom */
                box-shadow: none; /* Remove the shadow */
            }

            .main-content {
                margin-left: 0; /* No left margin */
                padding: 1rem; /* Adjust padding */
            }
        }

        /* Print Styles */
        @media print {
            body {
                background-color: white; /* Set a white background for print */
            }
            .top-bar, aside, .logout-button, .print-button, .total-price-button, .generate-payslip-button, .date-filter, .search-container {
                display: none; /* Hide top bar, sidebar, buttons, date filter, and search container during printing */
            }
            .main-content {
                margin-left: 0; /* Override the left margin */
                padding: 0; /* Remove padding for print */
            }
            #bookingTable {
                width: 100%; /* Ensure the table is full-width in print */
            }
        }
    </style>
</head>
<body>

    <div class="main-content">
        <div class="logo-container" style="text-align: center; margin-bottom: 20px;">
            <img src="<?php echo $logoPath; ?>" alt="TMO Logo" style="max-width: 19.5%; height: auto;"> <!-- Logo set to normal size and responsive -->
        </div>
        <h1>Booking List Dashboard</h1>
        
        <div class="date-filter">
            <input type="date" id="startDate" placeholder="Start Date">
            <input type="date" id="endDate" placeholder="End Date">
            <button id="filterButton">Filter</button> <!-- Filter button next to date inputs -->
        </div>

        <div class="search-container">
            <input type="text" id="nameSearch" placeholder="Search by Driver Name">
            <button id="searchButton">Search</button> <!-- Search button next to input -->
        </div>

        <div class="button-container">
            <button onclick="printDashboard()" class="print-button">Print Dashboard</button> <!-- Print button -->
            <button id="generatePayslipButton" class="generate-payslip-button">Generate Employee Payslip</button> <!-- Payslip button -->
            <button id="totalPriceButton" class="total-price-button">Total Price</button> <!-- Total Price button -->
        </div>

        <table id="bookingTable">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Driver Name</th>
                    <th>In/Out</th>
                    <th>Company</th>
                    <th>Date</th>
                    <th>Time</th>   <!-- Changed position to come before Price -->
                    <th>Seat No</th>
                    <th>Price</th>  <!-- Price now comes after Time -->
                </tr>
            </thead>
            <tbody>
                <?php if (empty($bookings)): ?>
                    <tr><td colspan="8">No bookings found.</td></tr>
                <?php else: ?>
                    <?php foreach ($bookings as $booking): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($booking['id']); ?></td>
                        <td><?php echo htmlspecialchars($booking['driverName']); ?></td>
                        <td><?php echo htmlspecialchars($booking['in_out']); ?></td>
                        <td><?php echo htmlspecialchars($booking['company']); ?></td>
                        <td><?php echo htmlspecialchars($booking['date']); ?></td>
                        <td><?php echo htmlspecialchars(date('h:i A', strtotime($booking['time']))); ?></td> <!-- Updated Time formatting -->
                        <td><?php echo htmlspecialchars($booking['seatNo']); ?></td>
                        <td>₱<?php echo htmlspecialchars(number_format($booking['price'], 2)); ?></td> <!-- Price formatted -->
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>

        <!-- Total Price Display -->
        <div class="total-price" id="totalPriceDisplay">Total Price: ₱0.00</div>
    </div>

    <script>
        function toggleDropdown(menuId) {
            const clickedDropdown = document.getElementById(menuId);
            clickedDropdown.classList.toggle('open'); // Toggle the clicked dropdown
            const dropdowns = document.querySelectorAll('.dropdown');
            dropdowns.forEach(dropdown => {
                if (dropdown !== clickedDropdown) {
                    dropdown.classList.remove('open'); // Close other dropdowns
                }
            });
        }

        // Print functionality
        function printDashboard() {
            window.print(); // Call print dialog
        }

        // Filter button functionality
        document.getElementById('filterButton').addEventListener('click', function() {
            const startDate = new Date(document.getElementById('startDate').value);
            const endDate = new Date(document.getElementById('endDate').value);
            const rows = document.querySelectorAll('#bookingTable tbody tr');

            rows.forEach(row => {
                const bookingDate = new Date(row.children[4].textContent); // Date is in the 5th column (index 4)

                // Show or hide the row based on the date filter
                const isDateInRange = (isNaN(startDate) || bookingDate >= startDate) && 
                                      (isNaN(endDate) || bookingDate <= endDate);
                row.style.display = isDateInRange ? '' : 'none'; // Show or hide based on date
            });
        });

        // Search functionality
        document.getElementById('searchButton').addEventListener('click', function() {
            const searchValue = document.getElementById('nameSearch').value.toLowerCase().trim(); // Get searched name
            const rows = document.querySelectorAll('#bookingTable tbody tr'); // Get all rows
            const startDate = new Date(document.getElementById('startDate').value); // Get start date
            const endDate = new Date(document.getElementById('endDate').value); // Get end date

            rows.forEach(row => {
                const driverName = row.children[1].textContent.toLowerCase(); // Get driver name
                const bookingDate = new Date(row.children[4].textContent); // Get booking date

                const isDateInRange = (isNaN(startDate) || bookingDate >= startDate) && 
                                      (isNaN(endDate) || bookingDate <= endDate);
                
                // Check if the driver name matches the search value and if the booking date is in range
                if (driverName === searchValue && isDateInRange) {
                    row.style.display = ''; // Match found, show row
                } else {
                    row.style.display = 'none'; // No match, hide row
                }
            });
        });

        // Total Price button functionality
        document.getElementById('totalPriceButton').addEventListener('click', function() {
            const startDate = new Date(document.getElementById('startDate').value);
            const endDate = new Date(document.getElementById('endDate').value);
            const rows = document.querySelectorAll('#bookingTable tbody tr');
            let totalPrice = 0;

            rows.forEach(row => {
                const bookingDate = new Date(row.children[4].textContent); // Date in the 5th column 
                const price = parseFloat(row.children[7].textContent.replace(/₱/, '').replace(/,/g,'', '')); // Price in the 8th column (index 7)

                // Check if the booking date falls within the selected range
                if (bookingDate >= startDate && bookingDate <= endDate && row.style.display !== 'none') {
                    totalPrice += price; // Sum up the prices
                }
            });

            // Display the total price in the designated div
            document.getElementById('totalPriceDisplay').textContent = "Total Price: ₱" + totalPrice.toFixed(2);
        });

        // Event listener for generating payslip
        document.getElementById('generatePayslipButton').addEventListener('click', function() {
            const startDate = new Date(document.getElementById('startDate').value);
            const endDate = new Date(document.getElementById('endDate').value);
            const rows = document.querySelectorAll('#bookingTable tbody tr');
            const attendance = {};
            let driverName = ''; // Store the last valid driver name 
            let daysAttended = 0; // Counter for days attended
            const attendedDates = new Set(); // Use a Set to track unique dates attended

            rows.forEach(row => {
                const bookingDate = new Date(row.children[4].textContent); // Date in the 5th column 
                const currentDriverName = row.children[1].textContent; // Driver Name in the 2nd column

                // Check if the booking date falls within the selected range and is visible
                if (bookingDate >= startDate && bookingDate <= endDate && row.style.display !== 'none') {
                    const dateString = bookingDate.toLocaleDateString(); // Format the date to string for unique tracking

                    // Only count it as attended if we haven't counted it already
                    if (!attendedDates.has(dateString)) {
                        attendedDates.add(dateString); // Mark this date as attended
                        attendance[dateString] = "Present"; // Mark it attended
                        driverName = currentDriverName; // Capture the driver name
                        daysAttended++; // Increase the count for attended days
                    }
                }
            });

            // If no attendance data found
            if (daysAttended === 0) {
                alert("No bookings found in the selected date range.");
                return; // Stop further execution
            }

            const employeeId = "EMP12345"; // Replace with actual Employee ID logic
            const queryString = `?employeeId=${employeeId}&attendanceData=${encodeURIComponent(JSON.stringify(attendance))}&startDate=${startDate.toISOString()}&endDate=${endDate.toISOString()}&driverName=${encodeURIComponent(driverName)}&daysAttended=${daysAttended}`;

            // Redirect to payslip page
            window.location.href = 'payslip.php' + queryString;
        });
    </script>
</body>
</html>