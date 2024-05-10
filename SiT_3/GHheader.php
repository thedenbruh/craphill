<audio id="MusicPlayer" src="https://blog.brick-hill.com/content/media/2022/12/AHillianChristmas2.mp3"></audio>
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
    //header('Location: /login/');
  }
  
  $power = $userRow->{'power'};
  $invite = $userRow->{'invite'};
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
<!DOCTYPE html>
  <head>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="viewport" content="width=device-width, initial-scale=1">
    

<link rel="stylesheet" href="https://web.archive.org/web/20211028052520cs_/https://www.vextoria.com/css/stylesheet.css">
<link rel="stylesheet" href="http://good-hill.ct8.pl/maintenance/css/themes/dark.css?v=1104163390">

  <script src=""></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
        <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.15.1/css/all.css?t=<?=time()?>" data-turbolinks-track="true" />
        <script src="https://cdn.jsdelivr.net/npm/vue"></script>
  <link rel="preconnect" href="https://web.archive.org/web/20211028052520/https://cdnjs.cloudflare.com/">
<link rel="preconnect" href="https://web.archive.org/web/20211028052520/https://fonts.gstatic.com/">
  <script type="text/javascript" src="https://web.archive.org/_static/js/bundle-playback.js?v=36gO9Ebf" charset="utf-8"></script>
<script type="text/javascript" src="https://web.archive.org/_static/js/wombat.js?v=UHAOicsW" charset="utf-8"></script>
  <script src="https://archive.org/includes/analytics.js?v=cf34f82" type="text/javascript"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
<script type="text/javascript">window.addEventListener('DOMContentLoaded',function(){var v=archive_analytics.values;v.service='wb';v.server_name='wwwb-app202.us.archive.org';v.server_ms=159;archive_analytics.send_pageview({});});</script>
  
       
  <script>
        var _token;

        $(() => {
            _token = $('meta[name="csrf-token"]').attr('content');

            $('[data-toggle="tooltip"]').tooltip();

            $('#sidebarToggler').click(function() {
                const enabled = !$('.sidebar').hasClass('show');

                if (enabled)
                    $('.sidebar').addClass('show');
                else
                    $('.sidebar').removeClass('show');
            });
        });
    </script>
<script src="https://web.archive.org/web/20211028052520js_/https://www.vextoria.com/js/search.js"></script>
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
    <link rel="stylesheet" href="/themes/light.css?r=<?php echo rand(10000,1000000) ?>" type="text/css">
  <?php
  if ($theme == 1) {
    ?>
  <link rel="stylesheet" href="/themes/christmas.css?r=<?php echo rand(10000,1000000) ?>" type="text/css">
    <?php
  } elseif ($theme == 2) {
    ?>
  <link rel="stylesheet" href="/themes/dark.css?r=<?php echo rand(10000,1000000) ?>" type="text/css">
    <?php
  } elseif ($theme == 3) {
    ?>
  <link rel="stylesheet" href="/themes/darkred.css?r=<?php echo rand(10000,1000000) ?>" type="text/css">
  <?php
  } elseif ($theme == 4) {
    ?>
    <link rel="stylesheet" href="/themes/darkgreen.css?r=<?php echo rand(10000,1000000) ?>" type="text/css">
    <?php
  } elseif ($theme == 5) {
    ?>
    <link rel="stylesheet" href="/themes/darkyellow.css?r=<?php echo rand(10000,1000000) ?>" type="text/css">
    <?php
  }
  ?>
  </head>
