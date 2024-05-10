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

<link rel="preconnect" href="https://cdnjs.cloudflare.com">
<link rel="preconnect" href="https://fonts.gstatic.com">

<link rel="shortcut icon" href="/img/retrimo.png">
<meta name="csrf-token" content="Bi4ZrpAqP0YijWOiFS2tjhbAdBCZUppRfuD2KSYU">

<link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.15.3/css/all.css">
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Source+Sans+Pro:ital,wght@0,300;0,400;0,500;0,600;0,700;1,400&display=swap">

<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
<link href="https://fonts.googleapis.com/css2?family=Noto+Sans:ital,wght@0,400;0,700;1,400;1,700&display=swap" rel="stylesheet">
<style>
        body {
            background: #eee;
            color: #333;
            font-family: 'Noto Sans', 'Helvetica Neue', Helvetica, Arial, sans-serif;
        }

        p:last-child {
            margin-bottom: 0;
        }

        b, strong {
            font-weight: 600;
        }

        img {
            max-width: 100%;
            height: auto;
        }

        .btn {
            box-shadow: none!important;
        }

        .navbar {
            background: #000;
        }

        .navbar .navbar-brand {
            margin-top: -5px;
        }

        .navbar .nav-link {
            color: #fff;
        }

        .navbar .headshot a {
            text-decoration: none;
        }

        .navbar .headshot .dropdown-toggle {
            margin-right: none!important;
        }

        .navbar .headshot .dropdown-toggle::after {
            border: none!important;
            margin: 0!important;
        }

        .card {
            margin-bottom: 16px;
        }

        .card, .breadcrumb {
            border: 1px solid rgba(0, 0, 0, .125);
            border-radius: 0;
        }

        @media  only screen and (min-width: 768px) {
            .show-sm-only {
                display: none !important
            }

            .mb-sm-only {
                margin-bottom: 0 !important
            }

            .nav-tabs.card-header-nav-tabs {
                margin-bottom: -5px
            }
        }

        @media  only screen and (max-width: 768px) {
            .hide-sm {
                display: none !important
            }

            .full-width-sm {
                width: 100% !important
            }

            .text-center-sm {
                text-align: center !important
            }
        }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-md border-bottom mb-4">
<div class="container">
<a href="/dashboard/" class="navbar-brand">
<img src="/assets/GoodIcon.png" width="30px">
</a>
<ul class="navbar-nav mr-auto">
<li class="nav-item">
<a href="/panel/" class="nav-link">
<i class="fas fa-home"></i>
</a>
</li>
  
</ul>

</div>
</nav>
  <script src="https://code.jquery.com/jquery-3.3.1.min.js" type="e437a3eac69e6d221c8218e9-text/javascript"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" type="e437a3eac69e6d221c8218e9-text/javascript"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" type="e437a3eac69e6d221c8218e9-text/javascript"></script>
<script src="/cdn-cgi/scripts/7d0fa10a/cloudflare-static/rocket-loader.min.js" data-cf-settings="e437a3eac69e6d221c8218e9-|49" defer=""></script></body>
</html>