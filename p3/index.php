<?php
    session_start();
?>
<!doctype html>
<html>
    <head>
        <title>Image Albums</title>
        <link rel="stylesheet" href="./font-awesome/css/font-awesome.min.css">
        <link href="https://fonts.googleapis.com/css?family=Roboto:100,300,400" rel="stylesheet">
        <link rel = "stylesheet" href = "./css/stylesheet.css">
    </head>
    <body>
        <?php
            include("./php/banner.php");
            require_once("./php/config.php");
            $mysqli = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
        ?>
        <div class = "content">
            <div class = "gallery">
                <?php
                
                    
                    $result = $mysqli->query("SELECT albums.album_id, albums.album_title, albums.cover_id, images.file_type FROM albums, images WHERE albums.cover_id = images.image_id ORDER BY albums.date_modified DESC");
                
                    include("./php/displaygallery.php");
                    displayGallery($result);
                ?>
            </div>
        </div>
    </body>
</html>