<nav class="navbar navbar-expand-md fixed-top">
<button class="navbar-toggler" id="sidebarToggler" style="border:none;" type="button">
<i class="fas fa-bars" style="font-size: 23px;"></i>
</button>
<?php
          if(!$loggedIn) {
             {
{echo '
<a href="/" class="navbar-brand">
<img src="http://good-hill.ml/goodhillLogo.png" width="150px">
</a>
<div class="nav-item show-sm-only"></div>
<div class="collapse navbar-collapse" id="navbarContent">
<ul class="navbar-nav mr-auto" style="width:50%;">
  ';}
               }
               }
  ?>
  <?php
          if($loggedIn) {
             {
{echo '
<a href="/dashboard" class="navbar-brand">
<img src="http://good-hill.ml/goodhillLogo.png" width="150px">
</a>
<div class="nav-item show-sm-only"></div>
<div class="collapse navbar-collapse" id="navbarContent">
<ul class="navbar-nav mr-auto" style="width:50%;">
  ';}
               }
               }
  ?>
  </ul>
  </div>
  
  <?php if($loggedIn){ ?>
  

  <?php
  echo '
<div class="nav-item hide-sm">
<a class="nav-link" data-placement="bottom" data-toggle="tooltip" title="'.$user->tokens.'';if($user->id == 50) {echo 'Trucks';}else{if($user->tokens > 1){ echo " Tokens";}else{echo " Token";}}echo ' "href="/my/currency"><i class="fa fa-certificate" style="color: #2def51" aria-hidden="true"></i> '. $userRow->{"bucks"}.'</a>
</div>
<div class="nav-item hide-sm" data-toggle="tooltip" title="'.$user->coins.'';if($user->id == 50) {echo 'Engines';}else{if($user->coins > 1){ echo " Coins";}else{echo " Coin";}}echo ' ""><a class="nav-link" href="/my/currency"><i class="fa fa-circle" style="color: #e6b13f;" aria-hidden="true"></i> '. $userRow->{"bits"}.'
</a>
</div>
';
  ?>
  
<!--<div class="nav-item show-sm-only"></div>-->
        
            <div class="nav-item dropdown headshot ">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                    <img src="http://good-hill.ml/storage/headshots" width="40px">
                </a>
                <div class="dropdown-menu">
                    <a href="<?php echo'/profile?id='.$userRow->{'id'}.'';?>" class="dropdown-item">
                        <i class="fas fa-user"></i>
                        <span>Profile</span>
                    </a>

                    
                        <a href="http://good-hill.ml/my/avatar" class="dropdown-item">
                            <i class="fas fa-tshirt"></i>
                            <span>Character</span>
                        </a>
                    

                     
                        <a href="http://good-hill.ml/settings" class="dropdown-item">
                            <i class="fas fa-wrench"></i>
                            <span>Settings</span>
                        </a>
                    

                    <a href="http://good-hill.ml/logout" class="dropdown-item">
                        <i class="fas fa-sign-out-alt"></i>
                        <span>Logout</span>
                    </a>
                </div>
            </div>

  <?php
  }
  ?>


  
  <?php
          if(!$loggedIn) {
             {
{echo '
<ul class="navbar-nav ml-auto">
<li class="nav-item hide-sm">
<a href="/login" class="nav-link">
<i class="fas fa-user"></i>
<span>Login</span>
</a>
</li>
<li class="nav-item hide-sm">
<a href="/register" class="nav-link">
<i class="fas fa-user-plus"></i>
<span>Register</span>
</a>
</li>
</ul>
  ';}
               }
               }
  ?>
</div>
</nav>
<div class="navbar-search-dropdown-parent">
<div class="navbar-search-dropdown" id="navbarSearchResults" style="display:none;"></div>
</div>
<nav class="sidebar">
<div class="mb-2"></div>
<div class="mt-2 show-sm-only"></div>
<div class="show-sm-only" style="padding-left: 5px; padding-right: 5px;">
<form action="/users" method="GET">
<input class="form-control" style="height: 38px;" type="text" name="search" placeholder="Search Good-Hill...">
</form>
</div>
  <?php if($loggedIn){ ?>
  


  <?php
  echo '
<div class="nav-item show-sm-only">
<a class="nav-link" data-placement="bottom" data-toggle="tooltip" title="'.$user->tokens.'';if($user->id == 6) {echo 'Trucks';}else{if($user->tokens > 1){ echo " Tokens";}else{echo " Token";}}echo ' "href="/my/currency"><i class="fa fa-certificate" style="color: #2def51" aria-hidden="true"></i> '. $userRow->{"bucks"}.'</a>
</div>
<div class="nav-item show-sm-only" data-toggle="tooltip" title="'.$user->coins.'';if($user->id == 6) {echo 'Engines';}else{if($user->coins > 1){ echo " Coins";}else{echo " Coin";}}echo ' ""><a class="nav-link" href="/my/currency"><i class="fa fa-circle" style="color: #e6b13f;" aria-hidden="true"></i> '. $userRow->{"bits"}.'
</a>
</div>
';}
  ?>
  
<?php
          if(!$loggedIn) {
             {
{echo '<div class="mb-1 show-sm-only"></div>
<div class="show-sm-only">
<div class="section-div">AUTH</div>
<a href="/login">
<i class="fas fa-user sidebar-icon"></i>
<span class="sidebar-text">Login</span>
</a>
<a href="/register">
<i class="fas fa-user-plus sidebar-icon"></i>
<span class="sidebar-text">Register</span>
</a>
</div>';
 }
               }
            }
            ?>


<div class="section-div">NAVIGATION</div>
<a href="/">
<i class="fas fa-home sidebar-icon"></i>
<span class="sidebar-text">Home</span>
</a>
<a href="https://web.archive.org/web/20211107160848/https://www.vextoria.com/games/roadmap">
<i class="fas fa-gamepad-alt sidebar-icon"></i>
<span class="sidebar-text">Games</span>
</a>
<a href="/item-shop">
<i class="fas fa-shopping-bag sidebar-icon"></i>
<span class="sidebar-text">Item Shop</span>
</a>
<a href="/discussion">
<i class="fas fa-comment-alt sidebar-icon"></i>
<span class="sidebar-text">Discussion</span>
</a>
<a href="/clubs">
<i class="fas fa-building sidebar-icon"></i>
<span class="sidebar-text">Clubs</span>
</a>
<a href="/people">
<i class="fas fa-user-friends sidebar-icon"></i>
<span class="sidebar-text">People</span>
</a>
  
</nav>
    
    <div class="container-custom">
<div class="alert alert-site text-center mb-4" style="background:#000000;color:#ffffff;">
<div class="row">
<div class="col-1 align-self-center pl-1 pr-1">
<i class="fas fa-exclamation-circle"></i>
</div>
<div class="col-10 align-self-center pl-1 pr-1">
<strong style="word-wrap:break-word;">Good Hill Is Currently Under Development Sorry!</a></strong>
</div>
<div class="col-1 align-self-center pl-1 pr-1">
<i class="fas fa-exclamation-circle"></i>
</div>
</div>
</div>
      
      <style>
        a:not([href]):not([class]) {
            color: var(--link_color);
            cursor: pointer;
        }

        a:not([href]):not([class]):hover, a:not([href]):not([class]):focus {
            color: var(--link_color_hover);
        }

        a, a:hover, a:focus {
            text-decoration: none;
        }

        .container-custom {
            max-width: 1420px;
        }

        .card, .card-header, .breadcrumb, .form-control, .btn, .nav-pills .nav-link {
            border-radius: 2px!important;
        }

        .navbar-search-dropdown-parent {
            width: 50%;
            position: fixed;
            top: 0;
            right: 0;
            left: 0;
            z-index: 1000;
            margin-left: 13%;
        }

        .navbar-search-dropdown {
            background: var(--section_bg);
            color: var(--section_color);
            border-radius: 8px;
            box-shadow: var(--section_box_shadow);
            padding: 15px 0;
            width: 100%;
            position: absolute;
            margin-top: 50px;
        }

        .navbar-search-result, .navbar-search-error {
            padding: 5px;
            padding-left: 10px;
            padding-right: 10px;
        }

        .navbar-search-result:hover {
            background: var(--section_bg_hover);
        }

        .navbar-search-result a {
            color: inherit;
            text-decoration: none;
        }

        .navbar-search-result img {
            background: var(--headshot_bg);
            border: 1px solid var(--headshot_border_color);
            border-radius: 50%;
            width: 40px;
        }

        .sidebar-icon {
            width: 35px!important;
            text-align: center;
        }
    </style>
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
          <title>Banned - Brick Hill</title>
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
  
  //kwame's testme
  /*else{
   echo"

   <Script>
  function testMe(){
     ";

     // Check if they are in the group - check userrow 
     $CheckIfInQ = mysqli_query($conn,"SELECT * FROM `clan_members` WHERE `group_id`='4' AND `user_id`='$UID' AND `status`='in'");
     $InGroup = mysqli_num_rows($CheckIfInQ);
     if ($InGroup > 0){ // they are in group
     
      // Check if they have egg
      $CheckEggQ = mysqli_query($conn,"SELECT * FROM `crate` WHERE `user_id`='$UID' AND `item_id`='913' AND `own`='no'");
      $HasEgg = mysqli_num_rows($CheckEggQ);
      if($HasEgg > 0){
        
        // Give them egg
        
        // Get current Serial and add 1
        $latestSerialQ = mysqli_query($conn,"SELECT * FROM  `crate` WHERE `item_id`='913' ORDER BY  `crate`.`serial` DESC LIMIT 1 ");
        $itemSerial = mysqli_fetch_array($latestSerialQ);
        $serial = $itemSerial[serial];
        $newSerial = $serial + 1;
        
        // Insert into their inventory
        //mysqli_query()
        echo "alert('u have egg')";
        }else{
          echo"alert('You already have this egg!')";
      }

      }else{
      echo "alert('You are not in BHH!')";
     }

     echo"
  };
</script>


    ";
  }*/
  
  }
  ////
?>