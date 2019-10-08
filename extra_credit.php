<!doctype html>
<html>
    <head>
        <style> 
            .required{color:red;}
            .message{color:green}
        </style>
    </head>
    <body>
        <?php
            $nameError = $emailError = "";
            $name = $email = $phone = $job = $gender = $feeling = "";      
            
            if ($_SERVER["REQUEST_METHOD"] == "POST"){
                if(empty(trim($_POST["name"])))
                { $nameError= "Name is required!";}
                else{
                    $name = test_input($_POST["name"]);
                }

                if (empty(trim($_POST["email"]))) {
                    $emailError = "Email is required";
                  } else{ $email = test_input($_POST["email"]);}

                if (empty ($_POST["jobSelect"])) {
                $jobSelect = "";
                } else{ $jobSelect = test_input($_POST["jobSelect"]);}

                if (empty ($_POST["phone"])) {
                    $phone= "";
                    } else{ $phone = test_input($_POST["phone"]);}

                if (empty ($_POST["feeling"])) {
                    $feeling= "";
                    } else{ $feeling = test_input($_POST["feeling"]);}
                if (empty ($_POST["gender"])) {
                    $gender= "";
                    } else{ $gender = test_input($_POST["gender"]);}

            }
            
            //sanitize data (we must sanitize data all the times)
            function test_input($value){
                $value = trim($value);
                $value = stripslashes($value);
                $value = htmlspecialchars($value);
                return $value;
            }
        
        ?>
         <!-- End PHP -->
        <h1>Information Form</h1>
        <p><span class="required">* means required </span></p>
        <form method="post" action="extra_credit.php">

            Name: <input type="text" name="name"><span class="required"> *<?php echo $nameError;?></span><br/><br/>
            Email: <input type="text" name="email"><span class="required"> * <?php echo $emailError;?></span><br><br/>
            Phone: <input type="text" name="phone"><br><br/>
            Ocupation: 
            
            <select name="jobSelect">
                <option checked>-Select-</option>
                <option name ="job" value="Doctor">Doctor</option> 
                <option name ="job" value="Nurse">Nurse</option> 
                <option name ="job" value="Engineer">Engineer</option>
                <option name ="job" value="Pilot">Pilot</option> 
                <option name ="job" value="Teacher">Teacher</option>
            </select>
            <br/><br/>
            Gender:
            <input type="radio" name="gender" value="female"> Female
            <input type="radio" name="gender" value="male"> Male
            <input type="radio" name="gender" value="other"> Other
            <br/><br/>
            How are you feeling today? <br/>
            <input type="checkbox" name="feeling" value="Happy"> Happy<br>
            <input type="checkbox" name="feeling" value="Good"> Good<br>
            <input type="checkbox" name="feeling" value="Bad"> Bad<br>
            <input type="checkbox" name="feeling" value="Sick">Sick<br> <br>
            <input type="submit" name="submit" value="Submit"> 
        </form>
            <br>
        <?php 
         if ((isset($_POST['submit']))  && (!empty($name)) && (!empty($email))) { 
            echo '<div class="message">Submitted successfully:'; echo "<br>";

            echo "Email";echo "<br>"; echo "Name"; echo "<br>";
            if(!empty($phone)){echo "Phone";}; echo "<br>";
            if(!empty($feeling)){echo "Feeling";}; echo "<br>";
            if(!empty($jobSelect)){echo "Occupation";}; echo "<br>";
            if(!empty($gender)){echo "Gender";}; echo "<br>";
            echo "******************** <br>"; echo "The Values are:"; echo "<br>";     
            echo $email; echo "<br>"; 
            echo $name; echo "<br>"; 
            echo $phone; echo "<br>"; 
            echo $feeling; echo "<br>"; 
            echo $jobSelect; echo "<br>"; 
            echo $gender; echo "<br>";         
        }  ;
       
        ?>
    <body>

</html>
