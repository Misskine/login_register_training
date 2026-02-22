<?php 
$connection = mysqli_connect("localhost", "root", "", "data");
if (!$connection) {
    die("Connection failed: " . mysqli_connect_error());
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    if (empty($email) || empty($password)) {
        echo "Email and password are required.";
        exit;
    }
    if (!filtrerpassword($password)) {
        echo "Password must be at least 8 characters long and contain a mix of letters and numbers.";
        exit;
    }

    $password_hash = password_hash($password, PASSWORD_DEFAULT);

    // Check if email already exists
    $check_sql = "SELECT email FROM users WHERE email = ?";
    $check_stmt = mysqli_prepare($connection, $check_sql);
    mysqli_stmt_bind_param($check_stmt, "s", $email);
    mysqli_stmt_execute($check_stmt);
    $result = mysqli_stmt_get_result($check_stmt);
    
    if (mysqli_num_rows($result) > 0) {
        echo "Email already registered. Please use a different email.";
        mysqli_stmt_close($check_stmt);
        exit;
    }
    mysqli_stmt_close($check_stmt);
    
    $sql = "INSERT INTO users (email, password_hash) VALUES (?, ?)";
    $stmt = mysqli_prepare($connection, $sql);
    mysqli_stmt_bind_param($stmt, "ss", $email, $password_hash);
    
    if (mysqli_stmt_execute($stmt)) {
        echo "Registration successful!";
        header("Location: log.php");
        exit();
    } else {
        echo "Error: " . mysqli_error($connection);
    }
    mysqli_stmt_close($stmt);
}
function filtrerpassword($password) {
    if (strlen($password) < 8) {
        return false;
    }
    if (!preg_match('/[A-Za-z]/', $password) || !preg_match('/\d/', $password)) {
        return false;
    }
    return true;
}

?>
<html>
<head>
    <title>Registration</title>     
</head>
<body>
    <h2>Registration Form</h2>
    <form method="post" action="reg.php">
        <label for="email">Email:</label><br>
        <input type="email" id="email" name="email"><br><br>
        <label for="password">Password:</label><br>
        <input type="password" id="password" name="password"><br><br>
        <input type="submit" value="Register">
            <a href="log.php">Login</a>
    </form>
    </body>
    </html>