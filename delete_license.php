<?php
// Include configuration and utilities
include('config.php');


// Redirect URL (where to go after the operation)
$redirectUrl = 'license_monitoring.php';

// Initialize variables
$message = '';
$status = '';

// Check if the ID is provided and valid
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = (int) $_GET['id']; // Sanitize the ID

    try {
        // Get the database connection
        $conn = getDatabaseConnection();

        // Prepare and execute the DELETE query
        $stmt = $conn->prepare("DELETE FROM licenses WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        // Check the result
        if ($stmt->rowCount() > 0) {
            $message = "User deleted successfully!";
        } else {
            $message = "No user found with this ID!";
        }
    } catch (PDOException $e) {
        $message = "Error: " . $e->getMessage();
    }
} else {
    $message = "Invalid or missing user ID!";
}
?>
<script>
window.location.href='llicense_monitoring.php';
</script>
<?php
exit;
?>