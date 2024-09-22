<?php 
include_once('dbconnect.php'); 
session_start();
if (isset($_GET['action']) && $_GET['action'] == 'logout') {
    session_destroy();
    header("Location: index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="stylesss.css">
    <title>STB Blog</title>
</head>
<body>
    <nav>
        <ul>
            <li><a href="index.php">STB Blog</a></li>
            <?php if (isset($_SESSION['user_id'])) { ?>
                <li><a href="post.php">Create Post</a></li>
                <li><a href="index.php?action=logout">Logout</a></li>
            <?php } else { ?>
                <li><a href="login.php">Login</a></li>
            <?php } ?>
        </ul>
    </nav>

    <div class="cover-image">
        <div class="search-bar">
            <input type="text" id="searchQuery" placeholder="Search blog posts...">
            <button onclick="searchPost()">Search</button>
        </div>
    </div>

    <div class="blog-container">
        <h1>Blogs</h1>

        <?php 
        $sql = "SELECT posts.title, posts.website_link, CONCAT(account.firstname, ' ', account.lastname) AS author 
                FROM posts 
                JOIN account ON posts.author_id = account.id";
        $result = mysqli_query($conn, $sql);

        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) { 
                $post_id = preg_replace('/[^a-zA-Z0-9]/', '', $row['title']);
        ?>
                <div class="blog-post" id="<?php echo $post_id; ?>">
                    <iframe src="<?php echo $row['website_link']; ?>"></iframe>
                    <h2><a href="<?php echo $row['website_link']; ?>" target="_blank"><?php echo $row['title']; ?></a></h2>
                    <p>Posted by: <?php echo $row['author']; ?></p>
                </div>
            <?php }
        } else {
            echo "<p>No blogs available.</p>";
        }
        ?>
    </div>

    <script>
        function searchPost() {
            var query = document.getElementById('searchQuery').value.toLowerCase();
            var posts = document.getElementsByClassName('blog-post');
            var found = false;

            for (var i = 0; i < posts.length; i++) {
                var postTitle = posts[i].getElementsByTagName('h2')[0].innerText.toLowerCase();
                if (postTitle.includes(query)) {
                    posts[i].scrollIntoView({ behavior: 'smooth' });
                    found = true;
                    break;
                }
            }

            if (!found) {
                alert('No matching blog post found.');
            }
        }
    </script>

</body>
</html>
