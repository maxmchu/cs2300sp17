<!doctype html>
<html>
    <head>
        <link rel="stylesheet" href="./font-awesome/css/font-awesome.min.css">
        <link href="https://fonts.googleapis.com/css?family=Roboto:100,300,400" rel="stylesheet">
        <link rel = "stylesheet" href = "./css/stylesheet.css">
        <title>Album Display</title>
    </head>
    <body>
        <?php
            include("./php/banner.php");
        ?>
        <div class = "content">
            <?php
                include("./php/displayalbum.php");
                displayAlbum("cMbS2D");
            ?>
        </div>
    </body>
</html>