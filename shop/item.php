<?php
include("../SiT_3/config.php");
include("../SiT_3/header.php");
include("../SiT_3/PHP/helper.php");
//if(!$loggedIn) {header("Location: /index"); die();}
if($loggedIn) {
  $userID = $_SESSION['id'];
  $userSQL = "SELECT * FROM `beta_users` WHERE `id`='$userID'";
  $user = $conn->query($userSQL);
  $currentUserRow = $user->fetch_assoc();
}

if (isset($_GET['id'])) {
  $itemID = mysqli_real_escape_string($conn,intval($_GET['id']));
  $sql = "SELECT * FROM `shop_items` WHERE  `id` = '$itemID'";
  $result = $conn->query($sql);
  $shopRow = $searchRow=$result->fetch_assoc();
  if($_GET['id'] == $shopRow['id']) {} else {echo"<script>location.replace('/shop/');</script>";}
    //header('Location: /shop/');}
  $id = $searchRow['owner_id'];
  $sqlUser = "SELECT * FROM `beta_users` WHERE  `id` = '$id'";
  $userResult = $conn->query($sqlUser);
  $userRow=$userResult->fetch_assoc();
} else {
  //echo"<script>location.replace('/shop/');</script>";
  header('Location: /shop/');
}

if($loggedIn) {
  if (isset($_GET['approve']) && $currentUserRow['power'] >= 1) {
    $approveSQL = "UPDATE `shop_items` SET `approved`='yes' WHERE `id`='$itemID'";
    $approve = $conn->query($approveSQL);
    //echo'<script>location.replace("/shop/item/'.$itemID.'");</script>';
    header('Location: /shop/item/'.$itemID.'');
  }
  if (isset($_GET['decline']) && $currentUserRow['power'] >= 1) {
    $declineSQL = "UPDATE `shop_items` SET `approved`='declined' WHERE `id`='$itemID'";
    $decline = $conn->query($declineSQL);
    //echo'<script>location.replace("/shop/item/'.$itemID.'");</script>';
    //header('Location: item?id='.$itemID);
    header('Location: /shop/item/'.$itemID.'');
  }
  if (isset($_GET['desc']) && $currentUserRow['power'] >= 2) {
    $scrubSQL = "UPDATE `shop_items` SET `description`='[Content Removed]' WHERE `id`='$itemID'";
    $scrub = $conn->query($scrubSQL);
    
    $scrubSQL = "UPDATE `shop_items` SET `title`='[Content Removed]' WHERE `id`='$itemID'";
    $scrub = $conn->query($scrubSQL);
    //header('Location: item?id='.$itemID);
    //echo'<script>location.replace("/shop/item/'.$itemID.'");</script>';
    header('Location: /shop/item/'.$itemID.'');
  }
  if (isset($_GET['scrub_comment']) && $currentUserRow['power'] >= 2) {
    $comID = mysqli_real_escape_string($conn,intval($_GET['scrub_comment']));
    $scrubSQL = "UPDATE `item_comments` SET `comment`='[Content Removed]' WHERE  `id`='$comID'";
    $scrub = $conn->query($scrubSQL);
    //echo'<script>location.replace("/shop/item/'.$itemID.'");</script>';
    header('Location: /shop/item/'.$itemID.'');
  }
}

if ($shopRow['approved'] == 'yes') {$thumbnail = $searchRow['id'];}
elseif ($shopRow['approved'] == 'declined') {$thumbnail = 'declined';}
else {$thumbnail = 'pending';}

$soldSQL = "SELECT * FROM `crate` WHERE `item_id` = '$itemID' AND `user_id` !='$id' AND `own`='yes'";
$soldResult = $conn->query($soldSQL);
$amountSold = $soldResult->num_rows;

$realSoldSQL = "SELECT * FROM `crate` WHERE `item_id` = '$itemID' AND `own`='yes'";
$realSoldResult = $conn->query($realSoldSQL);
$realAmountSold = $realSoldResult->num_rows;

if($loggedIn) {
$currentUserID = $_SESSION['id'];
} else {
$currentUserID = 0;
}
$ownsSQL = "SELECT * FROM `crate` WHERE `item_id`='$itemID' AND `user_id`='$currentUserID'";
$owns = $conn->query($ownsSQL);
if($owns->num_rows > 0) {$owns = true;} else {$owns = false;}

