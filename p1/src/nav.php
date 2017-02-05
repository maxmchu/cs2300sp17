<div class = "left-sidebar">
    <h1 class = "nav-title">Meme Groups</h1>
    <ul class = "nav-list">
        <?php

        $schools = array(
            'UC Berkeley Memes for Edgy Teens' => 'ucberkeley.php',
            'UCLA Memes for Sick AF Tweens' => 'ucla.php',
            'Columbia Buy Sell Memes' => 'columbia.php',
            'Make Cornell Meme Again' => 'cornell.php',
            'Stanford Memes for Edgy Trees' => 'stanford.php'
        );

        foreach($schools as $name => $link){
            print("<li class = \"nav-item\"><a href = \"/p1/schools/$link\">$name</a></li>");
        }   
        
        ?>
    </ul>
</div>