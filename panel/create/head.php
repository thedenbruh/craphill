<?php
include('../../SiT_3/config.php');
include('../adminheader.php');
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
              
              $newSQL = "INSERT INTO `shop_items` (`id`,`owner_id`,`name`,`description`,`bucks`,`bits`,`type`,`date`,`last_updated`,`offsale`,`collectible`,`collectable-edition`,`collectible_q`,`zoom`,`approved`) VALUES (NULL,'$ownerID','$name','$desc','$bucks','$bits','head','$today','$today','no','$collectible','$collectible','$stock',NULL,'yes')";
              $new = $conn->query($newSQL);
              $itemID = $conn->insert_id;
              
              $addItem = "INSERT INTO `crate` (`id`,`user_id`,`item_id`,`serial`) VALUES (NULL,'$ownerID','$itemID','1')";
              $add = $conn->query($addItem);
              
              move_uploaded_file($objTmp,"../../shop_storage/assests/heads/".$itemID.".obj");
              
              header('Location: ../../../shop/item/'.$itemID.'&render');
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
    
        <?php
        if(!empty($error)) {
          echo '<div style="background-color:#EE3333;margin:10px;padding:5px;color:white;">';
          foreach($error as $errno) {
            echo $errno."<br>";
          }
          echo '</div>';
        }
        ?>
        <div class="container">
<div class="row">
<div class="col-4">
<h3>Admin</h3>
</div>
<div class="col-8 text-right">
</div>
</div>
<ul class="breadcrumb bg-white">
<li class="breadcrumb-item"><a href="/panel/">Admin</a></li>
<li class="breadcrumb-item active">Create New Head</li>
</ul>
<div class="card">
<div class="card-body">
<form action="" method="POST" enctype="multipart/form-data">
<input type="hidden" name="_token" value="z7v32yBDCKN7fHbe7KdPVjSx1fpKLCFAIX4Kjgd1"> <input type="hidden" name="type" value="hat">
<div class="row">
<div class="col-md-6">
<label for="name">Name</label>
<input class="form-control mb-2" type="text" name="name" placeholder="Item Name">
</div>
<div class="col-md-6">
<label for="price">Price</label>
<input class="form-control mb-2" type="number" name="bucks" placeholder="Credits" min="0" max="1000000">
  <input class="form-control mb-2" type="number" name="bits" placeholder="Bits" min="0" max="1000000">
</div>
</div>
<label for="description">Description</label>
<textarea class="form-control mb-2" name="description" placeholder="Item Description" rows="5"></textarea>
<label for="stock">Stock</label>
<input class="form-control mb-2" type="number" name="stock" placeholder="Limited Stock" min="0" max="500">

<div class="row mb-1">
<div class="col-md-6">
<label for="model">Model</label><br>
<input class="mb-3" name="object" type="file">
</div>
</div>
<label>Options</label>
<div class="row mb-1">
<div class="col-md-3">
<div class="form-check mb-2">
<input class="form-check-input" type="checkbox" name="offsale">
<label class="form-check-label" for="offsale">Offsale</label>
</div>
</div>
<div class="col-md-3">
<div class="form-check mb-2">
<input class="form-check-input" type="checkbox" name="special">
<label class="form-check-label" for="special">Limited</label>
</div>
</div>
<div class="col-md-3">
<div class="form-check mb-2">
<input class="form-check-input" type="checkbox" name="free">
<label class="form-check-label" for="free">Free</label>
</div>
</div>
</div>
<button class="btn btn-block btn-success" name="submit" type="submit">Create</button>
</form>
</div>
</div>
</div>
<?php
include('../adminfooter.php');
?>