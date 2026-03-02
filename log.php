<?php

$connection = mysqli_connect("localhost", "root", "", "data");
if (!$connection) {
    die("Connexion échouée: " . mysqli_connect_error());
}

$email = $_POST['email'];
$password = $_POST['password'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (empty($email) || empty($password)) {
        $error = "Email et mot de passe sont requis.";
    } else{
        $sql = "SELECT * FROM users WHERE email = '$email'";
        $result = mysqli_query($connection, $sql);
        if (mysqli_num_rows($result) === 1) {
            $user = mysqli_fetch_assoc($result);
            if (password_verify($password, $user['password_hash'])) {
                session_start();
                $_SESSION['email'] = $email;
                $_SESSION['jwt'] = jwt($email);
                echo "Connexion réussie!";
                exit();
            } else {
                $error = "Mot de passe incorrect.";
            }
        } else {
            $error = "Email non trouvé.";
        }
    }
}
function jwt($email) {
    $header = json_encode(['alg' => 'HS256', 'typ' => 'JWT']);
    $payload = json_encode(['email' => $email, 'exp' => time() + 3600]);
    $base64UrlHeader = base64_encode($header);
    $base64UrlPayload = base64_encode($payload);
    $signature = hash_hmac('sha256', "$base64UrlHeader.$base64UrlPayload", 'secret_key', true);
    $base64UrlSignature =base64_encode($signature);
    return "$base64UrlHeader.$base64UrlPayload.$base64UrlSignature";
}
?>

<html>
<head>
    <title>Connexion</title>    
</head>
<body>
    <h2>connexion</h2>
    <form method="POST" action="log.php">
        <label for="email">Email:</label><br>
        <input type="email" id="email" name="email" required><br><br>
        <label for="password">Password:</label><br>
        <input type="password" id="password" name="password" required><br><br>
        <input type="submit" value="Login">
        <a href="reg.php">Register</a>
    </form>
</body>
</html>
