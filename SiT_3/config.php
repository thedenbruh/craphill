<?php 
//file_put_contents($_SERVER["DOCUMENT_ROOT"]."/SiT_3/connections.txt",$_SERVER['REMOTE_ADDR']."\n", FILE_APPEND | LOCK_EX);
error_reporting(0);

//"This is good as the Burger King." - 2017 Early Brick Hill Client Commentary.

 $conn = mysqli_connect( "localhost" , "root", "" , "anarchyhill2");
  if(!$conn) {
    //include("site/maint.php");
    die("Database Error");
  }
  
  //sorry, but every page should require a session -lukey
  if(session_status() == PHP_SESSION_NONE) {
    session_name("BRICK-SESSION");
    session_start();
  }
?>