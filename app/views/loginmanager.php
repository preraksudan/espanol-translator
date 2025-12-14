<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>AJAX Login Page</title>
    <style>
        /* Styles from previous example */
        body { font-family: sans-serif; display: flex; justify-content: center; align-items: center; height: 100vh; background-color: #f0f0f0; margin: 0; }
        .login-container { background-color: white; padding: 20px; border-radius: 8px; box-shadow: 0 4px 8px rgba(0,0,0,0.1); width: 300px; }
        h2 { text-align: center; color: #333; }
        .input-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; color: #666; }
        input[type="text"], input[type="password"] { width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; }
        button { width: 100%; padding: 10px; background-color: #007bff; color: white; border: none; border-radius: 4px; cursor: pointer; }
        button:hover { background-color: #0056b3; }
        #message { margin-top: 15px; text-align: center; padding: 10px; border-radius: 4px; display: none; }
        .success { background-color: #d4edda; color: #155724; }
        .error { background-color: #f8d7da; color: #721c24; }
    </style>
</head>
<body>

    <div class="login-container">
        <h2>Login</h2>
        <!-- Form action points to the PHP script -->
        <form id="loginForm" action="models/process_login_ajax.php" method="POST">
            <div class="input-group">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" required>
            </div>
            <div class="input-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
            </div>
            <button type="submit">Log In</button>
        </form>
        <!-- Area to display the response -->
        <div id="message"></div>
    </div>

    <script>
        // Get the form element
        const loginForm = document.getElementById('loginForm');
        const messageArea = document.getElementById('message');

        // Add an event listener for the form submission
        loginForm.addEventListener('submit', function (event) {
            // Prevent the default form submission (which causes a page redirect/refresh)
            event.preventDefault();

            // Clear previous messages
            messageArea.style.display = 'none';
            messageArea.innerHTML = '';

            // Get form data
            const formData = new FormData(loginForm);

            // Send the data using the Fetch API
            fetch('models/process_login_ajax.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json()) // Expect a JSON response from PHP
            .then(data => {
                // Handle the response from the PHP script
                messageArea.style.display = 'block';
                if (data.status === 'success') {
                    messageArea.className = 'success';
                    messageArea.innerHTML = data.message;
                    // Optional: redirect user after successful login using JS
                    window.location.href = 'controllers/crud_manager.php'; 
                    // header("controllers/crud_manager.php");
                    // exit(); // It is crucial to stop the script after the redirect
                } else {
                    messageArea.className = 'error';
                    messageArea.innerHTML = data.message;
                }
            })
            .catch(error => {
                // Handle network errors
                console.error('Error:', error);
                messageArea.style.display = 'block';
                messageArea.className = 'error';
                messageArea.innerHTML = 'An error occurred during the request.';
            });
        });
    </script>

</body>
</html>
