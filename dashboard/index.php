<?php
include('../SiT_3/head.php');

if ($loggedIn) {

  $currentUserID = $_SESSION['id'];
  $findUserSQL = "SELECT * FROM `beta_users` WHERE `id` = '$currentUserID'";
  $findUser = $conn->query($findUserSQL);
  
      //threads + posts = forum posts
            $postCountSQL = "SELECT * FROM `forum_posts` WHERE `author_id`='$currentUserID'"; //can't wait for php errors
            $postCount = $conn->query($postCountSQL);
            $posts = $postCount->num_rows;
            $threadCountSQL = "SELECT * FROM `forum_threads` WHERE `author_id`='$currentUserID'"; //wtf it worked, how?
          //can't believe it still works
            $threadCount = $conn->query($threadCountSQL);
            $threads = $threadCount->num_rows;
            
            $userPostCount = ($threads+$posts);

      //friends
$id = $userRow->{'id'};
$friendsQuery = "SELECT `from_id`,`to_id` FROM `friends` WHERE (`from_id`='$id' OR `to_id`='$id') AND `status`='accepted'";
$friends = $conn->query($friendsQuery);

$friendsArray = array();
while($friendRow = $friends->fetch_assoc()) {
  $friendsArray[] = $friendRow['from_id'];
  $friendsArray[] = $friendRow['to_id'];
}

$friendList = join("','",$friendsArray);
$friendnumrows = mysqli_num_rows($friends);

  if ($findUser->num_rows > 0) {
    $userRow = (object) $findUser->fetch_assoc();
  } else {
    unset($_SESSION['id']);
    header('Location: /');
    die();
  }
  
} else {
  header('Location: /');
  die();
}

//update desc
if (isset($_POST['desc'])) {
  $newDesc = mysqli_real_escape_string($conn,$_POST['desc']);
  //$newDesc = strip_tags($_POST['desc']);
  $userID = $userRow->{'id'};
  $updateDescSQL = "UPDATE `beta_users` SET `description` = '$newDesc' WHERE `id` = '$userID'";
  $updateDesc = $conn->query($updateDescSQL);
  header("Location: index");
  }
  //update status
if (isset($_POST['status'])) {
  //make something where you can't spam statuses
  $newStatus = mysqli_real_escape_string($conn,$_POST['status']);
  mysqli_query($conn,"INSERT INTO `statuses` VALUES (NULL,'$currentUserID','$newStatus','$curDate')");
  header("Location: index");
  }

$statusSQL = "SELECT * FROM `statuses` WHERE `owner_id`='$currentUserID' ORDER BY `id` DESC";
$findStatus = $conn->query($statusSQL);

if ($findStatus->num_rows > 0) {
  $statsRow = (object) $findStatus->fetch_assoc();
}
//games
$findGamesSQL = "SELECT * FROM `games`";
$findGames = $conn->query($findGamesSQL);
$gameRow = $findGames->fetch_assoc()

?>

<!DOCTYPE html>
<?php
  include("../SiT_3/alert.php");
  ?>

  <head>
    <title>Dashboard - <?php echo $sitename; ?></title>
  </head>
  <body>

