<?php
header('Content-Type: application/json');

$response = [];

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $input_username = $_POST['username'] ?? '';
    $input_password = $_POST['password'] ?? '';

    // Database connection details
    $host = 'db';         // Docker service name
    $dbname = 'audio_app';
    $db_username = 'root';
    $db_password = 'root';

    try {
        // Connect to MySQL via PDO
        $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $db_username, $db_password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Prepared statement to prevent SQL injection
        $stmt = $pdo->prepare("SELECT * FROM admin_users WHERE username = :username AND password = :password");
        $stmt->execute([
            ':username' => $input_username,
            ':password' => $input_password // In production, use hashed passwords
        ]);

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            // Successful login
            session_start();
            $_SESSION['username'] = $user['username'];
            $_SESSION['user_id'] = $user['id'] ?? null;

            $response['status'] = 'success';
            $response['message'] = "Login Successful! Welcome, " . htmlspecialchars($user['username']);
        } else {
            $response['status'] = 'error';
            $response['message'] = "Invalid username or password.";
        }

    } catch (PDOException $e) {
        $response['status'] = 'error';
        $response['message'] = "Database error: " . $e->getMessage();
    }

} else {
    // Not a POST request
    $response['status'] = 'error';
    $response['message'] = 'Invalid request method.';
}

// Return JSON response
echo json_encode($response);
