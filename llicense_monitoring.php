<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); // Redirect to login if not authenticated
    exit();
}

// Database connection
$host = 'localhost';
$dbname = 'license_db';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Fetch user's name and password
    $stmt = $pdo->prepare("SELECT name, username, passwords FROM users WHERE id = :user_id");
    $stmt->bindParam(':user_id', $_SESSION['user_id'], PDO::PARAM_INT);
    $stmt->execute();

    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        // Securely output the values
        $username = htmlspecialchars($user['name']); // Username
        $user_password = htmlspecialchars($user['passwords']); // User's password
    } else {
        $username = "Guest"; // Fallback if user not found
        $user_password = "";
    }
} catch (PDOException $e) {
    $username = "Error retrieving name."; // Handle error
    $user_password = ""; // Empty password on error
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IT Inventory</title>
    <link rel="icon" href="hepc.jpg" type="image/png">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- DataTables CSS -->
    <link href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css" rel="stylesheet">

    <!-- FontAwesome for Icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">


    <link rel="icon" href="icon.jfif" type="image/png">

    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;

            margin: 0;
            padding: 0;
            box-sizing: border-box;

        }

        .table-wrapper {
            background: #ffffff;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            padding: 20px;
            width: 100%;
            /* Ensure the wrapper spans the full width of its container */
            max-width: 100%;
            /* Prevents unnecessary overflow */
            font-size: small;
            overflow-x: auto;
            /* Enables horizontal scroll for small screens */
        }

        table {
            width: 100%;
            /* Make the table take up the full width of the wrapper */
            border-collapse: collapse;
            /* Prevent gaps between cells */
        }

        th,
        td {
            text-align: left;
            padding: 10px;
            border: 1px solid #ddd;
            /* Optional border for better visibility */
            word-wrap: break-word;
            /* Prevent long text from breaking layout */
            font-size: 13px;
        }

        @media (max-width: 768px) {
            .table-wrapper {
                font-size: 11px;
                /* Reduce font size for small screens */
            }
        }

        th,
        td {
            padding: 6px;
            /* Adjust padding for smaller screens */
        }


        @media screen and (max-width: 480px) {

            th:nth-child(n+3),
            /* Hide columns beyond the second one */
            td:nth-child(n+3) {
                display: none;
                /* Use only for non-essential columns */
            }
        }

        .parent-container {
            width: 100%;
            max-width: 100%;
        }


        .btn-cancel {
            background-color: #6c757d;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-weight: bold;
            text-decoration: none;
        }

        .btn-cancel:hover {
            background-color: #5a6268;
        }

        .section-header h1 {
            font-size: 2rem;
            font-weight: bold;
            color: #343a40;
        }

        .section-header p {
            color: #6c757d;
            font-size: 1rem;
        }

        .btn-action {
            display: inline-block;
            margin-right: 5px;
        }

        .btn-primary {
            background-color: #0d6efd;
            border: none;
        }

        .btn-primary:hover {
            background-color: #0b5ed7;
        }

        .btn-danger {
            background-color: #dc3545;
            border: none;
        }

        .btn-danger:hover {
            background-color: #c82333;
        }

        .dataTables_filter input {
            border-radius: 5px;
        }

        .form-container {
            max-width: 400px;
            margin: 20px auto;
            padding: 20px;
            background: #f9f9f9;
            border: 1px solid #ddd;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            font-family: Arial, sans-serif;
        }

        .form-container label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
            color: #333;
        }

        .form-container input[type="file"] {
            display: block;
            margin-bottom: 15px;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            background: #fff;
            font-size: 14px;
            color: #333;
        }

        .form-container button {
            display: block;
            width: 100%;
            padding: 10px;
            background: #007bff;
            color: #fff;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .form-container button:hover {
            background: #0056b3;
        }

        /* Customizing the select box appearance */
        .form-select {
            border-radius: 5px;
            padding: 0.375rem 1.5rem 0.375rem 0.75rem;
            /* Padding for a more spacious feel */
            background-color: #f8f9fa;
            /* Light background color */
            font-size: 1rem;
            /* Make text slightly larger for readability */
        }

        .input-group-text {
            background-color: #007bff;
            color: white;
            border-radius: 5px 0 0 5px;
        }

        /* Navbar container */
        .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: #f9f9f9;
            /* Slight off-white background */
            padding: 10px 10px;
            /* Increased padding for more breathing space */
            border-bottom: 2px solid #dcdcdc;
            /* Subtle bottom border */
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            /* Light shadow for depth */
        }

        /* Left content styling */
        /* Navbar Left */
        .navbar-left {
            display: flex;
            align-items: center;
            padding-left: 20px;
        }

        .logo-container {
            display: flex;
            align-items: center;
        }

        .navbar-logo {
            width: 45px;
            height: 45px;
            object-fit: contain;
            transition: transform 0.3s ease;
            /* Smooth scale transition */
        }

        .navbar-logo:hover {
            transform: scale(1.1);
            /* Subtle zoom effect on hover */
        }

        .logo-text {
            font-size: 28px;
            font-weight: 800;
            margin-left: 20px;
            color: #333;
            letter-spacing: 1px;
            transition: color 0.3s ease;
            /* Smooth color transition */
        }

        .logo-text:hover {
            color: #007BFF;
            /* Hover effect with primary brand color */
        }

        /* Date & Time */

        /* Navbar Right */
        /* Navbar Left */
        .navbar-left {
            display: flex;
            align-items: center;
            padding-left: 20px;
        }

        .logo-container {
            display: flex;
            align-items: center;
        }

        .navbar-logo {
            width: 50px;
            height: 50px;
            object-fit: contain;
        }

        .logo-text {
            font-size: 26px;
            font-weight: 700;
            margin-left: 15px;
            margin-top: 5px;
            color: #333;
        }

        /* Date & Time */
        .date-time-container p {
            font-size: 14px;
            color: #777;
            font-weight: 300;
            margin-left: 20px;
        }

        /* Navbar Right */
        .navbar-right {
            display: flex;
            align-items: center;
            padding-right: 20px;
            margin-top: 10px;
        }

        .user-info {
            display: flex;
            align-items: center;
        }

        .user-name {
            font-size: 16px;
            color: #444;
            font-weight: 500;
            margin-right: 10px;
        }

        .user-avatar {
            width: 38px;
            height: 38px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid #ddd;
            transition: border-color 0.3s;
        }

        .user-avatar:hover {
            border-color: #007BFF;
            /* Light blue border on hover */
        }

        .user-image {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            object-fit: cover;

        }

        /* Basic styling for dropdown */
        .user-info .dropdown {
            position: relative;
            display: inline-block;
        }

        .user-info .dropdown-menu {
            display: none;
            position: absolute;
            right: 0;
            background-color: #fff;
            box-shadow: 0px 8px 16px rgba(0, 0, 0, 0.2);
            min-width: 160px;
            z-index: 1;
        }

        .user-info .dropdown-menu li {
            padding: 8px 16px;
            cursor: pointer;
        }

        .user-info .dropdown-menu li:hover {
            background-color: #f1f1f1;
        }

        /* Display dropdown menu on hover */
        .user-info .dropdown:hover .dropdown-menu {
            display: block;
        }
    </style>
