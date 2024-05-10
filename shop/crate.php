<?php
  
session_name("BRICK-SESSION");
session_start();
include("../SiT_3/config.php");
if(isset($_GET['page'])) {
  $page = mysqli_real_escape_string($conn,$_GET['page']);
  $page = max($page,0);

  $limit = ($page)*12;
  if(isset($_GET['item']) || isset($_GET['search'])) {
    $crateSQL = "SELECT * FROM `shop_items` WHERE ";
    if(isset($_GET['item'])) {
      $item = mysqli_real_escape_string($conn,$_GET['item']);
      if($item == 'all') {
        $crateSQL = $crateSQL."`approved`='yes' AND `type`='hat' OR `type`='tool' OR `type`='head' OR `type`='face' AND ";
      } else {
        $crateSQL = $crateSQL."`approved`='yes' AND `type`='$item' AND ";
      }
    } else {$item = '';}
    if(isset($_GET['search'])) {
      $search = mysqli_real_escape_string($conn,$_GET['search']);
      $crateSQL = $crateSQL."`name` LIKE '%$search%' AND ";
    } else {$search = '';}
    $countSQL = $crateSQL."`approved`='yes'";
    $crateSQL = $crateSQL."`approved`='yes' ORDER BY `last_updated` DESC LIMIT $limit,12";
  } else {
    $crateSQL = "SELECT * FROM `shop_items` WHERE `type`='hat' OR `type`='tool' OR `type`='head' OR `type`='head' OR `type`='face' ORDER BY `last_updated` DESC LIMIT $limit,12";
    $countSQL = "SELECT * FROM `shop_items` WHERE `approved`='yes' AND (`type`='hat' OR `type`='tool' OR `type`='head' OR `type`='face')";
  }
  
  $result = $conn->query($crateSQL);
  $count = $conn->query($countSQL);
  $items = $count->num_rows;
  if ($items < 1) { echo '<div style="text-align: center;">No results found!</div>'; die();}
  $r = 0;
  echo '<div style="width:100%;display:block;overflow:auto;">';
  while($searchRow=$result->fetch_assoc()){
    $r++;
    //if($r >= (($page+1)*12)) {break;}
    //if($r >= ($page*12)) {
      $id = $searchRow['owner_id'];
      $sqlUser = "SELECT * FROM `beta_users` WHERE  `id` = '$id'";
      $userResult = $conn->query($sqlUser);
      $userRow=$userResult->fetch_assoc();
    
       
      
      /* if($searchRow['collectable-edition']=='yes'){
        echo'border:5px solid #FFD52D;';
      }
      elseif($searchRow['collectible']=='yes') {
        echo'border:5px solid #FFD52D;';
      }
      elseif($searchRow['bits']==0 or $searchRow['bucks']==0){
        echo'style="background-image:url(\'free_ban.png\'); background-size:cover; border:0px; width:142px; height:142px;"';
      }*/
    
      if ($searchRow['approved'] == 'yes') {$thumbnail = $searchRow['id'];}
      elseif ($searchRow['approved'] == 'declined') {$thumbnail = 'declined';}
      else {$thumbnail = 'pending';}
      
      
      echo '<div class="col-1-4 mobile-col-1-1 mobile-half-fill">
        <div class="card" style="height:47%;width:auto;">
          <a href="/shop/item/'. $searchRow['id'] .'">
            <div class="thumbnail dark" style="position:relative;';if($searchRow['collectable-edition']=='yes'){
        echo'border:5px solid #FFD52D;';
      }if($searchRow['collectible']=='yes'){
        echo'border:5px solid #FFD52D;';
      }
    echo'">';
              if($searchRow['collectable-edition']=='yes'){
        echo'<span class="special-e-icon"></span>';
      }
    elseif($searchRow['collectible']=='yes'){
        echo'<span class="special-icon"></span>';
      }
    echo'
              
              <img src="/shop_storage/thumbnails/'.$thumbnail.'.png?c=' . rand() . '" alt="'. substr(htmlentities($searchRow['name']),0,17). '">
            </div>
          </a>
          <div class="item-content">
            <a href="/shop/item/'. $searchRow['id'] .'" style="color:#000;">
              <span class="name">'. substr(htmlentities($searchRow['name']),0,17). '</span>
            </a>
            <div class="creator">
              By
              <a href="/user/'. $userRow['id'] .'">'. $userRow['username'] .'</a>
            </div>';
                                                               
    ?>
<?php
           echo' <div class="price">';
                          if ($searchRow['bucks'] >= 1) {
        echo '<span class="bucks-text"><span class="bucks-icon"></span> '. $searchRow['bucks'] .'</span>
           <div style="width:5px;display:inline-block;"></div>';}
      elseif ($searchRow['bucks'] == 0)
        {echo '<span class="offsale-text">Free</span>';}
      if ($searchRow['bits'] >= 1) {
        echo '<span class="bits-text"><span class="bits-icon"></span> '. $searchRow['bits'] .'';}
      elseif ($searchRow['bits'] == 0 && $searchRow['bucks'] != 0)
        {echo '<span class="offsale-text">Free</span>';}
    elseif ($searchRow['bits'] == -1 && $searchRow['bucks'] != -1)
        {echo '<span class="offsale-text">Offsale</span>';}
    elseif ($searchRow['bucks'] == -1)
        {echo '<span class="offsale-text">Offsale</span>';}
      echo '</span></div>
                         
            
          </div>
        </div>
      </div>';
                    
                ?>
      <?php
    //}
  }
  ?>
  <?php
  if(($items/12) > 1) {
    echo '<div>';
    for($i = 0; $i < ($items/12); $i++)
    {
      echo '<a class="page active" style="color:white;" onclick="getPage(\''.$item.'\',\''.$search.'\', '.$i.')">'.($i+1).'</a> ';
    }
    echo '</div>';
  }
  
  echo '</div>';
} else {
  echo 'Error loading store';
  die();
}

?>