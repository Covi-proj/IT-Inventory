<?php

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Database connection
    $host = 'localhost';
    $db = 'license_db';
    $user = 'root';
    $pass = '';

    try {
        $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        die("Could not connect to the database: " . $e->getMessage());
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        try {
            $empId = $_POST['emp_id'];
            $type = $_POST['emp_no'];
            $key = $_POST['name'];
            $remark = $_POST['bday'];
            $computer = $_POST['age'];
            $account = $_POST['division'];
            $password = $_POST['company'];

            $sql = "UPDATE licenses 
                SET type_of_license = :type,
                    license_key = :key,
                    remark = :remark,
                    computer_name = :computer,
                    ms_account = :account,
                    ms_password = :password
                WHERE id = :id";

            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':type' => $type,
                ':key' => $key,
                ':remark' => $remark,
                ':computer' => $computer,
                ':account' => $account,
                ':password' => $password,
                ':id' => $empId,
            ]);

            header("Location: llicense_monitoring.php?update=success");
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }


}
?>