<?php
include "../SiT_3/header.php";
  
$staffSQL = $conn->query("SELECT * FROM `beta_users` WHERE `power` > 1 ORDER BY id");
?>
<title>Staff - <?php echo $sitename; ?></title>
<div class="main-holder grid">
<div class="col-10-12 push-1-12">
<div class="card">
<div class="top blue">
Staff
</div>
<div class="content">
<div class="col-1-1" style="padding-right:0;">
<?php
while($staffRow = $staffSQL->fetch_assoc()){
?>
<a href="/user/<?php echo $staffRow['id']; ?>">
<div class="search-user-card ellipsis">
<img src="/avatar/render/avatars/<?php echo $staffRow['id']; ?>.png?c=<?php echo $staffRow['avatar_id']; ?>" style="vertical-align:middle; width:69px;">
<div class="data">
<div class="ellipsis">
<b><?php echo $staffRow['username']; ?></b>
  <?php             //$lastonline = strtotime($curDate)-strtotime($staffRow['last_online']);
  $lastonline = time()-strtotime($staffRow['last_online']);
?>    
<!--<span style="float:right;" class="status-dot "></span>-->
 <?php if ($lastonline >= 300) {
              $timedif = $lastonline . " seconds";
              if ($lastonline >= 300) {$timedif = (int)gmdate('i',$lastonline) . " minutes";}
              if ($lastonline >= 3600) {$timedif = (int)gmdate('H',$lastonline) . " hours";}
              if ($lastonline >= 86400) {$timedif = (int)gmdate('d',$lastonline) . " days";}
              if ($lastonline >= 2592000) {$timedif = (int)gmdate('m',$lastonline) . " months";}
              if ($lastonline >= 31536000) {$timedif = (int)gmdate('Y',$lastonline) . " years";}
              echo '<span style="float:right;" class="status-dot"></span>';}
            else {
              echo '<span style="float:right;" class="status-dot online"></span>';}
            if ($lastonline <= 300) {echo '';}
            else {echo '';}
  ?>
</div>
<span class="ellipsis"><?php echo $staffRow['description']; ?></span>
</div>
</div>
</a>
<hr>
<?php } ?></div></div></div></div></div></div>
<?php
include "../SiT_3/footer.php";
  ?>