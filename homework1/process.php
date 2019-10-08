<?php
 /*    

     $last = $email = $first = "";    
     
   //  if(isset($_POST[""]))
     
     if ($_SERVER["REQUEST_METHOD"] == "POST"){
        if(empty(trim($_POST["last"]))){
            $ $lastError= "First Name is required";
            } else{ $last = test_input($_POST["last"]);}

        if (empty(trim($_POST["email"]))) {
            $emailError = "Email is required";
          } else{ $email = test_input($_POST["email"]);}

        if (empty ($_POST["first"])) {
            $ $firstError= "First Name is required";
            } else{ $first = test_input($_POST["first"]);}
     }
     
     function test_input($value){
        $value = trim($value);
        $value = stripslashes($value);
        $value = htmlspecialchars($value);
        return $value;
      }
    
    if ((isset($_POST["submit"]))  && (!empty($first)) &&(!empty($email)) &&(!empty($last)) ) { 
        echo $email; echo "<br>"; 
      echo $last; echo "<br>"; 
      echo $first; echo "<br>"; }
      else{
          echo "information is not filled out";
      }     
      
    ?>
*/