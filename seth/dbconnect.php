
    <?php
    $servername ="localhost";
    $username = "root";
    $password ="";
    $dbname ="accounts";

    $conn = mysqli_connect($servername,$username,$password,$dbname);

    if(!$conn){
        die("Connection Failed: " . mysqli_connect_error());
    }
    ?>
 