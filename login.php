<?php
session_start();
include("./connection/connect.php"); 

$error = ""; 

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $u_uname = trim($_POST['username']);
    $u_password = trim($_POST['password']);

    $query = "SELECT * FROM user WHERE u_uname = :u_uname LIMIT 1";
    $stmt = $pdo->prepare($query);
    $stmt->bindValue(':u_uname', $u_uname);
    $stmt->execute();

    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        if (password_verify($u_password, $user['u_password'])) {
            $_SESSION['user_id'] = $user['u_id'];
            $_SESSION['u_uname'] = $user['u_uname'];
            $_SESSION['u_name'] = $user['u_name'];
            $_SESSION['u_email'] = $user['u_email'];
            $_SESSION['u_type'] = $user['u_type'];

            if ($user['u_type'] == 'admin') {
                header("Location: adminDash.php"); 
            } else {
                header("Location: Dashboard.php"); 
            }
            exit;
        } else {
            $error = "Invalid username or password."; 
        }
    } else {
        $error = "User not found.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image" href="https://img.icons8.com/color/48/nginx.png">
    <link rel="stylesheet" href="./style/style-home.css">
    <title>Sign In</title>
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

    <section id="form">
        <div class="wrapper-form">
            <form action="login.php" method="POST" class="login-form">
                <h1>Note<span>It!</span></h1>
                <br>
                <label for="username">Username</label> <br>
                <input type="text" id="username" name="username" placeholder="Username"> <br><br>
                <label for="password">Password</label><br>
                <input type="password" id="password" name="password" placeholder="Password">

                <div class="remember">
                    <input type="checkbox" id="sign-checkbox" name="sign-checkbox">
                    <label for="sign-checkbox">Sign Me In</label>
                    <a href="#">Forgot Password?</a>
                </div>
                <br>

                <?php if ($error): ?>
                    <p style="color: red; display:flex; justify-content:center; font-size: 14px; margin-top: -40px; margin-bottom: 10px;"><?php echo $error; ?></p>
                <?php endif; ?>

                <div class="btn-sign">
                    <button type="submit">SIGN IN</button>    
                </div>
            </form>
        </div>
    </section>
</body>
</html>
