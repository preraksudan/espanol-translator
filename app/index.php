<?php
session_start();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PHP Include Example</title>
    <style>
        body { font-family: sans-serif; margin: 20px; }
        .content-box { border: 1px solid #ccc; padding: 15px; background-color: #f9f9f9; }
    </style>
</head>
<body>

  <!-- Main Login Page -->
    <main>
        <?php
          include 'views/loginmanager.php';
        ?>
    </main>

</body>
</html>
