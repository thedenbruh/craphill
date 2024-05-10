<?php

  session_name("BRICK-SESSION");
  session_start();
  include($_SERVER["DOCUMENT_ROOT"]."/SiT_3/config.php");
  include($_SERVER["DOCUMENT_ROOT"]."/SiT_3/PHP/helper.php");
  
  if ($_SESSION['id'] == 0) {
    header("Location: /login/");
    die();
  }
  
  $id = $_SESSION['id'];
  $currentUserSQL = "SELECT * FROM `beta_users` WHERE `id` = '" . $id . "'";
  $currentUserQuery = $conn->query($currentUserSQL);
  $currentUserRow = $currentUserQuery->fetch_assoc();
  $power = $currentUserRow['power'];
  