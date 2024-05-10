<?php 
include('../SiT_3/config.php');
include('../SiT_3/header.php');
include('../SiT_3/PHP/helper.php');

$gameID = mysqli_real_escape_string($conn, intval($_GET['id']));

$findGameSQL = "SELECT * FROM `games` WHERE `id` = '$gameID'";
$findGame = $conn->query($findGameSQL);
if ( $findGame->num_rows > 0 ) {$gameRow = (object) $findGame->fetch_assoc();
} else {header("location: ../");}
?>
<!DOCTYPE html>
<html>

  <head>
  <title><?php echo $gameRow->{'name'}.' - Brick Hill'; ?></title>
  <?php if($loggedIn) { ?>
  <script>
    function launchClient() {
      var launchURI = "brickhill:<?php 
      
      $gameString = $userRow->{'unique_key'}."-".$gameRow->{'id'}."-client";  //userRow->{'id'}
      $gameLaunch = gameLaunch($gameString);
      echo $gameLaunch;
      ?>";
      var coolInput = "<?php echo $gameString; ?>";
      
      window.location = launchURI;
      
      /*var parentElement = document.getElementById("brickClient");
      var brickClient = document.createElement('iframe');
      
      brickClient.setAttribute('src',launchURI);
      parentElement.appendChild(brickClient);*/
    }
    </script>
  <?php } ?>
  </head>
  
  <body>
    <div class="main-holder grid">
<div class="col-10-12 push-1-12">
<div class="card" style="margin-bottom: 20px;">
<div class="top blue">
<?php echo htmlentities( $gameRow->{'name'} ); ?>
</div>
<div class="content">
<div class="col-5-12" style="padding-right:0;">
<div class="game-img">
<img height="250" width="340" src="/images/games/<?php echo htmlentities( $gameRow->{'id'} ); ?>.png">
</div>
</div>
<div class="col-4-12" style="padding-left:10px;padding-right:0;">
<span class="item-timer" style="display:block; color: red;"><?php
              if($gameRow->{'active'} == 1) {
                echo ''.$gameRow->{'playing'}.' players</a>
                ';
              } else {
                echo '<a style="color:Red;">This server is not being hosted</a>';
              }
              ?></span><br/>
<div class="padding-10"></div>
<div class="item-description"><?php echo htmlentities( $gameRow->{'description'} ); ?>
</div>
                <?php
              if($gameRow->{'active'} == 1) {
                echo '
                <br>';
                if ($loggedIn) {echo '<input style="padding:7px 10px 7px 10px; border:1px solid #000; background-color:Green; color:#FFF;" type="button" value="Play" onclick="launchClient()">';} else {
                echo '<a href="/login/"><input style="padding:7px 10px 7px 10px; border:1px solid #000; background-color:Green; color:#FFF;" type="button" value="Play"></a>';
                }
              } else {
                echo '';
              }
              ?>
<div class="padding-30"></div><br/>
<div class="item-info">
<span class="item-stats">
Created: <span class="item-stats-data" title="<?php echo htmlentities( $gameRow->{'date'} ); ?>"><?php echo htmlentities( $gameRow->{'date'} ); ?></span>
</span>
<span class="item-stats">
Updated: <span class="item-stats-data" title="<?php echo htmlentities( $gameRow->{'last_updated'} ); ?>"><?php echo htmlentities( $gameRow->{'last_updated'} ); ?></span>
</span><br>
<span class="item-stats">
Visits: <span class="item-stats-data"><?php echo htmlentities( $gameRow->{'visits'} ); ?></span>
</span>
</div>
<div class="padding-10"></div>
<div class="item-favorite">
<i class=" far  fa-star" aria-hidden="true"></i>
<span style="font-size:0.9rem">0</span>
</div>
</div><?php
   // Find Creator
   $ownerID = $gameRow->{'creator_id'};
   $findOwnerSQL = "SELECT * FROM `beta_users` WHERE `id` = $ownerID";
   
   $findOwner = $conn->query($findOwnerSQL);
   $ownerRows = (object) $findOwner->fetch_assoc();
   ?>
<div class="col-3-12">
<a href="/user/<?php echo $ownerRows->{'id'}; ?>/">
<div class="game-creator-img">
<img src="/avatar/render/avatars/<?php echo $ownerRows->{'id'}; ?>.png">
<span><b><?php echo $ownerRows->{'username'}; ?></b></span>
</div>
</a>
</div>
</div>
</div>

  
  </div></div></div>
  
  
  
  <?php
    include("../SiT_3/footer.php");
  ?>
  </body>
  
</html>