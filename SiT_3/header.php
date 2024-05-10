<?php

include('configuration.php');

  if($_SESSION["canAccess"] != "true"){
        if(!isset($_SESSION["canAccess"])){
            if($offline == "true"){
                die("<script>window.location = '/offline';</script>");
            }
        }   
    }

  

?>
<?php
function fix($str){
    return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}

function number_short( $n, $precision = 2 ) {
    if ($n < 900) {
        // 0 - 900
        $n_format = number_format($n, $precision);
        $suffix = '';
    } else if ($n < 900000) {
        // 0.9k-850k
        $n_format = number_format($n / 1000, $precision);
        $suffix = 'K';
    } else if ($n < 900000000) {
        // 0.9m-850m
        $n_format = number_format($n / 1000000, $precision);
        $suffix = 'M';
    } else if ($n < 900000000000) {
        // 0.9b-850b
        $n_format = number_format($n / 1000000000, $precision);
        $suffix = 'B';
    } else {
        // 0.9t+
        $n_format = number_format($n / 1000000000000, $precision);
        $suffix = 'T';
    }
  // Remove unecessary zeroes after decimal. "1.0" -> "1"; "1.00" -> "1"
  // Intentionally does not affect partials, eg "1.50" -> "1.50"
    if ( $precision > 0 ) {
        $dotzero = '.' . str_repeat( '0', $precision );
        $n_format = str_replace( $dotzero, '', $n_format );
    }
    return $n_format . $suffix;
}

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
  $executive = $userRow->{'executive'};
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
   <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css" integrity="sha384-UHRtZLI+pbxtHCWp1t77Bi1L4ZtiqrqD80Kn4Z8NTSRyMA2Fd33n5dQ8lWUE00s/" crossorigin="anonymous">

<script>
        (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
        (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
        m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
        })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');
        ga('create', 'UA-122702268-1', 'auto');
        ga('send', 'pageview')
    </script>
<script src="https://js.brkcdn.com/390f650b040fcc0f5f85.js"></script>
<script src="https://js.brkcdn.com/c709ecdec65c74ed46ff.js"></script>
<script src="https://js.brkcdn.com/d5fc6b02ce16b82664fd.js"></script>
<script src="https://js.brkcdn.com/d2c0d8a4805fdd0a4b41.js"></script>
<script src="https://js.brkcdn.com/57af023c87e71bf36f5a.js"></script>
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
  
  if ($theme != 3) {
    ?>
    <script src="/javascript/css/night.js?r=<?php echo rand(10000,1000000) ?>"></script>
    <?php
  }
  ?>
    <link rel="icon" href="http://good-hillreturned.thebesteprivatelordedwebsite.ct8.pl/assets/GoodIcon.png">
    <link rel="stylesheet" href="/SiT_3/css/light.css?r=<?php echo rand(10000,1000000) ?>" type="text/css">
  <?php
  if ($theme == 1) {
    ?>
  <link rel="stylesheet" href="/SiT_3/css/SeasonalLight.css?r=<?php echo rand(10000,1000000) ?>" type="text/css">
    <?php
  } elseif ($theme == 2) {
    ?>
  <link rel="stylesheet" href="/SiT_3/css/dark.css?r=<?php echo rand(10000,1000000) ?>" type="text/css">
    <?php
  } elseif ($theme == 3) {
    ?>
  <link rel="stylesheet" href="/SiT_3/css/future.css?r=<?php echo rand(10000,1000000) ?>" type="text/css">
    <?php
  } elseif ($theme == 4) {
    ?>
    <link rel="stylesheet" href="/SiT_3/css/SeasonalDark.css?r=<?php echo rand(10000,1000000) ?>" type="text/css">
    <?php
  } elseif ($theme == 5) {
    ?>
    <link rel="stylesheet" href="/SiT_3/css/Halloween.css?r=<?php echo rand(10000,1000000) ?>" type="text/css">
    <?php
  } elseif ($theme == 6) {
  ?>
<link rel="stylesheet" href="/SiT_3/css/windows98.css?r=<?php echo rand(10000,1000000) ?>" type="text/css">
 <?php
  } elseif ($theme == 7) {
  ?>
<link rel="stylesheet" href="/SiT_3/css/2017.css?r=<?php echo rand(10000,1000000) ?>" type="text/css">
<?php
 }
 ?>
    <nav>
<div class="primary">
<div class="grid">
<div class="push-left">
<ul>
<li>
<a href="/play/">
Play
</a>
</li>
<li>
<a href="/shop/">
Shop
</a>
</li>
<li>
<a href="/clans/">
Clans
</a>
</li>
<li>
<a href="/search/">
Users
</a>
</li>
<li>
<a href="/forum/">
Forum
</a>
</li>
<li>
<a href="/membership/">
Membership
</a>
  <a href="https://discord.gg/pTyPG2HaRS">
Discord
</a>
</li>
<?php
if($loggedIn) {
if($power >= 1) {echo '<li>
<a href="/panel/">
Admin'; if ($unapprovedNum > 0) { echo " <span class='nav-notif'>$unapprovedNum ";} echo '</a></li>';}
}
?>
</ul>
</div>
  <?php
          if($loggedIn) {
            echo '<div class="nav-user push-right" id="info">
<div class="info">
<a href="/currency" class="header-data" title="0">
<span class="bucks-icon img-white"></span>
'.number_short($userRow->{'bucks'}).'
</a>
<a href="/currency" class="header-data" title="74">
<span class="bits-icon img-white"></span>
'.number_short($userRow->{'bits'}).'
</a>
<a href="/messages" class="header-data">
<span class="messages-icon img-white"></span>
'.number_short($messages).'
</a>
<a href="/friends" class="header-data">
<span class="friends-icon img-white"></span>
'.number_short($requests).'
</a>
</div>
<div class="username ellipsis">
<div id="username-bar">
<div class="username-holder ellipsis inline unselectable">'. $userRow->{'username'}.'</div>
<i class="arrow-down img-white"></i>
</div>
</div>
</div>
</div>
</div>';
          } else {
            echo '<div class="nav-user push-right" id="info">
<div class="username login-buttons">
<a href="/login" class="login-button">Login</a>
<a href="/register" class="register-button">Register</a>
</div>
</div>
</div>
</div>
</div>';
          }
          ?>

      <?php
include('header2.php');
      ?>
      </nav>
  <dropdown id="dropdown-v" class="dropdown" activator="username-bar" contentclass="logout-dropdown">
<ul>
<li>
<a onclick="document.getElementById('logout').submit()">Logout</a>
</li>
</ul>
<form method="POST" action="/logout" id="logout">
<input type="hidden" name="_token" value="3KG00ZRURTxEHaqMw5oIZL4yS1IzDQBusJQj1NlS"> </form>
</dropdown>
<!--<?php
  $id = 1;
  $findalertSQL = "SELECT * FROM `alert` WHERE `id` = '$id'";
  $findalert = $conn->query($findalertSQL);
  $alertRow=$findalert->fetch_assoc();
echo('<div style="border: 1px solid #b30707;background-color: #ff9a9a;color: #b30707;text-align:center;padding: 3px;margin-top:5px;">'.$alertRow['alert'].'</div>');
?>-->
  <?php
  $id = 1;
  $findalertSQL = "SELECT * FROM `alert` WHERE `id` = '$id'";
  $findalert = $conn->query($findalertSQL);
  $alertRow=$findalert->fetch_assoc();
echo('<div class="grid"><div class="alert '.$alertRow['type'].'">'.$alertRow['alert'].'</div></div>');
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