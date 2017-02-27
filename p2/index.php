<!doctype html>
<html>
    <head>
        <title>CS Vector classes, the sane way</title>
        <link rel = stylesheet href = "./css/stylesheet.css">
        <link href="https://fonts.googleapis.com/css?family=Roboto:100,300,400" rel="stylesheet">
    </head>
    <body>
        <!--
        Reused Banner HTML and CSS from P1
        Banner Image sourced from Cornell Blog
        https://blogs.cornell.edu/gateshall/image-gallery/#jp-carousel-1034
        -->
        <div class = "banner">
            <div class = "banner-text">
                <h1 class = "site-title"><span class = "highlight-red">Cornell CS Major Vector Classes</span></h1>
                <h2 class = "site-desc"><span class = "highlight-red">organized in a sane, understandable way</span></h2>       
            </div>
        </div>
        <div class = "content">
            <h1 class = "content-title">CS Vectors @ Cornell</h1>
            <p class = "content-text">
                Every Computer Science major at Cornell is required to complete at least one vector (or concentration) in order to graduate.<br/>
                However, the CS department's website on these vectors is confusing, and doesn't always show you exactly what classes will fulfill each vector.<br/>
                This site lets you filter classes based on your vector interests, and add classes we're missing.<br/>
                This image of Gates Hall can be found on the Cornell Blog <a href = "https://blogs.cornell.edu/gateshall/image-gallery/#jp-carousel-1034" target = "_blank">here</a>.
            </p>
            <div class = "container">
                <div class = "catalog">
                    <h1 class = "content-title">Course List</h1>
                    <div class = "course-cards">
                        <?php
                            include("./src/display.php");
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
                                
                                echo '<p>Search results for "' . $queryInput . '":</p>';
                                
                                displayClasses($queryInput, $filters);
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
                    </div>
                </div>
                <div class = "sidebar">
                    <h1 class = "content-title">Search and Filter</h1>
                    <form action = "index.php" method = "post" name = "search-form">
                        <div class = "search">
                            <input type = "text" name = "field-search" placeholder = "Enter a class name (not a number)">                        
                            <input type = "submit" name = "search" value = "Search" id = "search-button">
                        </div>
                        <div class = "filter">
                            <p class = "form-field-title">Click on a box to set a filter for your search</p>
                            <label class = "form-label"><input type = "checkbox" name = "filter[]" value = "cscore"><span class = "tag tag-cscore">CS Core</span></label>
                            <label class = "form-label"><input type = "checkbox" name = "filter[]" value = "ai"><span class = "tag tag-ai">Artifical Intelligence</span></label>
                            <label class = "form-label"><input type = "checkbox" name = "filter[]" value = "renaissance"><span class = "tag tag-renaissance">Renaissance</span></label>
                            <label class = "form-label"><input type = "checkbox" name = "filter[]" value = "se"><span class = "tag tag-se">Software Engineering</span></label>
                            <label class = "form-label"><input type = "checkbox" name = "filter[]" value = "systems"><span class = "tag tag-systems">Systems/Databases</span></label>
                        </div>
                    </form>
                    <hr/>
                    <div class = "add-course">
                        <form action = "index.php" method = "post" name = "add-form">
                            <h1 class = "content-title">Add a Class</h1>
                            <p class = "form-field-title">Course Department Abbreviation</p>
                            <input type = "text" name = "field-course-dept" placeholder = "CS">

                            <p class = "form-field-title">Course Number</p>
                            <input type = "text" name = "field-course-number" placeholder = "9999">

                            <p class = "form-field-title">Course Name</p>
                            <input type = "text" name = "field-course-name" placeholder = "Achieving the Singularity">

                            <p class = "form-field-title">Credits</p>
                            <input type = "text" name = "field-course-credits" placeholder = "4">

                            <p class = "form-field-title">Course Vectors</p>
                            <label class = "form-label"><input type = "checkbox" name = "field-course-tags[]" value = "cscore"><span class = "tag tag-cscore">CS Core</span></label>
                            <label class = "form-label"><input type = "checkbox" name = "field-course-tags[]" value = "ai"><span class = "tag tag-ai">Artificial Intelligence</span></label>
                            <label class = "form-label"><input type = "checkbox" name = "field-course-tags[]" value = "renaissance"><span class = "tag tag-renaissance">Renaissance</span></label>
                            <label class = "form-label"><input type = "checkbox" name = "field-course-tags[]" value = "se"><span class = "tag tag-se">Software Engineering</span></label>
                            <label class = "form-label"><input type = "checkbox" name = "field-course-tags[]" value = "systems"><span class = "tag tag-systems">Systems/Databases</span></label>
                            <p class = "form-field-title">Course Description</p>
                            <textarea name = "field-course-desc" placeholder = "Enter your own cool description of the class, or copy it from the Course Roster."></textarea>
                            <br/>
                            <input type = "submit" name = "add-class" value = "Add Class">
                        </form>
                        
                    </div>
                </div>
            </div>
        </div>
        
    </body>

    
</html>