<?php 
include('../SiT_3/config.php');
include('../SiT_3/header.php');

?>
<!DOCTYPE html>
<html>

  <head>
    
    <title>Play - <?php echo $sitename; ?></title>
    
  </head>
  
  <body>
    
<div class="main-holder grid">
<div class="col-10-12 push-1-12">
<div class="large-text margin-bottom">
Games
</div>
      <?php 
      
      $findGamesSQL = "SELECT * FROM `games` WHERE `active`='1' ORDER BY `playing` DESC";
      $findGames = $conn->query($findGamesSQL);
      
      if ($findGames->num_rows > 0) {
        while($gameRow = $findGames->fetch_assoc()) {
          
          // Change array to OOP Array
          
          $gameRow = (object) $gameRow;
          
          // Find Game Owner
          
          $ownerID = $gameRow->{'creator_id'};
          $findOwnerSQL = "SELECT * FROM `beta_users` WHERE `id` = $ownerID";
          $findOwner = $conn->query($findOwnerSQL);
          $ownerRow = (object) $findOwner->fetch_assoc();
          
      ?>
       
        <div class="col-3-12">
<div class="card left game-card">
<div class="thumbnail">
<a href="set?id=<?php echo $gameRow->{'id'}; ?>"><img class="round-top" src="/images/games/<?=$gameRow->{'id'}?>.png"></a>
</div>
<div class="game-info">
<span class="name"><a href="set?id=<?php echo $gameRow->{'id'}; ?>"><?php echo $gameRow->{'name'}; ?></a></span><br>
<span class="creator">By <a href="/user/<?php echo $ownerRow->{'id'}; ?>/"><?php echo $ownerRow->{'username'}; ?></a></span>
</div>
<div class="game-details">
<span class="playing"><?php echo $gameRow->{'playing'}; ?> Playing</span>
</div>
</div>
</div></div></div></div>
        
      <?php 
        }
        } else {
          ?>
          <div style="text-align:center;">
            There aren't any active servers!
          </div>
          <?php
        }
      ?>
      </div>
    </div>
    
    <?php
    include("../SiT_3/footer.php");
    ?>
    
  </body>
  
</html>
