<?php
  include("../SiT_3/config.php");
  include("../SiT_3/header.php");
  
  if (isset($_GET['search'])) {
    echo '<head><title>\'' . $_GET['search'] . '\' Users - '.$sitename.'</title></head>';}
  else {
    echo '<head><title>Users - '.$sitename.'</title></head>';}
?>

<!DOCTYPE html>
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
          
        </div>
        <?php
          if(isset($_GET['search'])) {$query = mysqli_real_escape_string($conn,strtolower($_GET['search']));} else {$query = '';}
          
          $sqlSearch = "SELECT * FROM `beta_users` WHERE  `usernameL` LIKE '%$query%'";
          $result = $conn->query($sqlSearch);
          echo '<table width="100%"cellspacing="0"cellpadding="4"border="0"style="background-color:#fff;"><tbody>';
          while($searchRow=$result->fetch_assoc()){
            $lastonline = time()-strtotime($searchRow['last_online']);
            if ($lastonline <= 300) {
            echo '<div class="col-1-1" style="padding-right:0;">
<hr>
<a href="/user?id=' . $searchRow['id'] . '">
<div class="search-user-card ellipsis">
<img src="/avatar/render/avatars/' . $searchRow['id'] . '.png?c=' . $searchRow['avatar_id'] . '" style="vertical-align:middle; width:69px;">
<div class="data">
<div class="ellipsis">
<b>' . $searchRow['username'] . '</b>
<span style="float:right;" class="status-dot online"></span>
</div>
<span class="ellipsis">' . $searchRow['description'] . ' <br/>
</span>
</div>
</div>
</a>';
            //echo '<span style="padding-left:15px; color:#333; font-size:12px; font-weight: bold;">Online Now</span>';
            if ($lastonline <= 300) {echo '</tbody>';}
            else {echo '</tbody>';}
          }
        }echo '</table>';
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