<?php
include('dbconnect.php');
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    echo "<p>You must be logged in to create a blog post.</p>";
    echo '<a href="login.php">Login</a>';
    exit();
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $website_link = mysqli_real_escape_string($conn, $_POST['website_link']);
    $poster_name = mysqli_real_escape_string($conn, $_POST['poster_name']); // New field for poster's name
    $author_id = $_SESSION['user_id']; // ID of the logged-in user

    // Insert the post into the database
    $sql = "INSERT INTO posts (title, website_link, author_id, poster_name) VALUES ('$title', '$website_link', '$author_id', '$poster_name')";
    
    if (mysqli_query($conn, $sql)) {
        echo "<p>Blog post created successfully!</p>";
        echo '<a href="index.php">Go to Blog Posts</a>';
    } else {
        echo "<p>Error: " . mysqli_error($conn) . "</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Blog Post</title>
    <link rel="stylesheet" href="styles2.css">
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        nav {
            background-color: #333;
            color: white;
            padding: 10px;
            position: fixed;
            width: 100%;
            top: 0;
            z-index: 1000; /* Ensure the navbar is on top */
            text-align: center; /* Center the text in the navbar */
        }
        nav ul {
            list-style-type: none;
            margin: 0;
            padding: 0;
            display: inline-block; /* Align items in a line */
        }
        nav ul li {
            display: inline;
            margin-right: 30px; /* Space between links */
        }
        nav ul li a {
            color: white;
            text-decoration: none;
            padding: 5px 10px; /* Add some padding */
            transition: background-color 0.3s; /* Smooth transition for background */
        }
        nav ul li a:hover {
            background-color: #555; /* Darker background on hover */
            border-radius: 5px; /* Slightly rounded corners */
        }
        .content {
            margin-top: 60px; /* Space for fixed navbar */
            padding: 20px;
        }
    </style>
</head>
<body>
    <nav>
        <ul>
            <li><a href="index.php">Home</a></li>
            <?php if (isset($_SESSION['user_id'])) { ?>
                <li><a href="post.php">Create Post</a></li>
                <li><a href="account.php">My Account</a></li>
                <li><a href="index.php?action=logout">Logout</a></li>
            <?php } else { ?>
                <li><a href="login.php">Login</a></li>
                <li><a href="register.php">Register</a></li>
            <?php } ?>
        </ul>
    </nav>

    <div class="content">
        <h1>Create a Blog Post</h1>
        <form action="post.php" method="POST">
            <label for="title">Blog Title:</label>
            <input type="text" name="title" id="title" required><br><br>

            <label for="website_link">Website Link:</label>
            <input type="text" name="website_link" id="website_link" required><br><br>
            
            <label for="poster_name">Poster Name:</label>
            <input type="text" name="poster_name" id="poster_name" required><br><br> <!-- New field -->

            <input type="submit" value="Submit">
        </form>
    </div>
</body>
</html>
