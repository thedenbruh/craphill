<?php 
include('SiT_3/config.php');
include('SiT_3/header.php');
//if(!$loggedIn) {header("Location: /index"); die();}
if (isset($_GET['id'])) {
  $id = mysqli_real_escape_string($conn,intval($_GET['id']));
  $sqlUser = "SELECT * FROM `beta_users` WHERE  `id` = '$id'";
  $userResult = $conn->query($sqlUser);
  $userRow=$userResult->fetch_assoc();
  if($userResult->num_rows <= 0){
    echo"<script>location.replace('/search/');</script>";
    //header('Location: /search/');
    die();
  }
} else {
  echo"<script>location.replace('/search/');</script>";
  //header('Location: /search/');
  die();
}
  
if(isset($_GET['desc']) && $power >= 1) {
  $scrubSQL = "UPDATE `beta_users` SET `description`='[Content Removed]' WHERE `id`='$id'";
  $scrub = $conn->query($scrubSQL);
}
if(isset($_GET['name']) && $power >= 1) {
  $scrubSQL = "UPDATE `beta_users` SET `username` = '[Deleted $id]', `usernameL` = '[deleted $id]' WHERE `id`='$id'";
  $scrub = $conn->query($scrubSQL);
}

$statusReq = mysqli_query($conn,"SELECT * FROM `statuses` WHERE `owner_id`='$id' ORDER BY `id` DESC");
$statusReqData = mysqli_fetch_assoc($statusReq);
$currStatus = $statusReqData['body'];



////REWARDS ARE UPDATED AND CHECKED HERE

//Classic - have been a member for more than a year
if((time()-strtotime($userRow['date'])) >= 31536000) {
  $rewardSQL = "SELECT * FROM `user_rewards` WHERE `user_id`='$id' AND `reward_id`='1'";
  $reward = $conn->query($rewardSQL);
  if($reward->num_rows == 0)
  {
    $addSQL = "INSERT INTO `user_rewards` (`id`,`user_id`,`reward_id`) VALUES (NULL ,'$id','1');";
    $add = $conn->query($addSQL);
  }
}


if (isset($_GET['pin']) && $currentRank = 1) {
    $pinID = mysqli_real_escape_string($conn,$_GET['pin']);
    $pinSQL = "UPDATE `user_walls` SET `type`='pinned' WHERE `id`='$pinID' AND `user_id`='$id'";
    $pin = $conn->query($pinSQL);
    header("Location: /user/".$id);
  }
  if (isset($_GET['unpin']) && $currentRank = 1) {
    $pinID = mysqli_real_escape_string($conn,$_GET['unpin']);
    $pinSQL = "UPDATE `user_walls` SET `type`='normal' WHERE `id`='$pinID' AND `user_id`='$id'";
    $pin = $conn->query($pinSQL);
    header("Location: /user/".$id);
  }
  if (isset($_GET['delete']) && $currentRank = 1) {
    $pinID = mysqli_real_escape_string($conn,$_GET['delete']);
    $pinSQL = "UPDATE `user_walls` SET `type`='deleted' WHERE `id`='$pinID' AND `user_id`='$id'";
    $pin = $conn->query($pinSQL);
    header("Location: /user/".$id);
  }


  if(isset($_POST['wall'])) {
    if($loggedIn)
    {
    $gold = $_SESSION['id'];
    $posted = str_replace("'","\'",$_POST['wall']);
    $postSQL = "INSERT INTO `user_walls` (`id`,`user_id`,`owner_id`,`post`,`time`,`type`)VALUES (NULL ,  '$id',  '$gold',  '$posted',  '$curDate',  'normal');";
    $post = $conn->query($postSQL);
    }
}

///////ADD PROFILE VIEW
$findViewsQuery = "SELECT * FROM `beta_users` WHERE `id`='$id'";
$findViews = $conn->query($findViewsQuery);
$viewRow = $findViews->fetch_assoc();
$views = $viewRow['views']+1;
$addViewQuery = "UPDATE `beta_users` SET `views`='$views' WHERE `id`='$id'";
$addView = $conn->query($addViewQuery);



