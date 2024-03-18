<?php
session_start();

// Connexion à la base de données
$servername = "127.0.0.1";
$username = "root";
$password = "";
$database = "smartspenddb";

// Créer la connexion
$conn = new mysqli($servername, $username, $password, $database);

// Vérifier la connexion
if ($conn->connect_error) {
    die("La connexion a échoué : " . $conn->connect_error);
}

$message = ""; // Initialiser le message pour éviter des erreurs undefined variable

if (isset($_POST['login'])) {
    $email = $conn->real_escape_string($_POST['email']);
    $mdp = $conn->real_escape_string($_POST['password']);
    $MotDePasse = md5($mdp);

    $sql = "SELECT * FROM Utilisateurs WHERE Email='$email' AND MotDePasse='$MotDePasse'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $_SESSION['user_email'] = $email; // Stocker l'email dans la session pour l'utiliser plus tard
        header("Location: User.php"); // Remplacer 'User.php' par le chemin de l'interface utilisateur
        exit();
    } else {
        $message = "<div class='erreur'>Mauvais nom d'utilisateur ou mot de passe.</div>";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.0/css/line.css">
    <link rel="stylesheet" href="CSS/login.css">
    <title>Login & Registration Form</title>
</head>

<body>
    <div class="container">
        <div class="forms">
            <div class="form login">
                <span class="title">Login</span>

                <form action="" method="post">
                    <?php if (!empty($message)) echo $message; ?>
                    <div class="input-field">
                        <input type="text" name="email" placeholder="Enter your email" required>
                        <i class="uil uil-envelope icon"></i>
                    </div>
                    <div class="input-field">
                        <input type="password" name="password" class="password" placeholder="Enter your password" required>
                        <i class="uil uil-lock icon"></i>
                        <i class="uil uil-eye-slash showHidePw"></i>
                    </div>

                    <div class="checkbox-text">
                        <div class="checkbox-content">
                            <input type="checkbox" id="logCheck">
                            <label for="logCheck" class="text">Remember me</label>
                        </div>
                        <a href="#" class="text">Forgot password?</a>
                    </div>

                    <div class="input-field button">
                        <input type="submit" name="login" value="Login">
                    </div>
                </form>

                <div class="login-signup">
                    <span class="text">Not a member?
                        <a href="#" class="text signup-link">Signup Now</a>
                    </span>
                </div>
            </div>
        </div>
    </div>
    <script src="script.js"></script>
</body>

</html>
