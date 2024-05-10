<?php
include('config.php');
$membershipPower = -1;
if (isset($_SESSION['id'])) {
  
  $currentUserID = $_SESSION['id'];
  $findUserSQL = "SELECT * FROM `beta_users` WHERE `id` = '$currentUserID'";
  $findUser = $conn->query($findUserSQL);
  
  if ($findUser->num_rows > 0) {
    $userRow = (object) $findUser->fetch_assoc();
  } else {
    unset($_SESSION['id']);
    //header('Location: /landing/');
  }
  
  $power = $userRow->{'power'};
  $UID = $userRow->{'id'};
  $currentID = $userRow->{'id'};
  $curDate = date('Y-m-d H:i:s');
  $sqlRead = "UPDATE `beta_users` SET `last_online` = '$curDate' WHERE `id` = '$currentUserID'";
  $result = $conn->query($sqlRead);
  
  $membershipSQL = "SELECT * FROM `membership` WHERE `active`='yes' AND `user_id`='$currentUserID'";
  $membership = $conn->query($membershipSQL);
  while($membershipRow = $membership->fetch_assoc()) {
    $membershipID = $membershipRow['id'];
  $membershipValue = $membershipRow['membership'];
  $memSQL = "SELECT * FROM `membership_values` WHERE `value`='$membershipValue'";
  $mem = $conn->query($memSQL);
  $memRow = $mem->fetch_assoc();
  
  $membershipPower = max($membershipPower,$membershipValue);
  
  $currentDate = $curDate;
  $membershipEnd = date('Y-m-d H:i:s',strtotime($membershipRow['date'].' +'.$membershipRow['length'].' minutes'));
  if($currentDate >= $membershipEnd) {
    $stopSQL = "UPDATE `membership` SET `active`='no' WHERE `id`='$membershipID';";
    $stop = $conn->query($stopSQL);
  }
  }
  
  $membershipSQL = "SELECT * FROM `membership_values` WHERE `value`='$membershipPower'";
  $membership = $conn->query($membershipSQL);
  $membershipRow = $membership->fetch_assoc();

  $lastonline = strtotime($curDate)-strtotime($userRow->{'daily_bits'});
  if ($lastonline >= 86400) {
    $bits = ($userRow->{'bits'}+150);
    $sqlBits = "UPDATE `beta_users` SET `bits` = '$bits' WHERE `id` = '$currentUserID'";
    $result = $conn->query($sqlBits);

    $daily = $curDate;
    $sqlDaily = "UPDATE `beta_users` SET `daily_bits` = '$daily' WHERE `id` = '$currentUserID'";
    $result = $conn->query($sqlDaily);
    
    
    ////MEMBERSHIP CASH
  $membershipSQL = "SELECT * FROM `membership` WHERE `active`='yes' AND `user_id`='$currentUserID'";
  $membership = $conn->query($membershipSQL);
  while($membershipRow = $membership->fetch_assoc()) {
    $membershipValue = $membershipRow['membership'];
    $memSQL = "SELECT * FROM `membership_values` WHERE `value`='$membershipValue'";
    $mem = $conn->query($memSQL);
    $memRow = $mem->fetch_assoc();
    
    $userMemSQL = "SELECT * FROM `beta_users` WHERE `id`='$currentUserID'";
    $userMem = $conn->query($userMemSQL);
    $userMemRow = $userMem->fetch_assoc();
    $bucks = ($userMemRow['bucks']+$memRow['daily_bucks']);
    $bucksSQL = "UPDATE `beta_users` SET `bucks` = '$bucks' WHERE `id` = '$currentUserID'";
    $result = $conn->query($bucksSQL);
  }
  ////
  }
  $loggedIn = true;
} else {
  $loggedIn = false;
  
  $URI = $_SERVER['REQUEST_URI'];
  if ($URI != '/login/' && $URI != '/register/') {
    //header('Location: /login/');
  }
}

