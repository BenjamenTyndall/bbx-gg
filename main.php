<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Main Page</title>
</head>
<body>
<?php
// Start session to maintain user login state
session_start();

// Check if user is logged in
if(isset($_SESSION['email'])) {
    // User is logged in, display their email
    echo "<h1>Welcome, " . $_SESSION['email'] . "</h1>";
} else {
    // User is not logged in, redirect to login page
    header("Location: index.php");
    exit();
}
?>
</body>
</html>
