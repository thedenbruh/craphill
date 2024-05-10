<?php
  include("../SiT_3/config.php");
  include("../SiT_3/header.php");
  
  if(!$loggedIn) {header("Location: index"); die();}

  $error = array();

  $clanID = mysqli_real_escape_string($conn,$_GET['id']);
  $sqlClan = "SELECT * FROM `clans` WHERE `id`='$clanID'";
  $result = $conn->query($sqlClan);
  $clanRow = $result->fetch_assoc();
  
  $memCountSQL = "SELECT * FROM `clans` WHERE `id`='$clanID';";
  $memCount = $conn->query($memCountSQL);
  $memRow = $memCount->fetch_assoc();
  $memCount = $memRow['members'];
  
  $userID = $_SESSION['id'];
  $checkSQL = "SELECT * FROM `clans_members` WHERE `user_id`='$userID' AND `group_id`='$clanID' AND `status`='in';";
  $check = $conn->query($checkSQL);
  $isIn = $check->num_rows;
  if($isIn) {
    $currentRow = $check->fetch_assoc();
    $currentPower = $currentRow['rank'];
    $currentRankSQL = "SELECT * FROM `clans_ranks` WHERE `group_id`='$clanID' AND `power`='$currentPower'";
    $currentRankQuery = $conn->query($currentRankSQL);
    $currentRank = $currentRankQuery->fetch_assoc();
  }

  if(!($isIn && ($currentRank['perm_ranks'] == 'yes' || $currentRank['perm_members'] == 'yes'))) {
    header("Location: /clan?id=".$clanID);
  }
  
  if(isset($_POST['newDesc'])) {
    $desc = mysqli_real_escape_string($conn,$_POST['description']);
    
    $updateSQL = "UPDATE `clans` SET `description`='$desc' WHERE `id`='$clanID'";
    $update = $conn->query($updateSQL);
  }
  
  //this area is messed up and idk why
  if(isset($_POST['rankName'])) {
    if($currentRank['perm_ranks'] == 'yes') {
      //change rank names
      $findRanksSQL = "SELECT * FROM `clans_ranks` WHERE `group_id`='$clanID' ORDER BY `power` ASC;";
      $findRanks = $conn->query($findRanksSQL);
      while($rankRow = $findRanks->fetch_assoc()) {
        $rankPower = $rankRow['power'];
        if($rankPower <= $currentRank['power']) {
          $newName = mysqli_real_escape_string($conn,$_POST['rank'.$rankPower]);
          if(isset($_POST['perm_ranks'.$rankPower])) {$permRanks = 'yes';} else {$permRanks = 'no';}
          if(isset($_POST['perm_posts'.$rankPower])) {$permPosts = 'yes';} else {$permPosts = 'no';}
          if(isset($_POST['perm_members'.$rankPower])) {$permMembers = 'yes';} else {$permMembers = 'no';}
          
          $rankNameSQL = "UPDATE `clans_ranks` SET `name`='$newName',`perm_ranks`='$permRanks',`perm_posts`='$permPosts',`perm_members`='$permMembers' WHERE `group_id`='$clanID' AND `power`='$rankPower'";
          $rankName = $conn->query($rankNameSQL);
        } else {
          $error[] = "Cannot edit ranks of higher power";
        }
      }
      
      //change rank values
      $findRanksSQL = "SELECT * FROM `clans_ranks` WHERE `group_id`='$clanID' ORDER BY `power` ASC;";
      $findRanks = $conn->query($findRanksSQL);
      while($rankRow = $findRanks->fetch_assoc()) {
        $rankPower = $rankRow['power'];
        $newPower = mysqli_real_escape_string($conn,$_POST['power'.$rankPower]);
        
        //check if rank already exists
        $checkSQL = "SELECT * FROM `clans_ranks` WHERE `power`='$newPower' AND `group_id`='$clanID'";
        $check = $conn->query($checkSQL);
        
        //check if the power is greater than their own
        if($check->num_rows <= 0 && $newPower < $currentRank['power']) {
          $newRankPowerSQL = "UPDATE `clans_ranks` SET `power`='$newPower' WHERE `group_id`='$clanID' AND `power`='$rankPower'";
          $newRankPower = $conn->query($newRankPowerSQL);
          
          //update the users who are already in this rank
          $updateUsersSQL = "UPDATE `clans_members` SET `rank`='$newPower' WHERE `rank`='$rankPower' AND `group_id`='$clanID'";
          $updateUsers = $conn->query($updateUsersSQL);
          header("Location: edit?id=".$clanID);
        }
      }
    }
  }
  //mess up ends here
  
  if(isset($_POST['rankNew']) && ($currentRank['perm_ranks'] == 'yes')) {
    $currentUserSQL = "SELECT * FROM `beta_users` WHERE `id`='$userID'";
    $currentUser = $conn->query($currentUserSQL);
    $currentRow = $currentUser->fetch_assoc();
  
    if($currentRow['bucks'] >= 4 && !empty($_POST['power']) && !empty($_POST['rank'])) {
      $newPower = mysqli_real_escape_string($conn,$_POST['power']);
      $newRank = mysqli_real_escape_string($conn,$_POST['rank']);
      
      //if the power of this rank is less than their own
      if($currentRank['power'] > $newPower) {
      
        //check if rank already exists
        $checkSQL = "SELECT * FROM `clans_ranks` WHERE `power`='$newPower' AND `group_id`='$clanID'";
        $check = $conn->query($checkSQL);
        
        if($check->num_rows == 0) {
          $insertSQL = "INSERT INTO `clans_ranks` (`id`,`group_id`,`power`,`name`,`perm_ranks`,`perm_posts`,`perm_members`) VALUES (NULL,'$clanID','$newPower','$newRank','no','no','no')";
          $insert = $conn->query($insertSQL);
          
          $newBucks = $currentRow['bucks']-4;
          $newBucksSQL = "UPDATE `beta_users` SET `bucks`='$newBucks' WHERE `id`='$userID'";
          $buy = $conn->query($newBucksSQL);
          
          header("Location: edit?id=".$clanID);
        } else {
          $error[] = "Rank already exists";
        }
      } else {
        $error[] = "Power too high";
      }
    }
  }
  
  if(isset($_POST['newImage'])) {
    if(isset($_FILES['image'])) {
      $imgName = $_FILES['image']['name'];
      $imgSize = $_FILES['image']['size'];
      $imgTmp = $_FILES['image']['tmp_name'];
      $imgType = $_FILES['image']['type'];
      $isImage = getimagesize($imgTmp);
      
      if($isImage !== false) {
        if($imgSize < 2097152) {
          $approvedSQL = "UPDATE `clans` SET `approved`='no' WHERE `id`='$clanID'";
          $approved = $conn->query($approvedSQL);
          if($approved) {
            move_uploaded_file($imgTmp,"../../storage_subdomain/images/clans/".$clanID.".png");
          }
        } else {
          echo 'File size must be smaller than 2MB';
        }
      } else {
        echo "File must be an image!";
      }
    } else {
      echo "You did not upload a tshirt!";
    }
  }
