<?php
  include("../SiT_3/config.php");
  include("../SiT_3/header.php");
  
  if(!$loggedIn) {header('Location: ../'); die();}
  
  if(isset($_GET['page'])) {$page = mysqli_real_escape_string($conn,intval($_GET['page']));}
  $page = max($page,1);
  $limit = ($page-1)*20;
  
  $mID = $_SESSION['id'];
  $sqlCount = "SELECT * FROM `messages` WHERE  `recipient_id` = '$mID'";
  $countQ = $conn->query($sqlCount);
  $count = $countQ->num_rows;
  
  $sqlSearch = "SELECT * FROM `messages` WHERE  `recipient_id` = '$mID' ORDER BY `id` DESC LIMIT $limit,20";
  $result = $conn->query($sqlSearch);
?>

<!DOCTYPE html>
  <head>
    <title>Inbox - <?php echo $sitename; ?></title>
  </head>
  <body>
   <div class="main-holder grid">
<div class="col-10-12 push-1-12">
<div class="tabs">
<a class="tab active col-6-12" data-tab="1" href="/messages/">
Inbox
</a>
<a class="tab col-6-12" href="#">
Outbox
</a>
<div class="tab-holder" style="box-shadow:none;">
<div class="tab-body active" data-tab="1">
<div class="content" style="padding:0px">
          <?php
            while($messageRow=$result->fetch_assoc()) {
              if (!$messageRow['read']) {$weight = "bold";} else {$weight = "normal";}
              $senderID = $messageRow['author_id'];
              $sqlSender = "SELECT * FROM `beta_users` WHERE  `id` = '$senderID'";
              $sendResult = $conn->query($sqlSender);
              $senderRow=$sendResult->fetch_assoc();
              ?>
             
          
          <a href="/messages/message/<?php echo $messageRow['id']; ?>/">
<div class="hover-card thread-card m0 ">
<div class="col-7-12 topic">
<span class="small-text label dark"><?php echo htmlentities($messageRow['title']); ?></span><br>
<span class="label smaller-text" data-user-id="439464">From <span class="darkest-gray-text"><?php echo $senderRow['username'] ?></span></span>
</div>
<div class="no-mobile overflow-auto topic">
<div class="col-1-1 stat" style="text-align:right;">
<span class="title" title="2022-12-18 20:38:05">1 month ago</span><br>
 </div>
</div>
</div>
</a>
              <?php
            }
          ?>
          
          
          
        </tbody>
      </table>
      
      <?php
      echo '</div><div class="numButtonsHolder">';
      
      if($count/20 > 1) {
        for($i = 0; $i < ($count/20); $i++)
        {
          echo '<a href="?page='.$i.'">'.($i+1).'</a> ';
        }
      }
      
      echo '</div>';
      ?>
    </div></div></div></div></div></div></div></div></div></div></div></div></div></div></div></div></div></div></div></div></div></div></div></div></div></div></div></div></div></div></div></div></div></div></div></div></div></div></div></div></div></div></div></div></div></div></div></div></div></div></div></div></div></div></div></div></div></div></div></div></div></div></div></div></div></div></div></div></div></div></div></div></div></div></div></div></div></div></div></div></div></div></div></div></div></div></div></div></div></div></div></div></div></div></div></div></div></div></div></div></div></div></div></div></div></div></div></div></div></div></div></div></div></div></div></div></div></div></div></div></div></div></div></div></div></div></div></div></div></div></div></div>
  </body>
  <?php
    include("../SiT_3/footer.php");
  ?>
</html>

