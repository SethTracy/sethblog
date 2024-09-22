<?php include_once('dbconnect.php'); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <title>Register</title>
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
        input[type="text"],
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
        <h2>Register</h2>
        <form action="" method="POST">
            <p>Enter First Name: <input type="text" name="fname" required></p>
            <p>Enter Last Name: <input type="text" name="lname" required></p>
            <p>Enter Email: <input type="email" name="email" required></p>
            <p>Enter Password: <input type="password" name="pass" required></p>
            <input type="submit" value="Register" name="reg">
        </form>
        <a href="login.php">Already have an account? Login here</a>

        <?php
        if (isset($_POST['reg'])) {
            $first_name = $_POST['fname'];
            $last_name = $_POST['lname'];
            $email = $_POST['email'];
            $pass_word = password_hash($_POST['pass'], PASSWORD_DEFAULT);

            $query = "INSERT INTO account (firstname, lastname, email, password) VALUES ('$first_name', '$last_name', '$email', '$pass_word')";
            $stmt = mysqli_query($conn, $query);

            if ($stmt) {
                echo "<script>alert('Registration successful! Redirecting to login page...');</script>";
                echo "<script>window.location.href='login.php';</script>";
                exit();
            } else {
                echo "<p style='text-align: center; color: red;'>Error: " . mysqli_error($conn) . "</p>";
            }
        }
        ?>
    </div>
</body>
</html>
