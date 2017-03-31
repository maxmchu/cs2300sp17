<?php
    session_start();
?>
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
                require_once("./php/config.php");
                $mysqli = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
                include("./php/displayalbum.php");
                $albumid = $_GET["id"];
                if (isset($_SESSION['logged_user'])){
                    if ($_SESSION['admin'] == 1){
                        echo '<p class = "album-info">You are logged in as an admin. You can make changes to the album below.</p>';
                        echo '<form action = "./album.php?id=' . $albumid .'" class = "album-add" method = "post">
                                <p class = "album-info">Change album title</p>
                                <input type = "text" class = "input-album" name = "title" placeholder = "Enter a new title here."><br/>
                                <input type = "submit" class =  "submit-album" name = "changetitle" value = "Save Changes">
                            </form>';
                        echo '<form action = "./album.php?id=' . $albumid . '" class = "album-add" method = "post">
                                  <p class = "album-info">Delete Album: THIS CANNOT BE UNDONE!</p>
                                  <input type = "submit" class = "submit-album" name = "delete" value = "Delete Album">
                              </form>';
                        
                        if (isset($_POST['changetitle'])){
                            $title = "";
                            if (!empty($_POST['title'])){
                                $title = filter_input(INPUT_POST, 'title', FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW);
                            }
                            if ($title != ""){
                                $mysqli->query('UPDATE albums SET album_title = "' . $title . '", date_modified = CURRENT_TIMESTAMP WHERE album_id = "' . $albumid .'"');
                                echo '<p class = "album-info">Your album title has been changed!</p>';
                            }
                        }
                        if (isset($_POST['delete'])){
                            $mysqli->query('DELETE FROM albumset WHERE album_id = "' . $albumid . '"');
                            $mysqli->query('DELETE FROM albums WHERE album_id = "' . $albumid . '"');
                            echo '<p class = "album-info">Your album has been deleted!</p>';
                        }
                    }
                }
                displayAlbum($albumid);
                if (isset($_SESSION['logged_user'])){
                    if ($_SESSION['admin'] == 1){
                        echo '<h1 class = "album-title">Add an Existing Image</h1>';
                        echo '<div class = "content">';
                        $result = $mysqli->query('SELECT * FROM images WHERE images.image_id NOT IN(SELECT albumset.image_id FROM albumset WHERE albumset.album_id = "' . $albumid .'") ORDER BY date_added DESC');
                        echo '<div class = "gallery-row">';
                        $count = 0;
                        if ($result->num_rows > 0){
                            while ($row = $result->fetch_row()){
                                if ($count == 4){
                                    echo '</div>
                                          <div class = "gallery-row">';
                                    $count = 0;
                                }
                                echo '<div class = "gallery-card">
                                        <a class = "card-link" href = "./image.php?id=' . $row[0] . '">';
                                echo '      <img class = "gallery-img" alt = "" src = "./img/' . $row[0] . "." . $row[5] . '">';
                                echo '</a>';
                                echo '<form action = "./album?id=' . $albumid . '#' . $row[0] . '" method = "post" class = "album-add">
                                         <input class = "submit-album" type = "submit" value = "Add to album" name = "add' . $row[0] . '"></form></div>';
                                $count += 1;
                                if (isset($_POST['add' . $row[0]])){
                                    $addresult = $mysqli->query('SELECT position, image_id FROM albumset WHERE album_id = "' . $albumid . '" ORDER BY position DESC');
                                    $addrow = $addresult->fetch_row();
                                    if ($addrow[1] != "NAVAIL"){
                                        $nextIndex = $addrow[0] + 1;
                                        $mysqli->query('INSERT INTO albumset (album_id, image_id, position) VALUES ("' . $albumid . '","' . $row[0] . '",' . $nextIndex . ')');
                                        echo '<p class = "album-info">Image added! Refresh to see changes.</p>';
                                    }
                                    else{
                                        $mysqli->query('UPDATE albumset SET image_id = "' . $row[0] . '" WHERE album_id = "' . $albumid . '"');
                                        $mysqli->query('UPDATE albums SET cover_id = "' . $row[0] . '" WHERE album_id = "' . $albumid . '"');
                                        echo '<p class = "album-info">Image added! Refresh to see changes.</p>';
                                    }
                                }
                            }
                        }
                        
                        echo '</div>';
                    }
                }
            ?>
        </div>
    </body>
</html>