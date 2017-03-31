<?php
    session_start();
?>
<!doctype html>
<html>
    <head>
        <title>Search Results</title>
        <link rel = "stylesheet" href = "./font-awesome/css/font-awesome.min.css">
        <link href="https://fonts.googleapis.com/css?family=Roboto:100,300,400" rel="stylesheet">
        <link rel = "stylesheet" href = "./css/stylesheet.css">
    </head>
    <body>
        <?php
            include("./php/banner.php");
        ?>
        <div class = "content">
            <h1 class = "album-title">Search results</h1>
            <?php
                include("./php/config.php");
                include("./php/displaygallery.php");
                $mysqli = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
            
                $query = filter_input(INPUT_GET, 'query', FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW);
            
                $queryt = '"%' . $query . '%"';
                
                $albumQuery = 'SELECT albums.*, images.file_type FROM albums INNER JOIN images ON albums.cover_id = images.image_id WHERE albums.album_title LIKE ' . $queryt . ' OR albums.author LIKE ' . $queryt;
                
                $imageQuery = 'SELECT * FROM images WHERE caption LIKE ' . $queryt . ' OR credit LIKE ' . $queryt;
            
                $albumResults = $mysqli->query($albumQuery);
                $imageResults = $mysqli->query($imageQuery);
            
                if ($albumResults->num_rows == 0){
                    echo '<p class = "album-info">No album results found.</p>';
                }
                else{
                    echo '<p class = "album-info">' . $albumResults->num_rows . ' album result';
                    if ($albumResults->num_rows > 1) echo 's';
                    echo ' found for "' . $query . '".</p>';
                    echo '<div class = "gallery">';
                    echo '<div class = "gallery-row">';
                    $count = 0;
                    while ($row = $albumResults->fetch_row()){
                        if ($count == 4){
                            echo '</div><div class = "gallery-row">';
                            $count = 0;
                        }
                        
                        echo '<div class = "gallery-card">
                                  <a class = "card-link" href = "./album.php?id=' . $row[0] . '">' . 
                                     '<img class = "gallery-img" alt = "" src = "./img/';
                        echo $row[2] . "." . $row[6] . '">';
                        echo $row[1];
                        echo '</a></div>';
                        $count += 1;
                    }
                    echo '</div></div>';
                }
            
                if ($imageResults->num_rows == 0){
                    echo '<p class = "album-info">No image results found.</p>';
                }
                else{
                    echo '<p class = "album-info">' . $imageResults->num_rows . ' image result';
                    if ($imageResults->num_rows > 1) echo 's';
                    echo ' found for "' . $query . '".</p>';
                    echo '<div class = "gallery">';
                    echo '<div class = "gallery-row">';
                    $counti = 0;
                    while ($row = $imageResults->fetch_row()){
                        if ($counti == 4){
                            echo '</div><div class = "gallery-row">';
                            $count = 0;
                        }
                        echo '<div class = "gallery-card">
                                 <a class = "card-link" href = "./image.php?id=' . $row[0] . '">';
                        echo '      <img class = "gallery-img" alt = "" src = "./img/' . $row[0] . "." . $row[5] . '">';
                        echo 'Posted by ' . $row[4] . ' on ' . $row[3];
                        echo '</a></div>';
                        $counti += 1;
                    }
                    echo '</div></div>';
                }
                
            ?>
        </div>
    </body>
</html>