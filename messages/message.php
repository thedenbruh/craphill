<?php
  include("../SiT_3/config.php");
  include("../SiT_3/header.php");
  
  if(!$loggedIn) {header('Location: ../'); die();}
  
  if (isset($_GET['id'])) {
    $mid = mysqli_real_escape_string($conn,intval($_GET['id']));
    $sqlMessage = "SELECT * FROM `messages` WHERE  `id` = '$mid'";
    $result = $conn->query($sqlMessage);
    $messageRow=$result->fetch_assoc();
    if ($messageRow['recipient_id'] != $userRow->{'id'} && $power < 1) {header("location: index");}
    
    $senderID = $messageRow['author_id'];
    $sqlSender = "SELECT * FROM `beta_users` WHERE  `id`='$senderID'";
    $sendResult = $conn->query($sqlSender);
    $senderRow=$sendResult->fetch_assoc();
    
    $sqlRead = "UPDATE `messages` SET `read` = 1 WHERE `id` = '$mid'";
    $result = $conn->query($sqlRead);
  } else {
    header("location: index");
  }
?>

<!DOCTYPE html>
  <head>
    <title><?php echo $messageRow['title']; ?> - <?php echo $sitename; ?></title>
  </head>
  <body>
    <div class="main-holder grid">
<div class="col-10-12 push-1-12">
<div class="card">
<div class="top blue">
<?php echo $messageRow['title']; ?>
</div>
<div class="content" style="position:relative;">
          <div class="user-info" style="width:250px;overflow:hidden;display:inline-block;float:left;">
<a href="/user/<?php echo $senderRow['id']; ?>/">
<img src="/avatar/render/avatars/<?php echo $senderRow['id']; ?>.png?c=<?php echo $senderRow['avatar_id'] ?>" style="width:200px;display:block;">
<span style="white-space:nowrap;"><?php echo $senderRow['username']; ?></span>
</a>
</div>
<div style="padding-left:250px;padding-bottom:10px;">
<?php echo nl2br(htmlentities($messageRow['message'])); ?>
</div>
 <div class="admin-forum-options" style="position:absolute;bottom:0;right:2px;padding-bottom:5px;">
<a href="#" class="dark-gray-text cap-text">Report</a>
</div>
</div>
</div>
  <div class="center-text">
<a class="button blue inline" style="margin: 10px auto 10px auto;">REPLY</a>
</div>
  </div>
</div>
</div>
  <?php
    include("../SiT_3/footer.php");
  ?>
</html>