<?php
    session_start();
?>
<!doctype html>
<html>
    <head>
        <title>Single Image Display</title>
        <link rel = "stylesheet" href = "./font-awesome/css/font-awesome.min.css">
        <link href="https://fonts.googleapis.com/css?family=Roboto:100,300,400" rel="stylesheet">
        <link rel = "stylesheet" href = "./css/stylesheet.css">
    </head>
    <body>
        <?php
            include("./php/banner.php");
            
        ?>
        <div class = "content">
            <h1 class = "album-title">Displaying a Single Image</h1>
            <?php
                require_once("./php/config.php");
                $mysqli = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
                $imageid = $_GET["id"];
                include("./php/displayimage.php");
                displayImage($imageid);
                if (isset($_SESSION['logged_user'])){
                    if ($_SESSION['admin'] == 1){
                        echo '<p class = "album-info">You are logged in as an admin. You can make changes to the image below.</p>';
                        echo '<form action = "./image.php?id=' . $imageid . '" class = "album-add" method = "post">
                                  <p class = "album-info">Change caption</p>
                                  <input type = "text" class = "input-album" name = "caption" placeholder = "Enter a new caption here."><br/>
                                  <p class = "album-info">Change credit</p>
                                  <input type = "text" class = "input-album" name = "credit" placeholder = "Enter a new credit here."><br/>';
                        echo '<p class = "album-info">Add to another album</p>
                        <select name = "albumName" class = "select-album">
                             <option value = "" label = "(no album)"></option>';
                        $albumResults = $mysqli->query('SELECT album_id, album_title FROM albums ORDER BY album_title ASC');
                        
                        while ($row = $albumResults->fetch_row()){
                            echo '<option value = "' . $row[0] . '">' . $row[1] . '</option>';
                        }
                                  
                        echo '</select><br/>
                        <input type = "submit" class = "submit-album" name = "submit" value = "Save Changes">
                              </form>';
                        if ($imageid != "NAVAIL"){
                            echo '<form action = "./image.php?id=' . $imageid . '" class = "album-add" method = "post">
                                  <p class = "album-info">Delete image (cannot be undone!)</p>
                                  <input type = "submit" class = "submit-album" name = "delete" value = "Delete image">
                              </form>';    
                        }
                        if (isset($_POST['submit'])){
                            $caption = "";
                            $credit = "";
                            if (!empty($_POST['caption'])){
                                $caption = filter_input(INPUT_POST, 'caption', FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW);
                            }
                            if (!empty($_POST['credit'])){
                                $credit = filter_input(INPUT_POST, 'credit', FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW);
                            }
                            if ($caption != "" || $credit != ""){
                                $query = "UPDATE images SET ";
                                if ($caption != ""){
                                    $query.= 'caption = "' . $caption . '"';
                                }
                                if ($credit != ""){
                                    if ($caption != ""){
                                        $query.= ', ';
                                    }
                                    $query .= 'credit = "' . $credit . '"';
                                }
                                $query.= 'WHERE image_id = "' . $imageid . '"';
                                $mysqli->query($query);
                            }
                            if (isset($_POST['albumName'])){
                                $album = filter_input(INPUT_POST, 'albumName', FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW);
                                if ($album != ""){
                                    $result = $mysqli->query('SELECT position, image_id FROM albumset WHERE album_id = "' . $album . '" ORDER BY position DESC');
                                    $row = $result->fetch_row();

                                    if ($row[1] != "NAVAIL"){
                                        $nextIndex = $row[0] + 1;
                                        $mysqli->query('INSERT INTO albumset (album_id, image_id, position) VALUES ("' . $album . '","' . $imageid . '",' . $nextIndex . ')');
                                        $mysqli->query('UPDATE albums SET date_modified = CURRENT_TIMESTAMP WHERE album_id = "' . $album . '"');
                                    }
                                    else{
                                        $mysqli->query('UPDATE albumset SET image_id = "' . $imageid . '" WHERE album_id = "' . $album . '"');  
                                        $mysqli->query('UPDATE albums SET cover_id = "' . $imageid . '", date_modified = CURRENT_TIMESTAMP WHERE album_id = "' . $album . '"');
                                    }
                                }
                            }
                            echo '<p class = "album-info">The information has been updated. Refresh to see changes.</p>';
                        }
                        if (isset($_POST['delete'])){
                            $imageInfo = $mysqli->query('SELECT * FROM images WHERE image_id = "' . $imageid . '"');
                            $imagerow = $imageInfo->fetch_row();
                            $mysqli->query('DELETE FROM images WHERE image_id = "' . $imageid . '"');
                            $mysqli->query('DELETE FROM albumset WHERE image_id = "' . $imageid . '"');
                            $mysqli->query('UPDATE albums SET cover_id = "NAVAIL" WHERE cover_id ="' . $imageid . '"');
                            echo '<p class = "album-info">The image has been removed. Refresh to see changes.</p>';
                            unlink('./img/' . $imagerow[0] . '.' . $imagerow[5]);
                        }
                    }
                    else{
                        echo '<p class = "album-info">You are logged in as a normal user. You cannot make changes to the image.</p>';
                    }
                }
            ?>
        </div>
    </body>
</html>