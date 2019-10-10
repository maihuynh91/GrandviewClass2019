<!DOCTYPE html>
<html>
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/notie/4.3.1/notie.min.css">
<!-- use prepare to avoid hacker drops the table, SQL injection
    if it exist, show message, if not exits => call Mailchim to add data 
    Mailchim: create kurel request ??
    https://mailchimp.com/developer/reference/lists/
-->

<head>
    <title>Sign In Form</title>
    <link rel="stylesheet" type="text/css" href="myStyle.css" />
</head>

<body>

    <div class="div1">
        <div style="text-align: center"><img src="image.png" /></div>
        <br>

        <div>
            <h1>Sign In Station</h1>
            <p>Sign-up for reminders on your favorite event of the week, 1 Million Cups Des Moines.
                Once you're in the cool club, join us WEDNESDAY from 8-9:30AM in downtown Des Moines </p>
        </div>

        <br><br>
        <form class="form" action="index.php" method="POST">
            <label>Email Address</label> <br /><br />
            <input type="email" name="email">
            <br /><br />
            <label> First Name</label> <br /><br />
            <input type="text" name="first">
            <br /><br />
            <label> Last Name</label><br /><br />
            <input type="text" name="last"> <br /><br />
            <label> How did you hear about 1 Million Cups?</label><br /><br />
            <input type="text" name="reason"><br /><br />
            <p><button type="submit" name="addName" id="btn"> Submit</button></p>
        </form>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/notie/4.3.1/notie.min.js"></script>
    <?php

    include_once("credentials.php");
    include_once("mailchimp_setup.php");
    session_start();
    $conn = mysqli_connect($servername, $username, $password, $dbname);

    if (!$conn) {
        create_alert('error', "Connection failed: " . mysqli_connect_error());
        exit();
    } 
    if (isset($_POST["addName"])) {
        $firstN = htmlspecialchars($_POST["first"]);
        $email = htmlspecialchars($_POST["email"]);
        $lastN = htmlspecialchars($_POST["last"]);
        $reason = htmlspecialchars($_POST["reason"]);
        //check if email input is empty
        if (empty(trim($_POST["email"]))) {
            create_alert('error', "Email must be filled out!");
            exit();
        } else {
            $visit = 1;

            $sql = $conn->prepare("SELECT * FROM millionCup WHERE email = ?");
            $sql->bind_param("s", $email);
            $sql->execute();
            $result = $sql->get_result();

            //$result = mysqli_query($conn, $sql);
            // $resultCheck = mysqli_num_rows($result);
            if ($result->num_rows < 1) {
                //insert into db
                $stmt = $conn->prepare("INSERT INTO millionCup(firstname, lastname, email, reason, visit) VALUES (?,?,?,?,?);");
                $stmt->bind_param("ssssi", $firstN, $lastN, $email, $reason, $visit);
                $stmt->execute();
                $result2 = $stmt->get_result();
                $data = [
                    'email'     => $email,
                    'status'    => 'subscribed',
                    'firstname' => $firstN,
                    'lastname'  => $lastN
                ];
                syncMailchimp($data);
                create_alert('success', "Welcome to Million Cup! You registered sucessfully.");
            } else {
                while ($row = $result->fetch_assoc()) {
                    $email = $row["email"];
                    $visit = $row["visit"] + 1;
                    $update_visit_stmt = "UPDATE millioncup SET visit = '$visit' WHERE email = '$email'";
                    if ($conn->query($update_visit_stmt) === FALSE) {
                        echo "Cannot update '$update_visit_stmt'";
                    } else {
                        echo "Welcome Back " . $email . " !";
                    }
                }
            };
            $conn->close();
            echo "You have visited $visit times";
            exit();
        }
    }
    function create_alert($type, $message)
    {
        echo <<<EOL
        <script>
            notie.alert({
            time:4,
            type:"$type",
            text:"$message"
            })
        </script>
        EOL;
    }
    ?>

</body>

</html>