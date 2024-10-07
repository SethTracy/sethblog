<?php 
include_once('dbconnect.php'); 
session_start();
if (isset($_GET['action']) && $_GET['action'] == 'logout') {
    session_destroy();
    header("Location: index.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['add_stock'])) {
        $item_name = $_POST['item_name'];
        $amount = $_POST['amount'];
        $expiration_date = $_POST['expiration_date'];
        $category = $_POST['category'];

        $target_dir = "uploads/";
        $target_file = $target_dir . basename($_FILES["item_image"]["name"]);
        $upload_ok = 1;
        $image_file_type = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        $check = getimagesize($_FILES["item_image"]["tmp_name"]);
        if ($check === false) {
            echo "File is not an image.";
            $upload_ok = 0;
        }

        if ($_FILES["item_image"]["size"] > 2000000) {
            echo "Sorry, your file is too large.";
            $upload_ok = 0;
        }

        if (!in_array($image_file_type, ['jpg', 'png', 'jpeg', 'gif'])) {
            echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
            $upload_ok = 0;
        }

        if ($upload_ok == 0) {
            echo "Sorry, your file was not uploaded.";
        } else {
            if (move_uploaded_file($_FILES["item_image"]["tmp_name"], $target_file)) {
                $sql = "INSERT INTO inventory (item_name, amount, expiration_date, category, image_url) VALUES ('$item_name', '$amount', '$expiration_date', '$category', '$target_file')";
                mysqli_query($conn, $sql);
            } else {
                echo "Sorry, there was an error uploading your file.";
            }
        }
    }

    if (isset($_POST['update_stock'])) {
        $item_id = $_POST['item_id'];
        $updated_amount = $_POST['updated_amount'];
        $sql = "UPDATE inventory SET amount = amount + '$updated_amount' WHERE id = '$item_id'";
        mysqli_query($conn, $sql);
    }

    if (isset($_POST['use_stock'])) {
        $item_id = $_POST['item_id'];
        $used_amount = $_POST['used_amount'];
        if ($used_amount > 0) {
            $sql = "UPDATE inventory SET amount = amount - '$used_amount' WHERE id = '$item_id'";
            mysqli_query($conn, $sql);
        } else {
            echo "Used amount must be greater than zero.";
        }
    }

    if (isset($_POST['delete_food'])) {
        $food_id = $_POST['food_id'];
        $sql = "DELETE FROM food_items WHERE id = '$food_id'";
        mysqli_query($conn, $sql);
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chowking Inventory Management</title>
    <link rel="stylesheet" href="stylesinventory.css">
</head>
<body>
    <nav>
        <img src="textbg.png" alt="Chowking Logo">
        <ul>
            <?php if (isset($_SESSION['user_id'])) { ?>
                <?php if ($_SESSION['role'] === 'manager') { ?>
                    <li><a href="add_food.php">Add New Food</a></li>
                    <li><a href="index.php">View Menu</a></li>
                    <li><a href="inventory.php">Manage Inventory</a></li>
                <?php } ?>
                <li><a href="index.php?action=logout">Logout</a></li>
            <?php } else { ?>
                <li><a href="login.php">Login</a></li>
            <?php } ?>
        </ul>
    </nav>

    <div class="content">
        <div class="sidebar">
            <p>INVENTORY</p>
            <a href="#freezer-section">Freezer</a>
            <a href="#chiller-section">Chiller</a>
            <a href="#stockroom-section">Stockroom</a>
        </div>

        <div class="main-content">
            <h1>Inventory Management</h1>
            <h2>Available Food Items</h2>
            <div class="food-container">
                <?php 
                $sql = "SELECT * FROM food_items";
                $result = mysqli_query($conn, $sql);
                if (mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) { 
                ?>
                    <div class="food-item">
            <img src="<?php echo $row['image_url']; ?>" alt="<?php echo $row['name']; ?>" class="food-image">
               <h2><?php echo $row['name']; ?></h2>
            <p>Price: ₱<?php echo number_format($row['price'], 2); ?></p>
                 <form method="POST" action="">
                 <input type="hidden" name="food_id" value="<?php echo $row['id']; ?>">
                 <button type="submit" name="delete_food" class="delete-button">Delete Food</button>
                </form>
            </div>
                <?php 
                    }
                } else {
                    echo "<p>No food items available.</p>";
                }
                ?>
            </div>

            <h2>Add Stock</h2>
            <form method="POST" action="" class="add-stock-form" enctype="multipart/form-data">
                <input type="text" name="item_name" placeholder="Item Name" required>
                <input type="number" name="amount" placeholder="Amount" required>
                <input type="date" name="expiration_date" required>
                <input type="file" name="item_image" accept="image/*" required>
                <select name="category" required>
                    <option value="Freezer">Freezer</option>
                    <option value="Chiller">Chiller</option>
                    <option value="Stockroom">Stockroom</option>
                </select>
                <button type="submit" name="add_stock">Add Stock</button>
            </form>

            <h2 id="freezer-section">Freezer</h2>
            <?php displayInventory('Freezer', $conn); ?>

            <h2 id="chiller-section">Chiller</h2>
            <?php displayInventory('Chiller', $conn); ?>

            <h2 id="stockroom-section">Stockroom</h2>
            <?php displayInventory('Stockroom', $conn); ?>
        </div>
    </div>
</body>
</html>

<?php 
function displayInventory($category, $conn) {
    $stmt = $conn->prepare("SELECT * FROM inventory WHERE category=?");
    $stmt->bind_param("s", $category); 
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo "<div class='inventory-container'>";
        while ($row = $result->fetch_assoc()) {
            echo "<div class='inventory-item'>
                    <img src='{$row['image_url']}' alt='{$row['item_name']}' class='inventory-image'>
                    <h3>{$row['item_name']}</h3>
                    <p>Amount: {$row['amount']}</p>
                    <p>Expiry: {$row['expiration_date']}</p>
                    <form method='POST' action=''>
                        <input type='hidden' name='item_id' value='{$row['id']}'>
                        <input type='number' name='updated_amount' placeholder='Add amount' required class='amount-input'>
                        <button type='submit' name='update_stock' class='update-button'>Update</button>
                    </form>
                    <form method='POST' action=''>
                        <input type='hidden' name='item_id' value='{$row['id']}'>
                        <input type='number' name='used_amount' placeholder='Use amount' required class='amount-input'>
                        <button type='submit' name='use_stock' class='use-button'>Use</button>
                    </form>
                </div>";
        }
        echo "</div>";
    } else {
        echo "<p>No items in $category.</p>";
    }

    $stmt->close();
}
?>
