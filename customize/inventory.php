<?php
session_name("BRICK-SESSION");
session_start();
error_reporting(0);
include("../SiT_3/config.php");
$userID = $_SESSION['id'];
if($userID != 0) {
  if(isset($_GET['type']) && isset($_GET['page'])) {
    $page = mysqli_real_escape_string($conn,$_GET['page']);
    $type = mysqli_real_escape_string($conn,$_GET['type']);
  
    $itemsSQL = "SELECT `item_id` FROM `crate` WHERE `user_id`='$userID' AND `own`='yes' ORDER BY `id` DESC";
    $itemsResult = $conn->query($itemsSQL);
    
    $invItems = array();
    while($row=$itemsResult->fetch_assoc()){
      $invItems[] = $row['item_id'];
    }
    
    $shopItemsSQL = "SELECT * FROM `shop_items` WHERE `id` IN (".implode(',',array_map('intval',$invItems)).") AND `type`='$type' ORDER BY `id` DESC";
    $shopItems = $conn->query($shopItemsSQL);
    $items = $shopItems->num_rows;
    
    
    echo '<div style="width:100%;display:block;overflow:auto;">';
    
    $r = 0;
    $count = 1;
    while($itemRow=$shopItems->fetch_assoc()){
      if ($count%4 == 1) {
        echo "<div style=\"overflow:auto;\">";
      }
      $r++;
      if($r >= (($page+1)*9)) {break;}
      if($r >= ($page*9)) {
        if ($itemRow['approved'] == 'yes') {$thumbnail = $itemRow['id'];}
        elseif ($itemRow['approved'] == 'declined') {$thumbnail = 'declined';}
        else {$thumbnail = 'pending';}
      
        /*echo '<div style="clear:right;float:left;display:inline-block;width:22%;text-align:left;margin-right:2%">';
        echo '<button onclick="wear('.$itemRow['id'].')" style="color: #fff;background-color:green;position:absolute;margin:5px;">Wear</button>';
        echo '<img id="shopItem" style="width:100px;height:100px;" src="/shop_storage/thumbnails/'.$thumbnail.'.png?r='.time().'"><br>';
        echo '<a style="color:#000;" href="/shop/item/'.$itemRow['id'].'">'. htmlentities($itemRow['name']).'</a>';
        echo '</div>';*/
        /*echo'<a href="/shop/item/'.$itemRow['id'].'">
         <div class="profile-card award">
         <img src="/shop_storage/thumbnails/'.$thumbnail.'.png?c=' . rand() . '">
         <span class="ellipsis">'. htmlentities($itemRow['name']).'</span></div></a> ';*/
         echo '<div style="clear:right;float:left;display:inline-block;width:23%;text-align:left;margin-right:2%">';
        echo '<button onclick="wear('.$itemRow['id'].')" class="button small smaller-text green" style="position:absolute;margin:10px;padding:4px;">+</button>';
        echo '<div class="profile-card crate"><img src="/shop_storage/thumbnails/'.$thumbnail.'.png?c='.rand().'"><div class="ellipsis" style="color: rgb(118, 118, 118); height: 19px;">'. htmlentities($itemRow['name']).'</div></div>';
        echo '';
        echo '</div>';
      }
      if ($count%4 == 0) {
        echo "</div>";
      }
      $count++;
    }
    if ($count%4 != 1) { echo "</div>"; }
    echo '</div><div style="width:100%;display:block;overflow:auto;">';
    
    if(($items/8) > 1) {
      for($i = 0; $i < ($items/8); $i++)
      {
        echo '<a style="color:#000;" onclick="getPage(\''.$type.'\', '.$i.')">'.($i+1).'</a> ';
      }
    }
    
    echo '</div>';
  } else {
    echo 'Error loading inventory';
  }
} else {
  header("Location: ../login/");
}
?>