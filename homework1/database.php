<?php
//creating database webClass 
/*
    $link = mysqli_connect("localhost", "root", "");
    
    // Check connection
    if($link === false){
        die("ERROR: Could not connect. " . mysqli_connect_error());
    }
    
    $sql = "CREATE DATABASE webClass";
    if(mysqli_query($link, $sql)){
        echo "Database created successfully";
    } else{
        echo "ERROR: Could not able to execute $sql. " . mysqli_error($link);
    }    
    // Close connection
    mysqli_close($link);
    how connect to db to select for data, and infor for the mailchim implementation
*/
?>

 
<?php
include_once("credentials.php");

//using MySQLi Procedural
   
    #create connection
    $conn = mysqli_connect($servername,$username,$password,$dbname);
    //check connection
    if(!$conn){ die("Connection failed: " . mysqli_connect_error()); }

    $sql = "CREATE TABLE MillionCup(
        id int(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        firstname VARCHAR(20) NOT NULL,
        lastname VARCHAR(20) NOT NULL,
        email VARCHAR(50),
        reason VARCHAR(200),
        visit int(10)
    )";

    if (mysqli_query($conn, $sql)) {
        echo "Table MillionCup created successfully";
    } else {
        echo "Error creating table: " . mysqli_error($conn);
    }
    
    mysqli_close($conn);

?>

