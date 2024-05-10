<?php
include('../../SiT_3/config.php');
include('../../SiT_3/header.php');
include('../../SiT_3/PHP/helper.php');

if(!$loggedIn) { header('Location: ../');die(); }
if($power <= 2) { header('Location: ../');die(); }

if(isset($_POST['submit'])) {
  $error = array();
  
  if(isset($_FILES['object'])) {
    $itemOBJ = $_FILES['object'];
    $objName = $itemOBJ['name'];
    $objTmp = $itemOBJ['tmp_name'];
    $objType = end(explode( '.' , $itemOBJ['name']));
    if ($objType !== 'obj') {
      $error[] = 'Model must be .obj. Detected File Type: ' . $objType;
    } else {
      if(1 == 1) {
        if(1 == 1) {
          if(isset($_POST['name'])) {
            if(isset($_POST['bits']) or isset($_POST['bucks'])) {
              if(isset($_POST['special'])) {$stock = mysqli_real_escape_string($conn,$_POST['stock']); $collectible = 'yes';}
              else {$stock = 0; $collectible = 'no';}
              $name = mysqli_real_escape_string($conn,$_POST['name']);
              $desc = mysqli_real_escape_string($conn,$_POST['description']);
              $bits = mysqli_real_escape_string($conn,$_POST['bits']);
              $bucks = mysqli_real_escape_string($conn,$_POST['bucks']);
              
              if(isset($_POST['free'])) {$bits = 0; $bucks = 0;} else {$bits = max(1,$bits); $bucks = max(1,$bucks);}
              if(isset($_POST['offsale'])) {$bits = -1; $bucks = -1;}
              
              //$ownerID = $_SESSION['id'];
              $ownerID = -1;
              $today = date("Y-m-d");
              
              $newSQL = "INSERT INTO `shop_items` (`id`,`owner_id`,`name`,`description`,`bucks`,`bits`,`type`,`date`,`last_updated`,`offsale`,`collectible`,`collectable-edition`,`collectible_q`,`zoom`,`approved`) VALUES (NULL,'1','$name','$desc','$bucks','$bits','head','$today','$today','no','$collectible','$collectible','$stock',NULL,'yes')";
              $new = $conn->query($newSQL);
              $itemID = $conn->insert_id;
              
              /*$addItem = "INSERT INTO `crate` (`id`,`user_id`,`item_id`,`serial`) VALUES (NULL,'$ownerID','$itemID','1')";
              $add = $conn->query($addItem);*/
              
              move_uploaded_file($objTmp,"../../shop_storage/assests/heads/".shopItemHash($itemID).".obj");
              
              echo'<script>location.replace("../../../shop/item?id='.$itemID.'&headrender");</script>';
            } else {
              $error[] = "Your item needs a price!";
            }
          } else {
            $error[] = "Your item needs a name!";
          }
        } else {
          $error[] = "File size must be smaller than 2MB";
        }
          }else{
        $error[] = "You did not upload a head!";
        }
  }
}
}
?>

<!DOCTYPE html>
  <head>
    <title>Create Head - <?php echo $sitename; ?></title>
  </head>
  <body>
  <div class="main-holder grid">
<div class="col-10-12 push-1-12">
        <?php
        if(!empty($error)) {
          echo '<div style="background-color:#EE3333;margin:10px;padding:5px;color:white;">';
          foreach($error as $errno) {
            echo $errno."<br>";
          } 
          echo '</div>';
        }
        ?>
<div class="card">
<div class="top green">
Upload Head
</div>
<div class="content">
        <div style="float:left;">
          <form style="margin:10px;" action="" method="POST" enctype="multipart/form-data">
            <input type="text" name="name" style="font-size:14px;padding:4px;margin-bottom:10px;" placeholder="Title"><br>
            Model: <input type="file" name="object" style="margin-bottom:10px;"><br>
            <textarea name="description" placeholder="Description" style="width:320px;height:100px;margin-bottom:10px;"></textarea><br>
            <input style="margin-bottom:10px;" type="checkbox" name="special" value="special">Special<br>
            Stock: <input style="font-size:14px;padding:4px;margin-bottom:10px;width:80px;" type="number" name="stock"><br>
            <input style="margin-bottom:10px;" type="checkbox" name="offsale" value="offsale">Offsale<br>
            <input style="margin-bottom:10px;" type="checkbox" name="free" value="free">Free<br>
            Bits: <input style="font-size:14px;padding:4px;margin-bottom:10px;width:80px;" type="number" min="1" name="bits" placeholder="0 bits">
            Bucks: <input style="font-size:14px;padding:4px;margin-bottom:10px;width:80px;" type="number" min="1" name="bucks" placeholder="0 bucks"><br>
            <input type="submit" name="submit">
          </form>
        </div>
      </div>
    </div>
      </div>
    </div>
  </body>
</html>

<?php
include('../../SiT_3/footer.php');
?>