if($loggedIn && (isset($_POST['buyBucks']) || isset($_POST['buyBits']) || isset($_POST['buyFree']))) { //if they sent a buy request

  $serialSQL = "SELECT * FROM `crate` WHERE `item_id`='$itemID' ORDER BY `serial` DESC"; //find the serial SQL
  $serialQ = $conn->query($serialSQL); //
  $serialRow = $serialQ->fetch_assoc(); //
  $serial = $serialRow['serial']+1; //find the serial
  
  $currentUserSQL = "SELECT * FROM `beta_users` WHERE `id`='$currentUserID'";
  $currentUser = $conn->query($currentUserSQL);
  $currentRow = $currentUser->fetch_assoc();
  
  if(isset($_POST['buyBucks'])) {$type = 'bucks'; $price = $shopRow['bucks'];}
  if(isset($_POST['buyBits'])) {$type = 'bits'; $price = $shopRow['bits'];}
  if(isset($_POST['buyFree'])) {$type = 'bits'; $price = '0';}
  
  $buySQL = "INSERT INTO `crate` (`id`,`user_id`,`item_id`,`serial`,`payment`,`price`) VALUES (NULL,'$currentUserID','$itemID','$serial','$type','$price')"; //get ready to give item
  
  
  $newBucks = $currentRow['bucks']-$shopRow['bucks']; //take bucks from customer
  $newBucksSQL = "UPDATE `beta_users` SET `bucks`='$newBucks' WHERE `id`='$currentUserID'"; //prep query for taking bucks
  $sellerBucks = $userRow['bucks']+(int)($shopRow['bucks']*0.8); //give bucks to seller 80% tax
  $sellerBucksSQL = "UPDATE `beta_users` SET `bucks`='$sellerBucks' WHERE `id`='$id'"; //prep query for giving bucks
  
  
  $newBits = $currentRow['bits']-$shopRow['bits']; //take bits from customer
  $newBitsSQL = "UPDATE `beta_users` SET `bits`='$newBits' WHERE `id`='$currentUserID'"; //prep query for taking bits
  $sellerBits = $userRow['bits']+(int)($shopRow['bits']*0.8); //give bits to seller 80% tax
  $sellerBitsSQL = "UPDATE `beta_users` SET `bits`='$sellerBits' WHERE `id`='$id'"; //prep query for giving bits
  

  if(!($owns)) {
    if($shopRow['collectible'] == 'yes') { //if it is a collectible
      if($amountSold >= $shopRow['collectible_q']) { //if it's out of still in stock
        //echo'<script>location.replace("/shop/item/'.$itemID.'&msg=stock");</script>';
        header('Location: /shop/item/'.$itemID.'&msg=stock');
      }
    }
    
    if(isset($_POST['buyFree'])) { //if they tried to buy it for free
      if($shopRow['bucks'] == 0 || $shopRow['bits'] == 0) { //if it is indeed free
        $buy = $conn->query($buySQL);
        //echo'<script>location.replace("/shop/item/'.$itemID.'&msg=success");</script>';
        header('Location: /shop/item/'.$itemID.'&msg=success');
      } else {
        //echo'<script>location.replace("/shop/item/'.$itemID.'&msg=error");</script>';
        header('Location: /shop/item/'.$itemID.'&msg=error');
      }
    }
    elseif(isset($_POST['buyBucks'])) { //tried to buy for bucks
      if($currentRow['bucks'] >= $shopRow['bucks'] && $shopRow['bucks'] > 0) { //check they have enough bucks AND if it was on sale for bucks
        $buy = $conn->query($buySQL);
        $newBucksQ = $conn->query($newBucksSQL);
        $sellerBucksQ = $conn->query($sellerBucksSQL);
       //echo'<script>location.replace("/shop/item/'.$itemID.'&msg=success");</script>';
        header('Location: /shop/item/'.$itemID.'&msg=success');
      } else {
        //echo'<script>location.replace("/shop/item/'.$itemID.'&msg=error");</script>';
        header('Location: /shop/item/'.$itemID.'&msg=money');
      }
    }
    elseif(isset($_POST['buyBits'])) { //tried to buy for bucks
      if($currentRow['bits'] >= $shopRow['bits'] && $shopRow['bits'] > 0) { //check they have enough bucks AND if it was on sale for bucks
        $buy = $conn->query($buySQL);
        $newBitsQ = $conn->query($newBitsSQL);
        $sellerBitsQ = $conn->query($sellerBitsSQL);
      //echo'<script>location.replace("/shop/item/'.$itemID.'&msg=success");</script>';
        header('Location: /shop/item/'.$itemID.'&msg=success');
      } else {
        //echo'<script>location.replace("/shop/item/'.$itemID.'&msg=error");</script>';
        header('Location: /shop/item/'.$itemID.'&msg=money');
      }
    }
  } else {
    //echo'<script>location.replace("/shop/item/'.$itemID.'&msg=have");</script>';
    header('Location: /shop/item/'.$itemID.'&msg=have');
  }
}


