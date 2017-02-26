<?php
    $fname = "../data.txt";
    if (!file_exists($fname)){
        print("<p class = \"error\">File $fname not found.</p>");
    }
    $fptr = fopen($fname, 'r');

    $courses = array();

    while(!feof($fptr)){
        $line = fgets($fptr);
        $course = explode("|", $line);
        if (!empty($course)){
            $course[5] = intval($course[5]);
            $tags = array();
            $tags = explode(",", $course[4]);
            $courses[] = $course;
        }
    }

    

    foreach($courses as $course){
        echo '<div class = "course-card">
                <h2 class = "course-title">';
        echo $course[0] . $course[1] . ": " . $course[2];
        echo '  </h2><p class = "course-credits">';
        echo $course[5] . " credits";
        echo '  </p><p class = "course-desc">';
        echo $course[3];
        echo '  </p>';
        echo '</div>';
    }

    unset($course);
    unset($line);
    fclose($fptr);

?>