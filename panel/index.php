<?php

  include("../SiT_3/configuration.php");
  include("adminheader.php");
 if($power < 1) {header("Location: ../");die();}

  if(isset($_GET['seen'])) {
      $seenID = mysqli_real_escape_string($conn,$_GET['seen']);
      $seenSQL = "UPDATE `reports` SET `seen`='yes' WHERE `id`='$seenID'";
      $seen = $conn->query($seenSQL);
      header("Location: index");
    }
    
  if(isset($_POST['grant']) && isset($_POST['user']) && isset($_POST['item'])) {
    $userID = mysqli_real_escape_string($conn,intval($_POST['user']));
    $itemID = mysqli_real_escape_string($conn,$_POST['item']);
    
    if($userID != $_SESSION['id']) {
      $serialSQL = "SELECT * FROM `crate` WHERE `item_id`='$itemID' ORDER BY `serial` DESC";
      $serialQ = $conn->query($serialSQL);
      $serialRow = $serialQ->fetch_assoc();
      $serial = $serialRow['serial']+1;
      
      $addSQL = "INSERT INTO `crate` (`id`,`user_id`,`item_id`,`serial`) VALUES (NULL,'$userID','$itemID','$serial')";
      $add = $conn->query($addSQL);
      
      $admin = $_SESSION['id'];
      $action = 'Granted user'.$userID.' item '.$itemID;
      $date = date('d-m-Y H:i:s');
      $adminSQL = "INSERT INTO `admin` (`id`,`admin_id`,`action`,`time`) VALUES (NULL ,  '$admin',  '$action',  '$date')";
      $admin = $conn->query($adminSQL);
    }
  }
  
  if(isset($_POST['money']) && isset($_POST['user']) && isset($_POST['bits']) && isset($_POST['bucks'])) {
    $userID = mysqli_real_escape_string($conn,intval($_POST['user']));
    $bits = mysqli_real_escape_string($conn,$_POST['bits']);
    $bucks = mysqli_real_escape_string($conn,$_POST['bucks']);
    
    if($userID != $_SESSION['id']) {
      $userSQL = "SELECT * FROM `beta_users` WHERE `id`='$userID'";
      $user = $conn->query($userSQL);
      $userRow = $user->fetch_assoc();
      $newBits = $userRow['bits']+$bits;
      $newBucks = $userRow['bucks']+$bucks;
      
      $updateSQL = "UPDATE `beta_users` SET `bits`='$newBits', `bucks`='$newBucks' WHERE `id`='$userID'";
      $update = $conn->query($updateSQL);
      
      $admin = $_SESSION['id'];
      $action = 'Added '.$bucks.'bucks and '.$bits.' to user '.$userID;
      $date = date('d-m-Y H:i:s');
      $adminSQL = "INSERT INTO `admin` (`id`,`admin_id`,`action`,`time`) VALUES (NULL ,  '$admin',  '$action',  '$date')";
      $admin = $conn->query($adminSQL);
    }
  }
  
  if(isset($_POST['membership']) && isset($_POST['user']) && isset($_POST['value']) && isset($_POST['length'])) {
    $userID = mysqli_real_escape_string($conn,intval($_POST['user']));
    $value = mysqli_real_escape_string($conn,$_POST['value']);
    $length = mysqli_real_escape_string($conn,$_POST['length']);
    $date = date('d-m-Y H:i:s');
    
    if($userID != $_SESSION['id']) {
      $membershipSQL = "INSERT INTO `membership` (`id`,`user_id`,`membership`,`date`,`length`,`active`) VALUES (NULL,'$userID','$value','$date','$length','yes')";
      $membership = $conn->query($membershipSQL);
      
      $admin = $_SESSION['id'];
      $action = 'Granted user'.$userID.' membership '.$value.' for '.$length.' minutes';
      $date = date('d-m-Y H:i:s');
      $adminSQL = "INSERT INTO `admin` (`id`,`admin_id`,`action`,`time`) VALUES (NULL ,  '$admin',  '$action',  '$date')";
      $admin = $conn->query($adminSQL);
    }
  }
  
  if(isset($_POST['password']) && isset($_POST['user'])) {
    $userID = mysqli_real_escape_string($conn,intval($_POST['user']));
    
    function generateRandomString($length = 10) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
    
    $newPass = generateRandomString();
    $newPassEncrypt = password_hash($newPass, PASSWORD_BCRYPT);
    $passSQL = "UPDATE `beta_users` SET `password`='$newPassEncrypt' WHERE `id`='$userID'";
    $pass = $conn->query($passSQL);
    
    echo '<script>prompt("User '.$userID.'","'.$newPass.'");</script>';
    
    $admin = $_SESSION['id'];
    $action = 'Reset password user'.$userID;
    $date = date('d-m-Y H:i:s');
    $adminSQL = "INSERT INTO `admin` (`id`,`admin_id`,`action`,`time`) VALUES (NULL ,  '$admin',  '$action',  '$date')";
    $admin = $conn->query($adminSQL);
  }

  if (isset($_GET['clan-approve'])) {
  $clandID = mysqli_real_escape_string($conn,intval($_GET['clan-approve']));
  $approveSQL = "UPDATE `clans` SET `approved`='yes' WHERE `id`='$clandID'";
  $approve = $conn->query($approveSQL);
  header('Location: index?approval=clan');
}
  