?>
<head>
  <meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Edit Clan - <?php echo $sitename; ?></title>
</head>
<div class="main-holder grid">
<div class="col-10-12 push-1-12">
<div class="tabs">
<meta name="clan_id" content="3529">
<div class="tab active  col-1-2" data-tab="1">
Edit
</div>
<div class="tab  col-1-2" data-tab="2">
Members & Relations
</div>
<div class="tab-holder">
<div class="tab-body active " data-tab="1">
<div class="content p2">
<h1 style="font-size:23px;margin-top:0;">Edit <?php echo $clanRow['name']; ?></h1>
<div class="flex-container">
<div class="clan-edit-icon clan-edit col-3-12">
<div class="bold">Change Icon</div>
<img src="/clans/icons/<?php echo $clanID; ?>.png" style="width:150px;height:150px;">
<form method="POST" action="/clan/3529/thumbnail" enctype="multipart/form-data">
<input type="hidden" name="_token" value="f9CqqED12e1aKNkmEnf5U4pyBdQUpIpVuk1SwoNx"> <input class="upload-input" type="file" name="image" style="border:0;padding-left:0;" required>
<input class="button blue upload-submit" name="newImage" type="submit" value="UPLOAD">
</form>
</div>
<div class="clan-edit-description clan-edit col-9-12">
<div class="bold">Update Description</div>
<form method="POST" action="https://www.brick-hill.com/clan/edit" style="height:65%;">
<input type="hidden" name="_token" value="f9CqqED12e1aKNkmEnf5U4pyBdQUpIpVuk1SwoNx"> <input type="hidden" name="type" value="description">
<input type="hidden" name="clan_id" value="3529">
<textarea class="upload-input" name="description" style="width:90%;height:100%;"><?php echo $clanRow['description']; ?></textarea>
<input class="button blue upload-submit" type="submit" name="newDesc" value="SAVE">
</form>
</div>
</div>
<hr>
<div class="overflow-auto">
<div class="bold">Join Type</div>
<!--<form method="POST" action="https://www.brick-hill.com/clan/edit">
<input type="hidden" name="_token" value="f9CqqED12e1aKNkmEnf5U4pyBdQUpIpVuk1SwoNx"> <input type="hidden" name="type" value="join_type">
<input type="hidden" name="clan_id" value="3529">
<select name="value" class="select">
<option value="open">Open to all</option>
<option value="request" selected>Request to join</option>
</select>
<div></div>
<input class="button blue upload-submit" type="submit" value="SAVE">
</form>-->
  Coming Soon
