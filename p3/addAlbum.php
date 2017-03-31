<?php
    session_start();
?>
<!doctype html>
<html>
    <head>
        <title>Add an Album</title>
        <link rel="stylesheet" href="./font-awesome/css/font-awesome.min.css">
        <link href="https://fonts.googleapis.com/css?family=Roboto:100,300,400" rel="stylesheet">
        <link rel = "stylesheet" href = "./css/stylesheet.css">
    </head>
    <body>
        <?php
            include("./php/banner.php");
        ?>
        <div class = "content">
            <h1 class = "album-title">Add an Album</h1>
            <?php
                if (isset($_SESSION['logged_user'])){
                    if ($_SESSION['admin'] == 1){
                        echo '<form action = "addAlbum.php" method = "post" name = "addAlbumForm" class = "album-add" enctype="multipart/form-data">
                        <input type = "text" name = "album-name" placeholder = "Enter an album name" class = "input-album">

                        <p class = "album-info">
                        Add a cover image to the album (optional)
                        </p>
                        <input type = "file" name = "newphoto">
                        <br/>
                        <input type = "submit" class = "submit-album" value = "Add Album" name = "addAlbum">
                    </form>';
                    require_once("./php/config.php");
                    $mysqli = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
                    include('./php/gen_id.php');

                    $albumid = get_id();

                    if (isset($_POST['addAlbum'])){
                        $input = filter_input(INPUT_POST, 'album-name', FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW);
                        if (!($input == "")){
                            if (! empty($_FILES['newphoto'])){
                                $newPhoto = $_FILES['newphoto'];
                                $maxSize = 10485760;
                                $coverid = "";
                                $allowed = array("image/jpg", "image/jpeg", "image/png", "image/bmp", "image/gif");

                                if($newPhoto['error'] == 0 && $newPhoto['size'] <= $maxSize && in_array($newPhoto['type'],$allowed)){
                                    $tempName = $newPhoto['tmp_name'];
                                    $temp = explode(".", $newPhoto['name']);
                                    $imageid = get_id();

                                    $coverid = $imageid;
                                    $newName = $imageid . '.' . end($temp);
                                    echo $newName;
                                    move_uploaded_file($tempName, "./img/" . $newName);
                                    $mysqli->query('INSERT INTO images (image_id,caption,credit,author,file_type) VALUES("' . $imageid . '", "This is a temporary caption.", "temporary placeholder", "' . $_SESSION['logged_user'] .'", "' . end($temp) . '")');
                                    $mysqli->query('INSERT INTO albumset (album_id, image_id, position) VALUES ("' . $albumid . '", "' . $imageid . '", 0)');
                                    $mysqli->query('INSERT INTO albums (album_id, album_title, cover_id, author) VALUES ("' . $albumid . '", "' . $input . '", "' . $coverid . '", "'. $_SESSION['logged_user'].'")');
                                }
                                else{
                                    $mysqli->query('INSERT INTO albums (album_id, album_title, cover_id, author) VALUES ("' . $albumid . '", "' . $input . '", "NAVAIL", "' . $_SESSION['logged_user'] . '")');
                                    $mysqli->query('INSERT INTO albumset (album_id,     image_id, position) VALUES ("' . $albumid . '", "NAVAIL", 0)');
                                    echo '<p class = "album-info">There was a problem uploading your cover image, or you did not select one.. A placeholder has been used instead.</p>';
                                }
                            }
                            else{

                            }

                            echo '<p class = "album-info">Your album was succesfully created <a href = "./album.php?id=' . $albumid . '">here.</a></p>';

                            }

                        }
                    }
                    
                }
                else{
                    echo '<p class = "album-info">You must be logged in to create an album!</p>';
                }
            ?>
        </div>
    </body>
</html>