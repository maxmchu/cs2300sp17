<?php
    
    function displayClasses($search, $filters){
        $fname = "./data.txt";
        if (!file_exists($fname)){
            print("<p class = \"error\">File $fname not found.</p>");
            return;
        }
        else{
            $fptr = fopen($fname, 'r');

            $courses = array();

            while(!feof($fptr)){
                $line = fgets($fptr);
                $course = explode("|", $line);
                if (!empty($course)){
                    $course[1] = intval($course[1]);
                    $course[5] = intval($course[5]);
                    $tags = array();
                    $tags = explode(",", $course[4]);
                    $course[4] = $tags;
                    $courses[] = $course;
                }
            }

            function coursecmp($a, $b){
                if ($a[0] == $b[0]){
                    return strcasecmp($a[1], $b[1]);
                }
                else{
                    return $a[0] - $b[0];
                }
            }
            
            function isSubarray($a,$b){
                foreach($a as $item){
                    if (in_array($item, $b) == false){
                        return false;
                    }
                }
                return true;
            }

            usort($courses, "coursecmp");
            
            //does array A contain array B?

            foreach($courses as $course){
                
                strtolower($search);
                $coursetemp = strtolower($course[2]);
                
                if($search == "" || (strpos($coursetemp, $search) !== false)){
                    if (sizeof($filters) == 0 || isSubarray($filters, $course[4])){
                        
                        echo '<div class = "course-card">
                        <h2 class = "course-title">';
                        echo $course[0] . $course[1] . ": " . $course[2];
                        echo '  </h2><p class = "course-credits">';
                        echo $course[5] . " credits";
                        echo '  </p><p class = "course-desc">';
                        echo $course[3];
                        echo '  </p>';
                        foreach ($course[4] as $tag){
                            echo '<span class = "tag ';
                            switch ($tag){
                                case "se":
                                    echo 'tag-se">Software Engineering</span>';
                                    break;
                                case "cscore":
                                    echo 'tag-cscore">CS Core</span>';
                                    break;
                                case "ai":
                                    echo 'tag-ai">Artificial Intelligence</span>';
                                    break;
                                case "renaissance":
                                    echo 'tag-renaissance">Renaissance</span>';
                                    break;
                                case "systems":
                                    echo 'tag-systems">Systems/Databases</span>';
                                    break;
                                default:
                                    echo '"></span>';
                                    break;
                            }
                        }
                        echo '</div>';
                        
                    }
                }
                
                
            }

            unset($course);
            unset($line);
            fclose($fptr);
            return;
        }    
    }
    
    
?>