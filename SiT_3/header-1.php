<?php


include('configuration.php');

  if($_SESSION["canAccess"] != "true"){
        if(!isset($_SESSION["canAccess"])){
            if($offline == "true"){
                die("<script>window.location = '/maintenance';</script>");
            }
        }   
    }

  

?>
<?php
  $membershipPower = -1;
if (isset($_SESSION['id'])) {
  
  $currentUserID = $_SESSION['id'];
  $findUserSQL = "SELECT * FROM `beta_users` WHERE `id` = '$currentUserID'";
  $findUser = $conn->query($findUserSQL);
  
  if ($findUser->num_rows > 0) {
    $userRow = (object) $findUser->fetch_assoc();
  } else {
    unset($_SESSION['id']);
    //header('Location: /login/');
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
    $bits = ($userRow->{'bits'}+10);
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
    <link rel="icon" href="http://www.brick-hill.com/assets/BH_favicon.png">
    <link rel="stylesheet" href="/func/css/light.css?r=<?php echo rand(10000,1000000) ?>" type="text/css">
  <?php
  if ($theme == 1) {
    ?>
  <link rel="stylesheet" href="/func/css/halloween.css?r=<?php echo rand(10000,1000000) ?>" type="text/css">
    <?php
  } elseif ($theme == 2) {
    ?>
  <link rel="stylesheet" href="/func/css/dark.css?r=<?php echo rand(10000,1000000) ?>" type="text/css">
    <?php
  } elseif ($theme == 3) {
    ?>
  <link rel="stylesheet" href="/func/css/future.css?r=<?php echo rand(10000,1000000) ?>" type="text/css">
    <?php
  } elseif ($theme == 4) {
    ?>
    <link rel="stylesheet" href="/assets/SummerTheme.css?r=<?php echo rand(10000,1000000) ?>" type="text/css">
    <?php
  } elseif ($theme == 5) {
    ?>
    <link rel="stylesheet" href="/assets/FallTheme.css?r=<?php echo rand(10000,1000000) ?>" type="text/css">
    <?php
  }
  ?>

<?php
  function fix($str){
      return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}

function number_short( $n, $precision = 2 ) {
    if ($n < 1000) {
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
  ?>


<head
  <meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css" integrity="sha384-UHRtZLI+pbxtHCWp1t77Bi1L4ZtiqrqD80Kn4Z8NTSRyMA2Fd33n5dQ8lWUE00s/" crossorigin="anonymous">
<script src="https://partner.googleadservices.com/gampad/cookie.js?domain=www.brick-hill.com&amp;callback=_gfp_s_&amp;client=ca-pub-8980316398731579&amp;cookie=ID%3D1e8c389d2bcc8a1c-223a7bea7dcc00ea%3AT%3D1633795040%3ART%3D1633795040%3AS%3DALNI_Mby6-x5x5_mdVBDFkTH9DYULiWE-A"></script><script src="https://pagead2.googlesyndication.com/pagead/managed/js/adsense/m202203210101/show_ads_impl_with_ama.js?client=ca-pub-8980316398731579&amp;plah=www.brick-hill.com&amp;bust=31065814" id="google_shimpl"></script><script async="" src="https://www.google-analytics.com/analytics.js"></script><script src="https://js.brkcdn.com/a81b029bb2c2541839e4.js"></script>
<script src="https://js.brkcdn.com/cc09585f6f8d88515279.js"></script>
<script src="https://js.brkcdn.com/a979c41d76d0305a9017.js"></script>
<script src="https://js.brkcdn.com/bf386c61c25e5f413467.js"></script>
<script src="https://js.brkcdn.com/55b4c8288b59fc4e559d.js"></script>
<script data-ad-client="ca-pub-8980316398731579" async="" src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js" data-checked-head="true"></script>
  <script src="https://js.hcaptcha.com/1/api.js" async defer></script>
        <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
        <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.15.1/css/all.css?t=<?=time()?>" data-turbolinks-track="true" />
        <script src="https://cdn.jsdelivr.net/npm/vue"></script>
     
  <?php //echo '<link rel="stylesheet" href="http://planet-hill.ml/assets/css/foundation.css?r=1">';?>

      
  
</head>
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
 </li>
  <?php
          if($loggedIn) {
            if($power >= 1) {echo '<li>
<a href="/admin/">
Admin<span class="nav-notif">';if ($unapprovedNum > 0) { echo " $unapprovedNum ";}
  echo'</span>
</a>
 </li>';
          }
            }
          ?>
</ul>
</div>

  <?php
          if($loggedIn) {
             {
               ?>

<div class="nav-user push-right" id="info">
<div class="info">
<a href="/currency" class="header-data" title="<?php if($loggedIn) {echo number_short($userRow->{'bucks'});} ?>">
<span class="bucks-icon img-white"></span>
<?php if($loggedIn) {echo number_short($userRow->{'bucks'});} ?> 
   
</a>
<a href="/currency" class="header-data" title="<?php if($loggedIn) {echo number_short($userRow->{'bits'});} ?>">
<span class="bits-icon img-white"></span>
<?php if($loggedIn) {echo number_short($userRow->{'bits'});} ?></a>
<a href="/messages" class="header-data">
<span class="messages-icon img-white"></span>
0
</a>
<a href="/friends" class="header-data">
<span class="friends-icon img-white"></span>
0
</a>
</div>
 
<div class="username ellipsis">
<div id="username-bar">
<div class="username-holder ellipsis inline unselectable"><?php echo' '. $userRow->{"displayname"}.'  '; ?></div>
<i class="arrow-down img-white"></i>
</div>
   <?php
  }
  }
              
  
  ?>
  
  <?php
          if(!$loggedIn) {
             {
{echo '
<div class="nav-user push-right" id="info">
<div class="username login-buttons">
<a href="/login" class="login-button">Login</a>
<a href="/register" class="register-button">Register</a>
</div>
</div>
</div>
</div>
   ';}
               }
               }
  ?>
</div>
</div>
</div>
</div>
  <?php
          if($loggedIn) {
             {
{echo '
<div class="secondary">
<div class="grid">
<div class="bottom-bar">
<ul>
    
<li>
  <a href="/" id="pHome">
Home
</a>
</li>
<li>
<a href="/settings/" id="pSettings">
Settings
</a>
</li>
<li>
<a href="/customize/" id="pAvatar">
Avatar
</a>
</li>
<li>
<a href="/user/'.$userRow->{'id'}.'" id="pProfile">
Profile
</a>
</li>
<li>
<a href="/trades/" id="pTrades">
Trades
</a>
</li>

<li>
<a href="/currency/" id="pCurrency">
Currency
</a>
</li>

';}
          }
  }
  
          ?>
  
 
  
</a>
</li>
</ul>
</div>
</div>
</div>
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