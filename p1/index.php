<html>

    <head>
        <title>College Meme Groups</title>
        <link rel = "stylesheet" href = "./css/stylesheet.css">
        <link href="https://fonts.googleapis.com/css?family=Roboto:300,400" rel="stylesheet">
    </head>

    <body>
        <?php
            include("./src/banner.html")
        ?>
        <div class = "content">
                <?php
                    include("./src/nav.php");
                ?>
                <div class = "main-content">
                    <h1 class = "content-title">Welcome</h1>
                    <p class ="content-text">
                        This website is dedicated to the dank meme pages created by bored college students around the United States. Longterm exposure to them has been shown by studies to have a detrimental effect on GPA and social skills.<br/>
                        
                        A list of these sites can be found to the left.
                    </p>
                    <h3 class = "content-subsubtitle">Sign up for updates from the Unofficial Guide</h3>
                    <p class = "content-text">
                        If you're actually interested in learning more about this pile of nonsense, sign up for our email list and get updates.
                    </p>
                    <form class = "email-form" method = "post">
                        <input type = "text" name = "fname" placeholder = "First Name">
                        <input type = "text" name = "lname" placeholder = "Last Name">                
                        <br/>
                        <input type = "text" name = "email" placeholder="Email Address">
                        <br/>
                        <input type = "radio" name = "type" value = "student">I am a university student
                        <input type = "radio" name = "type" value = "other">Other
                        <br/>
                        <input type = "submit" name = "submit">
                        <?php
                            include("./src/email.php");
                        ?>
                    </form>
                    
                </div>
            </div>
        <h1>
        
        </h1>
        
        
    </body>
    
</html>