<?php 
session_start();
$connection = mysqli_connect("localhost", "root", "", "data");
if (!$connection) {
    die("Connection failed: " . mysqli_connect_error());
}
if (!isset($_SESSION['email']) || empty($_SESSION['email'])) {
    header("Location: log.php");
    exit();
}
?>
<html>
<head>
    <title>Home</title>
</head>
<body>
    <h1>Welcome <?php echo htmlspecialchars($_SESSION['email'], ENT_QUOTES, 'UTF-8'); ?>!</h1>
    <p>You are logged in.</p>
    <a href="logout.php">Logout</a>
</body>
</html>