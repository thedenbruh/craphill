 <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
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
  
  

  
    
    
    ////MEMBERSHIP CASH
  
    
    
  
  ////
  
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
$unapprovedNum = $shopUnapprovedAssets->num_rows;?>
<html lang="en" class="h-100">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link href="/auth/style.css" rel="stylesheet" crossorigin="anonymous">
    <link rel="icon" type="image/x-icon" href="/auth/icon.png">
    
    <meta name="description" content="Good Hill is a revival like no other. Multiple years, and free!"/>
    <meta name="twitter:card" content="summary" />
    <meta property="og:url" content="http://goodhill.myprivateclone.ct8.pl" />
    <meta property="og:title" content="Relive your favorite years with Good Hill" />
    <meta property="og:description" content="Good Hill is a revival like no other. Multiple years, and free!" />
    <meta property="og:image" content="" />

</head>
<body class="d-flex flex-column h-100">
<nav class="navbar navbar-expand-lg navbar-light bg-light border-bottom">
    <div class="container-fluid">
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <a class="navbar-brand" href="/">
    <!--<img src="https://media.discordapp.net/attachments/1028813942367211520/1058053271392108554/builderlandlogo.png" height="30" alt="Builder Land">-->
  </a>
                   <?php if(!$loggedIn) {echo' <li class="nav-item">
                                                <a class="nav-link" href="/auth/login">Login</a>
                                            </li>';
               }
               ?>
              <?php if($loggedIn) {echo' <li class="nav-item">
                                                <a class="nav-link" href="/dashboard">Home</a>
                                            </li>';
               }
               ?>
                <li class="nav-item">
                    <a class="nav-link" href="/auth/contact">Contact</a>
                </li>
                
                <li class="nav-item">
<a class="nav-link" href="/auth/tos">Terms of Service</a>
</li>
<li class="nav-item">
<a class="nav-link" href="/auth/privacy">Privacy</a>
</li>
<li class="nav-item">
<a class="nav-link" href="/auth/account-deletion">Account Deletion</a>
</li>
            </ul>
        </div>
    </div>
</nav>
    
<main class="flex-shrink-0">
    

<style>
    .navbar-auth {
        margin-bottom:  -1px;
    }
    body {
        overflow-x:  hidden;
    }
    #carouselIndicators {
        background:  #000;
    }
    .carousel-inner {
        opacity: 0.5;
    }
    img.c-home {
        max-height: 60vh;
        min-height: 370px;
        object-fit: cover;
    }
    .carousel-text {
        z-index: 3;
        position: relative;
        top: 0;
        height: 0;
        overflow: visible;
        text-shadow: 1px 1px 3px rgba(0,0,0,0.9);
    }
    .carousel-text h1 {
        font-size:  5rem;
        color:  #fff;
        padding-top: 1rem;
    }
    .carousel-text p {
        color:  #fff;
        font-weight: 500;
    }
    
    .container-one {
        padding-top: 4rem;
        padding-bottom: 4rem;
    }
    .selling-points > li {
        margin: 1rem 0;
    }
</style>