</div>
<hr>
<div class="clan-edit-ranks overflow-auto">
<div class="bold">Edit Ranks</div>
<!--<form method="POST" action="https://www.brick-hill.com/clan/edit">
<input type="hidden" name="_token" value="f9CqqED12e1aKNkmEnf5U4pyBdQUpIpVuk1SwoNx"> <input type="hidden" name="type" value="edit_ranks">
<input type="hidden" name="clan_id" value="3529">
<table>
<tbody>
<tr>
<td>
<h5>Power</h5>
</td>
<td>
<h5>Name</h5>
</td>
<td>
<h5 class="text-center"><i class="fa fa-info-circle" title="Let these users post to the wall"></i></h5>
</td>
<td>
<h5 class="text-center"><i class="fa fa-info-circle" title="Let these users moderate the wall"></i></h5>
</td>
<td>
<h5 class="text-center"><i class="fa fa-info-circle" title="Let these users invite/reject users"></i></h5>
</td>
<td>
<h5 class="text-center"><i class="fa fa-info-circle" title="Let these users ally/enemy other clans"></i></h5>
</td>
<td>
<h5 class="text-center"><i class="fa fa-info-circle" title="Let these users rank other users"></i></h5>
</td>
<td>
<h5 class="text-center"><i class="fa fa-info-circle" title="Let these users add/delete ranks"></i></h5>
</td>
<td>
<h5 class="text-center"><i class="fa fa-info-circle" title="Let these users edit the clan description"></i></h5>
</td>
<td>
<h5 class="text-center"><i class="fa fa-info-circle" title="Let these users use the shoutout box"></i></h5>
</td>
<td>
<h5 class="text-center"><i class="fa fa-info-circle" title="Let these users add funds to the clan"></i></h5>
</td>
<td>
<h5 class="text-center"><i class="fa fa-info-circle" title="Let these users take funds from the clan"></i></h5>
</td>
<td>
<h5 class="text-center"><i class="fa fa-info-circle" title="Let these users edit the clan"></i></h5>
</td>
</tr>
<tr>
<td>
<input disabled type="number" name="rank1power" value="1" style="width:65px;">
</td>
<td>
<input disabled type="text" name="rank1name" value="Admins">
</td>
<td>
<input type="checkbox" name="rank1perm_postWall" checked disabled>
</td>
<td>
<input type="checkbox" name="rank1perm_modWall" checked disabled>
</td>
<td>
<input type="checkbox" name="rank1perm_inviteDecline" checked disabled>
</td>
 <td>
