<?php 
include_once('dbconnect.php'); 
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'manager') {
    header("Location: index.php");
    exit();
}

if (isset($_POST['add_food'])) {
    $name = $_POST['name'];
    $price = $_POST['price'];

    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $image = $_FILES['image'];
        $image_name = $image['name'];
        $image_tmp_name = $image['tmp_name'];
        $image_size = $image['size'];
        $image_ext = strtolower(pathinfo($image_name, PATHINFO_EXTENSION));
        
        $allowed_exts = ['jpg', 'jpeg', 'png', 'gif'];

        if (in_array($image_ext, $allowed_exts)) {
            $upload_dir = 'uploads/';
            $new_image_name = uniqid() . '.' . $image_ext;  
            $image_upload_path = $upload_dir . $new_image_name;

            if (move_uploaded_file($image_tmp_name, $image_upload_path)) {
                $stmt = $conn->prepare("INSERT INTO food_items (name, image_url, price, created_at) VALUES (?, ?, ?, NOW())");
                $stmt->bind_param("ssd", $name, $image_upload_path, $price);

                if ($stmt->execute()) {
                    echo "<script>alert('Food item added successfully!');</script>";
                } else {
                    echo "<script>alert('Error adding food item.');</script>";
                }
                $stmt->close();
            } else {
                echo "<script>alert('Failed to upload image.');</script>";
            }
        } else {
            echo "<script>alert('Invalid image file type. Only JPG, JPEG, PNG, and GIF allowed.');</script>";
        }
    } else {
        echo "<script>alert('Please upload an image file.');</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Food Item</title>
    <link rel="stylesheet" href="stylesfood.css"> 
</head>
<body>
    <nav>
        <img src="textbg.png" alt="Chowking Logo">
        <ul>
            <li><a href="index.php">Home</a></li>
            <li><a href="inventory.php">Manage Inventory</a></li>
            <li><a href="index.php?action=logout">Logout</a></li>
        </ul>
    </nav>

    <div class="content">
        <div class="container">
            <h2>Add New Food Item</h2>
            <form method="post" action="" enctype="multipart/form-data">
                <label for="name">Food Name:</label>
                <input type="text" id="name" name="name" required class="input-red">

                <label for="image">Upload Image:</label>
                <input type="file" id="image" name="image" accept="image/*" required class="input-red">

                <label for="price">Price:</label>
                <input type="number" id="price" name="price" step="0.01" required class="input-red">

                <input type="submit" name="add_food" value="Add Food Item" class="btn-red">
            </form>
        </div>
    </div>
</body>
</html>
