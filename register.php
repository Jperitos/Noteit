<?php
session_start();
include("./Connection/connect.php");

$error = ""; 
$u_name = $u_uname = $u_email = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $u_name = trim($_POST['name']);
    $u_uname = trim($_POST['username']);
    $u_email = trim($_POST['email']);
    $u_password = trim($_POST['password']);
    
    if (empty($u_name) || empty($u_uname) || empty($u_email) || empty($u_password)) {
        $error = "Please fill in all fields.";
    } else {
        $password_hash = password_hash($u_password, PASSWORD_BCRYPT);
        $u_type = 'user';

        $query = "INSERT INTO user (u_name, u_uname, u_email, u_password, u_type) 
                  VALUES (:u_name, :u_uname, :u_email, :u_password, :u_type)";
        $stmt = $pdo->prepare($query);

        $stmt->bindValue(':u_name', $u_name);
        $stmt->bindValue(':u_uname', $u_uname);
        $stmt->bindValue(':u_email', $u_email);
        $stmt->bindValue(':u_password', $password_hash);
        $stmt->bindValue(':u_type', $u_type);

        if ($stmt->execute()) {
            $_SESSION['user_id'] = $pdo->lastInsertId();
            $_SESSION['u_uname'] = $u_uname;
            $_SESSION['u_name'] = $u_name;
            $_SESSION['u_email'] = $u_email;
            $_SESSION['u_type'] = $u_type;

            header("Location: login.php?success=1");
            exit;
        } else {
            $error = "Error: Could not execute the query.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./style/style-home.css">
    <link rel="icon" type="image" href="https://img.icons8.com/color/48/nginx.png">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <title>Register Account</title>
    <style>
        .input-error {
            border: 2px solid red;
        }
        .error-message {
            color: red;
            margin-bottom: 10px;
            margin-top: -30px;
            font-size: 0.8rem;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Note<span>It!</span></h1>
        <div class="nav">     
            <a href="index.php">Home</a>
            <a href="register.php">Register</a>
            <a href="login.php" class="active">Sign In</a>
        </div>
    </div>

    <div class="wrapper">
        <div class="container">
            <div class="left"> 
                <h1>Welcome Back!</h1>
                <img src="img/vector.jpg" alt="Vector Image">
                <p>"The future belongs to those who believe <br>in the beauty of their study goals.”</p>
            </div>
            
            <div class="right">
                <div class="head">
                    <h1>Create an Account</h1>
                    <p>or continue with </p>
                </div>
                
                <div class="icons">
                    <i class='bx bxl-meta'></i>
                    <i class='bx bxl-instagram'></i>
                    <i class='bx bxl-github'></i>
                </div>

                <form action="register.php" method="POST" class="form-register">
                    <label for="name">Name</label><br>
                    <input type="text" name="name" placeholder="Name" 
                        value="<?= htmlspecialchars($u_name) ?>" 
                        class="<?= ($_SERVER['REQUEST_METHOD'] === 'POST' && empty($u_name)) ? 'input-error' : '' ?>"><br>

                    <label for="email">Email</label><br>
                    <input type="text" name="email" placeholder="Email Address" 
                        value="<?= htmlspecialchars($u_email) ?>" 
                        class="<?= ($_SERVER['REQUEST_METHOD'] === 'POST' && empty($u_email)) ? 'input-error' : '' ?>"><br>

                    <label for="username">Username</label><br>
                    <input type="text" name="username" placeholder="Username" 
                        value="<?= htmlspecialchars($u_uname) ?>" 
                        class="<?= ($_SERVER['REQUEST_METHOD'] === 'POST' && empty($u_uname)) ? 'input-error' : '' ?>"><br>

                    <label for="password">Password</label><br>
                    <input type="password" name="password" placeholder="Password" 
                        class="<?= ($_SERVER['REQUEST_METHOD'] === 'POST' && empty($_POST['password'])) ? 'input-error' : '' ?>"><br><br>

                    <?php if (!empty($error)): ?>
                        <div class="error-message"><?= htmlspecialchars($error) ?></div>
                    <?php endif; ?>

                    <button type="submit">Sign Up</button>
                </form>

                <div class="loginTo">
                    <p>Already have an account? <a href="Dashbaord.php">Login Now</a></p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