if($loggedIn && isset($_POST['sell'])) {
  $bucks = mysqli_real_escape_string($conn,intval($_POST['sell']));

  $crateSQL = "SELECT * FROM `crate` WHERE `item_id`='$itemID' AND `user_id`='$currentUserID' AND `own`='yes'";
  $crate = $conn->query($crateSQL);
  
  $sellSQL = "SELECT * FROM `special_sellers` WHERE `item_id`='$itemID' AND `user_id`='$currentUserID' AND `active`='yes'";
  $sell = $conn->query($sellSQL);
  
  if($crate->num_rows > $sell->num_rows && $bucks >= 1) {
    $crateRow = $crate->fetch_assoc();
    $serial = $crateRow['serial'];
    $listSQL = "INSERT INTO `special_sellers` (`id`,`user_id`,`item_id`,`serial`,`bucks`,`active`) VALUES (NULL,'$currentUserID','$itemID','$serial','$bucks','yes')";
    $list = $conn->query($listSQL);
  }
  
  header("Location: /shop/item/".$itemID);
  //echo'<script>location.replace("/shop/item/'.$itemID.'");</script>';
}
if($loggedIn && isset($_POST['remove'])) {
  $sale = mysqli_real_escape_string($conn,intval($_POST['sale']));
  
  $currentUserSQL = "SELECT * FROM `beta_users` WHERE `id`='$currentUserID'";
  $currentUser = $conn->query($currentUserSQL);
  $currentRow = $currentUser->fetch_assoc();
  
  $sellSQL = "SELECT * FROM `special_sellers` WHERE `active`='yes'";
  $sell = $conn->query($sellSQL);
  $sellRow = $sell->fetch_assoc();
  
  if($currentRow['id'] == $sellRow['user_id']) {
    $updateSaleSQL = "UPDATE `special_sellers` SET `active`='no' WHERE `id`='$sale'";
    $updateSale = $conn->query($updateSaleSQL);
  }
  header("Location: /shop/item/".$itemID);
  //echo'<script>location.replace("/shop/item/'.$itemID.'");</script>';
}

