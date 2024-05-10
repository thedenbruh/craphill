<?php
  include("../SiT_3/config.php");
  include("../SiT_3/header.php");
  //if(!$loggedIn) {header("Location: /index"); die();}
  if(isset($_GET['page'])) {$page = mysqli_real_escape_string($conn,intval($_GET['page']));} else {$page = 0;}
  $page = max($page,1);
  $id = $_SESSION['id']; //for my clans thing, never thought i was even able to release that
?>
<!DOCTYPE html>
  <head>
    <title>Groups -  <?php echo $sitename; ?></title>
  </head>
  <body>
    <div class="main-holder grid">
<div class="col-10-12 push-1-12">
<div class="card">
<div class="top blue">
My Clans
</div>
<div class="content" style="text-align:center;">
<div class="carousel clans">
<div style="width:95%;margin-right:auto;margin-left:auto;overflow:hidden">
<ul style="max-height: 160px;">

               
<?php
          $clansSQL = "SELECT * FROM `clans_members` WHERE `user_id`='$id' AND `status`='in'";
          $clans = $conn->query($clansSQL);
          while($clanRow = $clans->fetch_assoc()){
            $clanID = $clanRow['group_id'];
            $findClanSQL = "SELECT * FROM `clans` WHERE `id`='$clanID'";
            $findClan = $conn->query($findClanSQL);
            $findClanRow = $findClan->fetch_assoc();
            
    if ($findClanRow['approved'] == 'yes') {$thumbnail = $findClanRow['id'];}
    elseif ($findClanRow['approved'] == 'declined') {$thumbnail = 'declined';}
    else {$thumbnail = 'pending';}
            
            echo '<li class="carousel li" data-iteration="1">
<a href="/clan?id='.$clanID.'">
<div class="profile-card">
<img src="/clans/icons/'.$thumbnail.'.png">
<span class="ellipsis">'.$findClanRow['name'].'</span>
</div>
</a>
</li>
            ';
            }
          
          ?>
        </ul>
      </div>
</div>
</div>
</div>
<div class="card">
<div class="top blue">
Popular Clans
</div>
<div class="content">
<div class="mb2 overflow-auto">
<div class="col-9-12">
<input type="text" name="search" style="height:41px;" class="width-100 rigid" placeholder="Search">
</div>
<div class="col-3-12">
<div class="acc-1-2 np">
<button class="button blue width-100">Search</button>
</div>
<div class="acc-1-2 np">
<a href="/clans/create" class="button green width-100">Create</a>
</div>
</div>
</div>
<div class="col-1-1" style="padding-right:0;">

        
        <?php
        if (isset($_GET['search'])) {
          $query = mysqli_real_escape_string($conn,$_GET['search']);
        } else {
          $query = '';
        }
        
          $sqlCount = "SELECT * FROM `clans` WHERE  `name` LIKE '%$query%' ORDER BY `members`";
          $countQ = $conn->query($sqlCount);
          $count = $countQ->num_rows;
          
          
  
          $limit = ($page-1)*10;
          
          $sqlSearch = "SELECT * FROM `clans` WHERE  `name` LIKE '%$query%' ORDER BY `members` DESC LIMIT $limit,10";
          $result = $conn->query($sqlSearch);
          
          while($searchRow=$result->fetch_assoc()){
            $group_id = $searchRow['id'];
            $memCount = $searchRow['members'];
            
            
            if ($searchRow['approved'] == 'yes') {$thumbnail = $searchRow['id'];}
            elseif ($searchRow['approved'] == 'declined') {$thumbnail = 'declined';}
            else {$thumbnail = 'pending';}
          
            /*echo '<div id="subsect" style="overflow:auto;">';
            echo '<div style="width:20%;text-align:center;float:left;"><img style="vertical-align:middle; width:100px; height:100px; margin: 0px 10px 10px 0px;" src="/clans/icons/'.$thumbnail.'.png?v='.time().'"></div>';
            echo '<div style="width:20%;text-align:center;float:left;"><a style="color:black;" href="/clan?id=' . $group_id . '">' . $searchRow['name'] . '</a>'; if($searchRow['verified'] >= 1) {echo '<i style="color: green;" class="fa fa-check"></i>';} echo'</div>';
            echo '<div style="width:45%;text-align:center;float:left;"><span style="padding-left:15px; color:#333; font-size:12px;">' . substr(htmlentities($searchRow['description']),0,100) . str_repeat("...",(strlen(htmlentities($searchRow['description'])) >= 100)) .'</span></div>';
            echo '<div style="width:15%;text-align:center;float:left;"><span style="padding-left:15px; color:#333; font-size:12px; font-weight: bold;">Members: ' . $memCount. '</span></div> </div>';*/
            echo'<a href="/clan/' . $group_id . '">
<div class="hover-card clan">
<div class="clan-logo">
<img class="width-100" src="/clans/icons/'.$thumbnail.'.png?v='.time().'">
</div>
<div class="data ellipsis">
<span class="clan-name bold mobile-col-1-2 ellipsis">' . $searchRow['name'] . '</span>
<span class="push-right">' . $memCount. ' Members</span>
</div>
<div class="clan-description">
' . substr(htmlentities($searchRow['description']),0,100) . str_repeat("...",(strlen(htmlentities($searchRow['description'])) >= 100)) .'
</div>
 </div>
</a>
<hr>';
            }
  
  
          /*} else {
            $topGroupsSQL = "SELECT * FROM `clans` ORDER BY `members` LIMIT 10";
            $topGroups = $conn->query($topGroupsSQL);
            while ($groupRow = $topGroups->fetch_assoc()) {
              $gID = $groupRow['id'];
            ?>
        
            <div id="subsect">
            <img style="vertical-align:middle; width:100px; margin: 0px 10px 10px 0px;" src="/clans/icons/<?php echo $gID; ?>.png">
            <a style="color:black;" href="/clan?id=<?php echo $gID; ?>"> <?php echo $groupRow['name']; ?></a>
  
            <span style="padding-left:15px; color:#333; font-size:12px;"><?php echo substr($groupRow['description'],0,100) . str_repeat("...",(strlen($groupRow['description']) >= 100)); echo "</div>"; ?></span>
            <?php
            }
          }*/
        ?>
        <?php
   ?>
        <?php
          echo '<div class="numButtonsHolder" style="margin-left:auto;margin-right:auto;margin-top:10px;">';
          if($page-2 > 0) {
              echo '<a style="color:#333;" href="?search='.$query.'&page=0">1</a> ... ';
          }
          if($count/10 > 1) {
                  for($i = max($page-2,0); $i < min($count/10,$page+2); $i++)
                  {
                    echo '<a style="color:#333;" href="?search='.$query.'&page='.($i+1).'">'.($i+1).'</a> ';
                  }
                }
                if($count/10 > 4) {
                    echo '... <a style="color:#333;" href="?search='.$query.'&page='.(int)($count/10).'">'.(int)($count/10).'</a> ';
                }
          
          echo '</div>';
        ?>
      </div>
    </div>
  </body>
              </div></div></div></div>
  <?php 
  include("../SiT_3/footer.php");
  ?>
</html>