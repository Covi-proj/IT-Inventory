<?php
session_start();

$servername = "localhost";  
$username = "root";         
$password = "";             
$dbname = "license_db";    

// PDO connection (using try-catch for better error handling)
try {
    $pdo = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    // Set the PDO error mode to exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Could not connect to the database: " . $e->getMessage());
}

// Get the posted data
$user = $_POST['username'];
$pass = $_POST['password'];

// Sanitize input to prevent SQL injection
$user = $pdo->quote($user);
$pass = $pdo->quote($pass);

// Modify the query to fetch the user's name as well
$sql = "SELECT id, username  FROM users WHERE username=$user AND passwords =$pass";

try {
    $stmt = $pdo->query($sql);
    
    if ($stmt->rowCount() > 0) {
        // User found
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // Set session variables
        $_SESSION['user_id'] = $row['id'];
        $_SESSION['username'] = $row['username'];
        
        // Assign the redirect URL directly based on the account type
        $redirectUrl = 'llicense_monitoring.php';  // Assuming both account types use the same redirect

        // Return success response
        echo json_encode(['status' => 'success', 'redirect' => $redirectUrl]);
    } else {
        // User not found
        echo json_encode([
            'status' => 'error',
            'message' => '<div class="alert alert-danger" style="font-weight: bold; color: red; text-align: center;">Invalid username or password</div>'
        ]);
    }
} catch (PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => '<div class="alert alert-danger">Database query failed: ' . $e->getMessage() . '</div>']);
}

// Close the PDO connection
$pdo = null;
?>