if($loggedIn && isset($_POST['buySale'])) { ///NOT TESTED YET
  $sale = mysqli_real_escape_string($conn,intval($_POST['buySale']));
  
  $currentUserSQL = "SELECT * FROM `beta_users` WHERE `id`='$currentUserID'";
  $currentUser = $conn->query($currentUserSQL);
  $currentRow = $currentUser->fetch_assoc();
  
  $sellSQL = "SELECT * FROM `special_sellers` WHERE `id`='$sale'";
  $sell = $conn->query($sellSQL);
  $sellRow = $sell->fetch_assoc();
  
  $bucks = $sellRow['bucks'];
  if($sellRow['user_id'] != $_SESSION['id'] && $bucks <= $currentRow['bucks'] && $sellRow['active'] == 'yes') {
    $newBucks = $currentRow['bucks']-$bucks;
    
    $updateSaleSQL = "UPDATE `special_sellers` SET `active`='no' WHERE `id`='$sale'";
    $updateSale = $conn->query($updateSaleSQL);
    
    $sellerID = $sellRow['user_id'];
    $sellItemID = $sellRow['item_id'];
    $sellSerial = $sellRow['serial'];
    
    $sellerSQL = "SELECT * FROM `beta_users` WHERE `id`='$sellerID'";
    $seller = $conn->query($sellerSQL);
    $sellerRow = $seller->fetch_assoc();
    $sellerBucks = $sellerRow['bucks']+round(0.8*$bucks);
    
    $removeSQL = "UPDATE `crate` SET `own`='no' WHERE `user_id`='$sellerID' AND `item_id`='$sellItemID' AND `serial`='$sellSerial'";
    $remove = $conn->query($removeSQL);
    
    $addSQL = "INSERT INTO `crate` (`id`,`user_id`,`item_id`,`serial`,`payment`,`price`) VALUES (NULL,'$currentUserID','$sellItemID','$sellSerial','bucks','$bucks')";
    $add = $conn->query($addSQL);
    
    $newSellerMoneySQL = "UPDATE `beta_users` SET `bucks`='$sellerBucks' WHERE `id`='$sellerID'";
    $newSellerMoney = $conn->query($newSellerMoneySQL);
    $newBuyerMoneySQL = "UPDATE `beta_users` SET `bucks`='$newBucks' WHERE `id`='$currentUserID'";
    $newBuyerMoney = $conn->query($newBuyerMoneySQL);
  }
}

?>




<!DOCTYPE html>
  <head>
    <title><?php echo htmlentities($shopRow['name']); ?> -  <?php echo $sitename; ?></title>
  </head>
  <body>
    <div id="body">
    
    <?php
  if(isset($_GET['msg'])){
    $msg = $_GET['msg'];
    if($msg == "money"){
      echo "<h5 style='text-align:center; background-color:red; color:white; margin-top:5px; margin-left: 0px; margin-right: 0px; padding-top: 5px; padding-bottom: 5px;'>You dont have enough money to buy $shopRow[name].</h5>";
    } elseif ($msg == "have") {
      ?>    <div class="col-10-12 push-1-12">
<div class="alert error">
You already own <?=$shopRow['name']?>
      </div>
</div><?php
    } elseif ($msg == "stock"){
      echo "<h5 style='text-align:center;background-color:red;color:white;margin-top:5px; margin-left: 0px; margin-right: 0px; padding-top: 5px; padding-bottom: 5px;'>This item is out of stock.</h5>";
    } elseif ($msg == "success") {
      ?><div class="col-10-12 push-1-12">
<div class="alert success">
<?=$shopRow['name']?> has been successfully purchased!
      </div>
</div><?php
    } elseif ($msg == "error") {
      echo "<h5 style='text-align:center;background-color:green;color:white;margin-top:5px; margin-left: 0px; margin-right: 0px; padding-top: 5px; padding-bottom: 5px;'>An unknown error occurred.</h5>";
    }
  }
    ?>

      <div id="box">
      <div class="main-holder grid">
<div class="col-10-12 push-1-12 shop-bar">
<div class="col-9-12">
<div class="card">
<div class="content">
<div class="overflow-auto">
<div class="col-8-12">
<input id="search-bar" type="text" style="height:41px;" class="input rigid width-100" placeholder="Search">
</div>
<div class="col-2-12 mobile-col-1-2">
<button class="button blue mobile-fill" id="search" style="font-size:15px;">Search</button>
</div>
<div class="col-2-12 mobile-col-1-2">
<a class="button green mobile-fill" href="/web/20191118013939/https://www.brick-hill.com/shop/create" style="font-size:15px;">Create</a>
</div>
</div>
<hr>
<div class="shop-categories">
<div class="category active">
<a data-item-type="all">
All
</a>
</div>
<div class="category ">
<a data-item-type="hat">
Hats
</a>
</div>
<div class="category ">
<a data-item-type="tool">
Tools
</a>
</div>
<div class="category ">
<a data-item-type="tshirt">
T-Shirts
</a>
</div>
<div class="category ">
<a data-item-type="face">
Faces
</a>
</div>
<div class="category ">
<a data-item-type="shirt">
Shirts
</a>
</div>
<div class="category ">
<a data-item-type="pants">
Pants
</a>
</div>
<div class="category ">
<a data-item-type="head">
Heads
</a>
</div>
</div>
</div>
</div>
</div>
<div class="col-3-12">
<div class="card">
<div class="content">
<div class="select-color-text mb1">Advanced Sort</div>
<hr style="margin-top:-3px;">
<select id="shopSort" class="select width-100">
<option data-sort="updated">Recently Updated</option>
<option data-sort="newest">Newest First</option>
<option data-sort="oldest">Oldest First</option>
<option data-sort="expensive">Most Expensive</option>
<option data-sort="inexpensive">Least Expensive</option>
</select>
</div>
</div>
</div>
</div>
 <div class="col-10-12 push-1-12 item-holder">