</div>
<div class="main-holder grid">
<div class="col-10-12 push-1-12">
<div class="col-4-12">
<div class="card">
<div class="content rounded center-text">
<img src="/avatar/render/avatars/<?php echo $userRow->{'id'}; ?>.png?c=<?php echo $userRow->{'avatar_id'}; ?>" style="width:100%;">
<span class="bold gray-text"><?php echo $userRow->{'username'}; ?></span>
<hr>
<div class="col-4-12 p0">
<div class="very-bold red-text smedium-text"><?php echo $friendnumrows ?></div>
<div class="gray-text cap-text bold small-text">Friends</div>
</div>
<div class="col-4-12 p0">
<div class="very-bold blue-text smedium-text"><?php echo $userPostCount ?></div>
<div class="gray-text cap-text bold small-text">Posts</div>
</div>
<div class="col-4-12 p0">
<div class="very-bold green-text smedium-text">0</div>
<div class="gray-text cap-text bold small-text">Visits</div>
</div>
</div>
</div>
<div class="card">
<div class="top red">
News
</div>
<div class="content">
<div class="block">
<?php
include('news.php'); //lifehack w/ currently wearing strikes back!
?>
</div>
</div>
</div>
</div>
<div class="col-8-12">
<div class="card">
<div class="top blue">
About Me
</div>
<div class="content">
<span class="gray-text very-bold">Status:</span>
<form style="width:75%;"action="" method="Post">
<input type="hidden" name="_token" value="wGfYHVszadgOYOEouCRRiCHqLkW0qgRGVw1bxxyN"> <div class="input-group fill">
<input name="status" placeholder="Right now I'm..." type="text">
<button class="input-button">Post</button>
</div>
</form>
<span class="gray-text very-bold">Blurb:</span>
<form method="POST" action="">
<input type="hidden" name="_token" value="wGfYHVszadgOYOEouCRRiCHqLkW0qgRGVw1bxxyN"> <textarea name="desc" class="width-100 mb1" style="height:80px;" placeholder="Hi, my name is <?php echo $userRow->{'username'}; ?>"><?php echo $userRow->{'description'}; ?></textarea>
<button class="button small smaller-text blue">Submit</button>
</form>
</div>
</div>
<div class="card">
<div class="top orange">
My Feed
</div>
          <table border="0" cellpadding="0" cellspacing="0" width="100%" style="border-collapse: collapse;">
    <tbody>
      <div class="content">

            <?php
$id = $userRow->{'id'};
$friendsQuery = "SELECT `from_id`,`to_id` FROM `friends` WHERE (`from_id`='$id' OR `to_id`='$id') AND `status`='accepted'";
$friends = $conn->query($friendsQuery);

$friendsArray = array();
while($friendRow = $friends->fetch_assoc()) {
  $friendsArray[] = $friendRow['from_id'];
  $friendsArray[] = $friendRow['to_id'];
}

$friendList = join("','",$friendsArray);

$statusQuery = "SELECT * FROM `statuses` WHERE `owner_id` IN('$friendList') AND `owner_id`!='$id' ORDER BY `id` DESC";
$status = $conn->query($statusQuery);

for($c = 0; $c < 10; $c++) {
  $statusRow = $status->fetch_assoc();
  
  if(empty($statusRow)) {break;}
  
  $findPosterRowSQL = "SELECT * FROM `beta_users` WHERE `id` = '" . $statusRow['owner_id'] . "'";
  $findPosterRow = $conn->query($findPosterRowSQL);
  $posterRow = $findPosterRow->fetch_assoc();
  echo '<div class="status">
<a href="/user?id='.$statusRow['owner_id'].'">
<img src="/avatar/render/avatars/'.$statusRow['owner_id'].'.png?c='.$posterRow['avatar_id'].'" style="vertical-align:middle; width:55px;">
</a>
<div class="status-text ellipsis">
<a href="/user/'.$statusRow['owner_id'].'" class="very-bold dark-gray-text block">' .$posterRow['username']. '</a>
<div class="status-body gray-text">' .$statusRow['body']. '</div>
<span class="bold dark-gray-text status-time absolute bottom" >'.$statusRow['date'].'</span>
</div>
</div>
<hr>';
}

/*while($statusRow = $status->fetch_assoc()) {
  $findPosterRowSQL = "SELECT * FROM `beta_users` WHERE `id` = '" . $statusRow['owner_id'] . "'";
  $findPosterRow = $conn->query($findPosterRowSQL);
  $posterRow = $findPosterRow->fetch_assoc();
  echo '<div id="subsect" style="overflow: auto;">
    <div style="display:inline-block;float:left;">
    <a href="http://www.brick-hill.com/user.php?id='.$statusRow['owner_id'].'" style="color:#000;text-decoration:none;">
      <img style="width:40px;" src="/avatar/avatars/'.$statusRow['owner_id'].'.png?c='.$posterRow['avatar_id'].'"><br>' .$posterRow['username']. '</a>
    </div>
    <div>'.str_replace(">","&gt;",str_replace("<","<",$statusRow['body'])).'</div>
    </div>';
  }*/
            
            
            
            ?>
            </tbody>
            </table>
          
</div>
</div>
</div>
</div>

</div>
</div>
  </div>
  </div>
      </div>
  </div>
      </div>
  </div>
      </div>
  </div>
    <?php include('../SiT_3/footer.php'); ?>
  </body>
</html>