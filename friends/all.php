<?php 
include('../SiT_3/header.php');
include('../SiT_3/config.php');
?>
<div id="body">
<?php

if(isset($_GET['id'])) {
  if(isset($_GET['page'])) {$page = mysqli_real_escape_string($conn,intval($_GET['page']));} else {$page = 1;}
  $page = max($page,1);
  $limit = ($page-1)*10;
  $id = $conn->real_escape_string($_GET['id']);
  $userRowSQL = "SELECT * FROM `beta_users` WHERE `id` = '$id' ";
  $userRowQuery = $conn->query($userRowSQL);
  
  if ($userRowQuery->num_rows == 0) {
    ?>
    <div id="box" class="center-text" style="padding-top:5px;">
      User does not exist!
    </div>
    </div>
    <?php
    include('../SiT_3/footer.php');
    die();
  }
  
  
  $findFriendsCountSQL = "SELECT * FROM `friends` WHERE `to_id` = '$id' AND `status`='accepted' OR `from_id` = '$id' AND `status` = 'accepted'";
  $findFriendsCountQuery = $conn->query($findFriendsCountSQL);
  $count = $findFriendsCountQuery->num_rows;
  
  $findFriendsSQL = "SELECT * FROM `friends` WHERE `to_id` = '$id' AND `status`='accepted' OR `from_id` = '$id' AND `status` = 'accepted' LIMIT $limit,10";
  $findFriendsQuery = $conn->query($findFriendsSQL);
  if ($findFriendsQuery->num_rows == 0) {
    ?>
<div class="col-10-12 push-1-12">
<div class="card">
<div class="top blue">
Friends
</div>
<div class="content">
      User has no friends! <?php if ($page > 1) { echo ' (or invalid page)'; } ?>
    </div>
    </div>
    <?php
    include('../SiT_3/footer.php');
    die();
  }
  ?>
<div class="col-10-12 push-1-12">
<div class="card">
<div class="top blue">
Friends
</div>
<div class="content">
<ul class="friends-list">  <?php
while ($friendRow = $findFriendsQuery->fetch_assoc()) {

  $friendUserRowSQL = "SELECT * FROM `beta_users` WHERE (`id`='$friendRow[from_id]' OR `id`='$friendRow[to_id]') AND `id`!='$id' ";
  $friendUserRowQuery = $conn->query($friendUserRowSQL);
  $friendUserRow = $friendUserRowQuery->fetch_assoc();

  $friendUsername = $friendUserRow['username'];
  if (strlen($friendUsername) > 9) {
    $friendUsername = substr($friendUsername, 0, 9) . '...';
  }
?>
    <li class="col-1-5 mobile-col-1-1">
      <a href="/user/<?php echo $friendUserRow['id']; ?>">
        <div class="profile-card">
          <img src="/avatar/render/avatars/<?php echo $friendUserRow['id']; ?>.png?c=<?php echo rand(); ?>" style="height:150px;width:150px;">
          <div class="ellipsis"><?php echo $friendUsername; ?></div>
        </div>
      </a>
    </li>
<?php
}
?>

</div>
<div class="numButtonsHolder" style="margin-left:auto;margin-right:auto;margin-top:10px;text-align:center;">
<?php    
      
      if($count/10 > 1) {
        for($i = 0; $i < ($count/10); $i++)
        {
          echo '<a href="/friends/all?id='.$id.'&page='.($i+1).'">'.($i+1).'</a> ';
        }
      }
      
      echo '</div></div>
</div>
</div>
</div>
';
      ?>
<style>

#friend {
    padding: 4px;
    text-align: center;
    float: left;
}

#friendThumb {
    width: 160px;
}
div#friend a {
  color:black;
}
          
</style>
  <div class="col-10-12 push-1-12">
<div style="text-align:center;margin-top:20px;padding-bottom:25px;">
<div id="100128-28">
<script src="//ads.themoneytizer.com/s/gen.js?type=28"></script>
<script src="//ads.themoneytizer.com/s/requestform.js?siteId=100128&formatId=28"></script>
</div>
</div>
</div>
</div>
<?php 
}
include('../SiT_3/footer.php');
?>