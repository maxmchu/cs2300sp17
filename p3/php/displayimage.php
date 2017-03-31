<?php

    require_once("./php/config.php");

    function displayImage($id){
        $mysqli = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
        $imageInfo = $mysqli->query('SELECT * FROM images WHERE image_id = "' . $id . '"');
        $row = $imageInfo->fetch_row();
        
        echo '<p class = album-info>This image was originally posted on ' . $row[3] . ' by ' . $row[4] . '.';    
        echo '<div class = "album-img-card">';
        echo '<a target = "_blank" href = "./img/'. $row[0] . '.' . $row[5] . '">';
        echo '<img alt = "" class = "album-img" title = "Click for fullsize image." src = "./img/' . $row[0] . '.' . $row[5] . '"></a>';
        echo '<p class = "album-img-caption">' . $row[1] . '</p>';
        echo '<p class = "album-img-credit">Credit: ' . $row[2] . '</p>';
        echo '</div>';
        
        echo '<p class = "album-info">This image can be found in the following albums:<br/>';
        $albumList = $mysqli->query('SELECT albumset.album_id, albums.album_title FROM albumset INNER JOIN albums ON albumset.album_id = albums.album_id WHERE albumset.image_id = "' . $id . '"');
        while ($row = $albumList->fetch_row()){
            echo '<a href = "./album.php?id=' . $row[0] . '">' . $row[1] . '</a><br/>';
        }
        echo '</p>';
        return;
    }

?>