<?php

    $result = $mysqli->query("SELECT albums.album_id, albums.album_title, albums.cover_id, images.file_type FROM albums, images WHERE albums.cover_id = images.image_id");
    
    echo '<div class = "gallery-row">';
    $count = 0;
    while ($row = $result->fetch_row()){
        if ($count == 4){
            echo '</div>
                  <div class = "gallery-row">
            ';
            $count = 0;
        }
        
        echo '<div class = "gallery-card">
                 <a class = "card-link" href = "./album.php">
                    <img class = "gallery-img" alt = "" src = "./img/';
        echo $row[2] . "." . $row[3] . '">';
        #echo '      <a class = "card-link" href = "./album.php">';
        echo $row[1];
        echo '</a>
                </div>';
        $count += 1;
        
    }
    echo '</div>';
?>