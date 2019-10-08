
<?php
    session_start();

    if(! isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] == false){
        header("Location: index.php");
    }
?>
<h1>hekko</h1>
<!DOCTYPE html>
<html>


    <head>
        <link rel="stylesheet" type="text/css" href="welcome.css">
    </head>
    <body>
        <div class="welcome_back">
            <h1 >Welcome Back to 1 Million Cup!</h1>
            <h1>You visited this page 2 times</h1>
        </div>

        <div>
            <h1>Welcome!! </h1>
            <h1> You registered for Des Moines 1 Million Cup organization </h1>
            <h1> This is your first visit</h1>
        </div>


    </body>
</html>