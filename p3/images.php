<?php
    session_start();
?>
<!doctype html>
<html>
    <head>
        <title>All Images</title>
        <link rel = "stylesheet" href = "./font-awesome/css/font-awesome.min.css">
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
                    $result = $mysqli->query("SELECT * FROM images ORDER BY date_added DESC");
                    echo '<div class = "gallery-row">';
                    $count = 0;
                    while ($row = $result->fetch_row()){
                        if ($count == 4){
                            echo '</div>
                                  <div class = "gallery-row">';
                            $count = 0;
                        }
                        echo '<div class = "gallery-card">
                                <a class = "card-link" href = "./image.php?id=' . $row[0] . '">';
                        echo '      <img class = "gallery-img" alt = "" src = "./img/' . $row[0] . "." . $row[5] . '">';
                        echo 'Posted by ' . $row[4] . ' on ' . $row[3];
                        echo '</a></div>';
                        $count += 1;
                    }
                    echo '</div>';
                ?>
            </div>
        </div>
    </body>
</html>