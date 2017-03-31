<?php
    session_start();
?>
<!doctype html>
<html>
    <head>
        <title>Add an Image</title>
        <link rel="stylesheet" href="./font-awesome/css/font-awesome.min.css">
        <link href="https://fonts.googleapis.com/css?family=Roboto:100,300,400" rel="stylesheet">
        <link rel = "stylesheet" href = "./css/stylesheet.css">
    </head>
    <body>
        <?php
            include("./php/banner.php");
        ?>
        <div class = "content">
            <h1 class = "album-title">Add an Image</h1>
            <?php
                if(isset($_SESSION['logged_user'])){
                    if ($_SESSION['admin'] == 1){
                        echo '<form action = "./addImage.php" method = "post" name = "addImageForm" class = "album-add" enctype="multipart/form-data">
                        <p class = "album-info">
                        Select an image to upload
                        </p>
                        <input type = "file" name = "newphoto">
                        <p class = "album-info">
                        Select an album for the image (optional)
                        </p>
                        <select name = "albumName" class = "select-album">
                            <option value = "" label = "(no album)"></option>';
                        require_once("./php/config.php");
                        $mysqli = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
                        $result = $mysqli->query('SELECT album_id, album_title FROM albums ORDER BY album_title ASC');
                        while ($row = $result->fetch_row()){
                            echo '<option value = "' . $row[0] . '">' . $row[1] . '</option>';
                        }
                    echo '</select>
                            <p class = "album-info">
                                Enter a caption for your image.
                            </p>
                            <input type = "text" name = "caption" placeholder="Your caption here." class = "input-album">
                            <p class = "album-info">
                                Enter the credit for your image.
                            </p>
                            <input type = "text" name = "credit" placeholder = "Your credit here." class = "input-album">
                            <br/>
                            <input type = "submit" class = "submit-album" value = "Add Image" name = "addImage">
                        </form>';
                    require_once("./php/config.php");
                    $mysqli = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
                    include('./php/gen_id.php');
                    $imageid = get_id();

                    if (isset($_POST['addImage'])){
                            $albumInput = filter_input(INPUT_POST, 'albumName', FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW);
                            $captionInput = filter_input(INPUT_POST, 'caption', FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW);
                            $creditInput = filter_input(INPUT_POST, 'credit', FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW);
                            if (! empty ($_FILES['newphoto'])){
                                $newPhoto = $_FILES['newphoto'];
                                $maxSize = 10485760;
                                $allowed = array("image/jpg", "image/jpeg", "image/png", "image/bmp", "image/gif");

                                if($newPhoto['error'] == 0 && $newPhoto['size'] <= $maxSize && in_array($newPhoto['type'],$allowed)){
                                    $tempName = $newPhoto['tmp_name'];
                                    $temp = explode(".", $newPhoto['name']);
                                    $newName = $imageid . '.' . end($temp);
                                    $caption = "This is a placeholder caption.";
                                    $credit = "This is a placeholder credit.";
                                    if ($captionInput != ""){
                                        $caption = $captionInput;
                                    }
                                    if ($creditInput != ""){
                                        $credit = $creditInput;
                                    }
                                    move_uploaded_file($tempName, "./img/" . $newName);
                                    $mysqli->query('INSERT INTO images (image_id, caption, credit, author, file_type) VALUES ("' . $imageid . '","' . $caption . '","' . $credit . '", "' . $_SESSION['logged_user'] . '", "' . end($temp) . '")');
                                    if ($albumInput != ""){
                                        $result = $mysqli->query('SELECT position, image_id FROM albumset WHERE album_id = "' . $albumInput . '" ORDER BY position DESC');
                                        $row = $result->fetch_row();

                                        if ($row[1] != "NAVAIL"){
                                            $nextIndex = $row[0] + 1;
                                            $mysqli->query('INSERT INTO albumset (album_id, image_id, position) VALUES ("' . $albumInput . '","' . $imageid . '",' . $nextIndex . ')');  
                                            $mysqli->query('UPDATE albums SET date_modified = CURRENT_TIMESTAMP WHERE album_id = "' . $albumInput .'"');
                                        }
                                        else{
                                            $mysqli->query('UPDATE albumset SET image_id = "' . $imageid . '" WHERE album_id = "' . $albumInput . '"');  
                                            $mysqli->query('UPDATE albums SET cover_id = "' . $imageid . '", date_modified = CURRENT_TIMESTAMP WHERE album_id = "' . $albumInput . '"');
                                        }
                                    }
                                    echo "Image uploaded successfully.";
                                }
                                else{
                                    echo "You didn't select an image, or your image was greater than 10MB in size.";
                                }
                                
                            }
                            else{
                                echo 'Select an image to upload.';
                            }
                        }
                    }
                    
                }
                else{
                    echo '<p class = "album-info">You must be logged in to add an image!</p>';
                }
            ?>
        </div>
    </body>
</html>