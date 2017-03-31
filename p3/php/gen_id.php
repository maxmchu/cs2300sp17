<?php
    require_once("./php/config.php");
    
    function gen_id(){
        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890";
        $str = "";
        $size = strlen($chars);
        for ($i = 0; $i < 6; $i++){
            $str .= $chars[rand(0, $size - 1)];
        }
        
        return $str;
    }

    function check_id($id){
        $mysqli = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
        $albumId = $mysqli->query('SELECT * FROM albums WHERE album_id = "' . $id . '"');
        if ($albumId->num_rows > 0){
            return false;
        }
        $imageId = $mysqli->query('SELECT * FROM images WHERE image_id = "' . $id . '"');
        if ($imageId->num_rows > 0){
            return false;
        }
        return true;
    }

    function get_id(){
        $id = "";
        do{
            $id = gen_id();
        }
        while (!check_id($id));
        return $id;
    }

?>