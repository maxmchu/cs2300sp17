<?php

$validfilters = array("cscore","ai","se","systems","renaissance");

if (isset($_POST['search'])){
    $queryInput = $_POST['field-search'];
    preg_replace('/[^a-zA-z ]+/','',$queryInput);
    $filters = array();

    if(isset($_POST['filter'])){
        foreach($_POST['filter'] as $filter){
            if (in_array($filter,$validfilters)){
                $filters[] = $filter;
            }
        }
        sort($filters, SORT_STRING);
    }

    echo '<p class = "form-output">Search results for "' . $queryInput . '":</p>';

    displayClasses($queryInput, $filters);
}

if (isset($_POST['view'])){
    displayClasses("",null);
}

if (isset($_POST['add-class'])){
    //validate department abbrev.
    $deptValid = false;
    $deptInput = strtoupper($_POST['field-course-dept']);
    preg_replace('/[^A-Z]+/','',$deptInput);
    if (preg_match('/[A-Z]{2,5}/', $deptInput)){
        $deptValid = true;
    }
    else{
        echo '<p class = "error">Invalid course department abbreviation. Must be 2-5 letters.</p>';
    }

    //valid course number
    $numValid = false;
    $numInput = $_POST['field-course-number'];
    preg_replace('/[^0-9]+/','',$numInput);
    if (preg_match('/[0-9]{4}/', $numInput)){
        $numValid = true;
    }
    else{
        echo '<p class = "error">Invalid course number. Must be 4 digits.';
    }
    $numInput = intval($numInput);

    //validate course name
    $nameValid = false;
    $nameInput = $_POST['field-course-name'];
    //I use | as a delimiter, so get rid of it. Course titles can be wild, so filtering just on string should be sufficient.
    $nameInput = filter_input(INPUT_POST, 'field-course-name', FILTER_SANITIZE_STRING);
    preg_replace('/[|]+/','',$nameInput);
    if (strlen($nameInput) > 0 && strlen($nameInput) < 200){
        $nameValid = true;
    }
    else{
        echo '<p class = "error">Course name cannot be empty or longer than 200 characters.</p>';
    }

    //validate course credits
    $creditsValid = false;
    $creditsInput = $_POST['field-course-credits'];
    preg_replace('/[^0-9]+/','',$creditsInput);
    $creditsInput = intval($creditsInput);
    //There is a 12 credit class at Cornell. Anything larger would just be absurd.
    if ($creditsInput <= 12){
        $creditsValid = true;
    }
    else{
        echo '<p class = "error">Invalid number of credits. Must be less than or equal to 12. You entered ' . $numInput .'</p>';
    }

    //tags
    $tagValid = false;
    $tags = array();

    if(isset($_POST['field-course-tags'])){
        foreach($_POST['field-course-tags'] as $tag){
            if (in_array($tag,$validfilters)){
                $tags[] = $tag;
            }
            else{
                echo '<p class = "error">Invalid course tag '. $tag . '</p>';
                break;
            }
        }
        $tagValid = true;
        sort($tags, SORT_STRING);
    }
    else{
        echo '<p class = "error">No course vector selected.</p>';
    }
    //course description

    $descValid = false;
    $descInput = filter_input(INPUT_POST, 'field-course-desc', FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW);
    preg_replace('/[|]+/', '', $descInput);

    //set a 2000 character max description
    if (strlen($descInput) <= 2000){
        $descValid = true;
    }
    else{
        echo '<p class = "error">Please limit descriptions to 2000 characters or less.</p>';
    }

    if($deptValid && $numValid && $nameValid && $creditsValid && $tagValid && $descValid){

        $fptr = fopen("./data.txt", 'r');
        $isDup = false;
        while(!feof($fptr)){
            $line = fgets($fptr);
            $course = explode("|", $line);
            if(!empty($course)){
                if ($course[0] == $deptInput && $course[1] == $numInput){
                    $isDup = true;
                    break;
                }
            }
        }
        if ($isDup){
            echo '<p class = "error">Duplicate class!</p>';
        }
        else{
            $output = "\n" . $deptInput . "|" . $numInput . "|" . $nameInput . "|" . $descInput . "|";
            for ($i = 0; $i < sizeof($tags); $i++){
                $output .= $tags[$i];
                if ($i + 1 < sizeof($tags)){
                    $output .= ",";
                }
            }
            $output .= "|" . $creditsInput;
            file_put_contents("./data.txt",$output,FILE_APPEND);
            echo '<p>Successfully added your class!</p>';
        }

    }

} 

?>