<input type="checkbox" name="rank1perm_allyEnemy" checked disabled>
</td>
<td>
<input type="checkbox" name="rank1perm_changeRank" checked disabled>
</td>
<td>
<input type="checkbox" name="rank1perm_addDelRank" checked disabled>
</td>
<td>
<input type="checkbox" name="rank1perm_editDesc" checked disabled>
</td>
<td>
<input type="checkbox" name="rank1perm_shoutBox" checked disabled>
</td>
<td>
<input type="checkbox" name="rank1perm_addFunds" checked disabled>
</td>
<td>
<input type="checkbox" name="rank1perm_takeFunds" checked disabled>
</td>
<td>
<input type="checkbox" name="rank1perm_editClan" checked disabled>
</td>
</tr>
<tr>
<td>
<input disabled type="number" name="rank75power" value="75" style="width:65px;">
</td>
<td>
<input disabled type="text" name="rank75name" value="Head Admins">
</td>
<td>
<input type="checkbox" name="rank75perm_postWall" checked disabled>
</td>
<td>
<input type="checkbox" name="rank75perm_modWall" checked disabled>
</td>
<td>
<input type="checkbox" name="rank75perm_inviteDecline" checked disabled>
</td>
<td>
<input type="checkbox" name="rank75perm_allyEnemy" checked disabled>
</td>
<td>
<input type="checkbox" name="rank75perm_changeRank" checked disabled>
</td>
 <td>
<input type="checkbox" name="rank75perm_addDelRank" checked disabled>
</td>
<td>
<input type="checkbox" name="rank75perm_editDesc" checked disabled>
</td>
<td>
<input type="checkbox" name="rank75perm_shoutBox" checked disabled>
</td>
<td>
<input type="checkbox" name="rank75perm_addFunds" checked disabled>
</td>
<td>
<input type="checkbox" name="rank75perm_takeFunds" checked disabled>
</td>
<td>
<input type="checkbox" name="rank75perm_editClan" checked disabled>
</td>
</tr>
<tr>
<td>
<input disabled type="number" name="rank100power" value="100" style="width:65px;">
</td>
<td>
<input disabled type="text" name="rank100name" value="Group Founder">
</td>
<td>
<input type="checkbox" name="rank100perm_postWall" checked disabled>
</td>
<td>
<input type="checkbox" name="rank100perm_modWall" checked disabled>
</td>
<td>
<input type="checkbox" name="rank100perm_inviteDecline" checked disabled>
</td>
<td>
<input type="checkbox" name="rank100perm_allyEnemy" checked disabled>
</td>
<td>
<input type="checkbox" name="rank100perm_changeRank" checked disabled>
</td>
<td>
<input type="checkbox" name="rank100perm_addDelRank" checked disabled>
</td>
<td>
<input type="checkbox" name="rank100perm_editDesc" checked disabled>
 </td>
<td>
<input type="checkbox" name="rank100perm_shoutBox" checked disabled>
</td>
<td>
<input type="checkbox" name="rank100perm_addFunds" checked disabled>
</td>
<td>
<input type="checkbox" name="rank100perm_takeFunds" checked disabled>
</td>
<td>
<input type="checkbox" name="rank100perm_editClan" checked disabled>
</td>
</tr>
</tbody>
</table>
<input class="button blue upload-submit" type="submit" value="SAVE">
</form>-->
<form action="" method="POST">
    <input type="hidden" name="_token" value="f9CqqED12e1aKNkmEnf5U4pyBdQUpIpVuk1SwoNx"> <input type="hidden" name="type" value="edit_ranks">