//primary group
$primary = $userRow['primary_group'];
if($primary > 0){
  $clansSQL = "SELECT * FROM `clans` WHERE `id`='$primary'";
  $clans = $conn->query($clansSQL);
  $clanRow = $clans->fetch_assoc();
  $clanTag = '['.$clanRow['tag'].']';
} else {$clanTag = '';}

?>
<!DOCTYPE html>
  <head>
    <?php /* if($_SERVER['REQUEST_URI'] != 'beta.brick-hill.com/user.php?='.$id.'') { echo '
    <script type="text/javascript">location.href = "/user?id='.$id.'";</script>';
    } else {
  //Do nonthing.
    } */
    ?>
    <title><?php echo $userRow['username']; ?> - BrickTip</title>
   
  <meta name="description" content="<?php echo $userRow['username'] ?> is a user on Brick Hill! Sign up today to get started!">
  <meta name="keywords" content="free,game">
  <meta property="og:title" content="<?php echo $userRow['username']; ?>'s Profile" />
  <meta property="og:description" content="<?php echo $userRow['username'] ?> is a user on Brick Hill! Sign up today to get started!" />
  <meta property="og:image" content="<?php echo '/avatar/render/avatars/'; ?><?php echo $userRow['id'];?><?php echo ".png?c=";?><?php echo $userRow['avatar_id']; ?>" />
  <meta property="og:type" content="website" />
  <meta property="og:url" content="https://down-hill.planetarium.digital/user/<?php echo $userRow['id'] ?>" />
  </head>
  <body>
  
    <div class="main-holder grid">
    <div class="col-10-12 push-1-12">
    <?php
    $bannedSQL = "SELECT * FROM `moderation` WHERE `active`='yes' AND `user_id`='$id'";
    $banned = $conn->query($bannedSQL);
    if($banned->num_rows > 0) {
    echo '
<div class="alert error">
User is banned
</div>
';
    }
    
      if ($userRow['power'] >= 2) {      
  ?><div class="alert warning">
User is an Admin.
</div><?php
    } else {
      echo''; }
      ?>
     

