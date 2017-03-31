<?php
    session_start();
    require_once("./php/config.php");
    $mysqli = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

    $userinput = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW);
    $pwinput = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW);
    
    if (!empty($userinput) && !empty($pwinput) && !isset($_SESSION['logged_user'])){
        $pwhash = password_hash($pwinput, PASSWORD_DEFAULT);
        
        $loginResult = $mysqli->query('SELECT * FROM users WHERE username = "' . $userinput . '"');

        if ($loginResult->num_rows > 0){
            $userRow = $loginResult->fetch_row();
            if(password_verify($pwinput, $userRow[2])){
                $_SESSION['logged_user'] = $userinput;
                $_SESSION['admin'] = $userRow[3];    
            }
        } 
    }
    
?>
<!doctype html>
<html>
    <head>
        <title>Login</title>
        <link rel = "stylesheet" href = "./font-awesome/css/font-awesome.min.css">
        <link href="https://fonts.googleapis.com/css?family=Roboto:100,300,400" rel="stylesheet">
        <link rel = "stylesheet" href = "./css/stylesheet.css">
    </head>
    <body>
        <?php
            include("./php/banner.php");
        ?>
        
        <div class = "content">
            <h1 class = "album-title">Login</h1>
            <?php
                if ((empty($userinput) || empty($pwinput)) && !isset($_SESSION['logged_user'])){
                    echo '<form action = "login.php" method = "post" name = "loginForm" class = "album-add">
                        <p class = "album-info">Username</p>
                        <input type = "text" name = "username" class = "input-album" placeholder = "Enter your username">
                        <p class = "album-info">Password</p>
                        <input type = "password" name = "password" class = "input-album" placeholder = "Enter your password">
                        <br/>
                        <input type = "submit" class = "submit-album" value = "Login" name = "input-login">
                    </form>';
                }
                else{
                    if(isset($_SESSION['logged_user'])){
                        if ($_SESSION['admin'] == 1){
                            echo '<p class = "album-info">You are logged in with admin privleges. With great power comes great responsibility.</p>';
                        }
                        else{
                            echo '<p class = "album-info">You are logged in as a normal user.</p>';
                        }
                        echo '<p class = "album-info">
                                <form class = "album-add" method = "post" action = "./login.php">
                                    <input type = "submit" class = "submit-album" value = "Log out" name = "logout">
                                </form>
                              <p>';
                        if(isset($_POST['logout'])){
                            unset($_SESSION['logged_user']);
                            unset($_SESSION['admin']);
                            unset($_SESSION);
                            $_SESSION = array();
                            session_destroy();
                            echo '<p class = "album-info">You are now logged out. Click <a href = "./login.php">here</a> to login again.</p>';
                        }
                    }
                    else{
                        echo '<p class = "album-info">There was a problem logging in. Check your username and/or password and try again.</p>';
                        echo '<form action = "login.php" method = "post" name = "loginForm" class = "album-add">
                            <p class = "album-info">Username</p>
                            <input type = "text" name = "username" class = "input-album" placeholder = "Enter your username">
                            <p class = "album-info">Password</p>
                            <input type = "password" name = "password" class = "input-album" placeholder = "Enter your password">
                            <br/>
                            <input type = "submit" class = "submit-album" value = "Login" name = "input-login">
                        </form>';
                    }
                }
            ?>
        </div>
        
    </body>

</html>