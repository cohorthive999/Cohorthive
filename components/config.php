<?php

define('DB_Server','localhost');
define('DB_Username','root');
define('DB_Password',"");
define('DB_Name','CohortHive');

$conn=mysqli_connect(DB_Server,DB_Username,DB_Password,DB_Name);

if(!$conn){
    echo '<h1>'."Error in Connection".'</h1>';
}

function create_unique_id(){
    $characters="1234567890abcdefghijklmnopqrstuvwxyz";
    $str="";
    $characters_length=strlen($characters);
    for($i=0;$i<20;$i++){
        $str.=$characters[mt_rand(0,$characters_length-1)];
    }
    return $str;
}

function create_unique_code(){
    $characters="1234567890abcdefghijklmnopqrstuvwxyz";
    $str="";
    $characters_length=strlen($characters);
    for($i=0;$i<10;$i++){
        $str.=$characters[mt_rand(0,$characters_length-1)];
    }
    return $str;
}

?>