if (isset($_GET['approve'])) {
  $itemID = mysqli_real_escape_string($conn,intval($_GET['approve']));
  $approveSQL = "UPDATE `shop_items` SET `approved`='yes' WHERE `id`='$itemID'";
  $approve = $conn->query($approveSQL);
  header('Location: index?approval=shop');
}
if (isset($_GET['decline'])) {
  $itemID = mysqli_real_escape_string($conn,intval($_GET['decline']));
  $declineSQL = "UPDATE `shop_items` SET `approved`='declined' WHERE `id`='$itemID'";
  $decline = $conn->query($declineSQL);
  header('Location: index');
}

  ?>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Admin Panel - <?php echo $sitename; ?></title>

  
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
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
<li class="breadcrumb-item active">Home</li>
</ul>
<div class="row">
<div class="col-6 col-md-3 text-center">
<a href="manage-users" style="color:#28a745;text-decoration:none;">
<div class="card">
<div class="card-body">
<i class="fas fa-user mb-2" style="font-size:60px;"></i>
<div class="text-truncate" style="font-weight:600;">Manage users and <br>economy</div>
</div>
</div>
</a>
</div>
<div class="col-6 col-md-3 text-center">
<a href="#" style="color:#28a745;text-decoration:none;">
<div class="card">
<div class="card-body">
<i class="fa fa-money mb-2" style="font-size:60px;"></i>
<div class="text-truncate" style="font-weight:600;">Manage Economy<br>(not implemented)</div>
</div>
</div>
</a>
</div>
<div class="col-6 col-md-3 text-center">
<a href="/panel/reports" style="color:#ffc107;text-decoration:none;">
<div class="card">
<div class="card-body">
<i class="fas fa-flag mb-2" style="font-size:60px;"></i>
<div class="text-truncate" style="font-weight:600;">Pending Reports</div>
</div>
  </div>
  </a>
  </div>
  <div class="col-6 col-md-3 text-center">
<a href="/panel/approval" style="color:#ffc107;text-decoration:none;">
<div class="card">
<div class="card-body">
<i class="fas fa-image mb-2" style="font-size:60px;"></i>
<div class="text-truncate" style="font-weight:600;">Pending Assets</div>
</div>
</div>
</a>
</div>
  <div class="col-6 col-md-3 text-center">
<a href="/shop/create/hat" style="color:#0082ff;text-decoration:none;">
<div class="card">
<div class="card-body">
<i class="fas fa-hat-cowboy mb-2" style="font-size:60px;"></i>
<div class="text-truncate" style="font-weight:600;">Create Hat</div>
</div>
</div>
</a>
</div>
  <div class="col-6 col-md-3 text-center">
<a href="/shop/create/face" style="color:#0082ff;text-decoration:none;">
<div class="card">
<div class="card-body">
<i class="fas fa-smile mb-2" style="font-size:60px;"></i>
<div class="text-truncate" style="font-weight:600;">Create Face</div>
</div>
</div>
</a>
</div>
  <div class="col-6 col-md-3 text-center">
<a href="/shop/create/tool" style="color:#0082ff;text-decoration:none;">
<div class="card">
<div class="card-body">
<i class="fas fa-hammer mb-2" style="font-size:60px;"></i>
<div class="text-truncate" style="font-weight:600;">Create Gadget</div>
</div>
</div>
</a>
</div>
  <div class="col-6 col-md-3 text-center">
<a href="/shop/create/head" style="color:#0082ff;text-decoration:none;">
<div class="card">
<div class="card-body">
<i class="fas fa-smile mb-2" style="font-size:60px;"></i>
<div class="text-truncate" style="font-weight:600;">Create Head</div>
</div>
</div>
</a>
</div>
    <div class="col-6 col-md-3 text-center">
<a href="site-settings" style="color:#0082ff;text-decoration:none;">
<div class="card">
<div class="card-body">
<i class="fas fa-cog mb-2" style="font-size:60px;"></i>
<div class="text-truncate" style="font-weight:600;">Site Settings</div>
</div>
</div>
</a>
</div>
  <div class="col-6 col-md-3 text-center">
<a href="create-blog" style="color:#0082ff;text-decoration:none;">
<div class="card">
<div class="card-body">
<i class="fas fa-newspaper mb-2" style="font-size:60px;"></i>
<div class="text-truncate" style="font-weight:600;">Create Blog Post</div>
</div>
</div>
</a>
</div>
  </div>
<?php
  include("../SiT_3/footer.php");
    ?>