<?php if (!empty($currStatus) || $currStatus !== NULL ) { echo '<div class="col-1-1" style="padding-right:0;">
<div class="card">
<div class="content" style="border-radius:5px;position:relative;word-break:break-word">
<div class="small-text very-bold light-gray-text">What\'s on my mind:</div>
'. fix($currStatus) .'
</div>
</div>
</div>';}
      ?>
<div class="col-6-12">
<div class="card">
<div class="content text-center bold medium-text relative ellipsis">
  <?php           $lastonlineTime = strtotime($userRow['last_online']);
      $lastOnline = time()-$lastonlineTime;
      
          if ($lastOnline <= 300) {
            echo ' <span class="online"><i class="status-dot online"></i></span>';
            } else {
            echo ' <span class="offline"><i class="status-dot"></i></span>';
            }  
  
  

          
        
          
          ?>
<span class="ellipsis"> <a href="/clan/<?php echo $userRow['primary_group']; ?>"><span class="mr1" style="color:#999999;"><?php echo $clanTag; ?></span></a> <?php echo $userRow['username']; ?></span>
<br>
<img src="/avatar/render/avatars/<?php echo $userRow['id']; ?>.png?c=<?php echo $userRow['avatar_id']; ?>" style="height:350px;">
<div class="user-description-box closed">
<div class="toggle-user-desc gray-text">
<div class="user-desc p2 darker-grey-text" style="font-size:16px;line-height:17px;">
<?php if (!empty($userRow['description']) || $userRow['description'] !== NULL ) {echo nl2br(htmlentities($userRow['description']));} ?>
</div>
<a class="darker-grey-text read-more-desc" style="font-size:16px;">Read More</a>
</div>
</div>

  <?php
if($loggedIn) {
          if ($userRow['id'] != $_SESSION['id']) {
            if ($id != -1) {
            echo '<form action="/messages/compose" method="POST" style="display:inline-block;">
              <input type="hidden" name="recipient" value="'.$userRow['id'].'">
              <input class="button small blue inline" style="font-size:14px;" type="submit" value="Message">
              </form>';
      }
            
            echo'<a class="button small blue inline" href="https://www.brick-hill.com/trade/create/971734" style="font-size:14px;">TRADE</a>';
            // Check if they are friends
            $senderID = $_SESSION['id'];
        $AlreadyFriendsQ = mysqli_query($conn,"SELECT * FROM `friends` WHERE `to_id`='$id' AND `from_id`='$senderID' AND `status`='accepted' OR `to_id`='$senderID' AND `from_id`='$id' AND `status`='accepted'");
        $AlreadyFriends = mysqli_num_rows($AlreadyFriendsQ);
            
            if($AlreadyFriends<1){
              echo '<a href="/friends/add?id=' . $id . '"><input class="button small inline blue" style="font-size:14px;" type="button" value="Add Friend"></a>';
            } else {
              echo '<a href="/friends/remove?id=' . $id . '"><input class="button small inline red" style="font-size:14px;" type="button" value="Remove Friend"></a>';
            }
          
          } else {
          echo '';
          }
        if($power >= 1 && $userRow['power'] < $power) {
          echo '<a href="/ban?id='.$id.'">
          <input class="button small inline red" style="font-size:14px;" type="button" value="Ban">
          </a><br>
          <a href="/avatar/render/?id='.$id.'">
          <input class="button small inline blue" style="font-size:14px;" type="button" value="Render">
          </a>';
          
          if ($power >= 1) {
          echo'
          <a href="/admin/user?id='.$id.'">
          <input class="button small inline red" style="font-size:14px;" type="button" value="Information">
          </a>';
          }
        }
}
        ?>
</div>
  </div>
<div class="card">
<div class="top green">
Awards
</div>
<div class="content" style="text-align:center;">
<?php
          $rewardsSQL = "SELECT * FROM `user_rewards` WHERE `user_id`='$id'";
          $rewards = $conn->query($rewardsSQL);
          if($rewards->num_rows != 0){
            while($rewardsRow = $rewards->fetch_assoc()){
              $rewardID = $rewardsRow['reward_id'];
              $findRewardSQL = "SELECT * FROM `awards` WHERE `id`='$rewardID'";
              $findReward = $conn->query($findRewardSQL);
              $rewardRow = $findReward->fetch_assoc();
              
              echo '<a href="/awards/">
<div class="profile-card award">
<img src="/assets/awards/'.$rewardRow['id'].'.png">
<span class="ellipsis">'.$rewardRow['name'].'</span>
</div>
</a>
';
              }
          }
  if ($userRow['power'] >= 2) {      
  ?><a href="/awards/">
<div class="profile-card award">
<img src="/assets/awards/3.png">
<span class="ellipsis">Admin</span>
</div>
</a><?php
    } else {
      echo'';
              } 
    $membershipSQL = "SELECT * FROM `membership` WHERE `active`='yes' AND `user_id`='$id'";
  $membership = $conn->query($membershipSQL);
  while($membershipRow = $membership->fetch_assoc()) {
    $memSQL = "SELECT * FROM `membership_values` WHERE `value`='".$membershipRow['membership']."'";
    $mem = $conn->query($memSQL);
    $memRow = $mem->fetch_assoc();
    echo '<a href="/awards/">
<div class="profile-card award">
<img src="/assets/membership/'.$memRow['value'].'.png">
<span class="ellipsis">'.$memRow['name'].'</span>
</div>
</a>
';
    }
          
          ?>
</div>
</div>
</div>
<div class="col-6-12" style="padding-right:0;">
<div class="card"><div class="top blue">Wearing</div><div class="content" style="text-align: center;"><?php include("ucurr.php"); ?>
</div>
</div></div>
<div class="col-1-1 tab-buttons">
<button class="tab-button blue" data-tab="1">CRATE</button>
<button class="tab-button transparent" data-tab="2">SOCIAL</button>
<button class="tab-button transparent" data-tab="3">STATS</button>
</div>
<div class="col-1-1" id="tabs">
<div class="button-tabs">
<div class="button-tab active" data-tab="1">
<div class="col-1-1">
<div class="card">
<div class="top red">
Crate
</div>
<div class="content">
        <div id="column" style="float:left;">
          <?php 
    $sortByArray = array(
    "All" => "all",
    "Hats" => "hat",
    "Tools" => "tool",
    "T-Shirts" => "tshirt",
    "Faces" => "face",
    "Shirt" => "shirt",
    "Pants" => "pants",
    "Heads" => "head"
    );
    foreach ($sortByArray as $sortByValue => $jsValue) {
    ?>
      <a class="tab-button transparent" onclick="getPage('<?php echo $jsValue; ?>',0);">
        <div class="" style="padding-right:15px; border-right:1px #000 solid;">
          <?php echo $sortByValue; ?>
        </div>
      </a>
    <?php 
    }
    ?>
        </div>
        <div id="column" style="float:right;">
          <div id="crate" style=" margin-left:25%;"></div>
        </div> 
</div>
</div>
 </div>
</div>
<div class="button-tab" data-tab="2">
<div class="row" style="padding-right:0.1px;">
<div class="col-6-12">
<div class="card">
<div class="top orange" style="position:relative;">
Clans
<a class="button orange" href="#" style="position:absolute;right:5px;top:4px;padding:5px;">SEE ALL</a>
</div>
<div class="content" style="text-align:center;min-height:330.86px;">
<?php
          $clansSQL = "SELECT * FROM `clans_members` WHERE `user_id`='$id' AND `status`='in'";
          $clans = $conn->query($clansSQL);
          while($clanRow = $clans->fetch_assoc()){
            $clanID = $clanRow['group_id'];
            $findClanSQL = "SELECT * FROM `clans` WHERE `id`='$clanID'";
            $findClan = $conn->query($findClanSQL);
            $findClanRow = $findClan->fetch_assoc();
            
    if ($findClanRow['approved'] == 'yes') {$thumbnail = $findClanRow['id'];}
    elseif ($findClanRow['approved'] == 'declined') {$thumbnail = 'declined';}
    else {$thumbnail = 'pending';}
            
            echo '<a class="col-1-3" href="/clan/'.$clanID.'" style="padding-right:5px;padding-left:5px;">
<div class="profile-card">
<img src="/clans/icons/'.$thumbnail.'.png">
<span class="ellipsis">'.$findClanRow['name'].'</span>
</div>
</a>
';
            }
          
          ?>
</div>
</div>
</div>
<?php
      $friendsListCount = $conn->query("SELECT * FROM `friends` WHERE  `to_id` = '$id' AND `status`='accepted' OR `from_id` = '$id' AND `status`='accepted'")->num_rows;
  $friendsList = mysqli_query($conn, "SELECT * FROM `friends` WHERE  `to_id` = '$id' AND `status`='accepted' OR `from_id` = '$id' AND `status`='accepted' ORDER BY `id` DESC LIMIT 0,8");
          $friendCount = mysqli_num_rows($friendsList);
          ?>
<div class="col-6-12">
<div class="card">
<div class="top red" style="position:relative;">
Friends
<a class="button red" href="/user/<?=$userRow['id']?>/friends/1" style="position:absolute;right:5px;top:4px;padding:5px;">SEE ALL</a>
</div>
<div class="content" style="text-align:center;min-height:330.86px;">
<?php
          if (mysqli_num_rows($friendsList) > 0) {
                while($friendsListRow = mysqli_fetch_assoc($friendsList)) {
              $friendRowQ = mysqli_query($conn,"SELECT * FROM `beta_users` WHERE (`id`='$friendsListRow[from_id]' OR `id`='$friendsListRow[to_id]') AND `id`!='$id' ");
                  $friendRow = mysqli_fetch_array($friendRowQ);
                  $friendUsername = $friendRow['username'];
          if (strlen($friendUsername) > 9) {
            $friendUsername = substr($friendUsername, 0, 9) . '...';
          }
                  echo "<a class='col-1-3' href='/user/".$friendRow['id']."/' style='padding-right:5px;padding-left:5px;'>
<div class='profile-card user'>
 <img src='/avatar/render/avatars/".$friendRow['id'].".png?c=".$friendRow['avatar_id']."'>
<span class='ellipsis'>".$friendUsername."</span>
</div>
</a>";
    }
    } else {
              echo "<i>This user has no friends!</i>";
          }
          ?>
            
           

</div>
</div>
</div>
</div>
</div>
<div class="button-tab" data-tab="3">
<div class="col-1-1">
<div class="card">
<div class="top red">
Statistics
</div>
<div class="content" style="min-height:330.86px;">
<?php
      
            $postCountSQL = "SELECT * FROM `forum_posts` WHERE `author_id`='$id'";
            $postCount = $conn->query($postCountSQL);
            $posts = $postCount->num_rows;
            $lastonline = time()-strtotime($userRow['last_online']);
            $threadCountSQL = "SELECT * FROM `forum_threads` WHERE `author_id`='$id'";
            $threadCount = $conn->query($threadCountSQL);
            $threads = $threadCount->num_rows;
            
            $userPostCount = ($threads+$posts);
            
    if ($lastonline >= 300) {
    $timedif = $lastonline . " seconds";
    if ($lastonline >= 300) {$timedif = (int)gmdate('i',$lastonline) . " minutes";}
    if ($lastonline >= 3600) {$timedif = (int)gmdate('H',$lastonline) . " hours";}
    if ($lastonline >= 86400) {$timedif = (int)gmdate('d',$lastonline) . " days";}
    if ($lastonline >= 2592000) {$timedif = (int)gmdate('m',$lastonline) . " months";}
    if ($lastonline >= 31536000) {$timedif = (int)gmdate('Y',$lastonline) . " years";}
    echo '<table class="stats-table">
<tr>
<td>
<b>Join Date:</b>
</td>
<td id="join-date">
' . gmdate('d/m/Y',strtotime($userRow['date'])) . '
</td>
</tr>
<tr>
<td>
<b>Last Online:</b>
 </td>
<td id="last-online">
' . $timedif . ' ago
</td>
</tr>
<tr>

<tr>
<td>
<b>Forum Posts:</b>
</td>
<td id="forum-posts">
'.$userPostCount.'
</td>
</tr>

</table>';}
  else {        
              

            echo'<table class="stats-table">
<tr>
<td>
<b>Join Date:</b>
</td>
<td id="join-date">
' . gmdate('d/m/Y',strtotime($userRow['date'])) . '
</td>
</tr>
<tr>
<td>
<b>Last Online:</b>
 </td>
<td id="last-online">
Now
</td>
</tr>

<tr>
<td>
<b>Forum Posts:</b>
</td>
<td id="forum-posts">
'.$userPostCount.'
</td>
</tr>';
  }
?>
<?php
echo '<tr>
<td>
<b>Friends:</b>
</td>
<td id="friends">';
if($loggedIn) {
                  $userID = $userRow{'id'};
                  $sqlSearch = "SELECT * FROM `friends` WHERE  `to_id` = '$userID' AND `status` = 'accepted'";
                  $result = $conn->query($sqlSearch);
                  
                  $messages = 0;
                  if ($result->num_rows > 0) {
                  while($searchRow = $result->fetch_assoc()) {$messages++;}
                  echo number_format($messages);
                  }else{
                    echo number_format($messages);
               }  
    }
echo '</td></tr></table>'; 
  ?>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
<script>
    if($('.user-description-box .user-desc').height() <= 80) {
        $('.read-more-desc').css('display', 'none');
        $('.toggle-user-desc').addClass('open');
    }
    $(document).on('click', '.read-more-desc', function () {
        $(this).parent().parent().toggleClass('closed');
        if($(this).text() == 'Read More') {
            $(this).text('Show Less');
            $('.user-description-box .content').css('min-height', $('.user-description-box .content').height() + 33)
        } else {
            $(this).text('Read More');
            $('.user-description-box .content').css('min-height', $('.user-description-box .content').height() - 33)
        }
    })
</script>
    
<div class="col-10-12 push-1-12">
<div style="text-align:center;margin-top:20px;padding-bottom:25px;">
</div>
</div>
</div>
      </div>
  </div>
        </div>
      </div>
    </div>
<script>
  var id = "<?php echo $id; ?>";

  window.onload = function() {
    getPage('hat',0);
  };
  
  function getPage(type, page) {
    $("#crate").load("/crate?id="+id+"&type="+type+"&page="+page);
  };

</script>
<?php
    include("SiT_3/footer.php");
  ?>