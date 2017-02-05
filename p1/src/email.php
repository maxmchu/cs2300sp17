<?php
//Checks whether or not a name input is valid, returns true/false accordingly
function checkName($input){
    
    $input = trim($input);
    $input = strtolower($input);
    //Names should include at least one letter
    if(!preg_match("/[a-z]+/",$input)){
        print("<p class = \"error-text\">Invalid name. Please only use unaccented letters in your name.</p>");
        return false;
    }
    else{
        $input = ucfirst($input);
        return true;
    }
    
}

//Checks whether or not an email is valid, returning true/false accordingly
function checkEmail($input){
    
    $input = trim($input);
    $input = strtolower($input);
    //Email usernames, subdomains, domains, and suffixes are usually at least 2 characters.
    if(!preg_match("/[a-z]{2,}+[a-z,.,0-9]*@[a-z]{2,}[a-z,.,0-9]*.[a-z]{2,}/",$input)){
        print("<p class = \"error-text\">Invalid email address. Please try again.</p>");
        return false;
    }
    else{
        return true;
    }
    
}

//Assumes that inputs are valid, and modifies them accordingly.
function processName($input){
    $input = trim($input);
    $input = strtolower($input);
    $input = ucfirst($input);
    return $input;
}

if(isset($_POST["submit"])){
    
    $fNameInput = $_POST["fname"];
    $lNameInput = $_POST["lname"];
    $emailInput = $_POST["email"];
    
    $fNameValid = checkName($fNameInput);
    $lNameValid = checkName($lNameInput);
    $emailValid = checkEmail($emailInput);
    
    
    if(!isset($_POST["type"])){
        print("<p class = \"error-text\">Please tell us whether or not you're a student.</p>");
    }
    
    //If all the inputs are valid, send out the email
    
    if($fNameValid && $lNameValid && $emailValid && isset($_POST["type"])){
        $fNameInput = processName($fNameInput);
        $lNameInput = processName($lNameInput);
        
        print("<p>Thank you, $fNameInput $lNameInput!</p>");
        $subj = "Thanks for signing up! - The College Meme Group Guide";
        $msghead = "Dear $fNameInput, <br/>";
        $message = "Thank you signing up for updates from the Unofficial Guide to College Meme Groups. Unfortunately, this is the only email you will receieve, because we don't have a database to store your information yet. Oops.";
        $message = $msghead.=$message;
        mail($emailInput,$subj,$message);
    }
    
}
?>

