<?php

function checkName($input){
    
    $input = trim($input);
    $input = strtolower($input);
    if(!preg_match("/[a-z]+/",$input)){
        print("<p class = \"error-text\">Invalid name. Please only use unaccented letters in your name.</p>");
        return false;
    }
    else{
        $input = ucfirst($input);
        return true;
    }
    
}

function checkEmail($input){
    
    $input = trim($input);
    $input = strtolower($input);
    if(!preg_match("/[a-z]{2,}+[a-z,.,0-9]*@[a-z]{2,}[a-z,.,0-9]*.[a-z]{2,}/",$input)){
        print("<p class = \"error-text\">Invalid email address. Please try again.</p>");
        return false;
    }
    else{
        return true;
    }
    
}

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
    
    if($fNameValid && $lNameValid && $emailValid && isset($_POST["type"])){
        $fNameInput = processName($fNameInput);
        $lNameInput = processName($lNameInput);
        
        print("<p>Thank you, $fNameInput $lNameInput!</p>");
        $subj = "Thanks for signing up! - The College Meme Group Guide";
        $message = "Thank you signing up for updates from the Unofficial Guide to College Meme Groups. Unfortunately, this is the only email you will receieve, because we don't have a database to store your information yet. Oops.";
        mail($emailInput,$subj,$message);
    }
    
}

?>

