<?php
// Database connection details from your input
$host = 'db';
$dbname = 'audio_app';
$username = 'root';
$password = 'root';

try {
    // Create a new PDO instance
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Ensure errors are reported clearly

    // SQL query
    $sql = "SELECT * FROM translations";

    // Execute the query and fetch all results
    $stmt = $pdo->query($sql);
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Display the results
    if ($results) {
        echo "<pre>";
        print_r($results); // Use print_r or var_dump for quick viewing
        echo "</pre>";
    } else {
        echo "No translations found.";
    }

} catch (PDOException $e) {
    die("Database error: " . $e->getMessage());
}
?>
