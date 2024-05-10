<?php
  include("../SiT_3/config.php");
  include("../SiT_3/head.php");
  
  if(!$loggedIn) {header('Location: ../'); die();}
  
  $userID = $userRow->{'id'};
?>
<!DOCTYPE>
<html>

  <head>
    <title>Friends - <?php echo $sitename; ?></title>
  </head>
  
  <body>
    <div id="body">
      <div id="box">
        <div id="friendsPending">
        <?php
          // Get Pending Friend Requests
          $requests = mysqli_query($conn, "SELECT * FROM `friends` WHERE `to_id`='$userID' AND `status`='pending' ORDER BY `id` DESC");
          $requestsCount = mysqli_num_rows($requests);
          
          echo '<div class="main-holder grid">
<div class="col-10-12 push-1-12">
<div class="card">
<div class="top blue">
Friends
</div>
<div class="content text-center">
<ul class="friends-list">';
          if (mysqli_num_rows($requests) > 0) {
              // output data of each row
              while($requestsData = mysqli_fetch_assoc($requests)) {
                // Get other user details
                $otherUserDetails = mysqli_query($conn,"SELECT * FROM `beta_users` WHERE `id`='$requestsData[from_id]'");
                $otherUser = mysqli_fetch_array($otherUserDetails);

                  echo "
                  <li class='col-1-5 mobile-col-1-1'>
<div class='friend-card'>
<a href='/user/503511/'>
<img src='/avatar/render/avatars/$otherUser[id].png?c=".$otherUser['avatar_id']."' style='vertical-align:middle; width:120px;'>
<div class='ellipsis'>$otherUser[username]</div>
</a>

                                                                   
        <form action='add' method='post' class='accept'>
                      <input type='hidden' name='accept'>
                      <input type='hidden' name='OtherUserId' value='$otherUser[id]'>
                      <button class='button small green inline' style='left:10px;font-size:10px;'>ACCEPT</button>                                          
                    </form>
                    
                    <form action='add' method='post' class='decline'>
                      <input type='hidden' name='decline'>
                      <input type='hidden' name='OtherUserId' value='$otherUser[id]'>
                      <button class='button small red inline' style='left:10px;font-size:10px;'>DECLINE</button>
                    </form>                                                           
                                                                   

</div>
</li>";
                }
          } else {
              echo "<span>You don't have any friend requests</span>";
          }
              echo "</ul></div></div>";
          $friendsList = mysqli_query($conn, "SELECT * FROM `friends` WHERE  `to_id` = '$userID' AND `status`='accepted' OR `from_id` = '$userID' AND `status`='accepted' ORDER BY `id` DESC");
          $friendCount = mysqli_num_rows($friendsList);
          
          echo '';
          
          if (mysqli_num_rows($friendsList) > 0) {
                while($friendsListRow = mysqli_fetch_assoc($friendsList)) {
              $friendRowQ = mysqli_query($conn,"SELECT * FROM `beta_users` WHERE (`id`='$friendsListRow[from_id]' OR `id`='$friendsListRow[to_id]') AND `id`!='$userID'");
                  $friendRow = mysqli_fetch_array($friendRowQ);
                  
                  echo "";
            }
          } else {
            echo "";
          }
          echo "";
          ?>
      </div>
    </div>
    </div>
    <?php
    include("../SiT_3/footer.php");
    ?>
  </body>
  
</html>