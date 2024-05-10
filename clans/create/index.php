<?php
  include('../../SiT_3/header.php');
  include('../../SiT_3/config.php');
  include('../../SiT_3/PHP/helper.php');
  
  if(!$loggedIn) {header("Location: index"); die();}
  
  $error = array();
  
  
  if(isset($_POST['submit'])) {
    if(isset($_FILES['image'])) {
      $imgName = $_FILES['image']['name'];
      $imgSize = $_FILES['image']['size'];
      $imgTmp = $_FILES['image']['tmp_name'];
      $imgType = $_FILES['image']['type'];
      $isImage = getimagesize($imgTmp);
      
      if($isImage !== false) {
        if($imgSize < 2097152) {
          if(isset($_POST['name'])) {
            $name = mysqli_real_escape_string($conn,$_POST['name']);
            $nameSQL = "SELECT * FROM `clans` WHERE `name` LIKE '$name'";
            $nameExists = $conn->query($nameSQL);
            if($nameExists->num_rows == 0) {
              if(isset($_POST['prefix'])) {
                if($userRow->{'bucks'} >= 25) {
                  $prefix = mysqli_real_escape_string($conn,$_POST['prefix']);
                  if(strlen($prefix) >= 3 and strlen($prefix) <= 4) {
                    if(ctype_alnum($prefix)) {
                      if(isset($_POST['description'])) {
                        $desc = mysqli_real_escape_string($conn,$_POST['description']);
                      } else {$desc = NULL;}
                    
                      $userID = $_SESSION['id'];
                      
                      $clanSQL = "INSERT INTO `clans` (`id`,`owner_id`,`name`,`tag`,`description`,`members`) VALUES (NULL ,'$userID','$name','$prefix','$desc','1')";
                      $clan = $conn->query($clanSQL);
                      $clanID = $conn->insert_id;
                      
                      $newMoney = $userRow->{'bucks'}-25;
                      $newMoneySQL = "UPDATE `beta_users` SET `bucks`='$newMoney' WHERE `id`='$userID'";
                      $newMoneyQ = $conn->query($newMoneySQL);
                      
                      $memberSQL = "INSERT INTO `clans_members` (`id`,`group_id`,`user_id`,`rank`,`status`) VALUES (NULL ,'$clanID','$userID','100','in')";
                      $member = $conn->query($memberSQL);
                      
                      ///ADD RANKS
                      $addOwnerSQL = "INSERT INTO `clans_ranks` (`id`,`group_id`,`power`,`name`,`perm_ranks`,`perm_posts`,`perm_members`) VALUES (NULL ,'$clanID','100','Owner','yes','yes','yes')";
                      $addOwner = $conn->query($addOwnerSQL);
                      
                      $addModeratorSQL = "INSERT INTO `clans_ranks` (`id`,`group_id`,`power`,`name`,`perm_ranks`,`perm_posts`,`perm_members`) VALUES (NULL ,'$clanID','75','Moderator','no','yes','no')";
                      $addModerator = $conn->query($addModeratorSQL);
                      
                      $addMemberSQL = "INSERT INTO `clans_ranks` (`id`,`group_id`,`power`,`name`,`perm_ranks`,`perm_posts`,`perm_members`) VALUES (NULL ,'$clanID','1','Member','no','no','no')";
                      $addMember = $conn->query($addMemberSQL);
                      
                      move_uploaded_file($imgTmp,"../icons/".$clanID.".png");
                      
                      header("location: /clan?id=".$clanID);
                    } else {
                      $error[] = 'Your prefix must contain only alphanumeric characters';
                    }
                  } else {
                    $error[] = 'Your clan prefix must be between 3 and 4 characters.';
                  }
                } else {
                  $error[] = 'Insufficient bucks!';
                }
              } else {
                $error[] = 'Your clan needs a prefix!';
              }
            } else {
              $error[] = 'Name taken!';
            }
          } else {
            $error[] = 'Your clan needs a name!';
          }
        } else {
        $error[] = 'File size must be smaller than 2MB';
      }
    } else {
      $error[] = "File must be an image!";
    }
  } else {
    $error[] = "You did not upload a tshirt!";
  }
  }
?>

<!DOCTYPE html>
  <head>
    <title>Create Clan - <?php echo $sitename; ?></title>
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
     <div class="main-holder grid">
 
       
      
<div class="col-10-12 push-1-12">
<div class="card" style="margin-bottom:20px;">
<div class="top green">
Create Clan
</div>
<div class="content">
<div style="width:100%;">
<form action="" method="POST" enctype="multipart/form-data">
<input type="hidden" name="_token" value="hJz4SuZ3S1nYnNJj6Qpj7oEsRP1Xj920Bvwcvcmi"> <input class="upload-input" style="width:50px;margin-right:5px;display:inline-block;" type="text" name="prefix" placeholder="Tag" required value="">
<input class="upload-input" style="display:inline-block;" type="text" name="name" placeholder="Title" required value="">
<input class="upload-input" type="file" name="image" style="border:0;" required value="">
<textarea class="upload-input" name="description" placeholder="Description" style="width:320px;height:100px;"></textarea>
<span style="color:green;display:block;">This will cost <span class="bucks-icon"></span>25</span>
<input class="button green upload-submit" name="submit" type="submit" value="PURCHASE CLAN">
</form>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
<?php
  include("../../SiT_3/footer.php");
?>