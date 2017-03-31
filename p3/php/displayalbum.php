<?php
    require_once("./php/config.php");
   
    function displayAlbum($album_id){
        $mysqli = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
        $albumInfo = $mysqli->query('SELECT album_title, author, date_added, date_modified, cover_id FROM albums WHERE album_id = "' . $album_id . '"');
        
        while ($rowa = $albumInfo->fetch_row()){
            echo '<h1 class = "album-title">' . $rowa[0] . '</h1>';
            echo '<p class = "album-info"> Posted on ' . $rowa[2] . ' by ' . $rowa[1] . '</p>';
            echo '<p class = "album-info"> Last modified on ' . $rowa[3] .'</p>';
        }
        
        $albumPics = $mysqli->query('SELECT albumset.image_id, images.* FROM albumset INNER JOIN images ON albumset.image_id = images.image_id WHERE albumset.album_id = "' . $album_id .'" ORDER BY position');
        
        while ($row = $albumPics->fetch_row()){
            if (isset($_SESSION['logged_user'])){
                if ($_SESSION['admin'] == 1){
                    echo '<form class = "album-add" method = "post" action = "./album.php?id=' . $album_id . '#' . $row[0] . '" name = "' . $row[0]. '" id = "' . $row[0] . '">
                              <input type = "submit" class = "submit-album" value = "Set as album cover" name = "cover' . $row[0] . '">
                              <input type = "submit" class = "submit-album" value = "Delete from album" name = "delete' . $row[0] . '">
                              
                          </form>';
                    if (isset($_POST['cover' . $row[0]])){
                        $mysqli->query('UPDATE albums SET cover_id = "' . $row[0] . '", date_modified = CURRENT_TIMESTAMP WHERE album_id = "' . $album_id . '"');

                        echo '<p class = "album-info">This image has been set as the cover!</p>';
                    }
                    if (isset($_POST['delete' . $row[0]])){
                        $mysqli->query('DELETE FROM albumset WHERE album_id = "' . $album_id . '" AND image_id ="' . $row[0] . '"');
                        $mysqli->query('UPDATE albums SET date_modified = CURRENT_TIMESTAMP WHERE album_id = "' . $album_id .'"');
                        echo '<p class = "album-info">This image has been removed from the album! Refresh to see changes.</p>';
                        if ($row[0] == $rowa[4]){
                            $mysqli->query('UPDATE albums SET cover_id = "NAVAIL", date_modified = CURRENT_TIMESTAMP WHERE album_id = "' . $album_id. '"');
                        }
                    }

                }
                
            }
            
            echo '<div class = "album-img-card" name = "' . $row[0] . '">';
            echo '<a href = "./image.php?id=' . $row[0] . '">';
            echo '<img class = "album-img" alt = "" src = "./img/' . $row[0] . '.' . $row[6] . '"></a>';
            echo '  <p class = "album-img-caption">' . $row[2] . '</p>';
            echo '  <p class = "album-img-credit">Credit: ' . $row[3] . '</p>';
            echo '</div>';
            
            
        }
        return;
    }
?>
