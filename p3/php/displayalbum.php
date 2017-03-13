<?php
    require_once("./php/config.php");
   
    function displayAlbum($album_id){
        $mysqli = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
        $albumInfo = $mysqli->query('SELECT album_title, author, date_added, date_modified FROM albums WHERE album_id = "' . $album_id . '"');
        
        while ($row = $albumInfo->fetch_row()){
            echo '<h1 class = "album-title">' . $row[0] . '</h1>';
            echo '<p class = "album-info"> Posted on ' . $row[2] . ' by ' . $row[1] . '</p>';
        }
        
        $albumPics = $mysqli->query('SELECT albumset.image_id, images.* FROM albumset INNER JOIN images ON albumset.image_id = images.image_id WHERE albumset.album_id = "' . $album_id .'" ORDER BY position');
        
        while ($row = $albumPics->fetch_row()){
            echo '<div class = "album-img-card">';
            echo '<img class = "album-img" alt = "" src = "./img/' . $row[0] . '.' . $row[6] . '">';
            echo '  <p class = "album-img-caption">' . $row[2] . '</p>';
            echo '  <p class = "album-img-credit">Credit: ' . $row[3] . '</p>';
            echo '</div>';
        }
    }
?>