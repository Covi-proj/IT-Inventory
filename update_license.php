<?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $host = 'localhost';
        $db = 'license_db';
        $user = 'root';
        $pass = '';

        try {
            $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $empId = $_POST['emp_id'];
            $type = $_POST['emp_no'];
            $key = $_POST['name'];
            $productId = $_POST['product_id'];
            $remark = $_POST['bday'];
            $computer = $_POST['age'];
            $account = $_POST['division'];
            $password = $_POST['company'];

            $sql = "UPDATE licenses 
                    SET type_of_license = :type,
                         lisence = :productId,
                        license_key = :license_key,
                        remark = :remark,
                        computer_name = :computer,
                        ms_account = :account,
                        ms_password = :password
                    WHERE id = :id";

            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':type' => $type,
                ':license_key' => $key,
                ':productId' => $productId,
                ':remark' => $remark,
                ':computer' => $computer,
                ':account' => $account,
                ':password' => $password,
                ':id' => $empId,
            ]);

            header("Location: llicense_monitoring.php?update=success");
        } catch (PDOException $e) {
            echo "<div class='alert alert-danger'>Error: " . $e->getMessage() . "</div>";
        }
    }
    ?>