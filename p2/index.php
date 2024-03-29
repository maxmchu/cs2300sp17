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
                    <form action = "index.php" method = "post" name = "view-form">
                        <input type = "submit" name = "view" value = "View All Courses">
                    </form>
                    <div class = "course-cards">
                        <?php
                            include("./src/display.php");
                            $validfilters = array("cscore","ai","se","systems","renaissance");
                            include("./src/interaction.php");
                        ?>
                    </div>
                </div>
                <div class = "sidebar">
                    <h1 class = "content-title">Search and Filter</h1>
                    <form action = "index.php" method = "post" name = "search-form">
                        <div class = "search">
                            <input type = "text" name = "field-search" placeholder = "Enter a class name (not a number)">                        
                            <input type = "submit" name = "search" value = "Search">
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