</head>

<body>
    <div class="navbar">
        <div class="navbar-left">
            <div class="logo-container">
                <img src="unnamed.png" alt="Health-e Logo" class="navbar-logo">
                <span class="logo-text"></span>
            </div>
            <div class="date-time-container">
                <h5>License Inventory</h5>
            </div>
        </div>

        <div class="navbar-right">
            <div class="user-info">
                <div class="dropdown">
                    <p class="user-name"><?php echo $username; ?></p>
                    <ul class="dropdown-menu">
                        <li class="fa fa-sign-out-alt"><a href="logout.php"
                                style="text-decoration: none; font-weight: bold;"> Log out</a></li>
                    </ul>
                </div>
            </div>
        </div>

        <script>


            document.querySelector('.user-name').addEventListener('click', function () {
                var dropdown = this.closest('.dropdown').querySelector('.dropdown-menu');
                dropdown.style.display = (dropdown.style.display === 'block') ? 'none' : 'block';
            });

        </script>
    </div>
    </div>

    <!-- Main Section -->
    <section class="py-5">
        <div class="container">

            <!-- Section Header -->
            <div class="section-header text-center mb-4">

            </div>
            <div class="container mt-5">




                <!-- Add Employee Button -->

            </div>


            <!-- Modal Structure -->
            <div class="modal fade" id="licenseModal" tabindex="-1" aria-labelledby="licenseModalLabel"
                aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="licenseModalLabel">License Products</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <?php
                            // Database connection settings
                            $host = 'localhost';
                            $db = 'license_db';
                            $user = 'root';
                            $pass = '';

                            // Create PDO instance
                            $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
                            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                            // SQL query to count the occurrences of each type_of_license
                            $sqloption = "SELECT type_of_license, COUNT(*) AS count FROM licenses GROUP BY type_of_license";

                            try {
                                $stmt = $pdo->prepare($sqloption);
                                $stmt->execute();
                            } catch (PDOException $e) {
                                echo 'Error: ' . $e->getMessage();
                            }

                            // Display the data in a list format
                            echo "<ul class='list-group' style='max-height: 300px; overflow-y: auto; font-weight: bold;'>";
                            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                echo "<li class='list-group-item'>";
                                echo htmlspecialchars($row['type_of_license']) . "  —  " . $row['count'];
                                echo "</li>";
                            }
                            echo "</ul>";
                            ?>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Include Bootstrap JS (optional for modal functionality) -->
            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

            <!-- Modal -->


            <!-- Bootstrap 5 JS Bundle (includes Popper.js) -->
            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
            <!-- Table Wrapper -->
            <div class="table-wrapper">

                <div class="mb-3">
                    <label for="companyFilter" class="form-label">Filter:</label>
                    <div class="input-group">

                        <div class=form-row>
                            <div class="form-group">
                                <select id="companyFilter" class="form-select" required onchange="filterData()">
                                    <option value="">--Select Type of License--</option>
                                    <?php
                                    // Database connection settings
                                    $host = 'localhost';
                                    $db = 'license_db';
                                    $user = 'root';
                                    $pass = '';

                                    // Create PDO instance
                                    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
                                    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                                    $sqloption = "SELECT DISTINCT type_of_license FROM licenses";

                                    try {
                                        $stmt = $pdo->prepare($sqloption);
                                        $stmt->execute();
                                    } catch (PDOException $e) {
                                        echo 'Error: ' . $e->getMessage();
                                    }

                                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                        $selected = (isset($_GET['type_of_license']) && $_GET['type_of_license'] === $row['type_of_license']) ? 'selected' : '';
                                        echo '<option value="' . htmlspecialchars($row['type_of_license']) . '" ' . $selected . '>' . htmlspecialchars($row['type_of_license']) . '</option>';
                                    }
                                    ?>
                                </select>

                                <script>
                                    function filterData() {
                                        const filterValue = document.getElementById('companyFilter').value;
                                        window.location.href = `?type_of_license=${encodeURIComponent(filterValue)}`;
                                    }
                                </script>

                            </div>
                        </div>

                        <div class="form-row"></div>

                        <div class="form-group" style="margin-left: 10px;">

                            <button type="button" id="btnAddEmployee" class="btn btn-primary"
                                style="font-weight: bold; margin-bottom: 10px; "><i
                                    class="fa-solid fa-file-signature"></i>
                                Add License
                            </button>

                        </div>

                        <div class="form-group" style="margin-left: 10px;">

                            <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css"
                                rel="stylesheet">

                            <!-- Button with an icon -->
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                style="font-weight: bold;" data-bs-target="#licenseModal">
                                <i class="bi bi-eye"></i> View License Products
                            </button>

                        </div>
                    </div>

                </div>

                <table id="employeeTable" class="table table-striped table-bordered">
                    <thead class="table-dark">
                        <tr>
                            <th>Product ID</th>
                            <th style="width: 150px;">Type of License</th>
                            <th>License</th>
                            <th style="width: 300px;">Product ID | Activation Code | Request Code | License Key |</th>
                            <th>Remarks</th>
                            <th>Computer Name</th>
                            <th>Account</th>
                            <th>Password</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        try {
                            // Get the filter value from the URL (if set)
                            $type_of_license = $_GET['type_of_license'] ?? '';

                            // Query to fetch data from the "licenses" table
                            $sql = "SELECT * FROM licenses";
                            if (!empty($type_of_license)) {
                                $sql .= " WHERE type_of_license = :type_of_license";
                            }

                            $stmt = $pdo->prepare($sql);
                            if (!empty($type_of_license)) {
                                $stmt->bindParam(':type_of_license', $type_of_license, PDO::PARAM_STR);
                            }
                            $stmt->execute();

                            // Fetch all rows as an associative array
                            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

                            // Check if data exists
                            if ($data) {
                                foreach ($data as $item) {
                                    echo '<tr>';
                                    echo '<td>' . (!empty($item['id']) ? htmlspecialchars($item['id']) : 'N/A') . '</td>';
                                    echo '<td>' . (!empty($item['type_of_license']) ? htmlspecialchars($item['type_of_license']) : 'N/A') . '</td>';
                                    echo '<td>' . (!empty($item['lisence']) ? htmlspecialchars($item['lisence']) : 'N/A') . '</td>';
                                    echo '<td>' . (!empty($item['license_key']) ? htmlspecialchars($item['license_key']) : 'N/A') . '</td>';
                                    echo '<td>' . (!empty($item['remark']) ? htmlspecialchars($item['remark']) : 'N/A') . '</td>';
                                    echo '<td>' . (!empty($item['computer_name']) ? htmlspecialchars($item['computer_name']) : 'N/A') . '</td>';
                                    echo '<td>' . (!empty($item['ms_account']) ? htmlspecialchars($item['ms_account']) : 'N/A') . '</td>';
                                    echo '<td>' . (!empty($item['ms_password']) ? htmlspecialchars($item['ms_password']) : 'N/A') . '</td>';
                                    echo '<td class="text-center" style="white-space: nowrap;">';
                                    echo '<a href="#" 
                                    class="btn btn-sm btn-primary btn-edit btn-action" 
                                    data-emp-id="' . htmlspecialchars($item['id']) . '" 
                                    data-type="' . htmlspecialchars($item['type_of_license']) . '" 
                                    data-product-Id="' . htmlspecialchars($item['license_key']) . '" 
                                    data-key="' . htmlspecialchars($item['lisence']) . '" 
                                    data-remarks="' . htmlspecialchars($item['remark']) . '" 
                                    data-computer="' . htmlspecialchars($item['computer_name']) . '" 
                                    data-account="' . htmlspecialchars($item['ms_account']) . '" 
                                    data-password="' . htmlspecialchars($item['ms_password']) . '">
                                    <i class="fas fa-edit"></i> Edit
                                </a>';

                                    echo '<a href="delete_license.php?id=' . htmlspecialchars($item['id']) . '" class="btn btn-sm btn-danger btn-action"><i class="fas fa-trash"></i> Delete</a>';
                                    echo '</td>';
                                    echo '</tr>';
                                }
                            } else {
                                echo '<tr><td colspan="8" class="text-center">No records found</td></tr>';
                            }
                        } catch (PDOException $e) {
                            echo '<tr><td colspan="8" class="text-center">Error: ' . htmlspecialchars($e->getMessage()) . '</td></tr>';
                        }
                        ?>
                    </tbody>
                </table>

                <p id="noDataMessage" style="display:none; text-align: center; color: red;">No data found</p>

            </div>
        </div>
    </section>



    <!-- Add Employee Modal -->
    <div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <!-- Modal Header -->
                <div class="modal-header">
                    <h5 class="modal-title" id="addModalLabel">Add New License</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <!-- Modal Body -->
                <div class="modal-body">
                    <form id="addForm" action="add_license.php" method="POST">
                        <div class="mb-3">
                            <label for="addEmpNo" class="form-label">Type of License:</label>
                            <input type="text" class="form-control" id="addEmpNo" name="type_of_license"
                                placeholder="Type of License" required>
                        </div>

                        <div class="mb-3">
                            <label for="addName" class="form-label">License:</label>
                            <input type="text" class="form-control" id="addName" name="lisence" placeholder="License"
                                required>
                        </div>

                        <div class="mb-3">
                            <label for="addage" class="form-label">Product ID | Activation Code | Request Code | License
                                Key:</label>
                            <input type="text" class="form-control" id="addage" name="license_key"
                                placeholder="Enter License Key" required>
                        </div>

                        <div class="mb-3">
                            <label for="addbday" class="form-label">Computer Name:</label>
                            <input type="text" class="form-control" id="addbday" name="computer_name"
                                placeholder="Computer Name" required>
                        </div>

                        <div class="mb-3">
                            <label for="addbday" class="form-label">Remarks:</label>
                            <input type="text" class="form-control" id="addbday" name="remark" required>
                        </div>

                        <h6>Require Account & Password(Optional)</h6>

                        <div class="mb-3">
                            <label for="addbday" class="form-label">Account:</label>
                            <input type="text" class="form-control" id="addbday" placeholder="Enter Account"
                                name="ms_account">
                        </div>

                        <div class="mb-3">
                            <label for="addbday" class="form-label">Password:</label>
                            <input type="text" class="form-control" id="addbday" name="ms_password"
                                placeholder="Enter Password">
                        </div>



                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">Add License</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>



    <!-- Edit Modal -->
    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <!-- Modal Header -->
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Edit License Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <!-- Modal Body -->
                <div class="modal-body">
                    <form id="editForm" action="update_license.php" method="POST">
                        <!-- Hidden field to store Employee ID -->
                        <input type="hidden" name="emp_id" id="editEmpId">

                        <div class="mb-3">
                            <label for="editEmpNo" class="form-label">License Type:</label>
                            <input type="text" class="form-control" id="edit_l_type" name="emp_no">
                        </div>

                        <div class="mb-3">
                            <label for="editName" class="form-label">License:</label>
                            <input type="text" class="form-control" id="edit_l_key" name="name">
                        </div>

                        <!-- Correct the second input's ID -->
                        <div class="mb-3">
                            <label for="editProductId" class="form-label">Product ID | Activation Code | Request Code |
                                License Key:</label>
                            <input type="text" class="form-control" id="edit_product_id" name="product_id">
                        </div>


                        <div class="mb-3">
                            <label for="editAge" class="form-label">Computer Name:</label>
                            <input type="text" class="form-control" id="edit_cm" name="age">
                        </div>

                        <div class="mb-3">
                            <label for="editBday" class="form-label">Remarks:</label>
                            <input type="text" class="form-control" id="edit_remarks" name="bday">
                        </div>

                        <div class="mb-3">
                            <label for="editDivision" class="form-label">Account:</label>
                            <input type="text" class="form-control" id="edit_account" name="division">
                        </div>

                        <div class="mb-3">
                            <label for="editCompany" class="form-label">Password:</label>
                            <input type="text" class="form-control" id="edit_pass" name="company">
                        </div>


                        <div class="d-grid">

                            <button type="submit" class="btn btn-success">Update License</button>

                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    
    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>

    <!-- Initialize DataTables -->
    <script>
        $(document).ready(function () {
            $('#employeeTable').DataTable({
                paging: true,        // Enable pagination
                searching: true,     // Enable search
                lengthChange: true,  // Allow users to change the number of rows displayed
                pageLength: 10,      // Default number of rows per page
                ordering: true,      // Enable column sorting
            });
        });
    </script>

    <script>
        $(document).ready(function () {
            // Add Employee - Open Add Modal
            $('#btnAddEmployee').click(function () {
                $('#addModal').modal('show');
            });

            // Edit Employee - Open Edit Modal

        });
    </script>

    <script>
        $(document).on('click', '.btn-edit', function () {
            // Get data attributes
            const empId = $(this).data('emp-id');
            const type = $(this).data('type');
            const key = $(this).data('key');
            const productId = $(this).data('product-id');
            const remarks = $(this).data('remarks');
            const computer = $(this).data('computer');
            const account = $(this).data('account');
            const password = $(this).data('password');

            // Log values to verify
            console.log({
                empId, type, key, productId, remarks, computer, account, password
            });

            // Populate modal fields
            $('#editEmpId').val(empId);
            $('#edit_l_type').val(type);
            $('#edit_l_key').val(key);
            $('#edit_product_id').val(productId);
            $('#edit_remarks').val(remarks);
            $('#edit_cm').val(computer);
            $('#edit_account').val(account);
            $('#edit_pass').val(password);

            // Show the modal
            $('#editModal').modal('show');
        });

    </script>

    <!--Set total entries each company-->

</body>

</html>