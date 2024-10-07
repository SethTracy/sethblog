<?php 
include_once('dbconnect.php'); 
session_start(); 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="styleslogin.css">
</head>
<body>
    <div class="container">
        <div class="login-section">
            <div class="login-form">
                <h2>Login</h2>
                <form action="" method="post">
                    <p><input type="email" name="email" placeholder="Email" required></p>
                    <p><input type="password" name="pass" placeholder="Password" required></p>
                    <input type="submit" value="Login" name="log">
                    <p class="register-text">Don't have an account? <a href="register.php">Register here</a></p>
                </form>

                <hr>
                <p class="or">or</p>
                <div class="social-login">
                    <button class="google-btn">Google</button>
                    <button class="facebook-btn">Facebook</button>
                    <button class="linkedin-btn">LinkedIn</button>
                </div>
            </div>
        </div>
        <div class="image-section">
            <img src="chowking1copy1.jpg" alt="Side Image">
        </div>
    </div>

    <?php
    if (isset($_POST['log'])) {
        $email = $_POST['email'];
        $pass_word = $_POST['pass'];

        $query = "SELECT * FROM users WHERE email = ?";
        $stmt = mysqli_prepare($conn, $query);

        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "s", $email);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);

            if (mysqli_num_rows($result) == 1) {
                $user = mysqli_fetch_assoc($result);

                if (password_verify($pass_word, $user['password'])) {
                    
                    $_SESSION['user_id'] = $user['id']; 
                    $_SESSION['role'] = $user['role'];

                    if ($user['role'] == 'manager') {
                        header("Location: index.php");
                    } else {
                        header("Location: index.php");
                    }
                    exit();
                } else {
                    echo "<script>window.alert('Invalid password');</script>";
                }
            } else {
                echo "<script>window.alert('Email not found');</script>";
            }
        } else {
            echo "<script>window.alert('Error in the query');</script>";
        }
    }
    ?>
</body>
</html>