<div class="card mb4">
<div class="content item-page">
<div class="col-5-12" style="padding-right:10px;">
<!--<div class="box relative shaded item-img ">-->
<?php echo'<div class="box relative shaded item-img ';if($shopRow['collectable-edition'] == 'yes'){echo'special';} elseif($shopRow['collectible'] == 'yes'){echo'special';} echo'">';?>
          <?php
          if(isset($_GET['render']) && ($shopRow['owner_id'] == $_SESSION['id'] || $currentUserRow['power'] >= 1)) {
            echo '<iframe style="background-color:#FFF;float:left;border:1px #c3cdd2 solid;width:340px;height:340px;" src="/avatar/render/shop_render?id='.$shopRow['id'].'"></iframe>';
          }
  if(isset($_GET['hatrender']) && ($shopRow['owner_id'] == $_SESSION['id'] || $currentUserRow['power'] >= 1)) {
            echo '<iframe style="background-color:#FFF;float:left;border:1px #c3cdd2 solid;width:340px;height:340px;" src="/avatar/render/hat?id='.$shopRow['id'].'"></iframe>';
          }
  if(isset($_GET['toolrender']) && ($shopRow['owner_id'] == $_SESSION['id'] || $currentUserRow['power'] >= 1)) {
            echo '<iframe style="background-color:#FFF;float:left;border:1px #c3cdd2 solid;width:340px;height:340px;" src="/avatar/render/hat?id='.$shopRow['id'].'"></iframe>';
          }
  if(isset($_GET['headrender']) && ($shopRow['owner_id'] == $_SESSION['id'] || $currentUserRow['power'] >= 1)) {
            echo '<iframe style="background-color:#FFF;float:left;border:1px #c3cdd2 solid;width:340px;height:340px;" src="/avatar/render/head?id='.$shopRow['id'].'"></iframe>';
          }
if(isset($_GET['frender']) && ($shopRow['owner_id'] == $_SESSION['id'] || $currentUserRow['power'] >= 1)) {
            echo '<iframe style="background-color:#FFF;float:left;border:1px #c3cdd2 solid;width:340px;height:340px;" src="/avatar/render/face?id='.$shopRow['id'].'"></iframe>';
          }
if(isset($_GET['srender']) && ($shopRow['owner_id'] == $_SESSION['id'] || $currentUserRow['power'] >= 1)) {
            echo '<iframe style="background-color:#FFF;float:left;border:1px #c3cdd2 solid;width:340px;height:340px;" src="/avatar/render/srender?id='.$shopRow['id'].'"></iframe>';
          }

//trender got removed due to it is being useless

          else {
            echo '<img id="shopItem" src="/shop_storage/thumbnails/'.$thumbnail.'.png?c='. rand() .'"';
            if($shopRow['collectable-edition'] == 'yes'){echo 'background-image:url(http://epic.ct8.pl/shop/speciale_big.png); background-size:cover; border:0px; width:342px; height:342px;';}
            elseif($shopRow['collectible'] == 'yes'){echo 'background-image:url(http://epic.ct8.pl/shop/special_big.png); background-size:cover;border:0px; width:342px; height:342px;';}
            elseif($shopRow['bits'] == '0' || $shopRow['bucks'] == '0'){echo 'background-image:url(http://epic.ct8.pl/shop/free_big.png); background-size:cover;border:0px; width:350px; height:350px;';}
            echo '">';
          }
            ?>
