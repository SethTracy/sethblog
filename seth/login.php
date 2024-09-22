<?php 
include_once('dbconnect.php'); 
session_start(); // Start the session
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 400px;
            margin: 50px auto;
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h2 {
            text-align: center;
            color: #333;
        }
        p {
            margin: 15px 0;
        }
        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 10px;
            margin: 5px 0 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }
        input[type="submit"] {
            width: 100%;
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 10px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }
        input[type="submit"]:hover {
            background-color: #45a049;
        }
        a {
            text-align: center;
            display: block;
            margin-top: 20px;
            color: #333;
            text-decoration: none;
        }
        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Login</h2>
        <form action="" method="post">
            <p>Enter Email: <input type="email" name="email" required></p>
            <p>Enter Password: <input type="password" name="pass" required></p>
            <input type="submit" value="Login" name="log">
            <a href="register.php">Don't have an account? Register here</a>
        </form>

        <?php
        if (isset($_POST['log'])) {
            $email = $_POST['email'];
            $pass_word = $_POST['pass'];

            $query = "SELECT * FROM account WHERE email = ?";
            $stmt = mysqli_prepare($conn, $query);

            if ($stmt) {
                mysqli_stmt_bind_param($stmt, "s", $email);
                mysqli_stmt_execute($stmt);
                $result = mysqli_stmt_get_result($stmt);

                if (mysqli_num_rows($result) == 1) {
                    $user = mysqli_fetch_assoc($result);

                    if (password_verify($pass_word, $user['password'])) {
                        // Set the session variable upon successful login
                        $_SESSION['user_id'] = $user['id']; // Store user ID in session
                        echo "<script>window.alert('Login successful'); window.location.href='index.php';</script>";
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
    </div>
</body>
</html>
