<div class = "nav">
    <ul class = "nav-list">
        <li class = "nav-item">
            <form class = "nav-search" action = "search.php" method = "get">
                <input class = "nav-search" type = "text" placeholder = "Search" name = "query" value = "">
                <button class = "nav-search-btn" type = "submit"><i class = "fa fa-search"></i></button>
            </form>
        </li>
        <li class = "nav-item"><a class = "nav-link" href = "./index.php">Albums</a></li>
        <li class = "nav-item"><a class = "nav-link" href = "./images.php">Images</a></li>
        <?php
            if (isset($_SESSION['logged_user'])){
                echo '<li class = "nav-item"><a class = "nav-link" href = "./addAlbum.php">Add Album</a></li>
                      <li class = "nav-item"><a class = "nav-link" href = "./addImage.php">Add Image</a></li>';
            }
        ?>
        
        <li class = "nav-item"><a class = "nav-link" href = "./login.php">
        <?php
            if (isset($_SESSION['logged_user'])){
                echo 'Logout';
            }
            else{
                echo 'Login';
            }
        ?>
        </a></li>
    </ul>
</div>