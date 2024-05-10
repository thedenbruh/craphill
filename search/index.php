<?php
  include("../SiT_3/config.php");
  include("../SiT_3/header.php");
  
  if (isset($_GET['search'])) {
    echo '<head><title>\'' . $_GET['search'] . '\' Users - '.$sitename.'</title></head>';}
  else {
    echo '<head><title>Users - '.$sitename.'</title></head>';}
  
  if(isset($_GET['page'])) {$page = mysqli_real_escape_string($conn,intval($_GET['page']));} else {$page = 0;}
  $page = max($page,1);
?>

<?php
  include("../SiT_3/alert.php");
  ?>

<div class="main-holder grid">
<div class="col-10-12 push-1-12">
<div class="card">
<div class="top blue">
Search Users
</div>
<div class="content">
<div class="col-1-1" style="text-align:center;margin-bottom:10px;padding-right:0;">
  <form action="" method="GET" style="margin:15px;">
<input style="width:70%;margin-right:5px;" class="input rigid" type="text" name="search" placeholder="Search users" autocomplete="off" value="">
  <button type="submit" name="submit" class="blue shop-search-button">
    SEARCH
  </button>
    <a href="/search/online" class="button green" style="font-size:14px;">ONLINE</a>
          </form>

</div>
<!DOCTYPE html>
  <body>
    <div id="body">
      <div id="box" style="text-align:center;">
        <div id="subsect">
          
        <?php
        if (isset($_GET['search'])) {
          $query = mysqli_real_escape_string($conn,strtolower($_GET['search']));
          
          $sqlCount = "SELECT * FROM `beta_users` WHERE  `usernameL` LIKE '%$query%' ORDER BY `username`";
          $countQ = $conn->query($sqlCount);
          $count = $countQ->num_rows;
          
          $page = min($page,max((int)($count/20),1));
  
          $limit = ($page-1)*20;
          
          $sqlSearch = "SELECT * FROM `beta_users` WHERE  `usernameL` LIKE '%$query%' ORDER BY `username` LIMIT $limit,20";
          $result = $conn->query($sqlSearch);
          echo '<table width="100%"cellspacing="0"cellpadding="4"border="0"style="background-color:#;"><tbody>';
          while($searchRow=$result->fetch_assoc()){
            $lastonline = time()-strtotime($searchRow['last_online']);
            echo '<div class="col-1-1" style="padding-right:0;">
<hr>
              <a href="/user/' . $searchRow['id'] . '/">
<div class="search-user-card ellipsis">
<img src="/avatar/render/avatars/' . $searchRow['id'] . '.png?c='.$searchRow['avatar_id'].'" style="vertical-align:middle; width:69px;">
<div class="data">
<div class="ellipsis">
<b>' . $searchRow['username'] . '</b>';
            if ($lastonline >= 300) {
              $timedif = $lastonline . " seconds";
              if ($lastonline >= 300) {$timedif = (int)gmdate('i',$lastonline) . " minutes";}
              if ($lastonline >= 3600) {$timedif = (int)gmdate('H',$lastonline) . " hours";}
              if ($lastonline >= 86400) {$timedif = (int)gmdate('d',$lastonline) . " days";}
              if ($lastonline >= 2592000) {$timedif = (int)gmdate('m',$lastonline) . " months";}
              if ($lastonline >= 31536000) {$timedif = (int)gmdate('Y',$lastonline) . " years";}
              echo '<span style="float:right;" class="status-dot"></span></div>';}
            else {
              echo '<span style="float:right;" class="status-dot online"></span></div>';}
            if ($lastonline <= 300) {echo '';}
            else {echo '';}
            //echo'<span style="float:right;" class="status-dot"></span></div>';
            echo'
<span class="ellipsis">' . $searchRow['description'] . '</span>
</div>
</div>
</a>



';
            /*if ($lastonline >= 300) {
              $timedif = $lastonline . " seconds";
              if ($lastonline >= 300) {$timedif = (int)gmdate('i',$lastonline) . " minutes";}
              if ($lastonline >= 3600) {$timedif = (int)gmdate('H',$lastonline) . " hours";}
              if ($lastonline >= 86400) {$timedif = (int)gmdate('d',$lastonline) . " days";}
              if ($lastonline >= 2592000) {$timedif = (int)gmdate('m',$lastonline) . " months";}
              if ($lastonline >= 31536000) {$timedif = (int)gmdate('Y',$lastonline) . " years";}
              echo '<div style="text-align:center;"><span style="float:right;" class="status-dot "></span></span></div>';}
            else {
              echo '<div style="text-align:center;"><span style="float:right;" class="status-dot online "></span></div>';}
            if ($lastonline <= 300) {echo '<td style="text-align:center;"><i style="color:Green;font-size:15px;"</i></td>';}
            else {echo '<td style="text-align:center;"><i style="color:#DD0000;font-size:15px;" ></i></td>';}*/
          }
        }else{
                    $sqlCount = "SELECT * FROM `beta_users` ORDER BY `last_online` DESC";
                    $countQ = $conn->query($sqlCount);
                    $count = $countQ->num_rows;
                    $page = min($page,max((int)($count/20),1));
                    
                    $limit = ($page-1)*20;
                    
                    $sqlSearch = "SELECT * FROM `beta_users` ORDER BY `last_online` DESC LIMIT $limit,20";
                    $result = $conn->query($sqlSearch);
                    
                    echo '<table width="100%"cellspacing="0"cellpadding="4"border="0"style="background-color:#;"><tbody>';
          while($searchRow=$result->fetch_assoc()){
            $lastonline = time()-strtotime($searchRow['last_online']);
            echo '<div class="col-1-1" style="padding-right:0;">
<hr>
              <a href="/user/' . $searchRow['id'] . '/">
<div class="search-user-card ellipsis">
<img src="/avatar/render/avatars/' . $searchRow['id'] . '.png?c='.$searchRow['avatar_id'].'" style="vertical-align:middle; width:69px;">
<div class="data">
<div class="ellipsis">
<b>' . $searchRow['username'] . '</b>';
            if ($lastonline >= 300) {
              $timedif = $lastonline . " seconds";
              if ($lastonline >= 300) {$timedif = (int)gmdate('i',$lastonline) . " minutes";}
              if ($lastonline >= 3600) {$timedif = (int)gmdate('H',$lastonline) . " hours";}
              if ($lastonline >= 86400) {$timedif = (int)gmdate('d',$lastonline) . " days";}
              if ($lastonline >= 2592000) {$timedif = (int)gmdate('m',$lastonline) . " months";}
              if ($lastonline >= 31536000) {$timedif = (int)gmdate('Y',$lastonline) . " years";}
              echo '<span style="float:right;" class="status-dot"></span></div>';}
            else {
              echo '<span style="float:right;" class="status-dot online"></span></div>';}
            if ($lastonline <= 300) {echo '';}
            else {echo '';}
            //echo'<span style="float:right;" class="status-dot"></span></div>';
            echo'
<span class="ellipsis">' . $searchRow['description'] . '</span>
</div>
</div>
</a>
';
            
          }
          } echo '
            
            </tbody></table>';
        ?>
        <hr>
        <span>
        
        <?php
        if(isset($_GET['search'])) {
          echo '<div class="pages" style="margin-left:auto;margin-right:auto;margin-top:10px;">';
          if($page-2 > 0) {
              echo '<a style="color:#333;" href="?search='.$query.'&page=0">1</a> ... ';
          }
          if($count/20 > 1) {
                  for($i = max($page-2,0); $i < min($count/20,$page+2); $i++)
                  {
                    echo '<a class="page active" style="color:#333;" href="?search='.$query.'&page='.($i+1).'">'.($i+1).'</a> ';
                  }
                }
                if($count/20 > 4) {
                    echo '... <a class="page active" style="color:#333;" href="?search='.$query.'&page='.round($count/20).'">'.round($count/20).'</a> ';
                }
          
          echo '</div>';
        }
        ?>
        
          
        </dv>
      </div>
    </div></div>
</div>
</div>
  </div>
          </div>
    <?php 
    include("../SiT_3/footer.php");
    ?>
  </body>
</html>