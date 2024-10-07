<?php
include_once('dbconnect.php');
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];

    $checkQuery = "SELECT * FROM users WHERE email='$email'";
    $checkResult = mysqli_query($conn, $checkQuery);

    if (mysqli_num_rows($checkResult) == 0) {
        $sql = "INSERT INTO users (email, password, firstname, lastname, role) 
                VALUES ('$email', '$password', '$firstname', '$lastname', 'customer')";
        if (mysqli_query($conn, $sql)) {
            $_SESSION['user_id'] = mysqli_insert_id($conn);
            header("Location: login.php"); 
            exit();
        } else {
            $error = "Error: " . $sql . "<br>" . mysqli_error($conn);
        }
    } else {
        $error = "Email already exists.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="stylesregister.css">
</head>
<body>
    <div class="container">
        <div class="image-container">
            <img id="background-image" src="maincover.jpg" alt="">
        </div>
        <div class="register-container">
            <form action="register.php" method="POST" class="register-form">
                <input type="text" name="firstname" placeholder="First Name" required><br>
                <input type="text" name="lastname" placeholder="Last Name" required><br>
                <input type="email" name="email" placeholder="Email" required><br>
                <input type="password" name="password" placeholder="Password" required><br>
                <input type="submit" value="Sign Up">
            </form>
            <?php if (isset($error)) { echo "<p class='error'>$error</p>"; } ?>
            <p>Already have an account? <a href="login.php">Login here</a></p>
        </div>
    </div>

    <script>
    const images = [
        'chowcopy1.jpg', 
            'chowcopy2.jpg',
            'chowcopy3.jpg',
            'chowcopy1.jpg',
            'chowcopy3.jpg'
    ];
    
    let currentIndex = 0;
    
    function changeBackgroundImage() {
        const imgElement = document.getElementById('background-image');
        
        imgElement.style.opacity = 0;

        setTimeout(() => {
            currentIndex = (currentIndex + 1) % images.length;
            imgElement.src = images[currentIndex];
            
            imgElement.style.opacity = 1;
        }, 500);
    }
    
    setInterval(changeBackgroundImage, 3000); 
</script>

</body>
</html>