<input type="hidden" name="clan_id" value="3529">

          
  
          <?php
            $findRanksSQL = "SELECT * FROM `clans_ranks` WHERE `group_id`='$clanID' ORDER BY `power` ASC;";
            $findRanks = $conn->query($findRanksSQL);
            while($rankRow = $findRanks->fetch_assoc()) {
              echo '<tr>';
              if($rankRow['perm_ranks'] == 'yes') {$perm_ranks = 1;} else {$perm_ranks = 0;}
              if($rankRow['perm_posts'] == 'yes') {$perm_posts = 1;} else {$perm_posts = 0;}
              if($rankRow['perm_members'] == 'yes') {$perm_members = 1;} else {$perm_members = 0;}
              echo '<input name="power'.$rankRow['power'].'" value="'.$rankRow['power'].'">
                    <input name="rank'.$rankRow['power'].'" value="'.$rankRow['name'].'">
                    <input type="checkbox"  name="perm_ranks'.$rankRow['power'].'" '.str_repeat("checked",$perm_ranks).'>
                    <input type="checkbox" name="perm_posts'.$rankRow['power'].'" '.str_repeat("checked",$perm_posts).'>
                    <input type="checkbox" name="perm_members'.$rankRow['power'].'" '.str_repeat("checked",$perm_members).'>
                    <br>';
            }
          ?>
            <input type="submit" name="rankName">
          </form>
</div>
<hr>
<div class="clan-new-rank clan-edit overflow-auto">
<div class="bold">New Rank</div>
<form method="POST" action="https://www.brick-hill.com/clan/edit">
<input type="hidden" name="_token" value="f9CqqED12e1aKNkmEnf5U4pyBdQUpIpVuk1SwoNx"> <input type="hidden" name="type" value="new_rank">
<input type="hidden" name="clan_id" value="">
<input type="text" name="power" placeholder="Power">
<input type="text" name="rank" placeholder="Rank name">
<div class="bucks-text bold">This will cost <span class="bucks-icon"></span>6</div>
<input class="button blue upload-submit" name="rankNew" type="submit" value="CREATE" style="display: block;">
</form>
</div>
<hr>
</div>
</div>
<div class="tab-body " data-tab="2">
<div class="p1">
<div class="content">
<h1 style="font-size:23px;margin-top:0;">Members & Relations</h1>
<div class="clan-change-ranks clan-edit rank-select">
<div class="bold">Members</div>
<select class="select" style="width:150px;" id="member-rank" onchange="loadEditMembers()">
<option value="1">Admins</option>
<option value="75">Head Admins</option>
<option value="100">Group Founder</option>
</select>
<div class="overflow-auto edit-holder unselectable">
</div>
<div class="pages unselectable">
</div>
</div>
<script>
                            $(document).on('change', 'select[data-user]', function() {
                                let select = $(this);
                                let user = select.data('user');
                                $.post(`https://www.brick-hill.com/clan/edit`, {
                                    _token: $('meta[name="csrf-token"]').attr('content'),
                                    user_id: user,
                                    clan_id: 3529,
                                    type: 'change_rank',
                                    new_rank: $(':selected', select).val()
                                }, () => {loadEditMembers($('.member-pages .forumPage.blue').text() || 1)})
                                .fail((data) => {
                                    $('.col-10-12.push-1-12').prepend(`
                                        <div class="error-notification">
                                            ${data.responseJSON.error}
                                        </div>
                                    `);
                                });
                            })
                            $(document).on('click', '#clan-search', searchRelationClans);
                            $(document).on('keyup', '#clan-search-bar', searchRelationClans);
                            loadEditMembers();
                        </script>

</div>
</div>
 
</div>
</div>
</div>
</div>
</div>
</div>
</div>
<div style="text-align:center;margin-top:20px;padding-bottom:25px;">
<div id="100128-28">
<script src="//ads.themoneytizer.com/s/gen.js?type=28"></script>
<script src="//ads.themoneytizer.com/s/requestform.js?siteId=100128&formatId=28"></script>
</div>
</div>
</div>
</div>
  

    <script>
      window.onload = function() {
        getRank(0,1);
      }
      
      function getRank(page, rank) {
        $("#members").load("http://www.brick-hill.com/clans/edit_members?id=<?php echo $clanID; ?>&rank="+rank+"&page="+page);
      }
    </script>
  <?php
    include("../SiT_3/footer.php");
  ?>
  </body>
</html>