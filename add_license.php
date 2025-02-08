<?php
// Database connection
$host = 'localhost';
$db   = 'license_db';
$user = 'root';
$pass = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Check if the form is submitted
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $type_of_license = $_POST['type_of_license'];
        $lisence = $_POST['lisence'];
        $license_key = $_POST['license_key'];
        $remark = $_POST['remark'];
        $computer_name = $_POST['computer_name'];
        $ms_account = $_POST['ms_account'];
        $ms_password = $_POST['ms_password'];


        // Check if the employee already exists
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM licenses WHERE type_of_license = ?");
        $stmt->execute([$type_of_license]);
        $employeeExists = $stmt->fetchColumn();

        if ($employeeExists) {
            // If employee exists, show a message
            header('Location: view_masterlist.php?message=Employee already exists');
            exit;
        } else {
            // Insert data into the database if the employee doesn't exist
            $stmt = $pdo->prepare("INSERT INTO licenses (type_of_license, lisence, license_key, remark, computer_name, ms_account, ms_password) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([$type_of_license, $lisence, $license_key, $remark, $computer_name, $ms_account, $ms_password]);

            // Redirect back with success
            header('Location: llicense_monitoring.php?message=License added successfully');
            exit;
        }
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