$shopUnapprovedAssetsSQL = "SELECT * FROM `shop_items` WHERE `approved`='no' ORDER BY `date` DESC LIMIT 0,10";
$shopUnapprovedAssets = $conn->query($shopUnapprovedAssetsSQL);
$unapprovedNum = $shopUnapprovedAssets->num_rows;
?>
<!DOCTYPE html>
  <head>
  <script
  src="https://code.jquery.com/jquery-3.1.1.min.js"
  integrity="sha256-hVVnYaiADRTO2PzUGmuLJr8BLUSjGIZsDYGmIJLv2b8="
  crossorigin="anonymous"></script>
  <script src="/javascript/security.js?r=<?php echo rand(10000,1000000) ?>"></script>
  <script src="https://kit.fontawesome.com/beb53ae18f.js" crossorigin="anonymous"></script>
  <script type="text/javascript"  src="https://apiv2.popupsmart.com/api/Bundle/398699" async></script>
  <?php 
  if($loggedIn) {
    $theme = $userRow->{'theme'};
  } else {
    $theme = 0;
  }
  $themeArray = array(
  "0" => "",
  "1" => "",
  );
  //$themeName = strtr();
  

    ?>

    <link rel="icon" href="/assets/SHIT.png">
    <link rel="stylesheet" href="/style.css?r=<?php echo rand(10000,1000000) ?>" type="text/css">
  <?php 
  if ($theme == 1) {
    ?>
    <link rel="stylesheet" href="/style.css?r=<?php echo rand(10000,1000000) ?>" type="text/css">
    <?php
  } elseif ($theme == 2) {
    ?>
  <link rel="stylesheet" href="/assets/night.css?r=<?php echo rand(10000,1000000) ?>" type="text/css">
    <?php
  } elseif ($theme == 3) { 
    ?>
<?php  }  elseif ($theme == 4) {
    ?>

    <?php
  } elseif ($theme == 5) {
    ?>
    <link rel="stylesheet" href="/assets/experimental.css?r=<?php echo rand(10000,1000000) ?>" type="text/css">
    <?php
  }
  ?>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  </head>
  <body>

  <?php  if ($theme == 4) {
    ?>

    <?php
  } ?>
    <div id="header">
      <div id="banner">
  <?php if($loggedIn) {echo '<div id="welcome"><a class="nav" href="/" >Welcome, '. $userRow->{'username'}.'</a></div>';} ?>
        <div id="info" <?php if(!$loggedIn) {echo 'style="visibility:hidden;"';} ?> >
          <span style="display:inline-block;float: left;margin-left: -5px;">
            <ul>
              <li><a class="nav" href="/information/money" title="bucks"><img style="float:middle;margin-bottom:-4px;" src="/assets/FireGrey.png" width="17" height="17"><i></i> <?php if($loggedIn) {echo number_format($userRow->{'bucks'});} ?></a></li>
              <li><a class="nav" href="/information/money" title="bits"><img style="float:middle;margin-bottom:-4px;" src="/assets/RockGrey.png" width="17" height="17"><i></i> <?php if($loggedIn) {echo number_format($userRow->{'bits'});} ?></a></li>
            </ul>
          </span>
          <span style="float:right; display:inline-block;padding-left: 10px;">
            <ul>
              <li><a class="nav" href="/messages/"><i class="fa fa-envelope"></i>
                <?php 
                if($loggedIn) {
                  $mID = $userRow->{'id'};
                  $sqlSearch = "SELECT * FROM `messages` WHERE  `recipient_id` = '$mID' AND `read` = 0";
                  $result = $conn->query($sqlSearch);
                  
                  $messages = 0;
                  while($searchRow=$result->fetch_assoc()) {$messages++;}
                  echo number_format($messages); 
                }
                ?>
              </a></li>
              <li><a class="nav" href="/friends/"><i class="fa fa-users"></i> 
              <?php
              if($loggedIn) {
                $requestsQuery = mysqli_query($conn,"SELECT * FROM `friends` WHERE `to_id`='$mID' AND `status`='pending'");
                $requests = mysqli_num_rows($requestsQuery);
                echo number_format($requests);
              }
              ?>
              </a></li>
            </ul>
          </span>
        </div>
      </div>
      <div id="navbar">
        <span>
          <span>
          <?php
          if($loggedIn) {
            echo '<a href="/user/'.$userRow->{'id'}.'/">You</a>';
          } else {
            echo '<a href="/login/">You</a>';
          }
          ?>
          </span>
          <span> | </span>
          <span>
            <a class="nav" href="/shop/">Shop</a>
          </span>
          <span> | </span>
          <span>
            <a class="nav" href="/clans/">Clans</a>
          </span>
          <span> | </span>
          <span>
            <a class="nav" href="/search/">Search</a>
          </span>
          <span> | </span>
          <span>
            <a class="nav" href="/forum/">Forum</a>
          </span>
                    <?php
            if($loggedIn) { if($power >= 5) {
            echo '<span> | </span><a class="nav" href="/admin/">Admin</a>';
          }}
          ?>
          <span style="float:right; margin-top: -6px;">
          <span style="float:right; margin-right: -187px;">
          <?php
          if($loggedIn) {
            echo '<a class="nav" href="/login/logout">     Logout</a>';
          } else {
            echo '   <a class="nav" href="/login/">     Login</a>';
          }
          ?>
          </span>
        </span>
      </div>
      <?php