</div>
</div>
<div class="col-7-12 item-data">
<div class="padding-bottom">
<div class="ellipsis">
<?php
          if($loggedIn) {
            echo '
<dropdown id="dropdown-v" class="dropdown" style="right:7.5px;">
<ul>';?>

<?php if($shopRow['owner_id'] == $_SESSION['id'] or $currentUserRow['power'] >= 1) {
              echo '<li>
<a href="/shop/edit/'.$shopRow['id'].'">Edit</a>
</li>
<li>
<a href="/shop/item/'.$shopRow['id'].'&render">Render</a>
</li>';
  }
  ?>
  
  <?php if($shopRow['approved'] != 'yes' && $currentUserRow['power'] >= 1) {
              echo '<li>
<a href="/shop/item/'.$shopRow['id'].'&approve">Approve</a>
</li>';
  }
if($currentUserRow['power'] >= 1) {
              echo '
<li>
<a href="/shop/item/'.$shopRow['id'].'&decline">Decline</a>
</li>';
  }
  
  ?>
<?php echo'<li>
<a href="/report?type=item&id='.$shopRow['id'].'">Report</a>
</li>';
  ?>
</ul>
</dropdown>
  <?php
  }
    ?>
        <?php
          echo '<span class="medium-text bold ablack-text">' . htmlentities($shopRow['name']).'</span><span> '.$shopRow['type'].'</span>

</div>
<div class="item-creator">
By
<a href="/user/'.$userRow['id'].'/">'.$userRow['username'].'</a>' ;
          
            if ($loggedIn) {
      if ($power > 3) {
        echo '<span style="font-size:15px;">('.shopItemHash($shopRow['id']).')</span>';
      }
    }
      echo '</h3>';
        ?>
  <span style="display:inline-block; float:left; width:640px;">
          <?php
            if ($shopRow['collectible'] == 'yes' || $shopRow['collectable-edition'] == 'yes') {
              $remaining = $shopRow['collectible_q']-$realAmountSold;
              echo "<span style=";
              if($remaining > 0) {echo 'font-weight:bold;';}
              echo "display:block;color:red;'><br>";
              echo $remaining." out of ".$shopRow['collectible_q']." remaining</span>";
            } else {$remaining = 1;}
            
            if($remaining > 0 ) {
      if ($shopRow['bucks'] == 0 || $shopRow['bits'] == 0) {
        echo "<span style='display:inline-block;'>
          <br>
                    <form method='post' action=''>
            <button type='submit' name='buyFree' class='button purchase blue flat no-cap'>FREE</button>
          </form>
        </span>";
      }
      
      if ($shopRow['bucks'] >= 1) {
        echo "<span style='display:inline-block;''>
          <a style='color:Green;'></a>
          <br>
          <form method='post' action=''>
            <button type='submit' name='buyBucks' class='button purchase bucks flat no-cap'><span class='bucks-icon img-white'></span>
        $shopRow[bucks] Bucks
    </button>
          </form>
        </span> ";
      }
      
      if ($shopRow['bits'] >= 1) {
        echo "<span style='display:inline-block;''> </a>
          <br>
          <form method='post' action=''>
           <button type='submit' name='buyBits' class='button purchase bits flat no-cap'><span class='bits-icon img-white'></span>
       $shopRow[bits] Bits
    </button>
          </form>
        </span>";
      }
    }
            
          ?>
</div>
          <div class="padding-10"></div>
<div class="agray-text bold">
<?php echo str_replace("\n","<br>",str_replace("<","<",str_replace(">","&gt;",$shopRow['description']))); ?>
 <?php
    if($loggedIn) {
      if($power >= 2) {
        echo '<p><span><a class="label" href="item?id='.$shopRow['id'].'&desc">Scrub</a></span></p>';
      }
    }
    ?>
 </div>
<div class="padding-30"></div>
<div class="small-text mt6 mb2">
<div class="item-stats">
<span class="agray-text">Created:</span>
<span class="darkest-gray-text" title="<?php echo'' . gmdate('d/m/Y',strtotime($shopRow['date'])) . '';?>">
<?php echo'' . gmdate('d/m/Y',strtotime($shopRow['date'])) . '';?></span>
</div>
<div class="item-stats">
<span class="agray-text">Updated:</span>
<span class="darkest-gray-text" title="<?php echo'' . gmdate('d/m/Y',strtotime($shopRow['last_updated'])) . ''; ?>">
<?php
 echo'' . gmdate('d/m/Y',strtotime($shopRow['last_updated'])) . ''; ?>
</span>
</div>
<div class="item-stats">
<span class="agray-text">Sold:</span>
<span class="darkest-gray-text">
<?php echo $amountSold; ?>
</span>
</div>
</div>
<favorite id="favorite-v" poly_id="259484" on_load_favorites="9" on_load_favorited="" logged_in="" type="1"></favorite>
          
          
          <?php
          if($loggedIn) {
            echo '<span><a href="/report?type=item&id='.$shopRow['id'].'"><i style="color:#444;font-size:13px;" class="fa fa-flag"></i></a></span>';
            
            if($shopRow['owner_id'] == $_SESSION['id'] or $currentUserRow['power'] >= 1) {
              echo '<span><a href="/shop/item/'.$shopRow['id'].'&render"><i style="color:#444;font-size:13px;" class="fa fa-refresh"></i></a></span>';
              echo '<span><a href="/shop/edit/'.$shopRow['id'].'"><i style="color:#444;font-size:13px;" class="fa fa-pencil"></i></a></span>';
            }
            
            if($shopRow['approved'] != 'yes' && $currentUserRow['power'] >= 1) {
              echo '<br><a style="color:#444;" href="/shop/item/'.$shopRow['id'].'&approve">Approve</a>';
            }
            if($currentUserRow['power'] >= 1) {
              echo '<br><a style="color:#444;" href="/shop/item/'.$shopRow['id'].'&decline">Decline</a>';
            }
          }
        ?>
      <?php
        if($remaining <= 0) {
      ?>
              <?php
            $crateSQL = "SELECT * FROM `crate` WHERE `item_id`='$itemID' AND `user_id`='$currentUserID' AND `own`='yes'";
            $crate = $conn->query($crateSQL);
            if($crate->num_rows >= 1) {
              echo '<br><br><br><br><form style="float:right;margin:10px;" action="" method="POST">
              <input type="text" name="sell" type="number" min="1">
              <input type="submit" value="Sell">
              </form>';
            }
          ?>
    </div></div></div></div>
<div class="col-1-2">
<div class="card" style="margin-bottom:20px;">
<div class="content item-page">
<div id="privatesellers-v"><span class="item-name" style="font-size: 1.1rem;">Private Sellers</span><hr>
         </div> 
        <?php
    $sellersSQL = "SELECT * FROM `special_sellers` WHERE `item_id`='$itemID' ORDER BY `bucks` ASC";
    $sellers = $conn->query($sellersSQL);
    while($sellRow = $sellers->fetch_assoc()) {
      $sellerID = $sellRow['user_id'];
      $bucks = $sellRow['bucks'];
      
      if($sellRow['active'] == 'yes') {
        $sellerSQL = "SELECT * FROM `beta_users` WHERE `id`='$sellerID'";
        $seller = $conn->query($sellerSQL);
        $sellerRow = $seller->fetch_assoc();
        
        //NEEDS CSS MAGIC
        echo '
        <div id="subsect" style="overflow:auto;">
          <form action="" method="POST">
          <div style="width:100px;float:left;">
            <a href="/user?id='.$sellerID.'"><img style="width:70px;" src="/avatar/render/avatars/'.$sellerID.'.png?c='.$shopRow['avatar_id'].'"></a>
            <br><a href="/user?id='.$sellerID.'">'.$sellerRow['username'].'</a>
           </div>
            <input type="hidden" name="buySale" value="'.$sellRow['id'].'">';
          if($sellerID != $_SESSION['id']) {
            echo '<label style="float:right;" class="label">#'.$sellRow['serial'].' of '.$shopRow['collectible_q'].'</label><br><button style="float:right;" type="submit" name="buyBucks" class="button purchase bucks flat no-cap">BUY FOR <span class="bucks-icon img-white"></span> '.number_format($bucks).'</button>';
        } else {
            echo '<input type="hidden" name="sale" value="'.$sellRow['id'].'">
            <label style="float:right;" class="label">#'.$sellRow['serial'].' of '.$shopRow['collectible_q'].'</label><br>                                                         
            <button style="float:right;" class="button purchase bits flat no-cap"" name="remove">REMOVE  <span class="bucks-icon img-white"></span> '.number_format($bucks).'</button>';
        }
        echo '</form>
        </div>';
      }
    }
        ?>
      </div>
      <?php
        }
      ?>
      </div>
  
      </div>
    
            </div>
      </div>
    
            </div>
    <div class="col-10-12 push-1-12 item-holder">
  <div class="col-10-12 push-1-12">
    <div style="text-align: center; margin-top: -7.5px; padding-bottom: 5px;"></div>
  </div>
  <div class="tabs" id="shopbottom-v">
    <div class="tab active col-1-2">Comments</div>
    <div class="tab-holder">
      <div class="tab-body active" style="display: inherit;">
         <?php error_reporting(0);
        if($loggedIn && isset($_POST['comment'])) {
          $lastCommentSQL = "SELECT * FROM `item_comments` WHERE `author_id`='$userID' ORDER BY `id` DESC";
          $lastCommentQ = $conn->query($lastCommentSQL);
          if($lastCommentQ->num_rows > 0) {
            $lastCommentRow = $lastCommentQ->fetch_assoc();
            $lastComment = $lastCommentRow['time'];
          } else {
            $lastComment = 0;
          }
          
          if(time()-strtotime($lastComment) >= 10) {//they can post
            $comment = mysqli_real_escape_string($conn,$_POST['comment']);
            if(strlen($comment) >= 1 && strlen($comment) <= 1000) {
              $commentSQL = "INSERT INTO `item_comments` (`id`,`author_id`,`item_id`,`comment`,`time`) VALUES (NULL,'$userID','$itemID','$comment',CURRENT_TIMESTAMP)";
              $commentQ = $conn->query($commentSQL);
              header("Location: item?id=".$itemID);
      } else {
        echo 'Comment must be between 1 and 1000 characters';
      }
          } else {
            echo 'Please wait before posting again';
          }
        }
               if ($loggedIn) { ?>

        <form method="POST" action="">
          <span class="smedium-text bold">Comment</span>
          <textarea name="comment" placeholder="Enter Comment" class="width-100 mb2" style="height: 80px;"></textarea>
          <input type="submit" value="Post" class="button blue">
        </form>
        <?php } ?>
        <hr>
        <div id="comments-v" class="comments-holder">
          <div>
            <div>
              <?php
        $comments = mysqli_query($conn, "SELECT * FROM `item_comments` WHERE `item_id`='$itemID' ORDER BY `id` DESC");

        while($commentRow = mysqli_fetch_assoc($comments)) {
              $commentUserID = $commentRow['author_id'];
              $userDataQ = mysqli_query($conn,"SELECT * FROM `beta_users` WHERE `id`='$commentUserID'");
              $userData = mysqli_fetch_array($userDataQ);
              ?><div class="comment">
                <div class="col-1-7">
                  <a href="/user/<?=$commentUserID;?>/" class="user-link">
                    <div class="comment-holder ellipsis">
                      <img src="/avatar/render/avatars/<?=$commentUserID;?>.png?c=<?=rand();?>">
                      <span class="ellipsis dark-gray-text"><?=$userData['username']?></span>
                    </div>
                  </a>
                </div>
                <div class="col-10-12">
                  <div class="body">
                    <div class="light-gray-text"><?=$commentRow['time']?></div>
                    <!----> <!----> <!---->
                    <div style="margin-top: 10px;">
                      <?php echo htmlentities($commentRow['comment']); ?>
                    </div>
                  </div>
                </div>
              </div>
               <?php
              if($loggedIn && $power >= 5) {
                echo '<div><a class="label" href="item?id='.$shopRow['id'].'&scrub_comment='.$commentRow['id'].'">Scrub</a></div>';
              }
             echo '<hr>'; 
              echo '</div>';
        }
        ?>
              
            <div>
                    </div>
      </div>
            </div>
      </div>
            </div>
      </div>
                </div>
      </div>            </div>
           

  </body>
  <?php
// ^^^ terrible site design awards 2024
  include("../SiT_3/footer.php");
  ?>
</html>