include('header2.php');
      ?>
<?php
  $id = 1;
  $findalertSQL = "SELECT * FROM `alert` WHERE `id` = '$id'";
  $findalert = $conn->query($findalertSQL);
  $alertRow=$findalert->fetch_assoc();
echo('<div style="border: 1px solid #b30707;background-color: #ff9a9a;color: #b30707;text-align:center;padding: 3px;margin-top:5px;">'.$alertRow['alert'].'</div>');
?>
    </div>
<?php
  if($_SERVER['REMOTE_ADDR'] != '82.21.246.202') { //for working on the site
    //exit;
  }
  ////
  if($loggedIn) {
    $bannedSQL = "SELECT * FROM `moderation` WHERE `active`='yes' AND `user_id`='$currentID'";
    $banned = $conn->query($bannedSQL);
    if($banned->num_rows != 0) {//they are banned
      $URI = $_SERVER['REQUEST_URI'];
      if ($URI != '/banned/') {
      header('Location: /banned/');
    
      $bannedRow = $banned->fetch_assoc();
      $banID = $bannedRow['id'];
      $currentDate = strtotime($curDate);
      $banEnd = strtotime($bannedRow['issued'])+($bannedRow['length']*60);
      if($bannedRow['length'] <= 0) {$title = "You have been warned";}
      elseif($bannedRow['length'] < 60) {$title = "You have been banned for ".$bannedRow['length']." minutes";}
      elseif($bannedRow['length'] >= 60) {$title = "You have been banned for ".round($bannedRow['length']/60)." hours";}
      elseif($bannedRow['length'] >= 1440) {$title = "You have been banned for ".round($bannedRow['length']/1440)." days";}
      elseif($bannedRow['length'] >= 43200) {$title = "You have been banned for ".round($bannedRow['length']/43200)." months";}
      elseif($bannedRow['length'] >= 525600) {$title = "You have been banned for ".round($bannedRow['length']/525600)." years";}
      elseif($bannedRow['length'] >= 36792000) {$title = "You have been terminated";}
      echo '<head>
          <title>Banned - TDBDNH</title>
        </head>
        <body>
          <div id="body">
            <div id="box">
              <h3>'.$title.'</h3>
              <div style="margin:10px">
                Reviewed: ' . gmdate('m/d/Y',strtotime($bannedRow['issued'])) . '<br>
                Moderator Note:<br>
                <div style="border:1px solid;width:400px;height:150px;background-color:#F9FBFF">
                  ' . $bannedRow['admin_note'] . '
                </div>';
      
      if($currentDate >= $banEnd) {
        if(isset($_POST['unban'])) {
          $unbanSQL = "UPDATE `moderation` SET `active`='no' WHERE `id`='$banID'";
          $unban = $conn->query($unbanSQL);
          header("Refresh:0");
        }
        echo 'You can now reactivate your account<br>
        <form action="" method="POST">
          <input type="submit" name="unban" value="Reactivate my account">
        </form>';
      } else {
        echo 'Your account will be unbanned on ' . date('d-m-Y H:i:s',$banEnd);
      }
      echo '
              </div>
            </div>
          </div>
        </body>';
      exit;
    }
  }
  
  }
  ////
?>